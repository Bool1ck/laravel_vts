<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    public function allConnectionPoints()
    {
        return $this->hasMany(ConnectingPoint::class, 'region_id', 'id');
    }

    public function cities()
    {
        return $this->hasMany(City::class, 'region_id', 'id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'role_region_users', 'region_id', 'user_id');
    }
}
