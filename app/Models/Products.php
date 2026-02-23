<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Products extends Model
{
    use HasFactory;

    protected $table = 'products';

    /**
     * ============================
     * Mass Assignable Fields
     * ============================
     */
    protected $fillable = [
        'store_id',
        'warehouse_id',
        'product_name',
        'slug',
        'sku',
        'selling_type',
        'category_id',
        'sub_category_id',
        'brand_id',
        'unit_id',
        'barcode_symbology',
        'item_barcode',
        'description',
        'product_type',
        'quantity',
        'price',
        'tax_type',
        'tax_value',
        'discount_type',
        'discount_value',
        'quantity_alert',
        'warranty_id',
        'manufacturer',
        'manufactured_date',
        'expiry_date',
    ];

    /**
     * ============================
     * Attribute Casting
     * ============================
     */
    protected $casts = [
        'price'             => 'decimal:2',
        'tax_value'         => 'decimal:2',
        'discount_value'    => 'decimal:2',
        'quantity'          => 'integer',
        'quantity_alert'    => 'integer',
        'manufactured_date' => 'date',
        'expiry_date'       => 'date',
    ];

    /**
     * ============================
     * Relationships
     * ============================
     */

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function warranty()
    {
        return $this->belongsTo(Warranty::class);
    }

    /**
     * ============================
     * Accessors (Optional but Useful)
     * ============================
     */

    public function getFinalPriceAttribute()
    {
        $price = $this->price;

        // Apply discount
        if ($this->discount_type === 'Percentage') {
            $price -= ($price * $this->discount_value / 100);
        } elseif ($this->discount_type === 'Fixed') {
            $price -= $this->discount_value;
        }

        // Apply tax
        if ($this->tax_type === 'Exclusive') {
            $price += ($price * $this->tax_value / 100);
        }

        return max($price, 0);
    }
}
