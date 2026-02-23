<?php

namespace App\Models\Purchases;

use App\Models\Other\Branch;
use App\Models\Other\Warehouses;
use App\Models\People\Suppliers;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchases extends Model
{

    protected $fillable = [
        'date',
        'reference_no',
        'si_reference_no',
        'branch_id',
        'warehouse_id',
        'supplier_id',
        'status',
        'order_tax',
        'order_discount',
        'total',
        'grand_total',
        'payment_term',
        'attachment',
        'note',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'date' => 'datetime',
        'order_tax' => 'decimal:2',
        'order_discount' => 'decimal:2',
        'total' => 'decimal:2',
        'grand_total' => 'decimal:2',
    ];


    public function items()
    {
        return $this->hasMany(PurchaseItem::class, 'purchase_id', 'id');
    }

    public function supplier()
    {
        return $this->belongsTo(Suppliers::class, 'supplier_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function warehouse()
    {

        return $this->belongsTo(Warehouses::class, 'warehouse_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}