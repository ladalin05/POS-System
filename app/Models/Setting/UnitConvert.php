<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Model;

class UnitConvert extends Model
{
    protected $table = 'unit_converts';

    protected $fillable = [
        'unit_from_id',
        'unit_to_id',
        'numerator',
        'operator',
        'is_active',
        'name',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'numerator' => 'float',
        'is_active' => 'boolean',
    ];

    public function fromUnit()
    {
        return $this->belongsTo(Unit::class, 'unit_from_id');
    }

    public function toUnit()
    {
        return $this->belongsTo(Unit::class, 'unit_to_id');
    }
}
