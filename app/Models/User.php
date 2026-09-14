<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'sales_id',
        'role',
        'phone',
        'avatar',
        'status',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isVas(): bool
    {
        return $this->role === 'admin_vas';
    }

    public function isAdminSales(): bool
    {
        return $this->role === 'admin_sales';
    }

    public function isOpj(): bool
    {
        return $this->role === 'opj';
    }

    public function isCCare(): bool
    {
        return $this->role === 'c_care';
    }

    public function isSales(): bool
    {
        return $this->role === 'sales';
    }

    public function getRoleBadgeAttribute(): string
    {
        return match ($this->role) {
            'admin_vas' => 'Admin VAS (Super)',
            'admin_sales' => 'Admin Sales',
            'opj' => 'OPJ (Field Ops)',
            'c_care' => 'C-Care',
            'sales' => 'Sales (AM)',
            default => ucfirst($this->role),
        };
    }

    public function customerRegistrations()
    {
        return $this->hasMany(CustomerRegistration::class, 'sales_user_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function unreadNotifications()
    {
        return $this->hasMany(Notification::class)->where('is_read', false);
    }
}
