<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model{

    use HasFactory;

    protected $fillable = [ 'name', 'email', 'phone', 'account', 'segment_id', 'address', 'status'];


    public function segment(){
        return $this->belongsTo(Segment::class, 'segment_id');
    }

    public function invoices(){
        return $this->hasMany(SellingInvoice::class, 'customer_id');
    }

    public function getTotalRemaining(){
        $totalRemaining = $this->invoices()->sum('remaining');
        $totalRemainingForCustomer = $this->invoices()->sum('remaining_for_customer');

        return $totalRemaining - $totalRemainingForCustomer;
    }
}