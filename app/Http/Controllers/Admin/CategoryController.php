<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('parent')->get();
        return response()->json($categories);
    }

    public function create()
    {
        $categories = Category::whereNull('parent_id')->get();
        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'status' => 'boolean',
        ]);

        $category = Category::create($data);

        return response()->json(['message' => 'Category created', 'category' => $category], 201);
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $parents = Category::where('id', '!=', $id)->get();
        return response()->json(['category' => $category, 'parents' => $parents]);
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id|different:id',
            'status' => 'boolean',
        ]);

        $category->update($data);

        return response()->json(['message' => 'Category updated', 'category' => $category]);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json(['message' => 'Category deleted']);
    }
}
