<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => true,
            'data' => Category::with('warehouse')->latest()->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'name' => 'required',
        ]);

        $category = Category::createCategory($request);

        return response()->json([
            'status' => true,
            'message' => 'Category created successfully',
            'data' => $category
        ], 201);
    }

    public function show(Category $category)
    {
        return response()->json([
            'status' => true,
            'data' => $category->load('warehouse')
        ]);
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'name' => 'required',
        ]);

        $category->updateCategory($request);

        return response()->json([
            'status' => true,
            'message' => 'Category updated successfully',
            'data' => $category
        ]);
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json([
            'status' => true,
            'message' => 'Category deleted successfully'
        ]);
    }

    public function myCategories()
    {
    return response()->json([
        'status' => true,
        'data' => Category::getManagerCategories()
    ]);
    }
}