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
        Schema::create('connecting_points', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('region_id');
            $table->foreign('region_id')->references('id')->on('regions');
            $table->string('technical_conditions');
            $table->date('technical_conditions_date');
            $table->string('customer');
//            $table->unsignedBigInteger('power_point_id');
//            $table->foreign('power_point_id')->references('id')->on('power_points');
            $table->unsignedBigInteger('city_id');
            $table->foreign('city_id')->references('id')->on('cities');
            $table->unsignedBigInteger('street_id');
            $table->foreign('street_id')->references('id')->on('streets');
            $table->string('building_number');
            $table->text('connecting_note');
            $table->unsignedBigInteger('power_line_type_id');
            $table->foreign('power_line_type_id')->references('id')->on('power_line_types');
            $table->unsignedBigInteger('tp_id');
            $table->foreign('tp_id')->references('id')->on('tp_types');
            $table->string('line');
            $table->string('pole');
            $table->text('power_point_note');
            $table->text('note');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('connecting_points');
    }
};
