<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Category extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'categories';
    protected $fillable = [
        'code',
        'name',
        'image',
        'biller',
        'project',
        'warehouse_id',
        'parent_id',
        'installment',
        'type_id',
        'type',
        'stock_acc',
        'adjustment_acc',
        'usage_acc',
        'cost_acc',
        'convert_acc',
        'discount_acc',
        'sale_acc',
        'expense_acc',
        'pawn_acc',
        'other_name',
        'size',
        'inactive',
        'transfer_acc',
    ];
}
