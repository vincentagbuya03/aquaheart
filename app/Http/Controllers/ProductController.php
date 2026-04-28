<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Only administrators can manage inventory.');
        }

        $query = Product::query();
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        $products = $query->orderBy('name')->paginate(10);
        
        // Calculate storage capacity
        $totalCapacity = Product::sum('stock_quantity') + Product::sum('reorder_level');
        $currentStorage = Product::sum('stock_quantity');
        $storagePercentage = $totalCapacity > 0 ? ($currentStorage / $totalCapacity) * 100 : 0;
        
        // Get low stock product
        $lowStockProduct = $products->filter(fn ($product) => $product->stock_quantity <= $product->reorder_level)->first();
        
        // Get high demand product (first product or configurable)
        $highDemandProduct = $products->first();

        return view('aquaheart.products.index', compact(
            'products',
            'storagePercentage',
            'highDemandProduct',
            'lowStockProduct'
        ));
    }

    public function create()
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Only administrators can add products.');
        }

        return view('aquaheart.products.form');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Only administrators can add products.');
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'reorder_level' => 'required|integer|min:0',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        Product::create($data);

        return redirect()->route('aquaheart.products.index')->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Only administrators can view product details.');
        }

        $product->loadCount('refills');

        return view('aquaheart.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Only administrators can edit products.');
        }

        return view('aquaheart.products.form', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Only administrators can edit products.');
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'reorder_level' => 'required|integer|min:0',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $product->update($data);

        return redirect()->route('aquaheart.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Only administrators can delete products.');
        }

        $product->delete();

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'message' => 'Product deleted successfully.',
            ]);
        }

        return redirect()->route('aquaheart.products.index')->with('success', 'Product deleted successfully.');
    }
}
