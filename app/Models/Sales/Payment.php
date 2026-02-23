<?php

namespace App\Models\Sales;

use App\Models\Other\CashAccount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'reference_no',
        'sale_id',
        'amount',
        'discount',
        'amount_usd',
        'rate_usd',
        'amount_khr',
        'rate_khr',
        'paying_by',
        'attachment',
        'note',
        'created_by',
        'allow_overpayment'
    ];

    protected $casts = [
        'date' => 'datetime',
        'amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'amount_usd' => 'decimal:2',
        'rate_usd' => 'decimal:6',
        'amount_khr' => 'decimal:2',
        'rate_khr' => 'decimal:4',
    ];


    public function sale()
    {
        return $this->belongsTo(Sales::class);
    }
    public function cashAccount()
    {
        return $this->belongsTo(CashAccount::class, 'paying_by');
    }


}
