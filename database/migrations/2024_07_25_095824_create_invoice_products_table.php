<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{

    public function up(): void{
        Schema::create('invoice_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('selling_invoice_id')->nullable()->references('id')->on('selling_invoices');
            $table->foreignId('purchasing_invoice_id')->nullable()->references('id')->on('purchasing_invoices');
            $table->foreignId('product_id')->references('id')->on('products');
            $table->decimal('price', 10, 1)->default(0);
            $table->integer('quantity')->default(0);
            $table->decimal('total_price', 10, 1)->default(0);
            $table->tinyInteger('type')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void{
        Schema::dropIfExists('invoice_products');
    }
};