<?php

namespace App\Models;

use App\Models\StockMovement;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'warehouse_id',
        'category_id',
        'name',
        'product_code',
        'price',
        'quantity',
        'description'
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public static function getAllProducts()
    {
        return self::with(['warehouse', 'category'])
            ->latest()
            ->get();
    }

    public static function validationRules($id = null)
    {
        return [
            'warehouse_id' => 'required|exists:warehouses,id',
            'category_id' => 'required|exists:categories,id',
            'name' => 'required',
            'product_code' => 'required|unique:products,product_code,' . $id,
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
        ];
    }

    public static function createProduct($request)
    {
        return self::create($request->only([
            'warehouse_id',
            'category_id',
            'name',
            'product_code',
            'price',
            'quantity',
            'description'
        ]));
    }

    public function updateProduct($request)
    {
        return $this->update($request->only([
            'warehouse_id',
            'category_id',
            'name',
            'product_code',
            'price',
            'quantity',
            'description'
        ]));
    }

    public static function getManagerProducts()
    {
        $managerId = auth('api')->id();

        return self::whereHas('warehouse', function ($query) use ($managerId) {
            $query->where('manager_id', $managerId);
        })
        ->with(['warehouse', 'category'])
        ->latest()
        ->get();
    }

    public static function getWarehouseProducts($warehouseId)
    {
        return self::with('category')
            ->where('warehouse_id', $warehouseId)
            ->latest()
            ->get();
    }

    public function stockIn($quantity)
    {
        $this->quantity += $quantity;
        return $this->save();
    }

    public function stockOut($quantity)
    {
        if ($this->quantity < $quantity) {
            return false;
        }

        $this->quantity -= $quantity;
        return $this->save();
    }
}