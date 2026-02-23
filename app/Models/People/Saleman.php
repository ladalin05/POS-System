<?php

namespace App\Models\People;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saleman extends Model
{
    use HasFactory;
     protected $table= 'saleman';
     protected $fillable = [
        'first_name',
        'last_name',
        'gender',
        'phone',
        'position',
        'group_id',
        'area_id',
        'status',
    ];
    public function group()
    {
        return $this->belongsTo(GroupSaleman::class);
    }
}
