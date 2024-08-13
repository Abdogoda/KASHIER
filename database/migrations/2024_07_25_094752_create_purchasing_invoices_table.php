<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{

    public function up(): void{
        Schema::create('purchasing_invoices', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('invoice_number');
            $table->tinyInteger('is_returned')->default(0);
            $table->string('supplier_type'); // مورد و مورد لحظي
            $table->string('payment_method'); // نقدية و أجل
            
            $table->foreignId('shift_id')->references('id')->on('shifts'); //
            $table->foreignId('employee_id')->references('id')->on('employees'); //
            $table->foreignId('supplier_id')->nullable()->references('id')->on('suppliers'); //
            $table->string('supplier_name')->default('غير محدد');
            $table->string('supplier_phone')->default('غير محدد');
            
            $table->string('payment_status')->default('معلق'); // تم الدفع و معلق
            
            $table->decimal('cost_before_discount', 10, 1)->default(0);
            $table->float('discount_rate')->default(0.0);
            $table->float('discount_value')->default(0.0);
            $table->decimal('cost_after_discount', 10, 1)->default(0);
            $table->decimal('total_cost', 10, 1)->default(0);
            
            $table->decimal('return_value', 10, 1)->default(0);
            
            $table->decimal('paid', 10, 1)->default(0);
            $table->decimal('over_paid', 10, 1)->default(0);
            $table->decimal('account_before', 10, 1)->default(0);
            $table->decimal('remaining', 10, 1)->default(0);
            
            $table->decimal('product_count', 5)->default(0);
            
            $table->string('status')->default('مفعل'); // مفعل و مؤرشف
            $table->text('description')->nullable(); 

            $table->date('invoice_date');
            $table->time('invoice_time');
            $table->timestamp('return_date_time')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void{
        Schema::dropIfExists('purchasing_invoices');
    }
};