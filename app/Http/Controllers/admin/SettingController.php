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
     * Show the General Store Settings form.
     */
    public function index(): View
    {
        return view('admin.settings.index', [
            'settings' => Setting::allSettings(),
            'currencies' => Setting::CURRENCY_SYMBOLS,
            'timezones' => DateTimeZone::listIdentifiers(),
        ]);
    }

    /**
     * Persist the General Store Settings.
     */
    public function update(Request $request): RedirectResponse
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
            ->route('admin.settings.index')
            ->with('success', 'Store settings saved.');
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