<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tp extends Model
{
    use HasFactory;
    //

    public function type() {
        return $this->belongsTo(TpType::class, 'tp_type_id');
    }

    public function city() {
        return $this->belongsTo(City::class, 'city_id');
    }
}
