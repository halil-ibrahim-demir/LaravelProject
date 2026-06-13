<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Product;

class FrontController extends Controller
{
    public function index()
    {
        $categories = Category::where('status', true)->get();
        $products = Product::where('status', true)->get();
        return view('front.index', compact('categories', 'products'));
    }

    public function categoryProducts($id)
    {
        $category = Category::where('status', true)->findOrFail($id);
        $products = $category->products()->where('status', true)->get();
        $categories = Category::where('status', true)->get();
        return view('front.products', compact('category', 'products', 'categories'));
    }

    public function productDetail($id)
    {
        $product = Product::where('status', true)->findOrFail($id);
        return view('front.product_detail', compact('product'));
    }
}
