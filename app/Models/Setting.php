<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Throwable;

class Setting extends Model
{
    /**
     * Supported store currencies and their display symbols.
     * The storefront reads the active symbol through
     * config('storefront.currency_symbol') (set in AppServiceProvider).
     */
    public const CURRENCY_SYMBOLS = [
        'PKR' => '₨',
        'USD' => '$',
        'GBP' => '£',
        'CAD' => 'C$',
    ];

    /**
     * Payment methods an admin can offer in Settings → Checkout & Payments.
     * Only the enabled methods are offered at checkout: AppServiceProvider
     * writes the filtered map to config('storefront.checkout.payment_methods'),
     * which CheckoutController uses for validation and display.
     */
    public const PAYMENT_METHODS = [
        'cash_on_delivery' => 'Cash on Delivery',
        'bank_transfer' => 'Bank Transfer',
    ];

    protected $fillable = ['key', 'value'];

    /**
     * Per-request memo of every setting (defaults merged with saved values).
     */
    protected static ?array $runtimeSettings = null;

    /**
     * Defaults used until an admin saves an override.
     * Every key an admin section can save must exist here —
     * allSettings() only exposes keys listed in this array.
     */
    public static function defaults(): array
    {
        return [
            // --- General Store ---
            'store_name' => 'AURELLE',
            'store_tagline' => 'Lahore • London • New York',
            'store_logo' => null,
            'store_favicon' => null,
            'contact_email' => 'contact@benazirclothes.com',
            'contact_phone' => '',
            'address' => '',
            'currency' => 'PKR',
            'timezone' => 'Asia/Karachi',
            'store_status' => 'open',

            // --- Shipping & Delivery ---
            'shipping_flat_fee' => '0',
            'shipping_free_over' => '',
            'shipping_estimated_days' => '3–5 business days',

            // --- Checkout & Payments (JSON array of enabled method keys) ---
            'payment_methods_enabled' => '["cash_on_delivery"]',
            'checkout_default_payment_method' => 'cash_on_delivery',
            'checkout_allow_guest' => '1',
            'checkout_order_notes' => '1',
            'checkout_bank_details' => '',

            // --- Products & Inventory ---
            'low_stock_threshold' => '5',
            'products_per_page' => '12',
        ];
    }

    /**
     * All settings: defaults merged with the saved overrides.
     */
    public static function allSettings(): array
    {
        if (static::$runtimeSettings !== null) {
            return static::$runtimeSettings;
        }

        $stored = [];

        try {
            $stored = static::query()->pluck('value', 'key')->all();
        } catch (Throwable) {
            // The settings table may not exist yet (fresh install / before migration).
        }

        return static::$runtimeSettings = array_merge(
            static::defaults(),
            array_intersect_key($stored, static::defaults())
        );
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return static::allSettings()[$key] ?? $default;
    }

    public static function set(string $key, mixed $value): void
    {
        static::query()->updateOrCreate(['key' => $key], ['value' => $value]);

        static::$runtimeSettings = null;
    }

    /**
     * Read a JSON-array setting (e.g. payment_methods_enabled) as a real array.
     *
     * @return array<int, mixed>
     */
    public static function listValue(string $key, array $default = []): array
    {
        $value = static::get($key);

        if (is_array($value)) {
            return $value;
        }

        if ($value === null || $value === '') {
            return $default;
        }

        $decoded = json_decode((string) $value, true);

        return is_array($decoded) ? $decoded : $default;
    }

    /**
     * Read a "0"/"1" toggle setting as a boolean.
     */
    public static function bool(string $key, bool $default = false): bool
    {
        $value = static::get($key);

        if ($value === null || $value === '') {
            return $default;
        }

        return in_array(strtolower((string) $value), ['1', 'true', 'yes', 'on'], true);
    }

    /**
     * Delete a stored file (logo/favicon) together with its setting.
     */
    public static function forgetFile(string $key): void
    {
        if ($path = static::get($key)) {
            Storage::disk('public')->delete($path);
        }

        static::set($key, null);
    }
}
