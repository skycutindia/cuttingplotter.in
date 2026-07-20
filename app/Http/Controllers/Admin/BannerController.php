<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BannerController extends Controller
{
    public function index(Request $request): View
    {
        $query = Banner::query()->orderBy('sort_order');

        if ($request->filled('location')) {
            $query->where('location', $request->location);
        }

        $banners = $query->paginate(15);
        $locations = $this->locations();

        return view('admin.banners.index', compact('banners', 'locations'));
    }

    public function create(): View
    {
        return view('admin.banners.create', ['locations' => $this->locations()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateBanner($request);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('banners', 'public');
        }
        if ($request->hasFile('mobile_image')) {
            $validated['mobile_image'] = $request->file('mobile_image')->store('banners', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active', true);
        Banner::create($validated);

        return redirect()->route('admin.banners.index')->with('success', 'Banner created.');
    }

    public function edit(Banner $banner): View
    {
        return view('admin.banners.edit', ['banner' => $banner, 'locations' => $this->locations()]);
    }

    public function update(Request $request, Banner $banner): RedirectResponse
    {
        $validated = $this->validateBanner($request);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('banners', 'public');
        }
        if ($request->hasFile('mobile_image')) {
            $validated['mobile_image'] = $request->file('mobile_image')->store('banners', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');
        $banner->update($validated);

        return redirect()->route('admin.banners.index')->with('success', 'Banner updated.');
    }

    public function destroy(Banner $banner): RedirectResponse
    {
        $banner->delete();

        return redirect()->route('admin.banners.index')->with('success', 'Banner deleted.');
    }

    protected function validateBanner(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'location' => 'required|string|max:50',
            'image' => 'nullable|image|max:5120',
            'mobile_image' => 'nullable|image|max:5120',
            'video_url' => 'nullable|url|max:500',
            'link' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'sort_order' => 'nullable|integer|min:0',
        ]);
    }

    protected function locations(): array
    {
        return [
            'homepage_hero' => 'Homepage Hero',
            'homepage_sidebar' => 'Homepage Sidebar',
            'category_top' => 'Category Page Top',
            'brand_top' => 'Brand Page Top',
            'popup' => 'Popup Banner',
            'offer' => 'Offer Banner',
        ];
    }
}
