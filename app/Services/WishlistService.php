<?php
namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

class WishlistService
{
    public const SESSION_KEY = 'wishlist';

    /**
     * Stored product ids, sanitized to positive integers.
     *
     * @return array<int, int>
     */
    public function ids(): array
    {
        $ids = session(self::SESSION_KEY, []);

        if (! is_array($ids)) {
            return [];
        }

        $ids = array_map('intval', $ids);
        $ids = array_filter($ids, fn (int $id) => $id > 0);

        return array_values(array_unique($ids));
    }

    public function count(): int
    {
        return count($this->ids());
    }

    public function has(int $productId): bool
    {
        return in_array($productId, $this->ids(), true);
    }

    /**
     * Toggle a product in the wishlist. Returns true when the product
     * is now wishlisted.
     */
    public function toggle(int $productId): bool
    {
        $ids = $this->ids();

        if (in_array($productId, $ids, true)) {
            $ids = array_values(array_filter($ids, fn (int $id) => $id !== $productId));
            $saved = false;
        } else {
            $ids[] = $productId;
            $saved = true;
        }

        session()->put(self::SESSION_KEY, $ids);

        return $saved;
    }

    /**
     * Only active products that still exist are returned; stale ids
     * are pruned from the session.
     *
     * @return Collection<int, Product>
     */
    public function products(): Collection
    {
        $ids = $this->ids();

        if ($ids === []) {
            return collect();
        }

        $products = Product::query()
            ->active()
            ->with(['category:id,name,slug', 'images', 'activeVariants' => fn ($query) => $query->orderBy('price')])
            ->whereIn('id', $ids)
            ->get();

        $validIds = $products->pluck('id')->all();

        if (count($validIds) !== count($ids)) {
            session()->put(self::SESSION_KEY, $validIds);
        }

        return $products;
    }
}
