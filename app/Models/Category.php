<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'warehouse_id',
        'name',
        'description'
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public static function createCategory($request)
    {
        return self::create($request->only([
            'warehouse_id',
            'name',
            'description'
        ]));
    }

    public function updateCategory($request)
    {
        return $this->update($request->only([
            'warehouse_id',
            'name',
            'description'
        ]));
    }

    public static function getManagerCategories()
    {
        $managerId = auth('api')->id();

        return self::whereHas('warehouse', function ($query) use ($managerId) {
            $query->where('manager_id', $managerId);
        })->with('warehouse')->latest()->get();
    }

    public static function getWarehouseCategories($warehouseId)
    {
        return self::where('warehouse_id', $warehouseId)
            ->latest()
            ->get();
    }
}