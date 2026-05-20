<?php

namespace App\Services\Admin;

use App\Models\City;
use App\Models\Region;
use Illuminate\Support\Facades\DB;

class CityService
{
    /**
     * Создать город, строго привязанный к указанному региону.
     */
    public function create(Region $region, array $data): City
    {
        return DB::transaction(function () use ($region, $data) {
            // Принудительно выставляем ID региона из URL, игнорируя подлоги в request
            $data['region_id'] = $region->id;

            return City::create($data);
        });
    }

    /**
     * Обновить данные города.
     */
    public function update(City $city, array $data): City
    {
        return DB::transaction(function () use ($city, $data) {
            $city->update($data);
            return $city;
        });
    }

    /**
     * Удалить город.
     */
    public function delete(City $city): bool
    {
        return DB::transaction(function () use ($city) {
            return $city->delete();
        });
    }

}
