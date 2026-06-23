<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Region;
use App\Models\User;

class UserPolicy
{
    /**
     * Глобальний перехоплювач прав Laravel Gates
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($ability === 'delete') {
            return null;
        }

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
    public function view(User $user, User $model): bool
    {
        return false;
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
    public function update(User $user, Region $region, User $model): bool
    {
        return $user->isAdminInRegion($region) && $model->roleInRegion($region) && ! $model->isAdminInRegion($region);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Region $region, User $model): bool
    {

        $targetRole = $model->roleInRegion($region);
        if (! $targetRole) {
            return false;
        }

        if ($targetRole->name === 'root') {
            return false;
        }

        if ($targetRole->name === 'admin') {
            return $user->isSuperAdmin();
        } else {
            return $user->isAdminInRegion($region) || $user->isSuperAdmin();
        }
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return false;
    }
}
