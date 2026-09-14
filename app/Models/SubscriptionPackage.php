<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'speed',
        'price',
        'description',
        'features',
        'is_popular',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'features' => 'array',
            'price' => 'float',
            'is_popular' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function customerRegistrations()
    {
        return $this->hasMany(CustomerRegistration::class, 'package_id');
    }
}
