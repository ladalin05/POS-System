<?php

namespace App\Models\Adjustment;

use App\Models\Other\Branch;
use App\Models\User;
use App\Models\Warehouses\Warehouses;
use Illuminate\Database\Eloquent\Model;


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
        'branch_id',
        'biller_id',
        'project_id',
        'status',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'date' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public $timestamps = true;

    public function products()
    {
        return $this->hasMany(AdjustmentItem::class, 'adjustment_id')->orderBy('id');
    }

    public function items()
    {
        return $this->hasMany(AdjustmentItem::class, 'adjustment_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouses::class, 'warehouse_id')->withDefault();
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by')->withDefault();
    }
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by')->withDefault();
    }
}

