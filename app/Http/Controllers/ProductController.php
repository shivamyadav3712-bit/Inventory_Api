<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => true,
            'data' => Product::getAllProducts()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate(Product::validationRules());

        $product = Product::createProduct($request);

        return response()->json([
            'status' => true,
            'message' => 'Product created successfully',
            'data' => $product
        ], 201);
    }

    public function show(Product $product)
    {
        return response()->json([
            'status' => true,
            'data' => $product->load(['warehouse', 'category'])
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $request->validate(Product::validationRules($product->id));

        $product->updateProduct($request);

        return response()->json([
            'status' => true,
            'message' => 'Product updated successfully',
            'data' => $product
        ]);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'status' => true,
            'message' => 'Product deleted successfully'
        ]);
    }

    public function myProducts()
    {  
    return response()->json([
        'status' => true,
        'data' => Product::getManagerProducts()
    ]);
    }
}