<?php

namespace App\Models\Adjustment;

use App\Models\Other\Branch;
use App\Models\Product\Product;
use App\Models\Warehouses\Warehouses;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockMove extends Model
{
    use SoftDeletes;
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

    public function product()
    {

        return $this->belongsTo(Product::class);
    }
    public function warehouse()
    {

        return $this->belongsTo(Warehouses::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
