<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConnectingPointWorkType extends Model
{
    use HasFactory;
    protected $guarded = [];
    //

    public function workType() {
        return $this->hasOne(WorkType::class, 'id','worktype_id');
    }
}
