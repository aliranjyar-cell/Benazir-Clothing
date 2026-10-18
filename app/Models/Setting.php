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

    protected $fillable = ['key', 'value'];

    /**
     * Per-request memo of every setting (defaults merged with saved values).
     */
    protected static ?array $runtimeSettings = null;

    /**
     * Defaults used until an admin saves an override.
     */
    public static function defaults(): array
    {
        return [
            'store_name' => 'BENAZIR',
            'store_tagline' => 'Lahore • London • New York',
            'store_logo' => null,
            'store_favicon' => null,
            'contact_email' => 'contact@benazirclothes.com',
            'contact_phone' => '',
            'address' => '',
            'currency' => 'PKR',
            'timezone' => 'Asia/Karachi',
            'store_status' => 'open',
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