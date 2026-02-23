<?php

namespace App\Models\Other;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currencies extends Model
{
    use HasFactory;
    protected $table = 'currencies';
    protected $fillable = [
        'code',
        'name',
        'rate',
    ];
}
