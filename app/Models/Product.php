<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Product extends Model{

    use HasFactory;
    
    protected static $cacheKey = 'products.all';

    protected $fillable = [
        'name',
        'description',
        'selling_price',
        'purchasing_price',
        'status',
    ];

    public function warehouseProduct(){
        return $this->hasOne(WarehouseProduct::class, 'product_id');
    }

    public function segments(){
        return $this->hasMany(ProductSegment::class, 'product_id');
    }

    protected static function boot(){
        parent::boot();

        static::saved(function () {
            Cache::forever('all_products', Product::all());
        });

        static::deleted(function () {
            Cache::forever('all_products', Product::all());
        });
    }
}