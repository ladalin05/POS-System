<?php

namespace App\Models\People;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupSaleman extends Model
{
    use HasFactory;
    protected $table = 'group_saleman';
    protected $fillable = [
        'group_name',
        'description',
    ];
}
