<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'menus';

    protected $fillable = [
        'name_en',
        'name_kh',
        'icon',
        'url',
        'parent_id',
        'order'
    ];


    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id');
    }
    // menu has permissions
    public function authorized()
    {
        $this->hasOne(Permission::class, 'menu_id')->where('is_menu', 1)->first();
    }
    public function permissions()
    {
        return $this->hasMany(Permission::class, 'menu_id');
    }
    // attributes name
    public function getNameAttribute($value) : string
    {
        return $this->{'name_' . app()->getLocale()};
    }
}
