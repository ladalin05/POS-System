<?php

namespace App\Models\Suspend;

use App\Models\Other\Warehouses;
use App\Models\People\Customer;
use App\Models\Setting\Room;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suspend extends Model
{
    use HasFactory;

    protected $table = 'suspends';

    protected $fillable = [
        'room_id',
        'customer_id',
        'warehouse_id',
        'salesman_id',
        'total',
        'discount',
        'shipping',
        'tax'

    ];

    public function items()
    {
        return $this->hasMany(SuspendItem::class);
    }
    public function customer(){
        return $this->belongsTo(Customer::class);
    }
    public function warehouse()
    {
         return $this->belongsTo(Warehouses::class);
    }
     public function room()
    {
         return $this->belongsTo(Room::class);
    }
}
