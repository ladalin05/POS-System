<?php

namespace App\Models\People;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupCustomer extends Model
{
    use HasFactory;
    protected $table = 'group_customers';
    protected $fillable = [
        'group_name',
        'group_percentage',
    ];
}
