<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'sales_am_id',
        'title',
        'message',
        'type',
        'customer_registration_id',
        'link',
        'action_type',
        'is_read',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'created_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function registration()
    {
        return $this->belongsTo(CustomerRegistration::class, 'customer_registration_id');
    }
}
