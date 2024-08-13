<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasingInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'supplier_type',
        'shift_id',
        'employee_id',
        'supplier_id',
        'supplier_name',
        'supplier_phone',
        
        'payment_method',
        'payment_status',

        'cost_before_discount',
        'cost_after_discount',
        'total_cost',
        'paid',
        'remaining',
        'status',
        'description',
        'invoice_date',
        'invoice_time',
        
        'is_returned',
        'return_value',
        'return_date_time',
        'over_paid',
        'account_before',
        'product_count',
    ];


    public function shift (){
        return $this->belongsTo(Shift::class, 'shift_id');
    }

    public function supplier (){
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function employee (){
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function products (){
        return $this->hasMany(InvoiceProduct::class, 'purchasing_invoice_id');
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