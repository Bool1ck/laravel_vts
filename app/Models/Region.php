<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    public function connectionPoints() {
        $poins = $this->hasMany(ConnectingPoint::class, 'region_id', 'id');
        return $poins;
    }

    public function userHasPermission(User $user) {

    }
}
