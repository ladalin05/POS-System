<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use App\Models\Inventory\AdjustmentItem;

class Adjustment extends Model
{
    protected $table = 'adjustments';

    protected $fillable = [
        'date',
        'reference_no',
        'warehouse_id',
        'note',
        'attachment',
        'created_by',
        'updated_by',
        'updated_at',
        'count_id',
        'biller_id',
        'project_id',
        'status',
        'approved_by',
        'approved_at',
    ];

    public $timestamps = true;

    // Example relationship
    public function items()
    {
        return $this->hasMany(AdjustmentItem::class, 'adjustment_id');
    }
}

