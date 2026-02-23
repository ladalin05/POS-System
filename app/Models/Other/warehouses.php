<?php

namespace App\Models\Other;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouses extends Model
{
    use HasFactory;

    protected $table = 'warehouses';

    protected $fillable = [
        'code',
        'name',
        'phone',
        'address',
        'branch_id',
        'email',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
