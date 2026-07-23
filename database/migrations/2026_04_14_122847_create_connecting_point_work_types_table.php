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
        // проміжна таблиця видів робіт по точці приєднання
        Schema::create('connecting_point_work_types', function (Blueprint $table) {
            $table->id();
            // id виду робіт
            $table->unsignedBigInteger('worktype_id');
            $table->foreign('worktype_id')->references('id')->on('work_types');
            // id точки приєднання
            $table->unsignedBigInteger('pointid');
            $table->foreign('pointid')->references('id')->on('connecting_points');
            $table->unique(['worktype_id', 'pointid']);
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
