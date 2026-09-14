<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerRegistration;
use App\Models\SubscriptionPackage;
use App\Models\RegistrationProgressLog;
use App\Models\AuditLog;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CustomerFormController extends Controller
{
    public function show($token)
    {
        $registration = CustomerRegistration::where('token', $token)->firstOrFail();
        $packages = SubscriptionPackage::where('is_active', true)->get();

        // If already approved, show completed notice
        if ($registration->status === 'approved') {
            return view('customer_form.completed', compact('registration'));
        }

        return view('customer_form.index', compact('registration', 'packages'));
    }

    public function submit(Request $request, $token)
    {
        $registration = CustomerRegistration::where('token', $token)->firstOrFail();

        $rules = [
            'customer_name' => ['required', 'string', 'max:255'],
            'brand_name' => ['nullable', 'string', 'max:255'],
            'identity_type' => ['required', 'string', 'in:KTP,SIM,Paspor'],
            'identity_number' => ['required', 'string', 'max:50'],
            'birth_date' => ['required', 'date'],
            'gender' => ['required', 'string', 'in:P,W,Laki-laki,Perempuan'],
            'phone_telp' => ['nullable', 'string', 'max:30'],
            'phone_wa' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:150'],
            'address_detail' => ['required', 'string'],
            'services' => ['nullable', 'array'],
            'subscription_period' => ['nullable', 'string', 'max:100'],
            'package_id' => ['nullable', 'exists:subscription_packages,id'],
            'addons' => ['nullable', 'array'],
            'billing_name' => ['required', 'string', 'max:255'],
            'billing_address' => ['required', 'string'],
            'billing_phone' => ['nullable', 'string', 'max:30'],
            'billing_mobile' => ['required', 'string', 'max:30'],
            'billing_method' => ['nullable', 'string', 'max:100'],
            'billing_email' => ['required', 'email', 'max:150'],
            'ktp_photo' => [$registration->ktp_photo_path ? 'nullable' : 'required', 'image', 'max:5120'], // 5MB max
            'house_photo' => [$registration->house_photo_path ? 'nullable' : 'required', 'image', 'max:5120'],
            'selfie_sales_photo' => [$registration->selfie_sales_path ? 'nullable' : 'required', 'image', 'max:5120'],
            'signature_data' => [$registration->signature_path ? 'nullable' : 'required', 'string'],
            'terms_agreed' => ['accepted'],
        ];

        $request->validate($rules);

        $now = now();
        $oldStatus = $registration->status;

        // Handle KTP Upload
        $ktpPath = $registration->ktp_photo_path;
        if ($request->hasFile('ktp_photo')) {
            $ktpPath = '/storage/' . $request->file('ktp_photo')->store('customers/ktp', 'public');
        }

        // Handle House Upload
        $housePath = $registration->house_photo_path;
        if ($request->hasFile('house_photo')) {
            $housePath = '/storage/' . $request->file('house_photo')->store('customers/house', 'public');
        }

        // Handle Selfie Sales Upload
        $selfiePath = $registration->selfie_sales_path;
        if ($request->hasFile('selfie_sales_photo')) {
            $selfiePath = '/storage/' . $request->file('selfie_sales_photo')->store('customers/selfie', 'public');
        }

        // Handle Virtual Signature (base64 data)
        $signaturePath = $registration->signature_path;
        if ($request->filled('signature_data')) {
            $sigData = $request->input('signature_data');
            if (str_starts_with($sigData, 'data:image')) {
                $image = str_replace('data:image/png;base64,', '', $sigData);
                $image = str_replace(' ', '+', $image);
                $imageName = 'customers/signatures/sig_' . $registration->registration_code . '_' . time() . '.png';
                Storage::disk('public')->put($imageName, base64_decode($image));
                $signaturePath = '/storage/' . $imageName;
            } else {
                $signaturePath = $sigData;
            }
        }

        // Prepare structured services selected
        $servicesSelected = [
            'tv_kabel' => [
                'opt1' => $request->boolean('services.tv_kabel.opt1'),
                'text1' => $request->input('services.tv_kabel.text1'),
                'opt2' => $request->boolean('services.tv_kabel.opt2'),
                'text2' => $request->input('services.tv_kabel.text2'),
            ],
            'internet' => [
                'opt1' => $request->boolean('services.internet.opt1'),
                'text1' => $request->input('services.internet.text1'),
                'opt2' => $request->boolean('services.internet.opt2'),
                'text2' => $request->input('services.internet.text2'),
            ],
            'telepon' => [
                'opt1' => $request->boolean('services.telepon.opt1'),
                'text1' => $request->input('services.telepon.text1'),
                'opt2' => $request->boolean('services.telepon.opt2'),
                'text2' => $request->input('services.telepon.text2'),
            ],
        ];

        $packageId = $request->package_id ?: $registration->package_id;
        $package = $packageId ? SubscriptionPackage::find($packageId) : null;
        $packageName = $package ? $package->name : 'Layanan Custom LifeMedia';

        $durationSeconds = $registration->verified_at ? max(0, abs((int) round($now->diffInSeconds($registration->verified_at)))) : null;

        // Normalize Gender format
        $gender = match($request->gender) {
            'P', 'Laki-laki' => 'Laki-laki',
            'W', 'Perempuan' => 'Perempuan',
            default => $request->gender,
        };

        // Update Registration
        $registration->update([
            'customer_name' => $request->customer_name,
            'brand_name' => $request->brand_name,
            'identity_type' => $request->identity_type,
            'identity_number' => $request->identity_number,
            'nik' => $request->identity_number, // backward compatibility
            'birth_date' => $request->birth_date,
            'gender' => $gender,
            'phone_telp' => $request->phone_telp,
            'phone_wa' => $request->phone_wa,
            'email' => $request->email,
            'address_detail' => $request->address_detail,
            'package_id' => $packageId,
            'addons' => $request->addons ?? [],
            'services_selected' => $servicesSelected,
            'subscription_period' => $request->filled('subscription_period') 
                ? (is_numeric($request->subscription_period) ? $request->subscription_period . ' Bulan' : $request->subscription_period) 
                : null,
            'billing_name' => $request->billing_name,
            'billing_address' => $request->billing_address,
            'billing_phone' => $request->billing_phone,
            'billing_mobile' => $request->billing_mobile,
            'billing_method' => $request->billing_method ?: 'BCA Virtual Account',
            'billing_email' => $request->billing_email,
            'ktp_photo_path' => $ktpPath,
            'house_photo_path' => $housePath,
            'selfie_sales_path' => $selfiePath,
            'signature_path' => $signaturePath,
            'terms_agreed' => true,
            'status' => 'filled',
            'filled_at' => $now,
            'rejection_notes' => null, // clear previous revision notes
        ]);

        // Progress Log
        RegistrationProgressLog::create([
            'customer_registration_id' => $registration->id,
            'user_id' => null,
            'actor_name' => $registration->customer_name . ' (Pelanggan)',
            'actor_role' => 'Pelanggan',
            'from_status' => $oldStatus,
            'to_status' => 'filled',
            'notes' => "Pelanggan telah melengkapi data pendaftaran ({$packageName}), mengunggah dokumen KTP, Foto Rumah, & Foto Selfie bersama Sales, serta menandatangani formulir secara virtual.",
            'duration_seconds' => $durationSeconds,
            'created_at' => $now,
        ]);

        // Audit Log
        AuditLog::log(
            action: 'SUBMIT_CUSTOMER_FORM',
            module: 'CustomerForm',
            description: "Calon pelanggan {$registration->customer_name} ({$registration->registration_code}) berhasil melengkapi formulir pendaftaran berlangganan.",
            targetType: 'CustomerRegistration',
            targetId: $registration->id,
            oldValues: ['status' => $oldStatus],
            newValues: ['status' => 'filled', 'package' => $packageName]
        );

        // Notify C-Care team
        $ccareUsers = User::where('role', 'c_care')->where('status', 'active')->get();
        foreach ($ccareUsers as $ccUser) {
            Notification::create([
                'user_id' => $ccUser->id,
                'sales_am_id' => null,
                'title' => 'Form Pendaftaran Baru Masuk!',
                'message' => "Pelanggan {$registration->customer_name} ({$registration->registration_code}) telah melengkapi data & TTD virtual. Silakan review dan verifikasi pendaftaran.",
                'type' => 'registration_filled',
                'customer_registration_id' => $registration->id,
                'link' => route('ccare.show', $registration->id),
                'action_type' => 'review_detail',
                'is_read' => false,
                'created_at' => $now,
            ]);
        }

        // Notify Sales AM
        Notification::create([
            'user_id' => $registration->sales_user_id,
            'sales_am_id' => $registration->sales_am_id,
            'title' => 'Pelanggan Telah Mengisi Formulir!',
            'message' => "Pelanggan {$registration->customer_name} ({$registration->registration_code}) telah menyelesaikan form pendaftaran. Saat ini sedang menunggu verifikasi C-Care.",
            'type' => 'registration_filled',
            'customer_registration_id' => $registration->id,
            'link' => null,
            'action_type' => 'view_detail',
            'is_read' => false,
            'created_at' => $now,
        ]);

        return redirect()->route('customer-form.success', $token);
    }

    public function success($token)
    {
        $registration = CustomerRegistration::with('package')->where('token', $token)->firstOrFail();
        return view('customer_form.success', compact('registration'));
    }
}
