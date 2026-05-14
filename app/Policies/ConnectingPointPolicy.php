<?php

namespace App\Policies;

use App\Models\ConnectingPoint;
use App\Models\Region;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ConnectingPointPolicy
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
    public function view(User $user, ConnectingPoint $connectingPoint): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Region $region)
    {
        return $user->isCanEditRegion($region)? Response::allow()
            : Response::deny('Вы не можете удалить чужой пост.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ConnectingPoint $connectingPoint): bool
    {
        $region = Region::find($connectingPoint->region_id);
        return ($user->isCanEditRegion($region)||$user->isMainEngineerInRegion($region));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ConnectingPoint $connectingPoint): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ConnectingPoint $connectingPoint): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ConnectingPoint $connectingPoint): bool
    {
        return false;
    }
}
