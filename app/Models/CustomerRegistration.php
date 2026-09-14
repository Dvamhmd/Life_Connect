<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_code',
        'sales_user_id',
        'sales_am_id',
        'sales_name',
        'customer_name',
        'brand_name',
        'phone_wa',
        'email',
        'latitude',
        'longitude',
        'province',
        'regency',
        'district',
        'village',
        'address_detail',
        'selfie_sales_path',
        'status',
        'token',
        'identity_type',
        'identity_number',
        'nik',
        'birth_date',
        'gender',
        'phone_telp',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relation',
        'package_id',
        'addons',
        'services_selected',
        'subscription_period',
        'billing_name',
        'billing_address',
        'billing_phone',
        'billing_mobile',
        'billing_method',
        'billing_email',
        'billing_cycle',
        'ktp_photo_path',
        'house_photo_path',
        'signature_path',
        'terms_agreed',
        'rejection_notes',
        'rejection_category',
        'submitted_at',
        'verified_at',
        'filled_at',
        'approved_at',
        'revision_at',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
            'addons' => 'array',
            'services_selected' => 'array',
            'birth_date' => 'date',
            'terms_agreed' => 'boolean',
            'submitted_at' => 'datetime',
            'verified_at' => 'datetime',
            'filled_at' => 'datetime',
            'approved_at' => 'datetime',
            'revision_at' => 'datetime',
        ];
    }

    public function sales()
    {
        return $this->belongsTo(User::class, 'sales_user_id');
    }

    public function package()
    {
        return $this->belongsTo(SubscriptionPackage::class, 'package_id');
    }

    public function progressLogs()
    {
        return $this->hasMany(RegistrationProgressLog::class)->orderBy('created_at', 'asc');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'submitted' => ['label' => 'Submitted', 'color' => 'amber', 'bg' => 'bg-amber-100 text-amber-800 border-amber-300'],
            'verified' => ['label' => 'Verified', 'color' => 'blue', 'bg' => 'bg-blue-100 text-blue-800 border-blue-300'],
            'filled' => ['label' => 'Filled', 'color' => 'indigo', 'bg' => 'bg-indigo-100 text-indigo-800 border-indigo-300'],
            'approved' => ['label' => 'Approved', 'color' => 'emerald', 'bg' => 'bg-emerald-100 text-emerald-800 border-emerald-300'],
            'revision' => ['label' => 'Revision', 'color' => 'rose', 'bg' => 'bg-rose-100 text-rose-800 border-rose-300'],
            default => ['label' => ucfirst($this->status), 'color' => 'gray', 'bg' => 'bg-gray-100 text-gray-800 border-gray-300'],
        };
    }

    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address_detail,
            $this->village ? 'Kel. ' . $this->village : null,
            $this->district ? 'Kec. ' . $this->district : null,
            $this->regency,
            $this->province,
        ]);
        return implode(', ', $parts);
    }

    public function getCustomerFormUrlAttribute(): string
    {
        return url('/pendaftaran/' . $this->token);
    }

    public function getWhatsappShareUrlAttribute(): string
    {
        $text = "Halo Bapak/Ibu {$this->customer_name},\n\nTerima kasih telah mengajukan pendaftaran layanan LifeMedia. Lokasi rumah Anda telah diverifikasi oleh tim teknis kami (OPJ).\n\nSilakan lengkapi data registrasi dan tanda tangan formulir berlangganan melalui tautan resmi LifeMedia berikut:\n" . $this->customer_form_url . "\n\nSalam hangat,\nTim LifeMedia";
        
        $cleanPhone = preg_replace('/[^0-9]/', '', $this->phone_wa);
        if (str_starts_with($cleanPhone, '0')) {
            $cleanPhone = '62' . substr($cleanPhone, 1);
        }
        
        return "https://api.whatsapp.com/send?phone={$cleanPhone}&text=" . urlencode($text);
    }
}
