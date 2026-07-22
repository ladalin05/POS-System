<?php

namespace App\Models\Sales;

use App\Models\Other\Biller;
use App\Models\Other\Branch;
use App\Models\People\Customer;
use App\Models\People\Saleman;
use App\Models\Warehouses\Warehouses;
use App\Models\Sales\Payment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'reference_no',
        'customer_id',
        'biller_id',
        'warehouse_id',
        'room_id',

        'total',
        'tax',
        'returned',
        'discount',
        'shipping',
        'grand_total',
        'paid',
        'balance',
        'return_amount',

        'delivery_status',
        'payment_status',

        'note',
        'created_by',
    ];

    // Relationships

    public function items()
    {
        return $this->hasMany(SaleItems::class, 'sale_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouses::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function biller()
    {
        return $this->belongsTo(Biller::class);
    }
    
    public function salesman()
    {
        return $this->belongsTo(Saleman::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'sale_id', 'id');
    }
}
