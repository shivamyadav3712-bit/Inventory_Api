<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => true,
            'data' => Warehouse::with('manager')->latest()->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:warehouses,name',
            'manager_id' => 'nullable|exists:users,id',
        ]);

        $warehouse = Warehouse::createWarehouse($request);

        return response()->json([
            'status' => true,
            'message' => 'Warehouse created successfully',
            'data' => $warehouse
        ], 201);
    }

    public function show(Warehouse $warehouse)
    {
        return response()->json([
            'status' => true,
            'data' => $warehouse->load('manager')
        ]);
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $request->validate([
            'name' => 'required',
            'manager_id' => 'nullable|exists:users,id',
        ]);

        $warehouse->updateWarehouse($request);

        return response()->json([
            'status' => true,
            'message' => 'Warehouse updated successfully',
            'data' => $warehouse
        ]);
    }

    public function destroy(Warehouse $warehouse)
    {
        $warehouse->delete();

        return response()->json([
            'status' => true,
            'message' => 'Warehouse deleted successfully'
        ]);
    }

    public function myWarehouse()
        {
    return response()->json([
        'status' => true,
        'data' => Warehouse::getManagerWarehouse()
    ]);
    }
}