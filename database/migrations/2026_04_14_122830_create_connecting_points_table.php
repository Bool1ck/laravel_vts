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
            /* for prod */
//            $table->unique(['region_id', 'technical_conditions']);
            /* for prod */
            $table->date('technical_conditions_date');
            $table->string('customer');
            $table->unsignedBigInteger('customer_type_id');
            $table->foreign('customer_type_id')->references('id')->on('customer_types');
            $table->string('point_place');
            $table->string('power_point');
            $table->unsignedBigInteger('power');
            $table->date('contract_date')->nullable();
            $table->date('payment_date')->nullable();
            $table->date('perform_by_date')->nullable();
            $table->date('performance_date')->nullable();
            $table->date('materials_order_date')->nullable();
            $table->date('materials_receipt_date')->nullable();
            $table->text('note')->nullable();
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
