<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(protected ProductRepository $repository) {}

    public function index(Request $request): View
    {
        $products = $this->repository->frontendPaginate($request->only('brand', 'category', 'search', 'sort'));
        $brands = Brand::where('is_active', true)->orderBy('name')->get();
        $categories = Category::whereNull('parent_id')->where('is_active', true)->with('children')->orderBy('name')->get();

        return view('frontend.products.index', compact('products', 'brands', 'categories'));
    }

    public function show(string $slug): View
    {
        $product = $this->repository->findBySlugWithRelations($slug);

        if (! $product) {
            abort(404);
        }

        $product->increment('views');

        return view('frontend.products.show', compact('product'));
    }
}
