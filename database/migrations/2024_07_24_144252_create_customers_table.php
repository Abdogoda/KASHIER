<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{

    public function up(): void{
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable()->unique()->default('غير محدد');
            $table->string('phone')->nullable()->default('غير محدد');
            $table->text('address')->nullable()->default('غير محدد');
            $table->foreignId('segment_id')->nullable()->references('id')->on('segments');
            $table->decimal('account', 10, 1)->default(0);
            $table->string('status')->default('مفعل'); // مفعل و غير مفعل
            $table->timestamps();
        });
    }

    public function down(): void{
        Schema::dropIfExists('customers');
    }
};