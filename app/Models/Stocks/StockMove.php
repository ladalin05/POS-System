<?php

namespace App\Models\Stocks;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockMove extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'stockmoves';

    protected $fillable = [
        'transaction',
        'transaction_id',
        'product_id',
        'product_type',
        'product_code',
        'date',
        'quantity',
        'unit_quantity',
        'unit_code',
        'unit_id',
        'option_id',
        'warehouse_id',
        'expiry',
        'real_unit_cost',
        'serial_no',
        'reference_no',
        'user_id',
        'actual_date',
    ];

    protected $casts = [
        'date' => 'datetime',
        'expiry' => 'date',
        'actual_date' => 'datetime',
    ];

    /* =========================
       RELATIONS
    ==========================*/

    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class, 'product_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(\App\Models\Warehouse::class, 'warehouse_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}