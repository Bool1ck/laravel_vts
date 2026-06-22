<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\City;
use App\Models\Region;
use App\Models\User;

class CityPolicy
{
    /**
     * Глобальний перехоплювач прав Laravel Gates
     */
    public function before(User $user, string $ability): ?bool
    {
        // Якщо перевіряється дія видалення міста — ігноруємо before() і спускаємось у метод delete()
        if ($ability === 'delete') {
            return null;
        }

        // Для всіх інших дій (view, create, update) Суперадмін автоматично отримує true
        if ($user->isSuperAdmin()) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Region $region): bool
    {
        return $user->isAdminInRegion($region);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Region $region): bool
    {
        return $user->isAdminInRegion($region);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, City $city): bool
    {
        return $user->isAdminInRegion($city->region);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, City $city): bool
    {
        //  Быстрая проверка на отсутствие связанных улиц и ТП
        if ($city->streets()->exists() || $city->tps()->exists()) {
            return false;
        }

        //  Проверяем, является ли пользователь админом в регионе этого города
        if ($user->isAdminInRegion($city->region)) {
            return true;
        }

        //  Якщо місто порожнє — дозволяємо видалення Головному Суперадміну...
        if ($user->isSuperAdmin()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, City $city): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, City $city): bool
    {
        return false;
    }
}
