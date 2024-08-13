<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model{

    use HasFactory;

    protected $fillable = ['employee_id' ,'day' ,'start_date' ,'start_time' ,'end_date' ,'end_time' ,'initial_amount' ,'total_amount' ,'added_amount' ,'withdraw_amount', 'difference_amount' ,'status'];

    public function employee(){
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function payments(){
        return $this->hasMany(Payment::class, 'shift_id');
    }

    public function selling_invoices(){
        return $this->hasMany(SellingInvoice::class, 'shift_id');
    }

    public function purchasing_invoices(){
        return $this->hasMany(PurchasingInvoice::class, 'shift_id');
    }

    public function invoices(){
        return $this->hasMany(Invoice::class, 'shift_id');
    }
}