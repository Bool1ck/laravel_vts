<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Email;

class Region extends Model
{
    use HasFactory;

    public function allConnectionPoints() {
        return $this->hasMany(ConnectingPoint::class, 'region_id', 'id');
    }

    public function Cities() {
        return $this->hasMany(City::class, 'region_id', 'id');
    }

//    public function Tps() {
//        return $this->hasMany(Tp::class, 'region_id', 'id');
//    }

    public function isHasTpNumber(string $string): bool {
        foreach ($this->Cities as $city) {
            foreach ($city->tps as $tp) {
                if ($string == $tp->name) {
                    return true;
                }
            }
        }
        return false;
    }

    public function isHasUserByEmail(string $email): bool
    {
        foreach ($this->users as $user) {
            if ($user->email == $email) {
                return true;
            }
        }
        return false;
    }

    public function users() {
        return $this->belongsToMany(User::class, 'role_region_users', 'region_id', 'user_id');
    }
}
