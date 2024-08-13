<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{

    public function up(): void{
        Schema::create('warehouse_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->references('id')->on('products');
            $table->integer('opening_balance')->default(0);
            $table->integer('incoming_balance')->default(0);
            $table->integer('outgoing_balance')->default(0);
            $table->integer('balance')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void{
        Schema::dropIfExists('warehouse_products');
    }
};