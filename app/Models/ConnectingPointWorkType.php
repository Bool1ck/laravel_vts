<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['worktype_id', 'pointid'])]
class ConnectingPointWorkType extends Model
{
    use HasFactory;

    public function workType()
    {
        return $this->hasOne(WorkType::class, 'id', 'worktype_id');
    }
}
