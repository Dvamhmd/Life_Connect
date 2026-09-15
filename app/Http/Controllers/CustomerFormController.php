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
            'birth_date' => ['required', 'date', 'before_or_equal:today'],
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
            'ktp_photo' => [$registration->ktp_photo_path ? 'nullable' : 'required', 'image', 'max:15360'], // up to 15MB input, auto-compressed to WebP
            'house_photo' => [$registration->house_photo_path ? 'nullable' : 'required', 'image', 'max:15360'],
            'selfie_sales_photo' => [$registration->selfie_sales_path ? 'nullable' : 'required', 'image', 'max:15360'],
            'signature_data' => [$registration->signature_path ? 'nullable' : 'required', 'string'],
            'terms_agreed' => ['accepted'],
        ];

        $request->validate($rules);

        // Ensure at least one service option is selected and all checked services have notes/package filled
        $hasServiceSelected = false;
        $missingServiceText = false;
        if ($request->has('services') && is_array($request->services)) {
            foreach ($request->services as $svc) {
                if (!empty($svc['opt1'])) {
                    $hasServiceSelected = true;
                    if (empty(trim($svc['text1'] ?? ''))) {
                        $missingServiceText = true;
                    }
                }
                if (!empty($svc['opt2'])) {
                    $hasServiceSelected = true;
                    if (empty(trim($svc['text2'] ?? ''))) {
                        $missingServiceText = true;
                    }
                }
            }
        }
        if (!$hasServiceSelected) {
            return back()->withErrors(['services' => 'Pilih minimal 1 paket layanan berlangganan.'])->withInput();
        }
        if ($missingServiceText) {
            return back()->withErrors(['services' => 'Mohon isi keterangan/paket untuk setiap layanan yang Anda centang.'])->withInput();
        }

        $now = now();
        $oldStatus = $registration->status;

        // Handle KTP Upload (Auto Convert to WebP)
        $ktpPath = $registration->ktp_photo_path;
        if ($request->hasFile('ktp_photo')) {
            $ktpPath = $this->storeAsWebp($request->file('ktp_photo'), 'customers/ktp', 1600, 82);
        }

        // Handle House Upload (Auto Convert to WebP)
        $housePath = $registration->house_photo_path;
        if ($request->hasFile('house_photo')) {
            $housePath = $this->storeAsWebp($request->file('house_photo'), 'customers/house', 1600, 82);
        }

        // Handle Selfie Sales Upload (Auto Convert to WebP)
        $selfiePath = $registration->selfie_sales_path;
        if ($request->hasFile('selfie_sales_photo')) {
            $selfiePath = $this->storeAsWebp($request->file('selfie_sales_photo'), 'customers/selfie', 1600, 82);
        }

        // Handle Virtual Signature (Auto Convert to WebP)
        $signaturePath = $registration->signature_path;
        if ($request->filled('signature_data')) {
            $sigData = $request->input('signature_data');
            if (str_starts_with($sigData, 'data:image')) {
                $signaturePath = $this->storeSignatureAsWebp($sigData, $registration->registration_code);
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

        // Auto-match package if package_id was not directly provided
        if (!$packageId) {
            $netText = trim((string) $request->input('services.internet.text1', ''));
            if ($netText !== '') {
                $allPackages = SubscriptionPackage::where('is_active', true)->get();
                foreach ($allPackages as $p) {
                    if (str_contains(strtolower($netText), strtolower($p->name)) || 
                        str_contains(strtolower($netText), strtolower($p->speed)) ||
                        str_contains(strtolower($netText), (string) $p->id)) {
                        $packageId = $p->id;
                        break;
                    }
                }
            }
        }

        // Fallback: If still no package_id, pick the first active package
        if (!$packageId) {
            $defaultPkg = SubscriptionPackage::where('is_popular', true)->first() ?? SubscriptionPackage::first();
            $packageId = $defaultPkg?->id;
        }

        $package = $packageId ? SubscriptionPackage::find($packageId) : null;
        $packageName = $package ? $package->name : 'Life Fiber';

        $durationSeconds = $registration->verified_at ? max(0, abs((int) round($now->diffInSeconds($registration->verified_at)))) : null;

        // Normalize Gender format
        $gender = match($request->gender) {
            'P', 'Laki-laki' => 'Laki-laki',
            'W', 'Perempuan' => 'Perempuan',
            default => $request->gender,
        };

        // Check if this was a revision submission
        $isRevision = ($oldStatus === 'revision') || ($registration->revision_at !== null) || ($registration->progressLogs()->where('to_status', 'revision')->exists());

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
            'notes' => $isRevision 
                ? "Pelanggan telah mengirimkan perbaikan/revisi data pendaftaran ({$packageName}) dan tanda tangan virtual."
                : "Pelanggan telah melengkapi data pendaftaran ({$packageName}), mengunggah dokumen KTP, Foto Rumah, & Foto Selfie bersama Sales, serta menandatangani formulir secara virtual.",
            'duration_seconds' => $durationSeconds,
            'created_at' => $now,
        ]);

        // Audit Log
        AuditLog::log(
            action: 'SUBMIT_CUSTOMER_FORM',
            module: 'CustomerForm',
            description: $isRevision
                ? "Calon pelanggan {$registration->customer_name} ({$registration->registration_code}) berhasil mengirimkan perbaikan/revisi formulir pendaftaran."
                : "Calon pelanggan {$registration->customer_name} ({$registration->registration_code}) berhasil melengkapi formulir pendaftaran berlangganan.",
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
                'title' => $isRevision ? 'Revisi Data Formulir Masuk!' : 'Form Pendaftaran Baru Masuk!',
                'message' => $isRevision
                    ? "Pelanggan {$registration->customer_name} ({$registration->registration_code}) telah mengirimkan revisi perbaikan formulir. Silakan review kembali."
                    : "Pelanggan {$registration->customer_name} ({$registration->registration_code}) telah melengkapi data & TTD virtual. Silakan review dan verifikasi pendaftaran.",
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
            'title' => $isRevision ? 'Pelanggan Telah Memperbaiki Formulir!' : 'Pelanggan Telah Mengisi Formulir!',
            'message' => $isRevision
                ? "Pelanggan {$registration->customer_name} ({$registration->registration_code}) telah mengirimkan revisi perbaikan data pendaftaran. Saat ini sedang menunggu verifikasi C-Care."
                : "Pelanggan {$registration->customer_name} ({$registration->registration_code}) telah menyelesaikan form pendaftaran. Saat ini sedang menunggu verifikasi C-Care.",
            'type' => 'registration_filled',
            'customer_registration_id' => $registration->id,
            'link' => null,
            'action_type' => 'view_detail',
            'is_read' => false,
            'created_at' => $now,
        ]);

        return redirect()->route('customer-form.success', $token)->with('is_revision', $isRevision);
    }

    public function success($token)
    {
        $registration = CustomerRegistration::with(['package', 'progressLogs'])->where('token', $token)->firstOrFail();
        $isRevision = session('is_revision');
        if ($isRevision === null) {
            $isRevision = $registration->progressLogs->where('to_status', 'revision')->isNotEmpty() || ($registration->revision_at !== null);
        }
        return view('customer_form.success', compact('registration', 'isRevision'));
    }

    /**
     * Convert and store uploaded image to WebP format for fast performance and lightweight storage.
     */
    private function storeAsWebp(\Illuminate\Http\UploadedFile $file, string $folder, int $maxWidth = 1600, int $quality = 82): string
    {
        try {
            $imageContent = file_get_contents($file->getRealPath());
            $sourceImage = @imagecreatefromstring($imageContent);

            if ($sourceImage === false) {
                // Fallback to standard store if GD cannot decode
                return '/storage/' . $file->store($folder, 'public');
            }

            $origWidth = imagesx($sourceImage);
            $origHeight = imagesy($sourceImage);

            // Resize if exceeds maxWidth while preserving aspect ratio
            if ($origWidth > $maxWidth || $origHeight > $maxWidth) {
                if ($origWidth > $origHeight) {
                    $newWidth = $maxWidth;
                    $newHeight = (int) round(($origHeight / $origWidth) * $maxWidth);
                } else {
                    $newHeight = $maxWidth;
                    $newWidth = (int) round(($origWidth / $origHeight) * $maxWidth);
                }

                $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
                imagealphablending($resizedImage, false);
                imagesavealpha($resizedImage, true);

                imagecopyresampled(
                    $resizedImage,
                    $sourceImage,
                    0, 0, 0, 0,
                    $newWidth,
                    $newHeight,
                    $origWidth,
                    $origHeight
                );

                imagedestroy($sourceImage);
                $finalImage = $resizedImage;
            } else {
                $finalImage = $sourceImage;
            }

            // Output to WebP buffer
            ob_start();
            imagewebp($finalImage, null, $quality);
            $webpData = ob_get_clean();
            imagedestroy($finalImage);

            $filename = $folder . '/' . Str::uuid() . '_' . time() . '.webp';
            Storage::disk('public')->put($filename, $webpData);

            return '/storage/' . $filename;
        } catch (\Throwable $e) {
            \Log::warning('WebP conversion failed, falling back to default store: ' . $e->getMessage());
            return '/storage/' . $file->store($folder, 'public');
        }
    }

    /**
     * Convert and store base64 signature as WebP.
     */
    private function storeSignatureAsWebp(string $sigData, string $registrationCode, int $quality = 85): string
    {
        try {
            // Remove data URI scheme header if present
            $sigDataClean = preg_replace('/^data:image\/[a-zA-Z0-9]+;base64,/', '', $sigData);
            $sigDataClean = str_replace(' ', '+', $sigDataClean);
            $binaryData = base64_decode($sigDataClean);

            if ($binaryData === false) {
                return $sigData;
            }

            $sourceImage = @imagecreatefromstring($binaryData);
            if ($sourceImage === false) {
                // Fallback to raw png storage
                $imageName = 'customers/signatures/sig_' . $registrationCode . '_' . time() . '.png';
                Storage::disk('public')->put($imageName, $binaryData);
                return '/storage/' . $imageName;
            }

            $width = imagesx($sourceImage);
            $height = imagesy($sourceImage);

            // Create canvas with solid white background
            $finalImage = imagecreatetruecolor($width, $height);
            $white = imagecolorallocate($finalImage, 255, 255, 255);
            imagefilledrectangle($finalImage, 0, 0, $width, $height, $white);
            imagealphablending($finalImage, true);
            imagecopy($finalImage, $sourceImage, 0, 0, 0, 0, $width, $height);
            imagedestroy($sourceImage);

            ob_start();
            imagewebp($finalImage, null, $quality);
            $webpData = ob_get_clean();
            imagedestroy($finalImage);

            $filename = 'customers/signatures/sig_' . $registrationCode . '_' . time() . '.webp';
            Storage::disk('public')->put($filename, $webpData);

            return '/storage/' . $filename;
        } catch (\Throwable $e) {
            \Log::warning('Signature WebP conversion failed: ' . $e->getMessage());
            $imageName = 'customers/signatures/sig_' . $registrationCode . '_' . time() . '.png';
            Storage::disk('public')->put($imageName, base64_decode(preg_replace('/^data:image\/[a-zA-Z0-9]+;base64,/', '', $sigData)));
            return '/storage/' . $imageName;
        }
    }
}
