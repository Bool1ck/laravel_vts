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

    public function Cities() {
        return $this->hasMany(City::class, 'region_id', 'id');
    }

    public function Tps() {
        return $this->hasMany(Tp::class, 'region_id', 'id');
    }
}
