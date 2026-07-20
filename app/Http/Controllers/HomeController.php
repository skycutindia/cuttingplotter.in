<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Blog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Testimonial;
use App\Repositories\ProductRepository;
use App\Services\SettingsService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        protected ProductRepository $products,
        protected SettingsService $settings
    ) {}

    public function index(): View
    {
        $featuredProducts = $this->products->featured(8);
        $brands = Brand::where('is_active', true)->where('is_featured', true)->orderBy('sort_order')->get();
        $categories = Category::whereNull('parent_id')->where('is_active', true)->where('is_featured', true)->with('children')->orderBy('sort_order')->get();
        $banners = Banner::active()->where('location', 'homepage_hero')->orderBy('sort_order')->get();
        $testimonials = Testimonial::where('is_active', true)->where('is_featured', true)->orderBy('sort_order')->limit(6)->get();
        $blogs = Blog::where('status', 'published')->latest('published_at')->limit(3)->get();
        $settings = $this->settings->all();

        return view('frontend.home', compact(
            'featuredProducts', 'brands', 'categories', 'banners',
            'testimonials', 'blogs', 'settings'
        ));
    }
}
