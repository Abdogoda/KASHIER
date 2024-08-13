<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{

    public function up(): void{
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('type'); //  شراء | بيع | صرف
            $table->string('payment_type'); //  صرف | قبض
            $table->foreignId('shift_id')->nullable()->references('id')->on('shifts');
            $table->foreignId('customer_id')->nullable()->references('id')->on('customers');
            $table->foreignId('supplier_id')->nullable()->references('id')->on('suppliers');
            $table->foreignId('employee_id')->nullable()->references('id')->on('employees');
            $table->bigInteger('invoice_id')->nullable();

            $table->decimal('before_balance', 15, 1)->default(0.00);
            $table->decimal('after_balance', 15, 1)->default(0.00);
            $table->decimal('amount', 10, 1);
            $table->timestamps();
        });
    }

    public function down(): void{
        Schema::dropIfExists('payments');
    }
};