<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\ConnectingPoint;
use App\Models\Region;
use App\Models\User;

class ConnectingPointPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user, Region $region): bool
    {
        return $user->isCanViewRegion($region);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ConnectingPoint $connectingPoint): bool
    {
        return $user->isCanViewRegion($connectingPoint->region);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Region $region)
    {
        return $user->isCanEditRegion($region);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ConnectingPoint $connectingPoint): bool
    {
        $region = $connectingPoint->region;

        if (! $region) {
            return false;
        }

        // БИЗНЕС-ПРАВИЛО: Если точка уже выполнена (закрыта), ее редактирование запрещено
        if ($connectingPoint->performance_date) {
            return false;
        }

        return $user->isCanEditRegion($connectingPoint->region) || $user->isMainEngineerInRegion($connectingPoint->region);
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
