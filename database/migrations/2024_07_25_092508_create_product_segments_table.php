<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{

    public function up(): void{
        Schema::create('product_segments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->references('id')->on('products');
            $table->foreignId('segment_id')->references('id')->on('segments');
            $table->decimal('segment_price', 8, 1)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void{
        Schema::dropIfExists('product_segments');
    }
};