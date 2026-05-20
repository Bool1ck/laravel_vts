<?php

namespace App\Services\Admin;

use App\Models\Region;
use App\Models\Tp;
use Illuminate\Support\Facades\DB;

class TPService
{
    /**
     * Создать TP
     */
    public function create(array $data): Tp
    {
        return DB::transaction(function () use ($data) {
            return Tp::create($data);
        });
    }

    /**
     * Обновить данные TP.
     */
    public function update(Tp $tp, array $data): Tp
    {
        return DB::transaction(function () use ($tp, $data) {
            $tp->update($data);
            return $tp;
        });
    }

    /**
     * Удалить TP.
     */
    public function delete(Tp $tp): bool
    {
        return DB::transaction(function () use ($tp) {
            return $tp->delete();
        });
    }

}
