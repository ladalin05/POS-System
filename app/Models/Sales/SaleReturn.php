<?php

namespace App\Models\Sales;

use App\Models\Other\Warehouses;
use App\Models\People\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleReturn extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'reference_no',
        'sale_id',
        'customer_id',
        'warehouse_id',
        'total',
        'tax',
        'grand_total',
        'paid',
        'balance',
        'note',
        'created_by'
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    // Relationships
    public function items()
    {
        return $this->hasMany(SaleReturnItem::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sales::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouses::class);
    }
}