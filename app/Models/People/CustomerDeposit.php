<?php

namespace App\Models\People;

use App\Models\Other\Branch;
use App\Models\Other\CashAccount;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerDeposit extends Model
{
    use HasFactory;

    protected $table = 'customer_deposits';

    protected $fillable = [
        'customer_id',
        'reference_no',
        'branch_id',     // <-- use branch_id (FK), not "branch"
        'amount',
        'paid_by',
        'attachment',
        'note',
    ];

    public function customer_name()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function paying_by()
    {
        return $this->belongsTo(CashAccount::class, 'paid_by');
    }
}
