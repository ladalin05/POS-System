<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'products';
    protected $fillable = [
        'code',
        'name',
        'unit_id',
        'cost',
        'old_cost',
        'price',
        'alert_quantity',
        'image',
        'category_id',
        'subcategory_id',
        'cf1',
        'cf2',
        'cf3',
        'cf4',
        'cf5',
        'cf6',
        'quantity',
        'tax_rate',
        'track_quantity',
        'details',
        'warehouse',
        'barcode_symbology',
        'file',
        'product_details',
        'tax_method',
        'type',
        'promo_qty',
        'promotion',
        'promo_price',
        'start_date',
        'end_date',
        'customer_id',
        'sale_unit',
        'purchase_unit',
        'brand',
        'model',
        'adjustment_qty',
        'rate',
        'manual_product',
        'accounting_method',
        'seperate_qty',
        'p_length',
        'p_width',
        'p_height',
        'p_weight',
        'currency_rate',
        'currency_code',
        'product_additional',
        'inactive',
        'stregth',
        'market_code1',
        'market_name1',
        'market_code2',
        'market_name2',
        'market_code3',
        'market_name3'
    ];
    protected $casts = [
        'start_date' => 'date',   // not datetime
        'end_date' => 'date',
        'promotion' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(\App\Models\Product\Category::class);
    }
    public function unit()
    {
        return $this->belongsTo(\App\Models\Setting\Unit::class, 'unit_id'); // or use 'units_id' if that's your column name
    }
    public function productUnits()
    {
        return $this->hasMany(\App\Models\Product\ProductUnit::class);
    }

    public function getActivePriceAttribute(): float
    {
        $now = now('Asia/Phnom_Penh');
        $active = $this->promotion
            && ($this->start_date ? $now->gte(\Carbon\Carbon::parse($this->start_date)->startOfDay()) : true)
            && ($this->end_date ? $now->lte(\Carbon\Carbon::parse($this->end_date)->endOfDay()) : true);

        return $active && $this->promo_price !== null ? (float) $this->promo_price : (float) $this->price;
    }

}

