<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Lead;
use App\Models\Product;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'products' => Product::count(),
            'brands' => Brand::count(),
            'categories' => Category::count(),
            'blogs' => Blog::count(),
            'leads' => Lead::count(),
            'new_leads' => Lead::where('status', 'new')->count(),
            'customers' => User::role('customer')->count(),
            'low_stock' => Product::where('stock', '<', 5)->count(),
        ];

        $recentLeads = Lead::latest()->limit(5)->get();
        $topProducts = Product::orderByDesc('views')->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'recentLeads', 'topProducts'));
    }
}
