<?php

namespace App\Models\Sales;

use App\Models\Product\product;
use App\Models\Setting\Unit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleItems extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'product_id',
        'unit_id',
        'qty',
        'unit_price',
        'subtotal',
        'name',
        'code'
    ];

    // Relationships

    public function sale()
    {
        return $this->belongsTo(Sales::class);
    }

    public function product()
    {
        return $this->belongsTo(product::class);
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
