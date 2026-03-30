<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('name')->paginate(10);

        return view('aquaheart.products.index', compact('products'));
    }

    public function create()
    {
        return view('aquaheart.products.form');
    }

    public function store(Request $request)
    {
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
        $product->loadCount('refills');

        return view('aquaheart.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('aquaheart.products.form', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
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
        $product->delete();

        return redirect()->route('aquaheart.products.index')->with('success', 'Product deleted successfully.');
    }
}
