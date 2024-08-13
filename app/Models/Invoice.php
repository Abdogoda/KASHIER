<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model{

    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'is_returned',
        'shift_id',
        'employee_id',
        'cost',
        'type',
        'description',
        'invoice_date',
        'invoice_time',
        'return_value',
        'return_date_time',
    ];

    public function employee(){
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function shift(){
        return $this->belongsTo(Shift::class, 'shift_id');
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