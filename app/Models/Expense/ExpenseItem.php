<?php

namespace App\Models\Expense;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseItem extends Model
{
    protected $table = 'expense_items';

    protected $fillable = [
        'expense_id',
        'expense_category_id', 
        'expense_name',
        'expense_code',
        'description',
        'unit_cost',
        'quantity',
        'subtotal',
        'line_no',
    ];

    protected $casts = [
        'unit_cost' => 'decimal:4',
        'quantity' => 'decimal:3',
        'subtotal' => 'decimal:2',
    ];

    public function expense()
    {
        return $this->belongsTo(Expense::class, 'expense_id');
    }


}