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
        Schema::create('connecting_points', function (Blueprint $table) {
            $table->id();
            // id регіону, в якому створюється нове приєднання
            $table->unsignedBigInteger('region_id');
            $table->foreign('region_id')->references('id')->on('regions');
            // номер тихнічних умов
            $table->string('technical_conditions');
            // дата технічних умов
            $table->date('technical_conditions_date');
            // замовник
            $table->string('customer');
            // id типу замовника
            $table->unsignedBigInteger('customer_type_id');
            $table->foreign('customer_type_id')->references('id')->on('customer_types');
            // поштова адреса приєдання - збирається з заповнених полів
            $table->string('point_place');
            // точка приєднання: ТП, лінія - збирається з заповнених полів
            $table->string('power_point');
            // потужність приєднання
            $table->unsignedBigInteger('power');
            // дата договору приєднання
            $table->date('contract_date')->nullable();
            // дата оплати
            $table->date('payment_date')->nullable();
            // кінцева дата побудови приєднання - розраховується автоматично, в залежності від дати оплати та потужності приєднання
            $table->date('perform_by_date')->nullable();
            // запланова дата виконання робіт, встановлює головний інженер
            $table->date('planning_date')->nullable();
            // дата фактичного виконання робіт по приєднанню
            $table->date('performance_date')->nullable();
            // дата замовлення матеріалів
            $table->date('materials_order_date')->nullable();
            // дата отримання матеріалів
            $table->date('materials_receipt_date')->nullable();
            // нотатки при приєднанню
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
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
