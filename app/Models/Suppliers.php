<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suppliers extends Model
{
    use HasFactory;

    protected $table = 'suppliers';

    protected $fillable = [
        'company_name',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'tax_number',
        'payment_terms',
        'image',
        'status',
    ];

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'supplier_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
