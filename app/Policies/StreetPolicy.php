<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\City;
use App\Models\Region;
use App\Models\Street;
use App\Models\User;

class StreetPolicy
{
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
        if ($user->isAdminInRegion($region) && $city->region_id == $region->id) {
            return true;
        }

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
    public function update(User $user, Street $street): bool
    {
        $region = $street->city->region;

        return $region && $user->isAdminInRegion($region);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Street $street): bool
    {
        $region = $street->city->region;

        return $region && $user->isAdminInRegion($region);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Street $street): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Street $street): bool
    {
        return false;
    }
}
