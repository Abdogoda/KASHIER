<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model{

    use HasFactory;

    
    protected $fillable = [ 'name', 'email', 'phone', 'account', 'address', 'status'];


    public function invoices(){
        return $this->hasMany(PurchasingInvoice::class, 'supplier_id');
    }
    
}