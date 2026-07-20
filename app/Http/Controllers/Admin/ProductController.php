<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Services\ProductService;
use App\Repositories\ProductRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(
        protected ProductRepository $repository,
        protected ProductService $service
    ) {}

    public function index(Request $request): View
    {
        $products = $this->repository->paginateWithRelations(15, $request->only('search', 'brand_id', 'category_id', 'is_active'));
        $brands = Brand::orderBy('name')->get();
        $categories = Category::whereNull('parent_id')->orderBy('name')->get();

        return view('admin.products.index', compact('products', 'brands', 'categories'));
    }

    public function create(): View
    {
        $brands = Brand::where('is_active', true)->orderBy('name')->get();
        $categories = Category::whereNull('parent_id')->with('children')->orderBy('name')->get();

        return view('admin.products.create', compact('brands', 'categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku',
            'brand_id' => 'nullable|exists:brands,id',
            'category_id' => 'nullable|exists:categories,id',
            'subcategory_id' => 'nullable|exists:categories,id',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'offer_price' => 'nullable|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'hsn' => 'nullable|string|max:20',
            'gst_rate' => 'nullable|numeric|min:0|max:100',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'images.*' => 'nullable|image|max:5120',
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['features'] = array_filter($request->input('features', []));

        $this->service->create(
            $validated,
            $request->file('images', []),
            $request->input('specifications', [])
        );

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(int $id): View
    {
        $product = $this->repository->find($id);
        $product->load(['images', 'specifications', 'brand', 'category']);
        $brands = Brand::where('is_active', true)->orderBy('name')->get();
        $categories = Category::whereNull('parent_id')->with('children')->orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'brands', 'categories'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku,'.$id,
            'brand_id' => 'nullable|exists:brands,id',
            'category_id' => 'nullable|exists:categories,id',
            'subcategory_id' => 'nullable|exists:categories,id',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'offer_price' => 'nullable|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'images.*' => 'nullable|image|max:5120',
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active');

        $this->service->update($id, $validated, $request->file('images', []), $request->input('specifications', []));

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->repository->delete($id);

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }

    public function clone(int $id): RedirectResponse
    {
        $product = $this->service->clone($id);

        return redirect()->route('admin.products.edit', $product->id)->with('success', 'Product cloned successfully.');
    }
}
