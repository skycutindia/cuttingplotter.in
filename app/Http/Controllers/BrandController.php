<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\View\View;

class BrandController extends Controller
{
    public function index(): View
    {
        $brands = Brand::where('is_active', true)->withCount('products')->orderBy('sort_order')->paginate(12);

        return view('frontend.brands.index', compact('brands'));
    }

    public function show(string $slug): View
    {
        $brand = Brand::where('slug', $slug)->where('is_active', true)->firstOrFail();

        return view('frontend.brands.show', compact('brand'));
    }
}
