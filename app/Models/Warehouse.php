<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $fillable = [
        'name',
        'address',
        'city',
        'state',
        'country',
        'zip_code',
        'manager_id'
    ];

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public static function createWarehouse($request)
    {
        return self::create($request->only([
            'name',
            'address',
            'city',
            'state',
            'country',
            'zip_code',
            'manager_id'
        ]));
    }

    public function updateWarehouse($request)
    {
        return $this->update($request->only([
            'name',
            'address',
            'city',
            'state',
            'country',
            'zip_code',
            'manager_id'
        ]));
    }

    public static function getManagerWarehouse()
    {
        return self::where('manager_id', auth('api')->id())->get();
    }

    public static function getWarehousesForUser()
    {
        return self::latest()->get();
    }
}