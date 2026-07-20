<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ProductsExport;
use App\Http\Controllers\Controller;
use App\Imports\ProductsImport;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ProductBulkController extends Controller
{
    public function index(): View
    {
        $brands = Brand::orderBy('name')->get();
        $categories = Category::whereNull('parent_id')->orderBy('name')->get();

        return view('admin.products.bulk', compact('brands', 'categories'));
    }

    public function export(Request $request): BinaryFileResponse
    {
        $filters = $request->only('brand_id', 'category_id', 'is_active');
        $filename = 'products-'.now()->format('Y-m-d-His').'.xlsx';

        return Excel::download(new ProductsExport($filters), $filename);
    }

    public function exportSelected(Request $request): BinaryFileResponse|RedirectResponse
    {
        $request->validate(['ids' => 'required|array|min:1', 'ids.*' => 'integer|exists:products,id']);

        return Excel::download(
            new ProductsExport(['ids' => $request->ids]),
            'products-selected-'.now()->format('Y-m-d-His').'.xlsx'
        );
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        $import = new ProductsImport;
        Excel::import($import, $request->file('file'));

        return redirect()->route('admin.products.bulk')
            ->with('success', "Import complete: {$import->created} created, {$import->updated} updated, {$import->skipped} skipped.");
    }

    public function template(): BinaryFileResponse
    {
        $headers = [[
            'SKU', 'Product Code', 'Name', 'Slug', 'Brand', 'Category',
            'Short Description', 'Price', 'Offer Price', 'Stock', 'HSN', 'GST Rate',
            'Weight', 'Dimensions', 'Meta Title', 'Meta Description',
            'Is Featured', 'Is Trending', 'Is New Arrival', 'Is Best Seller', 'Is Active',
        ], [
            'DEMO-SKU-001', 'PC-001', 'Sample Vinyl Cutter 24"', 'sample-vinyl-cutter-24',
            'Skycut', 'Cutting Plotters', 'Demo product short description',
            '85000', '79999', '10', '8443', '18', '25', '60x40x30',
            'Sample Vinyl Cutter | Cutting Plotter India', 'Buy sample vinyl cutter',
            '1', '0', '1', '0', '1',
        ]];

        return Excel::download(
            new class($headers) implements \Maatwebsite\Excel\Concerns\FromArray
            {
                public function __construct(private array $rows) {}

                public function array(): array
                {
                    return $this->rows;
                }
            },
            'product-import-template.xlsx'
        );
    }

    public function bulkUpdate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:products,id',
            'action' => 'required|in:activate,deactivate,feature,unfeature,delete',
        ]);

        $query = Product::whereIn('id', $validated['ids']);
        $count = $query->count();

        match ($validated['action']) {
            'activate' => $query->update(['is_active' => true]),
            'deactivate' => $query->update(['is_active' => false]),
            'feature' => $query->update(['is_featured' => true]),
            'unfeature' => $query->update(['is_featured' => false]),
            'delete' => $query->delete(),
        };

        return back()->with('success', "Bulk action applied to {$count} product(s).");
    }
}
