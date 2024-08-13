<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellingInvoice extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoice_number',
        'is_returned',
        'customer_type',
        'payment_method',

        'shift_id',
        'employee_id',
        'customer_id',
        'customer_name',
        'customer_phone',

        'delivery_status',
        'payment_status',
        'payment_fund_status',

        'cost_before_discount',
        'discount_rate',
        'discount_value',
        'cost_after_discount',
        'total_cost',
        'return_value',

        'paid',
        'remaining',
        'account_before',
        'account_after',
        'over_paid',
        'profits',

        'product_count',

        'status',
        'description',
        'invoice_date',
        'invoice_time',
        'return_date_time',

    ];

    public function shift (){
        return $this->belongsTo(Shift::class, 'shift_id');
    }

    public function customer (){
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function employee (){
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function products (){
        return $this->hasMany(InvoiceProduct::class, 'selling_invoice_id');
    }

    public static function boot(){
        parent::boot();
        static::creating(function ($invoice) {
            $invoice->invoice_number = self::generateInvoiceNumber();
        });
    }

    public static function generateInvoiceNumber(){
        $date = Carbon::now()->format('Ymd');
        $lastInvoice = self::whereDate('created_at', Carbon::today())->orderBy('id', 'desc')->first();
        if ($lastInvoice) {
            $lastNumber = (int)substr($lastInvoice->invoice_number, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        return $date . $newNumber;
    }
}