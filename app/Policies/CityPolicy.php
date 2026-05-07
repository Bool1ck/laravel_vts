<?php

namespace App\Policies;

use App\Models\City;
use App\Models\Region;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CityPolicy
{
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
        $region = Region::find($city->region_id);
        return $user->isAdminInRegion($region);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, City $city): bool
    {
        $region = Region::find($city->region_id);
        $streets = $city->streets();
        $tps = $city->tps();
        if ($user->isAdminInRegion($region) && $streets->count() == 0 && $tps->count() == 0) {
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
