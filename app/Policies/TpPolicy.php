<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\City;
use App\Models\Region;
use App\Models\Tp;
use App\Models\User;

class TpPolicy
{
    /**
     * Глобальний перехоплювач прав Laravel Gates
     */
    public function before(User $user, string $ability): ?bool
    {
        // Якщо це користувач з ID=1 — він автоматично отримує доступ до будь-якої дії в системі
        if ($user->isSuperAdmin()) {
            return true;
        }

        return null; // Для всіх інших користувачів Laravel продовжує стандартну перевірку методів
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user, Region $region): bool
    {
        return $user->isAdminInRegion($region);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Region $region, City $city): bool
    {
        return $user->isAdminInRegion($region) && $city->region_id === $region->id;
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
    public function update(User $user, Tp $tp): bool
    {
        $region = $tp->city->region;

        return $region && $user->isAdminInRegion($region);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Tp $tp): bool
    {
        $region = $tp->city->region;

        return $region && $user->isAdminInRegion($region);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Tp $tp): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Tp $tp): bool
    {
        return false;
    }
}
