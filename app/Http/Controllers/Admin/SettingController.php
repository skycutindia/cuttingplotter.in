<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function __construct(protected SettingsService $settings) {}

    public function index(): View
    {
        $settings = $this->settings->all();

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'site_name' => 'nullable|string|max:255',
            'site_tagline' => 'nullable|string|max:500',
            'company_name' => 'nullable|string|max:255',
            'company_address' => 'nullable|string',
            'company_phone' => 'nullable|string|max:20',
            'company_whatsapp' => 'nullable|string|max:20',
            'company_email' => 'nullable|email|max:255',
            'company_gst' => 'nullable|string|max:20',
            'google_analytics' => 'nullable|string',
            'google_tag_manager' => 'nullable|string',
            'facebook_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'youtube_url' => 'nullable|url',
            'linkedin_url' => 'nullable|url',
            'default_meta_title' => 'nullable|string|max:255',
            'default_meta_description' => 'nullable|string',
            'custom_css' => 'nullable|string',
            'custom_js' => 'nullable|string',
        ]);

        $this->settings->setMany($validated);

        return redirect()->route('admin.settings.index')->with('success', 'Settings updated successfully.');
    }
}
