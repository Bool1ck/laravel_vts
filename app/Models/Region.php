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

    public function userRole() {
        $roleRegion = RoleRegionUser::where('user_id', Auth::user()->id)->where('region_id',$this->id)->first();
        return Role::where('id', $roleRegion->role_id)->first();
    }

    public function IsUserRoleVtg() {
        return $this->userRole()->name == 'vtg';
    }
    public function IsUserRoleAdmin() {
        return $this->userRole()->name == 'admin';
    }

    public function IsUserRoleLegal() {
        return $this->userRole()->name == 'legal';
    }

    public function IsUserRoleHousehold() {
        return $this->userRole()->name == 'household';
    }

    public function IsUserRoleViewer() {
        return $this->userRole()->name == 'viewer';
    }

    public function IsUserCanEdit() {
        return $this->IsUserRoleAdmin() || $this->IsUserRoleVtg();
    }

    public function Cities() {
        return $this->hasMany(City::class, 'region_id', 'id');
    }
}
