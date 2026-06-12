<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'user'])->get();
        return response()->json($products);
    }

    public function create()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'keywords' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'detail' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'minstock' => 'integer|min:0',
            'discount' => 'numeric|min:0|max:100',
            'image' => 'nullable|string|max:255',
            'status' => 'boolean',
        ]);

        $data['user_id'] = auth()->id();

        $product = Product::create($data);

        return response()->json(['message' => 'Product created', 'product' => $product], 201);
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return response()->json(['product' => $product, 'categories' => $categories]);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'keywords' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'detail' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'minstock' => 'integer|min:0',
            'discount' => 'numeric|min:0|max:100',
            'image' => 'nullable|string|max:255',
            'status' => 'boolean',
        ]);

        $product->update($data);

        return response()->json(['message' => 'Product updated', 'product' => $product]);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Product deleted']);
    }
}
