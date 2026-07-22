<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['region_id', 'technical_conditions', 'technical_conditions_date', 'customer', 'customer_type_id',
    'point_place', 'power_point', 'power', 'contract_date', 'payment_date', 'perform_by_date', 'planning_date',
    'performance_date', 'materials_order_date', 'materials_receipt_date', 'note'])]
class ConnectingPoint extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function customerType(): BelongsTo
    {
        return $this->belongsTo(CustomerType::class);
    }

    public function workTypes()
    {
        return $this->belongsToMany(WorkType::class, 'connecting_point_work_types', 'pointid', 'worktype_id');
    }
}
