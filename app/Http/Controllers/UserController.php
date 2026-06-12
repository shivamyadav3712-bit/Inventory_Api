<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Models\Category;
use App\Models\Product;

class UserController extends Controller
{
    public function warehouses()
    {
        return response()->json([
            'status' => true,
            'data' => Warehouse::getWarehousesForUser()
        ]);
    }

    public function warehouseCategories($warehouseId)
    {
        return response()->json([
            'status' => true,
            'data' => Category::getWarehouseCategories($warehouseId)
        ]);
    }

    public function warehouseProducts($warehouseId)
    {
        return response()->json([
            'status' => true,
            'data' => Product::getWarehouseProducts($warehouseId)
        ]);
    }

    public function warehouseDetails($warehouseId)
    {
        $warehouse = Warehouse::with([
            'categories',
            'products.category'
        ])->find($warehouseId);

        if (!$warehouse) {
            return response()->json([
                'status' => false,
                'message' => 'Warehouse not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $warehouse
        ]);
    }
}