<?php

namespace App\Models\Other;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashAccount extends Model
{
    
    use HasFactory;
      protected $fillable = [
        'code',
        'name',
        'type',
    ];
}
