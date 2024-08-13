<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    
    public function up(): void{
        Schema::create('fund_accounts', function (Blueprint $table) {
            $table->id();
            $table->decimal('balance', 15, 1)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void{
        Schema::dropIfExists('fund_accounts');
    }
};