<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
'shift_id',
'customer_id',
'supplier_id',
'employee_id',
'invoice_id',
'before_balance',
'after_balance',
'amount','payment_type'
    ];

    public function employee (){
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function shift (){
        return $this->belongsTo(Shift::class, 'shift_id');
    }
}