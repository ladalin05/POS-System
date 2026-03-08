<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Setting\Unit;

class Product extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'product_name',
        'slug',
        'sku',
        'selling_type',
        'category_id',
        'sub_category_id',
        'brand_id',
        'unit_id',
        'manufacture_id',
        'barcode_type',
        'barcode',
        'product_type',
        'price',
        'tax_type',
        'tax_value',
        'warranty_id',
        'description',
        'mfg_date',
        'exp_date'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(Category::class, 'sub_category_id');
    }

    // public function brand()
    // {
    //     return $this->belongsTo(Brand::class);
    // }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    // public function manufacture()
    // {
    //     return $this->belongsTo(Manufacture::class);
    // }

    // public function warranty()
    // {
    //     return $this->belongsTo(Warranty::class);
    // }
}