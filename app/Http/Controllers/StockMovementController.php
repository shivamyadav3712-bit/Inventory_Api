<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    public function stockIn(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'note' => 'nullable'
        ]);

        $product = Product::findOrFail($request->product_id);

        $product->stockIn($request->quantity);

        $movement = StockMovement::create([
            'warehouse_id' => $product->warehouse_id,
            'product_id' => $product->id,
            'type' => 'in',
            'quantity' => $request->quantity,
            'note' => $request->note,
            'created_by' => auth('api')->id()
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Stock added successfully',
            'data' => $movement->load([
                'warehouse',
                'product',
                'createdBy:id,name,email'
            ])
        ]);
    }

    public function stockOut(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'note' => 'nullable'
        ]);

        $product = Product::findOrFail($request->product_id);

        if (!$product->stockOut($request->quantity)) {
            return response()->json([
                'status' => false,
                'message' => 'Not enough stock available'
            ], 400);
        }

        $movement = StockMovement::create([
            'warehouse_id' => $product->warehouse_id,
            'product_id' => $product->id,
            'type' => 'out',
            'quantity' => $request->quantity,
            'note' => $request->note,
            'created_by' => auth('api')->id()
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Stock removed successfully',
            'data' => $movement->load([
                'warehouse',
                'product',
                'createdBy:id,name,email'
            ])
        ]);
    }

    public function history()
    {
        return response()->json([
            'status' => true,
            'data' => StockMovement::with([
                'warehouse',
                'product',
                'createdBy:id,name,email'
            ])->latest()->get()
        ]);
    }
}