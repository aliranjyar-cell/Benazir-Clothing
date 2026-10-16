<?php

namespace App\Services;

use App\Models\ProductVariant;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class CartService
{
    public const SESSION_KEY = 'cart';

    /**
     * Variant ids mapped to requested quantities, sanitized to integers.
     *
     * @return array<int, int>
     */
    public function entries(): array
    {
        $entries = session(self::SESSION_KEY, []);

        if (! is_array($entries)) {
            return [];
        }

        $clean = [];

        foreach ($entries as $variantId => $quantity) {
            $variantId = (int) $variantId;
            $quantity = (int) $quantity;

            if ($variantId > 0 && $quantity > 0) {
                $clean[$variantId] = $quantity;
            }
        }

        return $clean;
    }

    /**
     * Total number of items in the cart (session only, no queries).
     */
    public function count(): int
    {
        return array_sum($this->entries());
    }

    public function isEmpty(): bool
    {
        return $this->entries() === [];
    }

    /**
     * Resolve cart lines from the database so prices always come from
     * the catalogue, never from the browser. Stale entries (deleted,
     * deactivated products or variants) are pruned from the session.
     *
     * @return Collection<int, array{variant: ProductVariant, quantity: int, line_total: float, exceeds_stock: bool}>
     */
    public function lines(bool $pruneStale = true): Collection
    {
        $entries = $this->entries();

        if ($entries === []) {
            return collect();
        }

        $variants = ProductVariant::query()
            ->with([
                'product:id,slug,name,is_active,category_id,fabric',
                'product.category:id,name,slug',
                'product.primaryImage',
                'product.images',
            ])
            ->whereIn('id', array_keys($entries))
            ->where('is_active', true)
            ->whereHas('product', fn($query) => $query->active())
            ->get()
            ->keyBy('id');

        // Prune entries that are no longer sellable.
        $stale = array_diff(array_keys($entries), $variants->keys()->all());

        if ($stale !== [] && $pruneStale) {
            $this->put(array_diff_key($this->entries(), array_flip($stale)));
        }

        return $variants->map(function (ProductVariant $variant) use ($entries) {
            $quantity = $entries[$variant->id];

            return [
                'variant' => $variant,
                'product' => $variant->product,
                'quantity' => $quantity,
                'line_total' => (float) $variant->price * $quantity,
                'exceeds_stock' => $quantity > (int) $variant->stock_quantity,
            ];
        })->values();
    }

    public function subtotal(): float
    {
        return (float) $this->lines()->sum('line_total');
    }

    /**
     * Add a variant to the cart after validating sellability and stock.
     *
     * @throws ValidationException
     */
    public function add(int $variantId, int $quantity = 1): void
    {
        $variant = $this->resolveSellableVariant($variantId);

        $newQuantity = ($this->entries()[$variantId] ?? 0) + $quantity;

        $this->assertStock($variant, $newQuantity);

        $entries = $this->entries();
        $entries[$variantId] = $newQuantity;

        $this->put($entries);
    }

    /**
     * Set a variant's quantity. A quantity of zero removes the line.
     *
     * @throws ValidationException
     */
    public function update(int $variantId, int $quantity): void
    {
        $entries = $this->entries();

        if (! array_key_exists($variantId, $entries)) {
            throw ValidationException::withMessages([
                'cart' => 'That item is no longer in your cart.',
            ]);
        }

        if ($quantity < 1) {
            unset($entries[$variantId]);
            $this->put($entries);

            return;
        }

        $variant = $this->resolveSellableVariant($variantId);

        $this->assertStock($variant, $quantity);

        $entries[$variantId] = $quantity;
        $this->put($entries);
    }

    public function remove(int $variantId): void
    {
        $entries = $this->entries();
        unset($entries[$variantId]);
        $this->put($entries);
    }

    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    /**
     * @throws ValidationException
     */
    private function resolveSellableVariant(int $variantId): ProductVariant
    {
        $variant = ProductVariant::query()
            ->with('product:id,slug,name,is_active')
            ->find($variantId);

        if (
            ! $variant
            || ! $variant->is_active
            || ! $variant->product
            || ! $variant->product->is_active
        ) {
            throw ValidationException::withMessages([
                'variant_id' => 'The selected product is not available.',
            ]);
        }

        return $variant;
    }

    /**
     * @throws ValidationException
     */
    private function assertStock(ProductVariant $variant, int $quantity): void
    {
        $available = (int) $variant->stock_quantity;

        if ($available < 1) {
            throw ValidationException::withMessages([
                'variant_id' => "{$variant->product->name} is out of stock.",
            ]);
        }

        if ($quantity > $available) {
            throw ValidationException::withMessages([
                'variant_id' => "Only {$available} unit(s) of {$variant->product->name} are available.",
            ]);
        }
    }

    /**
     * @param  array<int, int>  $entries
     */
    private function put(array $entries): void
    {
        session()->put(self::SESSION_KEY, $entries);
    }
}
