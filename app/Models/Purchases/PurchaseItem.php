<?php

namespace App\Models\Purchases;

use App\Models\Product\product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{


    protected $fillable = [
        'purchase_id',
        'product_id',
        'unit_id',
        'net_unit_cost',
        'quantity',
        'discount',
        'subtotal',
    ];

    protected $casts = [
        'net_unit_cost' => 'decimal:4',
        'quantity' => 'decimal:4',
        'discount' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchases::class, 'purchase_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(product::class, 'product_id');
    }
}