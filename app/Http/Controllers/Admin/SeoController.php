<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Redirect;
use App\Services\SeoService;
use App\Services\SettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SeoController extends Controller
{
    public function __construct(
        protected SeoService $seo,
        protected SettingsService $settings
    ) {}

    public function index(): View
    {
        $redirects = Redirect::latest()->paginate(15, ['*'], 'redirects_page');
        $settings = $this->settings->all();

        return view('admin.seo.index', compact('redirects', 'settings'));
    }

    public function storeRedirect(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'from_url' => 'required|string|max:500|unique:redirects,from_url',
            'to_url' => 'required|string|max:500',
            'status_code' => 'required|in:301,302',
            'is_active' => 'boolean',
        ]);

        $validated['from_url'] = '/'.ltrim($validated['from_url'], '/');
        $validated['is_active'] = $request->boolean('is_active', true);

        Redirect::create($validated);

        return back()->with('success', 'Redirect created.');
    }

    public function updateRedirect(Request $request, Redirect $redirect): RedirectResponse
    {
        $validated = $request->validate([
            'from_url' => 'required|string|max:500|unique:redirects,from_url,'.$redirect->id,
            'to_url' => 'required|string|max:500',
            'status_code' => 'required|in:301,302',
            'is_active' => 'boolean',
        ]);

        $validated['from_url'] = '/'.ltrim($validated['from_url'], '/');
        $validated['is_active'] = $request->boolean('is_active');
        $redirect->update($validated);

        return back()->with('success', 'Redirect updated.');
    }

    public function destroyRedirect(Redirect $redirect): RedirectResponse
    {
        $redirect->delete();

        return back()->with('success', 'Redirect deleted.');
    }

    public function updateRobots(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'robots_txt' => 'nullable|string|max:10000',
        ]);

        $this->settings->setMany([
            'robots_txt' => $validated['robots_txt'] ?? "User-agent: *\nAllow: /\nSitemap: ".url('/sitemap.xml'),
        ], 'seo');

        return back()->with('success', 'robots.txt updated.');
    }

    public function updateGlobalSeo(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'default_meta_title' => 'nullable|string|max:255',
            'default_meta_description' => 'nullable|string|max:500',
            'default_meta_keywords' => 'nullable|string|max:500',
            'og_image' => 'nullable|string|max:500',
            'twitter_handle' => 'nullable|string|max:100',
        ]);

        $this->settings->setMany($validated, 'seo');

        return back()->with('success', 'Global SEO settings updated.');
    }

    public function clearSitemapCache(): RedirectResponse
    {
        $this->seo->clearSitemapCache();

        return back()->with('success', 'Sitemap cache cleared. It will regenerate on next request.');
    }
}
