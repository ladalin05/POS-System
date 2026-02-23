<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdjustmentItem extends Model
{
   use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'adjustment_items';
    protected $fillable = [
        'adjustment_id',
        'product_id',
        'option_id',
        'quantity',
        'warehouse_id',
        'serial_no',
        'type',
        'product_unit_id',
        'product_unit_code',
        'unit_quantity',
        'expiry',
        'real_unit_cost',
    ];
}
