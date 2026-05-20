<?php

namespace App\Services\Admin;

use App\Models\Street;
use Illuminate\Support\Facades\DB;

class UserService
{
    /**
     * Создать street
     */
    public function create(array $data): Street
    {
        return DB::transaction(function () use ($data) {
            return Street::create($data);
        });
    }

    /**
     * Обновить данные Street.
     */
    public function update(Street $street, array $data): Street
    {
        return DB::transaction(function () use ($street, $data) {
            $street->update($data);
            return $street;
        });
    }

    /**
     * Удалить street.
     */
    public function delete(Street $street): bool
    {
        return DB::transaction(function () use ($street) {
            return $street->delete();
        });
    }

}
