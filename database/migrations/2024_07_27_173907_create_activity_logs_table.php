<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{

    public function up(): void{
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->text('action');
            $table->string('type'); // مصادقة, منتجات ومخزن, فواتير, الوردية
            $table->foreignId('employee_id')->references('id')->on('employees');
            $table->timestamps();
        });
    }

    public function down(): void{
        Schema::dropIfExists('activity_logs');
    }
};