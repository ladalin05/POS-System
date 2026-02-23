<?php

namespace App\Models\Expense;

use App\Models\Other\Branch;
use App\Models\Other\CashAccount;
use App\Models\Other\Warehouses;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{


    protected $table = 'expenses';

    protected $fillable = [
        'date',
        'reference_no',
        'branch_id',
        'warehouse_id',
        'paid_by',
        'attachment',
        'note',
        'grand_total',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'date' => 'datetime',
        'grand_total' => 'decimal:2',
    ];

    /* Relationships */
    public function items()
    {
        return $this->hasMany(ExpenseItem::class, 'expense_id')->orderBy('line_no');
    }

    public function branch()
    {
        
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function warehouse()
    {
       
        return $this->belongsTo(Warehouses::class, 'warehouse_id');
       
    }

    public function cashAccount()
    {
        return $this->belongsTo(CashAccount::class, 'paid_by');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

   
    public function recalcTotals(): void
    {
        $total = $this->items()->sum('subtotal');
        $this->grand_total = $total;
        $this->saveQuietly();
    }

    public function getComputedTotalAttribute(): string
    {
        $sum = $this->items->sum('subtotal');
        return number_format((float) $sum, 2, '.', '');
    }
}