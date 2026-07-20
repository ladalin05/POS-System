<?php

namespace App\Models\Other;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Biller extends Model
{
    use HasFactory;

    protected $table = 'billers';

    protected $fillable = [
        'name',
        'company',
        'phone',
        'email',
    ];
}