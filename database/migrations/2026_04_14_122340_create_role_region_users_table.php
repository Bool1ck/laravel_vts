<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('role_region_users', function (Blueprint $table) {
            $table->id();
            // роль користувача в регіоні
            $table->unsignedBigInteger('role_id');
            $table->foreign('role_id')->references('id')->on('roles');
            // до якого регіону прив'язаний користувач
            $table->unsignedBigInteger('region_id');
            $table->foreign('region_id')->references('id')->on('regions');
            // id користувача, прив'язаного до регіону
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unique(['region_id', 'user_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_region_users');
    }
};
