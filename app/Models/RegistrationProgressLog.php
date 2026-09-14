<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistrationProgressLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'customer_registration_id',
        'user_id',
        'actor_name',
        'actor_role',
        'from_status',
        'to_status',
        'notes',
        'duration_seconds',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'duration_seconds' => 'integer',
        ];
    }

    public function setDurationSecondsAttribute($value): void
    {
        $this->attributes['duration_seconds'] = $value !== null ? max(0, (int) round(abs((float) $value))) : null;
    }

    public function registration()
    {
        return $this->belongsTo(CustomerRegistration::class, 'customer_registration_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCalculatedDurationSecondsAttribute(): ?int
    {
        if ($this->duration_seconds !== null && $this->duration_seconds > 0) {
            return $this->duration_seconds;
        }

        if ($this->to_status === 'verified') {
            $startTime = $this->registration?->submitted_at ?? $this->registration?->created_at;
            if ($startTime && $this->created_at) {
                return max(0, abs((int) round($this->created_at->diffInSeconds($startTime))));
            }
        } elseif ($this->to_status === 'filled') {
            $startTime = $this->registration?->verified_at;
            if ($startTime && $this->created_at) {
                return max(0, abs((int) round($this->created_at->diffInSeconds($startTime))));
            }
        } elseif (in_array($this->to_status, ['approved', 'revision'])) {
            $startTime = $this->registration?->filled_at;
            if ($startTime && $this->created_at) {
                return max(0, abs((int) round($this->created_at->diffInSeconds($startTime))));
            }
        }

        return $this->duration_seconds;
    }

    public function getFormattedDurationAttribute(): string
    {
        $seconds = $this->calculated_duration_seconds ?? $this->duration_seconds;
        if ($seconds === null) {
            return '-';
        }

        if ($seconds < 60) {
            return $seconds . ' detik';
        }
        $minutes = floor($seconds / 60);
        if ($minutes < 60) {
            return $minutes . ' menit';
        }
        $hours = floor($minutes / 60);
        $remMin = $minutes % 60;
        if ($hours < 24) {
            return $hours . ' jam ' . ($remMin > 0 ? $remMin . 'm' : '');
        }
        $days = floor($hours / 24);
        $remHours = $hours % 24;
        return $days . ' hari ' . ($remHours > 0 ? $remHours . 'j' : '');
    }
}
