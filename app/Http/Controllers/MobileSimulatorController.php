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
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
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

    public function apiGetOnlineRegions(Request $request)
    {
        $type = strtolower($request->query('type', 'provinces'));
        $parentId = $request->query('parent_id');

        // Normalize type aliases
        if ($type === 'provinsi' || $type === 'province') $type = 'provinces';
        if ($type === 'kabupaten' || $type === 'regency') $type = 'regencies';
        if ($type === 'kecamatan' || $type === 'district') $type = 'districts';
        if ($type === 'kelurahan' || $type === 'desa' || $type === 'village') $type = 'villages';

        $cacheKey = "online_wilayah_{$type}_" . ($parentId ?: 'all');

        // If cached and not empty, return cached
        if (Cache::has($cacheKey)) {
            $cached = Cache::get($cacheKey);
            if (is_array($cached) && !empty($cached)) {
                return response()->json([
                    'success' => true,
                    'source' => 'online_api_cache',
                    'type' => $type,
                    'parent_id' => $parentId,
                    'data' => $cached,
                ]);
            }
        }

        try {
            $url = match ($type) {
                'provinces' => 'https://emsifa.github.io/api-wilayah-indonesia/api/provinces.json',
                'regencies' => $parentId ? "https://emsifa.github.io/api-wilayah-indonesia/api/regencies/{$parentId}.json" : null,
                'districts' => $parentId ? "https://emsifa.github.io/api-wilayah-indonesia/api/districts/{$parentId}.json" : null,
                'villages' => $parentId ? "https://emsifa.github.io/api-wilayah-indonesia/api/villages/{$parentId}.json" : null,
                default => null,
            };

            $data = [];
            if ($url) {
                $response = Http::timeout(6)
                    ->withoutVerifying()
                    ->withHeaders([
                        'User-Agent' => 'LifeConnect-SalesSimulator/2.0 (admin@lifemedia.id)',
                        'Accept' => 'application/json',
                    ])
                    ->get($url);

                if ($response->successful()) {
                    $items = $response->json();
                    if (is_array($items) && !empty($items)) {
                        $data = array_map(function ($item) use ($type) {
                            $rawName = $item['name'] ?? '';
                            $formattedName = $this->formatIndonesianRegionName($rawName, $type);

                            return [
                                'id' => (string) ($item['id'] ?? ''),
                                'name' => $formattedName,
                                'raw_name' => $rawName,
                            ];
                        }, $items);

                        // Cache for 7 days
                        Cache::put($cacheKey, $data, 86400 * 7);
                    }
                }
            }
        } catch (\Throwable $e) {
            $data = [];
        }

        return response()->json([
            'success' => true,
            'source' => 'online_api',
            'type' => $type,
            'parent_id' => $parentId,
            'data' => $data,
        ]);
    }

    private function formatIndonesianRegionName(string $name, string $type): string
    {
        $name = trim($name);
        if ($type === 'provinces') {
            if (strcasecmp($name, 'DAERAH ISTIMEWA YOGYAKARTA') === 0 || strcasecmp($name, 'DI YOGYAKARTA') === 0) {
                return 'D.I. Yogyakarta';
            }
            if (strcasecmp($name, 'DKI JAKARTA') === 0 || strcasecmp($name, 'DAERAH KHUSUS IBUKOTA JAKARTA') === 0) {
                return 'DKI Jakarta';
            }
            return ucwords(strtolower($name));
        }

        if ($type === 'regencies') {
            if (stripos($name, 'KABUPATEN') === 0) {
                return 'Kabupaten ' . ucwords(strtolower(trim(substr($name, 9))));
            }
            if (stripos($name, 'KOTA') === 0) {
                return 'Kota ' . ucwords(strtolower(trim(substr($name, 4))));
            }
            return 'Kabupaten ' . ucwords(strtolower($name));
        }

        if ($type === 'districts') {
            $clean = preg_replace('/^(kecamatan|kec\.)\s*/i', '', $name);
            return 'Kecamatan ' . ucwords(strtolower(trim($clean)));
        }

        if ($type === 'villages') {
            $clean = preg_replace('/^(kelurahan|desa|kel\.)\s*/i', '', $name);
            return ucwords(strtolower(trim($clean)));
        }

        return ucwords(strtolower($name));
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
        $source = 'openstreetmap';

        // 1. Primary Reverse Geocoder: OpenStreetMap Nominatim
        try {
            $response = Http::timeout(4)
                ->withoutVerifying()
                ->withHeaders([
                    'User-Agent' => 'LifeConnect-SalesSimulator/2.0 (admin@lifemedia.id)',
                    'Accept-Language' => 'id,en;q=0.9',
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
            // Log or fallback
        }

        // 2. Secondary Fallback Geocoder: BigDataCloud Reverse Geocode
        if (!$addressData || empty($addressData['address'])) {
            try {
                $bdcResponse = Http::timeout(3)
                    ->withoutVerifying()
                    ->get("https://api.bigdatacloud.net/data/reverse-geocode-client", [
                        'latitude' => $lat,
                        'longitude' => $lng,
                        'localityLanguage' => 'id',
                    ]);
                if ($bdcResponse->successful()) {
                    $bdcData = $bdcResponse->json();
                    $source = 'bigdatacloud';
                    $addressData = [
                        'display_name' => ($bdcData['locality'] ?? '') . ', ' . ($bdcData['city'] ?? '') . ', ' . ($bdcData['principalSubdivision'] ?? ''),
                        'address' => [
                            'state' => $bdcData['principalSubdivision'] ?? '',
                            'city' => $bdcData['city'] ?? '',
                            'county' => $bdcData['locality'] ?? '',
                            'suburb' => $bdcData['locality'] ?? '',
                            'village' => $bdcData['locality'] ?? '',
                            'road' => $bdcData['locality'] ?? '',
                        ]
                    ];
                }
            } catch (\Throwable $e) {
                // Secondary failed
            }
        }

        $addr = $addressData['address'] ?? [];
        $displayName = $addressData['display_name'] ?? '';

        // Extract raw fields
        $rawState = $addr['state'] ?? $addr['province'] ?? $addr['region'] ?? '';
        $rawCity = $addr['city'] ?? $addr['county'] ?? $addr['regency'] ?? $addr['city_district'] ?? $addr['town'] ?? '';
        $rawDistrict = $addr['municipality'] ?? $addr['suburb'] ?? $addr['district'] ?? $addr['city_district'] ?? '';
        $rawVillage = $addr['village'] ?? $addr['quarter'] ?? $addr['neighbourhood'] ?? $addr['residential'] ?? $addr['subdistrict'] ?? $addr['hamlet'] ?? '';
        $rawRoad = $addr['road'] ?? $addr['pedestrian'] ?? $addr['street'] ?? '';
        $houseNumber = $addr['house_number'] ?? '';

        if (!$rawRoad && $displayName) {
            $parts = explode(',', $displayName);
            $rawRoad = trim($parts[0] ?? '');
        }
        $fullRoad = $rawRoad;
        if ($houseNumber && $fullRoad && !str_contains($fullRoad, $houseNumber)) {
            $fullRoad .= ' No. ' . $houseNumber;
        }

        // Format and standardize names for Indonesia
        $normProvince = $rawState ? $this->formatIndonesianRegionName($rawState, 'provinces') : 'D.I. Yogyakarta';
        $normRegency = $rawCity ? $this->formatIndonesianRegionName($rawCity, 'regencies') : '';
        $normDistrict = $rawDistrict ? $this->formatIndonesianRegionName($rawDistrict, 'districts') : '';
        $normVillage = $rawVillage ? $this->formatIndonesianRegionName($rawVillage, 'villages') : '';

        // If district / village are same or ambiguous, ensure good defaults
        if ($normDistrict === '' && $normRegency !== '') {
            $normDistrict = 'Kecamatan ' . preg_replace('/^(kabupaten|kota)\s*/i', '', $normRegency);
        }
        if ($normVillage === '' && $normDistrict !== '') {
            $normVillage = preg_replace('/^kecamatan\s*/i', '', $normDistrict);
        }

        // Complete fallback if all external providers were offline
        if (!$normRegency) $normRegency = 'Kabupaten Sleman';
        if (!$normDistrict) $normDistrict = 'Kecamatan Depok';
        if (!$normVillage) $normVillage = 'Condongcatur';

        // Lookup Online API IDs (provinces, regencies, districts, villages)
        $onlineMatch = $this->matchOnlineRegionIds($normProvince, $normRegency, $normDistrict, $normVillage);

        // Fuzzy match with Local Database (if exists)
        $dbMatch = $this->matchDbRegions($normProvince, $normRegency, $normDistrict, $normVillage);

        return response()->json([
            'success' => true,
            'source' => $source,
            'data' => [
                'province' => $normProvince,
                'province_id' => $onlineMatch['province_id'] ?? null,
                'regency' => $normRegency,
                'regency_id' => $onlineMatch['regency_id'] ?? null,
                'district' => $normDistrict,
                'district_id' => $onlineMatch['district_id'] ?? null,
                'village' => $normVillage,
                'village_id' => $onlineMatch['village_id'] ?? null,
                'road' => $fullRoad,
                'display_name' => $displayName,
                'online_match' => $onlineMatch,
                'db_match' => $dbMatch,
                'raw_address' => $addr,
            ],
        ]);
    }

    private function matchOnlineRegionIds(string $provinceName, string $regencyName, string $districtName, string $villageName): array
    {
        $result = [
            'province_id' => null,
            'province_name' => $provinceName,
            'regency_id' => null,
            'regency_name' => $regencyName,
            'district_id' => null,
            'district_name' => $districtName,
            'village_id' => null,
            'village_name' => $villageName,
        ];

        try {
            // 1. Match Province
            $provinces = Cache::remember('online_wilayah_provinces_all', 86400 * 7, function () {
                $res = Http::timeout(4)->withoutVerifying()->get('https://emsifa.github.io/api-wilayah-indonesia/api/provinces.json');
                return $res->successful() ? $res->json() : [];
            });

            $cleanProv = strtoupper(trim(preg_replace('/[^a-zA-Z0-9]/', '', $provinceName)));
            $matchedProv = null;

            foreach ($provinces as $p) {
                $pClean = strtoupper(trim(preg_replace('/[^a-zA-Z0-9]/', '', $p['name'])));
                if ($pClean === $cleanProv || str_contains($pClean, $cleanProv) || str_contains($cleanProv, $pClean)) {
                    $matchedProv = $p;
                    break;
                }
            }

            if ($matchedProv) {
                $result['province_id'] = (string) $matchedProv['id'];

                // 2. Match Regency
                $regencies = Cache::remember("online_wilayah_regencies_{$matchedProv['id']}", 86400 * 7, function () use ($matchedProv) {
                    $res = Http::timeout(4)->withoutVerifying()->get("https://emsifa.github.io/api-wilayah-indonesia/api/regencies/{$matchedProv['id']}.json");
                    return $res->successful() ? $res->json() : [];
                });

                $cleanReg = strtoupper(trim(preg_replace('/^(kabupaten|kota)\s*/i', '', $regencyName)));
                $cleanReg = trim(preg_replace('/[^a-zA-Z0-9]/', '', $cleanReg));
                $matchedReg = null;

                foreach ($regencies as $r) {
                    $rClean = strtoupper(trim(preg_replace('/^(kabupaten|kota)\s*/i', '', $r['name'])));
                    $rClean = trim(preg_replace('/[^a-zA-Z0-9]/', '', $rClean));
                    if ($rClean === $cleanReg || str_contains($rClean, $cleanReg) || str_contains($cleanReg, $rClean)) {
                        $matchedReg = $r;
                        break;
                    }
                }

                if ($matchedReg) {
                    $result['regency_id'] = (string) $matchedReg['id'];

                    // 3. Match District
                    $districts = Cache::remember("online_wilayah_districts_{$matchedReg['id']}", 86400 * 7, function () use ($matchedReg) {
                        $res = Http::timeout(4)->withoutVerifying()->get("https://emsifa.github.io/api-wilayah-indonesia/api/districts/{$matchedReg['id']}.json");
                        return $res->successful() ? $res->json() : [];
                    });

                    $cleanDist = strtoupper(trim(preg_replace('/^(kecamatan|kec\.)\s*/i', '', $districtName)));
                    $cleanDist = trim(preg_replace('/[^a-zA-Z0-9]/', '', $cleanDist));
                    $matchedDist = null;

                    foreach ($districts as $d) {
                        $dClean = strtoupper(trim(preg_replace('/[^a-zA-Z0-9]/', '', $d['name'])));
                        if ($dClean === $cleanDist || str_contains($dClean, $cleanDist) || str_contains($cleanDist, $dClean)) {
                            $matchedDist = $d;
                            break;
                        }
                    }

                    if ($matchedDist) {
                        $result['district_id'] = (string) $matchedDist['id'];

                        // 4. Match Village
                        $villages = Cache::remember("online_wilayah_villages_{$matchedDist['id']}", 86400 * 7, function () use ($matchedDist) {
                            $res = Http::timeout(4)->withoutVerifying()->get("https://emsifa.github.io/api-wilayah-indonesia/api/villages/{$matchedDist['id']}.json");
                            return $res->successful() ? $res->json() : [];
                        });

                        $cleanVill = strtoupper(trim(preg_replace('/^(kelurahan|desa|kel\.)\s*/i', '', $villageName)));
                        $cleanVill = trim(preg_replace('/[^a-zA-Z0-9]/', '', $cleanVill));
                        $matchedVill = null;

                        foreach ($villages as $v) {
                            $vClean = strtoupper(trim(preg_replace('/[^a-zA-Z0-9]/', '', $v['name'])));
                            if ($vClean === $cleanVill || str_contains($vClean, $cleanVill) || str_contains($cleanVill, $vClean)) {
                                $matchedVill = $v;
                                break;
                            }
                        }

                        if ($matchedVill) {
                            $result['village_id'] = (string) $matchedVill['id'];
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
            // Soft fail
        }

        return $result;
    }

    private function matchDbRegions(string $province, string $regency, string $district, string $village): array
    {
        $matchedProvince = null;
        $matchedRegency = null;
        $matchedDistrict = null;
        $matchedVillage = null;

        try {
            // Province Match
            $matchedProvince = Region::where('type', 'provinsi')
                ->where(function ($q) use ($province) {
                    $q->where('name', 'LIKE', "%{$province}%")
                      ->orWhereRaw('? LIKE CONCAT("%", name, "%")', [$province]);
                })->first();

            if ($matchedProvince) {
                $cleanCity = trim(preg_replace('/^(kabupaten|kota)\s*/i', '', $regency));
                $matchedRegency = Region::where('type', 'kabupaten')
                    ->where('parent_id', $matchedProvince->id)
                    ->where(function ($q) use ($regency, $cleanCity) {
                        $q->where('name', 'LIKE', "%{$cleanCity}%")
                          ->orWhere('name', 'LIKE', "%{$regency}%")
                          ->orWhereRaw('? LIKE CONCAT("%", name, "%")', [$regency]);
                    })->first();

                if ($matchedRegency) {
                    $cleanDist = trim(preg_replace('/^(kecamatan|kec\.)\s*/i', '', $district));
                    $matchedDistrict = Region::where('type', 'kecamatan')
                        ->where('parent_id', $matchedRegency->id)
                        ->where(function ($q) use ($district, $cleanDist) {
                            $q->where('name', 'LIKE', "%{$cleanDist}%")
                              ->orWhere('name', 'LIKE', "%{$district}%")
                              ->orWhereRaw('? LIKE CONCAT("%", name, "%")', [$district]);
                        })->first();

                    if ($matchedDistrict) {
                        $cleanVill = trim(preg_replace('/^(kelurahan|desa|kel\.)\s*/i', '', $village));
                        $matchedVillage = Region::where('type', 'kelurahan')
                            ->where('parent_id', $matchedDistrict->id)
                            ->where(function ($q) use ($village, $cleanVill) {
                                $q->where('name', 'LIKE', "%{$cleanVill}%")
                                  ->orWhere('name', 'LIKE', "%{$village}%")
                                  ->orWhereRaw('? LIKE CONCAT("%", name, "%")', [$village]);
                            })->first();
                    }
                }
            }
        } catch (\Throwable $e) {
            // ignore
        }

        return [
            'province' => $matchedProvince ? ['id' => $matchedProvince->id, 'name' => $matchedProvince->name] : null,
            'regency' => $matchedRegency ? ['id' => $matchedRegency->id, 'name' => $matchedRegency->name] : null,
            'district' => $matchedDistrict ? ['id' => $matchedDistrict->id, 'name' => $matchedDistrict->name] : null,
            'village' => $matchedVillage ? ['id' => $matchedVillage->id, 'name' => $matchedVillage->name] : null,
        ];
    }
}
