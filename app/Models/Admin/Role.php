<?php

namespace App\Models\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'roles';

    protected $fillable = [
        'name_en',
        'name_kh',
        'administrator',
        'description',
        'description_kh',
        'order'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = auth()->id();
        });

        static::updating(function ($model) {
            $model->updated_by = auth()->id();
        });

        static::deleting(function ($model) {
            if (!$model->forceDeleting) {
                $model->deleted_by = auth()->id();
                $model->save();
            }
        });
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission', 'role_id', 'permission_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_role', 'role_id', 'user_id');
    }
    
    public function getCreatedAtAttribute($value): string
    {
        return date('d/m/Y h:i A', strtotime($value));
    }
    
    public function getUpdatedAtAttribute($value): string
    {
        return date('d/m/Y h:i A', strtotime($value));
    }
    
    public function getDeletedAtAttribute($value): string
    {
        return date('d/m/Y h:i A', strtotime($value));
    }
    
    public function getNameAttribute(): string
    {
        return $this->{'name_' . app()->getLocale()};
    }
}
