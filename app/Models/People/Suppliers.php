<?php

namespace App\Models\People;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suppliers extends Model
{
    use HasFactory;
    protected $table = 'suppliers';
    protected $fillable = [
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
    ];
}
