<?php

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
        Schema::create('connecting_point_work_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('work_type_id');
            $table->foreign('work_type_id')->references('id')->on('work_types');
            $table->unsignedBigInteger('connecting_point_id');
            $table->foreign('connecting_point_id')->references('id')->on('connecting_points');
            $table->unique(['work_type_id', 'connecting_point_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('connecting_point_work_types');
    }
};
