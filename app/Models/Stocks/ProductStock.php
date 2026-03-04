<?php

namespace App\Models\Stocks;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product\Product;
use App\Models\Other\Warehouses;

class ProductStock extends Model
{
    protected $table = 'product_stock';

    protected $fillable = [
        'product_id',
        'warehouse_id',
        'stock',
        'alert_quantity'
    ];

    protected $casts = [
        'stock' => 'decimal:2',
        'alert_quantity' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouses::class);
    }
}