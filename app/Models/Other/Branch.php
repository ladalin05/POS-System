<?php

namespace App\Models\Other;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $table = 'branches';

    protected $fillable = [
        'name',
        'name_kh',
        'phone',
        'phone_kh',
        'address',
        'address_kh',
        'city',
        'city_kh',
        'country',
        'country_kh',
        'vat_number',
        'vat_number_kh',
        'email',
        'prefix',
        'default_cash',
        'working_day',
        'invoice_footer',
        'logo',
    ];
}
