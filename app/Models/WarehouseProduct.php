<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseProduct extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'opening_balance', 'balance', 'incoming_balance', 'outgoing_balance'];

    public function product(){
        return $this->belongsTo(Product::class, 'product_id');
    }
}