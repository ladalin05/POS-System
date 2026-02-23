<?php

namespace App\Models\Product;

use App\Models\Setting\Unit;
use Illuminate\Database\Eloquent\Model;

class ProductUnit extends Model
{
    protected $table = 'product_units';

    protected $fillable = [
        'product_id',
        'unit_id',
        'qty',
        'price',
    ];

    protected $casts = [
        'qty' => 'decimal:4',
        'price' => 'decimal:4',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
