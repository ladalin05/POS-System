<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $table = 'permissions';

    protected $fillable = [
        'name_en',
        'name_kh',
        'menu_id',
        'slug',
        'is_menu'
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }
    public function getNameAttribute(): string
    {
        return $this->{'name_' . app()->getLocale()};
    }
}
