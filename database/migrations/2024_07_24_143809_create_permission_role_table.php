<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{

    public function up(): void{
        Schema::create('permission_role', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->refrences('id')->on('roles');
            $table->foreignId('permission_id')->refrences('id')->on('permissions');
            $table->timestamps();
        });
    }

    public function down(): void{
        Schema::dropIfExists('role_permissions');
    }
};