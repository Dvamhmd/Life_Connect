<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CustomerRegistration;
use App\Models\Region;
use App\Models\Notification;
use App\Models\RegistrationProgressLog;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MobileSimulatorController extends Controller
{
    public function index()
    {
        $salesUsers = User::where('role', 'sales')->where('status', 'active')->get();
        $provinces = Region::provinces()->get();

        return view('mobile_simulator.index', compact('salesUsers', 'provinces'));
    }

    public function apiLogin(Request $request)
    {
        $request->validate([
            'sales_id' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('sales_id', $request->sales_id)
                    ->orWhere('email', $request->sales_id)
                    ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'ID Sales (AM) atau Password salah.',
            ], 401);
        }

        if ($user->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Akun Sales Anda dinonaktifkan.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil!',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'sales_id' => $user->sales_id,
                    'phone' => $user->phone,
                    'role' => $user->role,
                ],
            ],
        ]);
    }

    public function apiGetRegions(Request $request)
    {
        $type = $request->query('type', 'provinsi');
        $parentId = $request->query('parent_id');

        $query = Region::where('type', $type);
        if ($parentId) {
            $query->where('parent_id', $parentId);
        }

        $regions = $query->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data' => $regions,
        ]);
    }

    public function apiSubmitSurvey(Request $request)
    {
        $request->validate([
            'sales_user_id' => ['required', 'exists:users,id'],
            'customer_name' => ['required', 'string', 'max:150'],
            'phone_wa' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:150'],
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
            'province' => ['required', 'string'],
            'regency' => ['required', 'string'],
            'district' => ['required', 'string'],
            'village' => ['required', 'string'],
            'address_detail' => ['nullable', 'string', 'max:300'],
        ]);

        $salesUser = User::findOrFail($request->sales_user_id);
        $code = 'REG-' . date('Y') . '-' . str_pad(CustomerRegistration::count() + 1, 4, '0', STR_PAD_LEFT);
        $token = 'tok_' . Str::random(32);

        $now = now();
        $registration = CustomerRegistration::create([
            'registration_code' => $code,
            'sales_user_id' => $salesUser->id,
            'sales_am_id' => $salesUser->sales_id,
            'sales_name' => $salesUser->name,
            'customer_name' => $request->customer_name,
            'phone_wa' => $request->phone_wa,
            'email' => $request->email,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'province' => $request->province,
            'regency' => $request->regency,
            'district' => $request->district,
            'village' => $request->village,
            'address_detail' => $request->address_detail,
            'selfie_sales_path' => null, // akan diisi oleh pelanggan pada form kelengkapan data
            'status' => 'submitted',
            'token' => $token,
            'submitted_at' => $now,
        ]);

        // Progress Log
        RegistrationProgressLog::create([
            'customer_registration_id' => $registration->id,
            'user_id' => $salesUser->id,
            'actor_name' => $salesUser->name,
            'actor_role' => 'Sales (AM)',
            'from_status' => null,
            'to_status' => 'submitted',
            'notes' => 'Sales telah melakukan survey lapangan dan menentukan titik koordinat calon pelanggan.',
            'duration_seconds' => 0,
            'created_at' => $now,
        ]);

        // Audit Log
        AuditLog::log(
            action: 'CREATE_SURVEY',
            module: 'Survey',
            description: "Sales {$salesUser->name} ({$salesUser->sales_id}) submit survey pelanggan {$registration->customer_name} ({$code}).",
            targetType: 'CustomerRegistration',
            targetId: $registration->id,
            newValues: $registration->toArray()
        );

        // Notify OPJ Team
        $opjUsers = User::where('role', 'opj')->where('status', 'active')->get();
        foreach ($opjUsers as $opj) {
            Notification::create([
                'user_id' => $opj->id,
                'sales_am_id' => null,
                'title' => 'Survey Baru Masuk!',
                'message' => "Sales {$salesUser->name} telah submit survey baru untuk {$registration->customer_name} ({$registration->village}, {$registration->regency}). Silakan verifikasi di Dashboard OPJ.",
                'type' => 'survey_new',
                'customer_registration_id' => $registration->id,
                'link' => route('opj.index'),
                'action_type' => 'verify_survey',
                'is_read' => false,
                'created_at' => $now,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data survey berhasil dikirim ke sistem Life Connect! Status: Submitted.',
            'data' => $registration,
        ]);
    }

    public function apiGetSurveys(Request $request, $salesId)
    {
        $user = is_numeric($salesId) ? User::find($salesId) : User::where('sales_id', $salesId)->first();
        $surveys = CustomerRegistration::with('package')
            ->where(function ($q) use ($salesId, $user) {
                if (is_numeric($salesId)) {
                    $q->where('sales_user_id', $salesId);
                }
                if ($user && $user->sales_id) {
                    $q->orWhere('sales_am_id', $user->sales_id);
                }
            })
            ->latest('submitted_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $surveys,
        ]);
    }

    public function apiGetNotifications(Request $request, $salesId)
    {
        $user = is_numeric($salesId) ? User::find($salesId) : User::where('sales_id', $salesId)->first();
        $notifications = Notification::with('registration')
            ->where(function ($q) use ($salesId, $user) {
                if (is_numeric($salesId)) {
                    $q->where('user_id', $salesId);
                }
                if ($user && $user->sales_id) {
                    $q->orWhere('sales_am_id', $user->sales_id);
                }
            })
            ->latest('id')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $notifications,
        ]);
    }

    public function apiMarkNotificationRead(Request $request, $id)
    {
        $notification = Notification::findOrFail($id);
        $notification->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi ditandai telah dibaca.',
        ]);
    }

    public function apiMarkAllNotificationsRead(Request $request, $salesId)
    {
        $user = is_numeric($salesId) ? User::find($salesId) : User::where('sales_id', $salesId)->first();
        Notification::where(function ($q) use ($salesId, $user) {
            if (is_numeric($salesId)) {
                $q->where('user_id', $salesId);
            }
            if ($user && $user->sales_id) {
                $q->orWhere('sales_am_id', $user->sales_id);
            }
        })->where('is_read', false)->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi berhasil ditandai telah dibaca.',
        ]);
    }

    public function apiReverseGeocode(Request $request)
    {
        $lat = $request->query('lat');
        $lng = $request->query('lng');

        if (!$lat || !$lng) {
            return response()->json([
                'success' => false,
                'message' => 'Latitude dan longitude diperlukan.',
            ], 400);
        }

        $addressData = null;
        try {
            $response = \Illuminate\Support\Facades\Http::timeout(3)
                ->withHeaders([
                    'User-Agent' => 'LifeConnect-SalesSimulator/1.0',
                    'Accept-Language' => 'id,en',
                ])
                ->get('https://nominatim.openstreetmap.org/reverse', [
                    'format' => 'jsonv2',
                    'lat' => $lat,
                    'lon' => $lng,
                    'zoom' => 18,
                    'addressdetails' => 1,
                ]);

            if ($response->successful()) {
                $addressData = $response->json();
            }
        } catch (\Throwable $e) {
            // In case of timeout or offline, fallback to DB default lookup
        }

        $addr = $addressData['address'] ?? [];
        
        $state = $addr['state'] ?? $addr['province'] ?? $addr['region'] ?? '';
        $city = $addr['city'] ?? $addr['county'] ?? $addr['regency'] ?? $addr['city_district'] ?? $addr['town'] ?? '';
        $district = $addr['municipality'] ?? $addr['suburb'] ?? $addr['district'] ?? '';
        $village = $addr['village'] ?? $addr['quarter'] ?? $addr['neighbourhood'] ?? $addr['residential'] ?? '';
        $road = $addr['road'] ?? $addr['pedestrian'] ?? $addr['street'] ?? '';
        if (!$road && isset($addressData['display_name'])) {
            $parts = explode(',', $addressData['display_name']);
            $road = trim($parts[0] ?? '');
        }

        // Fuzzy match with database regions
        // 1. Province Match
        $matchedProvince = null;
        if ($state) {
            $matchedProvince = Region::where('type', 'provinsi')
                ->where(function ($q) use ($state) {
                    $q->where('name', 'LIKE', "%{$state}%")
                      ->orWhereRaw('? LIKE CONCAT("%", name, "%")', [$state]);
                })->first();
        }
        if (!$matchedProvince) {
            $matchedProvince = Region::where('type', 'provinsi')->where('name', 'LIKE', '%Yogyakarta%')->first()
                ?? Region::where('type', 'provinsi')->first();
        }

        // 2. Regency Match
        $matchedRegency = null;
        if ($matchedProvince) {
            if ($city) {
                $cleanCity = trim(str_ireplace(['kabupaten', 'kota'], '', $city));
                $matchedRegency = Region::where('type', 'kabupaten')
                    ->where('parent_id', $matchedProvince->id)
                    ->where(function ($q) use ($city, $cleanCity) {
                        $q->where('name', 'LIKE', "%{$cleanCity}%")
                          ->orWhere('name', 'LIKE', "%{$city}%")
                          ->orWhereRaw('? LIKE CONCAT("%", name, "%")', [$city]);
                    })->first();
            }
            if (!$matchedRegency) {
                $matchedRegency = Region::where('type', 'kabupaten')
                    ->where('parent_id', $matchedProvince->id)
                    ->first();
            }
        }

        // 3. District Match
        $matchedDistrict = null;
        if ($matchedRegency) {
            if ($district) {
                $cleanDist = trim(str_ireplace(['kecamatan', 'distrik', 'kec.'], '', $district));
                $matchedDistrict = Region::where('type', 'kecamatan')
                    ->where('parent_id', $matchedRegency->id)
                    ->where(function ($q) use ($district, $cleanDist) {
                        $q->where('name', 'LIKE', "%{$cleanDist}%")
                          ->orWhere('name', 'LIKE', "%{$district}%")
                          ->orWhereRaw('? LIKE CONCAT("%", name, "%")', [$district]);
                    })->first();
            }
            if (!$matchedDistrict) {
                $matchedDistrict = Region::where('type', 'kecamatan')
                    ->where('parent_id', $matchedRegency->id)
                    ->first();
            }
        }

        // 4. Village Match
        $matchedVillage = null;
        if ($matchedDistrict) {
            if ($village) {
                $cleanVill = trim(str_ireplace(['kelurahan', 'desa', 'kel.'], '', $village));
                $matchedVillage = Region::where('type', 'kelurahan')
                    ->where('parent_id', $matchedDistrict->id)
                    ->where(function ($q) use ($village, $cleanVill) {
                        $q->where('name', 'LIKE', "%{$cleanVill}%")
                          ->orWhere('name', 'LIKE', "%{$village}%")
                          ->orWhereRaw('? LIKE CONCAT("%", name, "%")', [$village]);
                    })->first();
            }
            if (!$matchedVillage) {
                $matchedVillage = Region::where('type', 'kelurahan')
                    ->where('parent_id', $matchedDistrict->id)
                    ->first();
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'province' => $matchedProvince ? ['id' => $matchedProvince->id, 'name' => $matchedProvince->name] : null,
                'regency' => $matchedRegency ? ['id' => $matchedRegency->id, 'name' => $matchedRegency->name] : null,
                'district' => $matchedDistrict ? ['id' => $matchedDistrict->id, 'name' => $matchedDistrict->name] : null,
                'village' => $matchedVillage ? ['id' => $matchedVillage->id, 'name' => $matchedVillage->name] : null,
                'road' => $road,
                'raw_address' => $addr,
            ],
        ]);
    }
}
