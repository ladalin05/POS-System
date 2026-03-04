<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Model;
use App\Models\People\Customer;
use App\Models\Other\Warehouses;
use App\Models\Other\Currencies;
use App\Models\Sales\InvoiceDetail;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_no',
        'customer_id',
        'warehouse_id',
        'currency_id',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'grand_total',
        'paid_amount',
        'due_amount',
        'status',
        'invoice_date',
        'created_by'
    ];

    protected $casts = [
        'invoice_date' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouses::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currencies::class);
    }

    public function details()
    {
        return $this->hasMany(InvoiceDetail::class);
    }
}