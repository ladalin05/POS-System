<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'sku',
        'quantity',
        'unit_price',
        'tax_type',
        'tax_value',
        'tax_amount',
        'discount_amount',
        'subtotal',
        'note',
    ];

    protected $casts = [
        'unit_price'      => 'decimal:2',
        'tax_value'       => 'decimal:2',
        'tax_amount'      => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'subtotal'        => 'decimal:2',
        'quantity'        => 'decimal:2',
    ];

    // ─── Relationships ────────────────────────────────────────────

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // ─── Helpers ──────────────────────────────────────────────────

    /**
     * Compute subtotal: (unit_price * quantity) - discount + tax (if Exclusive)
     */
    public function computeSubtotal(): float
    {
        $base = $this->unit_price * $this->quantity - $this->discount_amount;

        if ($this->tax_type === 'Exclusive') {
            return round($base + $this->tax_amount, 2);
        }

        // Inclusive: tax already baked into unit_price
        return round($base, 2);
    }
}