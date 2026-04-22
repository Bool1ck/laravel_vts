<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Region extends Model
{
    use HasFactory;

    public function allConnectionPoints() {
        return $this->hasMany(ConnectingPoint::class, 'region_id', 'id');
    }

    public function userHasPermission(User $user) {
        return !RoleRegionUser::all()->where('user_id', $user->id)->where('region_id',$this->id)->isEmpty();
    }

    public function ConnectionPointsWithUserPermission() {
        $user = Auth::user();
    }
}
