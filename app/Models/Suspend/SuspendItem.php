<?php

namespace App\Models\Suspend;

use App\Models\Product\product;
use App\Models\Setting\Unit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuspendItem extends Model
{
    use HasFactory;

    protected $table = 'suspend_items';

    protected $fillable = [
        'suspend_id',
        'product_id',
        'unit_id',
        'name',
        'code',
        'price',
        'qty',
        'subtotal'
    ];

    public function suspendSale()
    {
        return $this->belongsTo(Suspend::class);
    }

    public function product()
    {
        return $this->belongsTo(product::class);
    }
    public function unit()
    {
         return $this->belongsTo(Unit::class);
    }
}
