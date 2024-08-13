<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{

    public function up(): void{
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->references('id')->on('employees');
            
            $table->string('day');
            $table->date('start_date');
            $table->time('start_time');
            $table->date('end_date')->nullable();
            $table->time('end_time')->nullable();
            
            $table->decimal('initial_amount', 15, 1)->default(0);
            $table->decimal('total_amount', 15, 1)->default(0);
            $table->decimal('added_amount', 15, 1)->default(0);
            $table->decimal('withdraw_amount', 15, 1)->default(0); 
            $table->decimal('difference_amount', 15, 1)->default(0); 

            $table->string('status')->default('جارية');

            $table->timestamps();
        });
    }

    public function down(): void{
        Schema::dropIfExists('shifts');
    }
};