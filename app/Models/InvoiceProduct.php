<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceProduct extends Model{

    use HasFactory;

    protected $fillable = [ 
        'selling_invoice_id', 
        'purchasing_invoice_id', 
        'product_id', 
        'price', 
        'quantity', 
        'total_price',
        'type'
    ];

        public function product(){
            return $this->belongsTo(Product::class, 'product_id');
        }

        public function selling_invoice(){
            return $this->belongsTo(SellingInvoice::class, 'selling_invoice_id');
        }

        public function purchasing_invoice(){
            return $this->belongsTo(PurchasingInvoice::class, 'purchasing_invoice_id');
        }
}