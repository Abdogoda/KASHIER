<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{

    public function up(): void{
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('invoice_number');
            $table->tinyInteger('is_returned')->default(0);
            $table->foreignId('shift_id')->references('id')->on('shifts'); //
            $table->foreignId('employee_id')->references('id')->on('employees'); //
            $table->decimal('cost', 10, 1)->default(0);
            $table->string('type')->default('صرف');
            $table->longText('description')->nullable();
            
            $table->date('invoice_date');
            $table->time('invoice_time');
            $table->timestamp('return_date_time')->nullable();
            $table->decimal('return_value', 10, 1)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};