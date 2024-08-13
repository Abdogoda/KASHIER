<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{

    public function up(): void{
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('password');
            $table->foreignId('role_id')->references('id')->on('roles');
            $table->string('status')->default('مفعل'); // مفعل و غير مفعل
            $table->timestamps();
        });
    }

    public function down(): void{
        Schema::dropIfExists('employees');
    }
};