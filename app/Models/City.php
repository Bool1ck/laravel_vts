<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function cityType() {
        return $this->belongsTo(CityType::class, 'city_type_id');
    }

    public function streets() {
        return $this->hasMany(Street::class, 'city_id', 'id');
    }

    public function tps() {
        return $this->hasMany(Tp::class, 'city_id', 'id');
    }

    public function fullName() {
        return $this->cityType->name . ' ' . $this->name;
    }

    public function region() {
        return $this->belongsTo(Region::class);
    }
}
