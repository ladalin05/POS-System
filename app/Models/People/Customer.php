<?php

namespace App\Models\People;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $table = 'customers';
    protected $fillable = [
        'customer_group_id',
        'price_group_id',
        'salesman_id',
        'code',
        'company',
        'name',
        'phone',
        'address',
        'city',
        'state',
        'email_address',
        'vat_number',
        'postal_code',
        'country',
        'credit_day',
        'credit_amount',
        'attachment',
    ];

    public function group_customer()
    {
        return $this->belongsTo(GroupCustomer::class, 'customer_group_id');
    }
    public function depositsAmount()
    {
        return $this->hasMany(CustomerDeposit::class, 'customer_id');
    }

    // public function priceGroup()
    // {
    //     return $this->belongsTo(PriceGroup::class, 'price_group_id');
    // }
    // public function salesman()
    // {
    //     return $this->belongsTo(User::class, 'salesman_id');
    // }
}
