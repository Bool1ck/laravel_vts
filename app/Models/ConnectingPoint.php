<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

//#[Fillable(['region_id ', 'technical_conditions', 'password'])]
class ConnectingPoint extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function region() :belongsTo {
        return $this->belongsTo(Region::class);
    }

    public function customerType() :belongsTo {
        return $this->belongsTo(CustomerType::class);
    }

    public function workTypes() {
        return $this->belongsToMany(WorkType::class, 'connecting_point_work_types', 'pointid', 'worktype_id');
    }
}
