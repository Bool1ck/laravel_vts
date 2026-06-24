<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Region;
use App\Models\User;

class RegionPolicy
{
    /**
     * Глобальний перехоплювач прав
     */
    public function before(User $user, string $ability): ?bool
    {
        // Якщо це Головний Суперадміністратор (ID=1) — він автоматично отримує доступ
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
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Region $region): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Region $region): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Region $region): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Region $region): bool
    {
        return false;
    }
}
