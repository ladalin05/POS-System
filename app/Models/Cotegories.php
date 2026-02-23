<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cotegories extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'description',
        'status',
    ];

    public function parent()
    {
        return $this->belongsTo(Cotegories::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Cotegories::class, 'parent_id');
    }

    public function products()
    {
        return $this->hasMany(Products::class, 'category_id');
    }
}
