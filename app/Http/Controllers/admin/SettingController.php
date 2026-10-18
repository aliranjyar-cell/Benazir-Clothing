<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use DateTimeZone;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * The eight sections of the Settings module (overview cards + tab nav).
     *
     * - status "ready" → dedicated form route (route key points at it)
     * - status "soon"  → clickable roadmap page (admin.settings.section)
     */
    public const SECTIONS = [
        [
            'key' => 'general',
            'title' => 'General Store',
            'description' => 'Store identity, contact details, currency and open/closed status.',
            'icon' => 'home',
            'route' => 'admin.settings.general',
            'status' => 'ready',
        ],
        [
            'key' => 'storefront-branding',
            'title' => 'Storefront & Branding',
            'description' => 'Logo, colour palette, typography and storefront theme.',
            'icon' => 'sparkle',
            'route' => 'admin.settings.section',
            'status' => 'soon',
        ],
        [
            'key' => 'products',
            'title' => 'Products & Inventory',
            'description' => 'Low-stock alerts, catalogue page size and stock behaviour.',
            'icon' => 'cube',
            'route' => 'admin.settings.products',
            'status' => 'ready',
        ],
        [
            'key' => 'shipping',
            'title' => 'Shipping & Delivery',
            'description' => 'Flat rates, free-shipping thresholds and delivery estimates.',
            'icon' => 'truck',
            'route' => 'admin.settings.shipping',
            'status' => 'ready',
        ],
        [
            'key' => 'checkout',
            'title' => 'Checkout & Payments',
            'description' => 'Payment methods, guest checkout and bank transfer details.',
            'icon' => 'banknotes',
            'route' => 'admin.settings.checkout',
            'status' => 'ready',
        ],
        [
            'key' => 'orders-notifications',
            'title' => 'Orders & Notifications',
            'description' => 'Order statuses, email alerts and customer updates.',
            'icon' => 'bell',
            'route' => 'admin.settings.section',
            'status' => 'soon',
        ],
        [
            'key' => 'policies-support',
            'title' => 'Policies & Support',
            'description' => 'Returns, privacy and shipping policies, plus support channels.',
            'icon' => 'document-text',
            'route' => 'admin.settings.section',
            'status' => 'soon',
        ],
        [
            'key' => 'account-security',
            'title' => 'Account & Security',
            'description' => 'Admin passwords, sessions, roles and two-factor auth.',
            'icon' => 'key',
            'route' => 'admin.settings.section',
            'status' => 'soon',
        ],
    ];

    /**
     * Settings overview: all eight clickable sections.
     */
    public function index(): View
    {
        return view('admin.settings.overview', [
            'sections' => self::SECTIONS,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | General Store
    |--------------------------------------------------------------------------
    */

    /**
     * Show the General Store Settings form.
     */
    public function general(): View
    {
        return view('admin.settings.general', [
            'settings' => Setting::allSettings(),
            'currencies' => Setting::CURRENCY_SYMBOLS,
            'timezones' => DateTimeZone::listIdentifiers(),
            'sections' => self::SECTIONS,
        ]);
    }

    /**
     * Persist the General Store Settings.
     */
    public function updateGeneral(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'store_name' => ['required', 'string', 'max:100'],
            'store_tagline' => ['nullable', 'string', 'max:200'],
            'contact_email' => ['required', 'email', 'max:190'],
            'contact_phone' => ['nullable', 'string', 'max:40'],
            'address' => ['nullable', 'string', 'max:255'],
            'currency' => ['required', Rule::in(array_keys(Setting::CURRENCY_SYMBOLS))],
            'timezone' => ['required', Rule::in(DateTimeZone::listIdentifiers())],
            'store_status' => ['required', Rule::in(['open', 'maintenance'])],
            'store_logo' => ['nullable', 'image', 'max:2048'],
            'store_favicon' => ['nullable', 'mimes:png,jpg,jpeg,ico,svg,webp', 'max:512'],
        ]);

        foreach (['store_name', 'store_tagline', 'contact_email', 'contact_phone', 'address', 'currency', 'timezone', 'store_status'] as $key) {
            Setting::set($key, $data[$key] ?? null);
        }

        if ($request->hasFile('store_logo')) {
            Setting::set('store_logo', $this->storeFile($request, 'store_logo', 'settings/logo'));
        }

        if ($request->hasFile('store_favicon')) {
            Setting::set('store_favicon', $this->storeFile($request, 'store_favicon', 'settings/favicon'));
        }

        return redirect()
            ->route('admin.settings.general')
            ->with('success', 'Store settings saved.');
    }

    /*
    |--------------------------------------------------------------------------
    | Shipping & Delivery
    |--------------------------------------------------------------------------
    */

    /**
     * Show the Shipping & Delivery settings form.
     */
    public function shipping(): View
    {
        return view('admin.settings.shipping', [
            'settings' => Setting::allSettings(),
            'sections' => self::SECTIONS,
        ]);
    }

    /**
     * Persist the Shipping & Delivery settings.
     */
    public function updateShipping(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'shipping_flat_fee' => ['required', 'numeric', 'min:0', 'max:1000000'],
            'shipping_free_over' => ['nullable', 'numeric', 'min:0', 'max:1000000'],
            'shipping_estimated_days' => ['required', 'string', 'max:60'],
        ]);

        Setting::set('shipping_flat_fee', (string) (0 + $data['shipping_flat_fee']));
        Setting::set('shipping_free_over', isset($data['shipping_free_over']) && $data['shipping_free_over'] !== ''
            ? (string) (0 + $data['shipping_free_over'])
            : '');
        Setting::set('shipping_estimated_days', $data['shipping_estimated_days']);

        return redirect()
            ->route('admin.settings.shipping')
            ->with('success', 'Shipping & delivery settings saved.');
    }

    /*
    |--------------------------------------------------------------------------
    | Checkout & Payments
    |--------------------------------------------------------------------------
    */

    /**
     * Show the Checkout & Payments settings form.
     */
    public function checkout(): View
    {
        return view('admin.settings.checkout', [
            'settings' => Setting::allSettings(),
            'sections' => self::SECTIONS,
            'paymentMethods' => Setting::PAYMENT_METHODS,
            'enabledMethods' => Setting::listValue('payment_methods_enabled', ['cash_on_delivery']),
        ]);
    }

    /**
     * Persist the Checkout & Payments settings.
     */
    public function updateCheckout(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'payment_methods_enabled' => ['required', 'array', 'min:1'],
            'payment_methods_enabled.*' => ['string', Rule::in(array_keys(Setting::PAYMENT_METHODS))],
            'checkout_default_payment_method' => [
                'required',
                'string',
                Rule::in($request->input('payment_methods_enabled', [])),
            ],
            'checkout_allow_guest' => ['nullable', 'in:0,1'],
            'checkout_order_notes' => ['nullable', 'in:0,1'],
            'checkout_bank_details' => ['nullable', 'string', 'max:500'],
        ]);

        Setting::set('payment_methods_enabled', (string) json_encode(array_values($data['payment_methods_enabled'])));
        Setting::set('checkout_default_payment_method', $data['checkout_default_payment_method']);
        Setting::set('checkout_allow_guest', $data['checkout_allow_guest'] ?? '0');
        Setting::set('checkout_order_notes', $data['checkout_order_notes'] ?? '0');
        Setting::set('checkout_bank_details', $data['checkout_bank_details'] ?? '');

        return redirect()
            ->route('admin.settings.checkout')
            ->with('success', 'Checkout & payment settings saved.');
    }

    /*
    |--------------------------------------------------------------------------
    | Products & Inventory
    |--------------------------------------------------------------------------
    */

    /**
     * Show the Products & Inventory settings form.
     */
    public function products(): View
    {
        return view('admin.settings.products', [
            'settings' => Setting::allSettings(),
            'sections' => self::SECTIONS,
        ]);
    }

    /**
     * Persist the Products & Inventory settings.
     */
    public function updateProducts(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'low_stock_threshold' => ['required', 'integer', 'min:1', 'max:100'],
            'products_per_page' => ['required', 'integer', 'min:4', 'max:96'],
        ]);

        Setting::set('low_stock_threshold', (string) (int) $data['low_stock_threshold']);
        Setting::set('products_per_page', (string) (int) $data['products_per_page']);

        return redirect()
            ->route('admin.settings.products')
            ->with('success', 'Products & inventory settings saved.');
    }

    /*
    |--------------------------------------------------------------------------
    | Not-yet-implemented sections
    |--------------------------------------------------------------------------
    */

    /**
     * Roadmap page for sections that are listed but not built yet
     * (Storefront & Branding, Orders & Notifications, ...).
     */
    public function section(string $slug): View
    {
        $section = collect(self::SECTIONS)->firstWhere('key', $slug);

        abort_unless($section !== null, 404);

        return view('admin.settings.section', [
            'section' => $section,
            'sections' => self::SECTIONS,
        ]);
    }

    /**
     * Store an uploaded file on the public disk, removing the previous one.
     */
    private function storeFile(Request $request, string $field, string $directory): string
    {
        if ($old = Setting::get($field)) {
            Storage::disk('public')->delete($old);
        }

        return $request->file($field)->store($directory, 'public');
    }
}
