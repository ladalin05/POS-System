<?php

namespace App\Models\Warehouses;

use App\Models\Sales\Sales;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouses extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'address',
    ];

    // If you want to see all sales in this warehouse:
    public function sales()
    {
        return $this->hasMany(Sales::class);
    }
}
