<?php

namespace App\Policies;

use App\Models\City;
use App\Models\Region;
use App\Models\Tp;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TpPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user, Region $region): bool
    {
        if ($user->isAdminInRegion($region)) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user,Region $region, City $city): bool
    {
        if ($user->isAdminInRegion($region) && $city->region == $region) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Region $region): bool
    {
        if ($user->isAdminInRegion($region)) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user,Region $region, Tp $tp): bool
    {
        if ($user->isAdminInRegion($region) && $tp->city->region == $region) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user,Region $region, Tp $tp): bool
    {
        if ($user->isAdminInRegion($region) && $tp->city->region == $region) {
            return true;
        }
        return false;
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
