<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Region;
use App\Models\SubscriptionPackage;
use App\Models\CustomerRegistration;
use App\Models\RegistrationProgressLog;
use App\Models\AuditLog;
use App\Models\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Users
        $users = [
            [
                'name' => 'Admin VAS LifeMedia',
                'email' => 'vas@lifemedia.id',
                'role' => 'admin_vas',
                'sales_id' => null,
                'phone' => '081122334401',
                'password' => Hash::make('password'),
                'status' => 'active',
            ],
            [
                'name' => 'Admin Sales Leader',
                'email' => 'salesadmin@lifemedia.id',
                'role' => 'admin_sales',
                'sales_id' => null,
                'phone' => '081122334402',
                'password' => Hash::make('password'),
                'status' => 'active',
            ],
            [
                'name' => 'Tim OPJ Verifikator',
                'email' => 'opj@lifemedia.id',
                'role' => 'opj',
                'sales_id' => null,
                'phone' => '081122334403',
                'password' => Hash::make('password'),
                'status' => 'active',
            ],
            [
                'name' => 'Customer Care Officer',
                'email' => 'ccare@lifemedia.id',
                'role' => 'c_care',
                'sales_id' => null,
                'phone' => '081122334404',
                'password' => Hash::make('password'),
                'status' => 'active',
            ],
            [
                'name' => 'Budi Pratama (AM)',
                'email' => 'sales01@lifemedia.id',
                'role' => 'sales',
                'sales_id' => 'AM-101',
                'phone' => '081234567801',
                'password' => Hash::make('password'),
                'status' => 'active',
            ],
            [
                'name' => 'Dewi Lestari (AM)',
                'email' => 'sales02@lifemedia.id',
                'role' => 'sales',
                'sales_id' => 'AM-102',
                'phone' => '081234567802',
                'password' => Hash::make('password'),
                'status' => 'active',
            ],
        ];

        $userMap = [];
        foreach ($users as $u) {
            $created = User::create($u);
            $userMap[$created->email] = $created;
        }

        // 2. Seed Subscription Packages
        $packages = [
            [
                'code' => 'IZZI-30M',
                'name' => 'Izzi Life 30',
                'speed' => '30 Mbps',
                'price' => 166500,
                'description' => 'Kecepatan Internet s/d 30 Mbps, ideal untuk browsing harian dan kebutuhan keluarga hemat.',
                'features' => ['Kecepatan hingga 30 Mbps', '100% Fiber Optic murni', 'Gratis Sewa ONT Wi-Fi Router', 'Unlimited Tanpa FUP'],
                'is_popular' => false,
                'is_active' => true,
            ],
            [
                'code' => 'IZZI-50M',
                'name' => 'Izzi Life 50',
                'speed' => '50 Mbps',
                'price' => 277500,
                'description' => 'Kecepatan Internet s/d 50 Mbps, streaming HD lancar dan meeting online multi-perangkat.',
                'features' => ['Kecepatan hingga 50 Mbps', 'Dual Band Wi-Fi Router', 'Prioritas Dukungan Teknis 24/7', 'Unlimited Tanpa FUP'],
                'is_popular' => true,
                'is_active' => true,
            ],
            [
                'code' => 'IZZI-100M',
                'name' => 'Izzi Life 100',
                'speed' => '100 Mbps',
                'price' => 388500,
                'description' => 'Kecepatan Internet s/d 100 Mbps, streaming 4K tanpa buffering dan download super cepat.',
                'features' => ['Kecepatan simetris 100 Mbps', 'Dual Band AC Gigabit Router', 'Prioritas Dukungan Teknis 24/7', 'Bisa tambah Add-on IPTV'],
                'is_popular' => false,
                'is_active' => true,
            ],
            [
                'code' => 'IZZI-200M',
                'name' => 'Izzi Life 200',
                'speed' => '200 Mbps',
                'price' => 666000,
                'description' => 'Kecepatan Internet s/d 200 Mbps, koneksi ultra-stabil untuk gaming, smart home, dan konten kreator.',
                'features' => ['Kecepatan simetris 200 Mbps', 'Gaming Traffic Routing Priority', 'Dual Band Wi-Fi 6 Router', 'Unlimited Tanpa FUP'],
                'is_popular' => false,
                'is_active' => true,
            ],
        ];

        $packageMap = [];
        foreach ($packages as $pkg) {
            $created = SubscriptionPackage::create($pkg);
            $packageMap[$pkg['code']] = $created;
        }

        // 3. Seed Regions (Hierarchical)
        $diy = Region::create(['type' => 'provinsi', 'name' => 'D.I. Yogyakarta', 'code' => 'DIY']);
        $jateng = Region::create(['type' => 'provinsi', 'name' => 'Jawa Tengah', 'code' => 'JTG']);
        $dki = Region::create(['type' => 'provinsi', 'name' => 'DKI Jakarta', 'code' => 'DKI']);
        $jabar = Region::create(['type' => 'provinsi', 'name' => 'Jawa Barat', 'code' => 'JBR']);
        $jatim = Region::create(['type' => 'provinsi', 'name' => 'Jawa Timur', 'code' => 'JTM']);
        $banten = Region::create(['type' => 'provinsi', 'name' => 'Banten', 'code' => 'BTN']);
        $bali = Region::create(['type' => 'provinsi', 'name' => 'Bali', 'code' => 'BAL']);

        // DIY -> Sleman, Bantul, Kota Yogyakarta, Kulon Progo, Gunungkidul
        $sleman = Region::create(['type' => 'kabupaten', 'parent_id' => $diy->id, 'name' => 'Kabupaten Sleman', 'code' => 'SLM']);
        $bantul = Region::create(['type' => 'kabupaten', 'parent_id' => $diy->id, 'name' => 'Kabupaten Bantul', 'code' => 'BTL']);
        $jogja = Region::create(['type' => 'kabupaten', 'parent_id' => $diy->id, 'name' => 'Kota Yogyakarta', 'code' => 'YK']);
        $kulonprogo = Region::create(['type' => 'kabupaten', 'parent_id' => $diy->id, 'name' => 'Kabupaten Kulon Progo', 'code' => 'KP']);
        $gunungkidul = Region::create(['type' => 'kabupaten', 'parent_id' => $diy->id, 'name' => 'Kabupaten Gunungkidul', 'code' => 'GK']);

        // Sleman Districts & Villages
        $depok = Region::create(['type' => 'kecamatan', 'parent_id' => $sleman->id, 'name' => 'Kecamatan Depok']);
        Region::create(['type' => 'kelurahan', 'parent_id' => $depok->id, 'name' => 'Condongcatur']);
        Region::create(['type' => 'kelurahan', 'parent_id' => $depok->id, 'name' => 'Caturtunggal']);
        Region::create(['type' => 'kelurahan', 'parent_id' => $depok->id, 'name' => 'Maguwoharjo']);

        $mlati = Region::create(['type' => 'kecamatan', 'parent_id' => $sleman->id, 'name' => 'Kecamatan Mlati']);
        Region::create(['type' => 'kelurahan', 'parent_id' => $mlati->id, 'name' => 'Sinduadi']);
        Region::create(['type' => 'kelurahan', 'parent_id' => $mlati->id, 'name' => 'Sendangadi']);
        Region::create(['type' => 'kelurahan', 'parent_id' => $mlati->id, 'name' => 'Sumberadi']);

        $ngaglik = Region::create(['type' => 'kecamatan', 'parent_id' => $sleman->id, 'name' => 'Kecamatan Ngaglik']);
        Region::create(['type' => 'kelurahan', 'parent_id' => $ngaglik->id, 'name' => 'Sariharjo']);
        Region::create(['type' => 'kelurahan', 'parent_id' => $ngaglik->id, 'name' => 'Minomartani']);
        Region::create(['type' => 'kelurahan', 'parent_id' => $ngaglik->id, 'name' => 'Sardonoharjo']);

        // Bantul Districts & Villages
        $banguntapan = Region::create(['type' => 'kecamatan', 'parent_id' => $bantul->id, 'name' => 'Kecamatan Banguntapan']);
        Region::create(['type' => 'kelurahan', 'parent_id' => $banguntapan->id, 'name' => 'Banguntapan']);
        Region::create(['type' => 'kelurahan', 'parent_id' => $banguntapan->id, 'name' => 'Baturetno']);
        Region::create(['type' => 'kelurahan', 'parent_id' => $banguntapan->id, 'name' => 'Wirokerten']);

        $kasihan = Region::create(['type' => 'kecamatan', 'parent_id' => $bantul->id, 'name' => 'Kecamatan Kasihan']);
        Region::create(['type' => 'kelurahan', 'parent_id' => $kasihan->id, 'name' => 'Tamantirto']);
        Region::create(['type' => 'kelurahan', 'parent_id' => $kasihan->id, 'name' => 'Ngestiharjo']);

        // Kota Jogja Districts & Villages
        $gondokusuman = Region::create(['type' => 'kecamatan', 'parent_id' => $jogja->id, 'name' => 'Kecamatan Gondokusuman']);
        Region::create(['type' => 'kelurahan', 'parent_id' => $gondokusuman->id, 'name' => 'Kotabaru']);
        Region::create(['type' => 'kelurahan', 'parent_id' => $gondokusuman->id, 'name' => 'Demangan']);
        Region::create(['type' => 'kelurahan', 'parent_id' => $gondokusuman->id, 'name' => 'Klitren']);

        // Kulon Progo & Gunungkidul Districts & Villages
        $wates = Region::create(['type' => 'kecamatan', 'parent_id' => $kulonprogo->id, 'name' => 'Kecamatan Wates']);
        Region::create(['type' => 'kelurahan', 'parent_id' => $wates->id, 'name' => 'Wates']);
        Region::create(['type' => 'kelurahan', 'parent_id' => $wates->id, 'name' => 'Bendungan']);

        $wonosari = Region::create(['type' => 'kecamatan', 'parent_id' => $gunungkidul->id, 'name' => 'Kecamatan Wonosari']);
        Region::create(['type' => 'kelurahan', 'parent_id' => $wonosari->id, 'name' => 'Wonosari']);
        Region::create(['type' => 'kelurahan', 'parent_id' => $wonosari->id, 'name' => 'Kepek']);

        // Jawa Tengah, DKI Jakarta, Jawa Barat Regencies
        $semarang = Region::create(['type' => 'kabupaten', 'parent_id' => $jateng->id, 'name' => 'Kota Semarang', 'code' => 'SMG']);
        $klaten = Region::create(['type' => 'kabupaten', 'parent_id' => $jateng->id, 'name' => 'Kabupaten Klaten', 'code' => 'KLT']);
        $solo = Region::create(['type' => 'kabupaten', 'parent_id' => $jateng->id, 'name' => 'Kota Surakarta', 'code' => 'SOC']);

        $jaksel = Region::create(['type' => 'kabupaten', 'parent_id' => $dki->id, 'name' => 'Kota Administrasi Jakarta Selatan', 'code' => 'JKTS']);
        $jakpus = Region::create(['type' => 'kabupaten', 'parent_id' => $dki->id, 'name' => 'Kota Administrasi Jakarta Pusat', 'code' => 'JKTP']);

        $bandung = Region::create(['type' => 'kabupaten', 'parent_id' => $jabar->id, 'name' => 'Kota Bandung', 'code' => 'BDG']);
        $kabBandung = Region::create(['type' => 'kabupaten', 'parent_id' => $jabar->id, 'name' => 'Kabupaten Bandung', 'code' => 'KBDG']);

        // 4. Seed Sample Customer Registrations across all stages
        $sales1 = $userMap['sales01@lifemedia.id'];
        $sales2 = $userMap['sales02@lifemedia.id'];
        $opjUser = $userMap['opj@lifemedia.id'];
        $ccareUser = $userMap['ccare@lifemedia.id'];

        // Demo 1: SUBMITTED (Surveyed by Sales1, ready for OPJ verification)
        $reg1 = CustomerRegistration::create([
            'registration_code' => 'REG-2026-0001',
            'sales_user_id' => $sales1->id,
            'sales_am_id' => $sales1->sales_id,
            'sales_name' => $sales1->name,
            'customer_name' => 'Agus Setiawan, S.T.',
            'phone_wa' => '081223344551',
            'email' => 'agus.setiawan@gmail.com',
            'latitude' => -7.76135200,
            'longitude' => 110.38541200,
            'province' => 'D.I. Yogyakarta',
            'regency' => 'Kabupaten Sleman',
            'district' => 'Kecamatan Depok',
            'village' => 'Condongcatur',
            'address_detail' => 'Jl. Ring Road Utara No. 45, RT 04 / RW 12',
            'selfie_sales_path' => '/assets/demo/selfie_sample1.jpg',
            'status' => 'submitted',
            'token' => 'tok_' . Str::random(32),
            'submitted_at' => now()->subHours(3),
        ]);

        RegistrationProgressLog::create([
            'customer_registration_id' => $reg1->id,
            'user_id' => $sales1->id,
            'actor_name' => $sales1->name,
            'actor_role' => 'Sales (AM)',
            'from_status' => null,
            'to_status' => 'submitted',
            'notes' => 'Survey awal pelanggan di lokasi Condongcatur. Coverage FAT-04 tersedia.',
            'duration_seconds' => 0,
            'created_at' => now()->subHours(3),
        ]);

        // Demo 2: VERIFIED (Verified by OPJ, WhatsApp link ready to share to customer)
        $reg2 = CustomerRegistration::create([
            'registration_code' => 'REG-2026-0002',
            'sales_user_id' => $sales1->id,
            'sales_am_id' => $sales1->sales_id,
            'sales_name' => $sales1->name,
            'customer_name' => 'dr. Siti Rahmawati',
            'phone_wa' => '081398765432',
            'email' => 'siti.rahmawati@yahoo.com',
            'latitude' => -7.77254000,
            'longitude' => 110.37890000,
            'province' => 'D.I. Yogyakarta',
            'regency' => 'Kabupaten Sleman',
            'district' => 'Kecamatan Depok',
            'village' => 'Caturtunggal',
            'address_detail' => 'Kompleks Dosen UGM Blok B-14',
            'selfie_sales_path' => '/assets/demo/selfie_sample2.jpg',
            'status' => 'verified',
            'token' => 'tok_' . Str::random(32),
            'submitted_at' => now()->subHours(6),
            'verified_at' => now()->subHours(5),
        ]);

        RegistrationProgressLog::create([
            'customer_registration_id' => $reg2->id,
            'user_id' => $sales1->id,
            'actor_name' => $sales1->name,
            'actor_role' => 'Sales (AM)',
            'from_status' => null,
            'to_status' => 'submitted',
            'notes' => 'Survey awal di Kompleks Dosen UGM.',
            'duration_seconds' => 0,
            'created_at' => now()->subHours(6),
        ]);

        RegistrationProgressLog::create([
            'customer_registration_id' => $reg2->id,
            'user_id' => $opjUser->id,
            'actor_name' => $opjUser->name,
            'actor_role' => 'OPJ',
            'from_status' => 'submitted',
            'to_status' => 'verified',
            'notes' => 'Koordinat valid dan berada dalam jangkauan ODP-LM-CT-09 (jarak 45m). Siap bagikan link pendaftaran.',
            'duration_seconds' => 3600,
            'created_at' => now()->subHours(5),
        ]);

        Notification::create([
            'user_id' => $sales1->id,
            'sales_am_id' => $sales1->sales_id,
            'title' => 'Survey Terverifikasi OPJ!',
            'message' => 'Pengajuan pelanggan dr. Siti Rahmawati (REG-2026-0002) telah diverifikasi OPJ. Silakan bagikan link WhatsApp ke pelanggan.',
            'type' => 'survey_verified',
            'customer_registration_id' => $reg2->id,
            'link' => '/pendaftaran/' . $reg2->token,
            'action_type' => 'share_whatsapp',
            'is_read' => false,
            'created_at' => now()->subHours(5),
        ]);

        // Demo 3: FILLED (Customer completed online registration, waiting for C-Care review)
        $reg3 = CustomerRegistration::create([
            'registration_code' => 'REG-2026-0003',
            'sales_user_id' => $sales2->id,
            'sales_am_id' => $sales2->sales_id,
            'sales_name' => $sales2->name,
            'customer_name' => 'Hendro Prasetyo',
            'phone_wa' => '081755443322',
            'email' => 'hendro.prasetyo@outlook.com',
            'latitude' => -7.78421000,
            'longitude' => 110.36952000,
            'province' => 'D.I. Yogyakarta',
            'regency' => 'Kota Yogyakarta',
            'district' => 'Kecamatan Gondokusuman',
            'village' => 'Kotabaru',
            'address_detail' => 'Jl. I Dewa Nyoman Oka No. 8',
            'selfie_sales_path' => '/assets/demo/selfie_sample3.jpg',
            'status' => 'filled',
            'token' => 'tok_' . Str::random(32),
            // Customer filled data
            'nik' => '3471011504890003',
            'birth_date' => '1989-04-15',
            'gender' => 'Laki-laki',
            'emergency_contact_name' => 'Rina Wahyuni (Istri)',
            'emergency_contact_phone' => '081755443399',
            'emergency_contact_relation' => 'Istri',
            'package_id' => $packageMap['LM-100M']->id,
            'addons' => ['4K IPTV STB', 'WiFi Extender'],
            'billing_method' => 'BCA Virtual Account',
            'billing_email' => 'hendro.prasetyo@outlook.com',
            'billing_cycle' => 'Bulanan (Setiap Tgl 1)',
            'ktp_photo_path' => '/assets/demo/ktp_sample.jpg',
            'house_photo_path' => '/assets/demo/house_sample.jpg',
            'signature_path' => 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIzMDAiIGhlaWdodD0iMTAwIj48cGF0aCBkPSJNMTAgODAgQyA0MCAxMCwgNjUgMTAwLCA5NSA1MCBTIDE1MCAxMCwgMTgwIDgwIFMgMjMwIDQwLCAyODAgNzAiIHN0cm9rZT0iIzFhM2E1ZiIgc3Ryb2tlLXdpZHRoPSIzIiBmaWxsPSJub25lIi8+PC9zdmc+',
            'terms_agreed' => true,
            'submitted_at' => now()->subDays(1)->subHours(4),
            'verified_at' => now()->subDays(1)->subHours(3),
            'filled_at' => now()->subHours(2),
        ]);

        RegistrationProgressLog::create([
            'customer_registration_id' => $reg3->id,
            'user_id' => $sales2->id,
            'actor_name' => $sales2->name,
            'actor_role' => 'Sales (AM)',
            'from_status' => null,
            'to_status' => 'submitted',
            'notes' => 'Survey lokasi Kotabaru.',
            'duration_seconds' => 0,
            'created_at' => now()->subDays(1)->subHours(4),
        ]);

        RegistrationProgressLog::create([
            'customer_registration_id' => $reg3->id,
            'user_id' => $opjUser->id,
            'actor_name' => $opjUser->name,
            'actor_role' => 'OPJ',
            'from_status' => 'submitted',
            'to_status' => 'verified',
            'notes' => 'Tervalidasi di ODP Kotabaru-03.',
            'duration_seconds' => 3600,
            'created_at' => now()->subDays(1)->subHours(3),
        ]);

        RegistrationProgressLog::create([
            'customer_registration_id' => $reg3->id,
            'user_id' => null,
            'actor_name' => 'Hendro Prasetyo (Pelanggan)',
            'actor_role' => 'Pelanggan',
            'from_status' => 'verified',
            'to_status' => 'filled',
            'notes' => 'Pelanggan telah mengisi data NIK, memilih paket Life Fiber 100 Mbps, upload dokumen KTP & TTD digital.',
            'duration_seconds' => 75600,
            'created_at' => now()->subHours(2),
        ]);

        // Demo 4: APPROVED (Fully approved by C-Care, ready for installation)
        $reg4 = CustomerRegistration::create([
            'registration_code' => 'REG-2026-0004',
            'sales_user_id' => $sales1->id,
            'sales_am_id' => $sales1->sales_id,
            'sales_name' => $sales1->name,
            'customer_name' => 'Bambang Trihatmojo',
            'phone_wa' => '081299887766',
            'email' => 'bambang.tri@gmail.com',
            'latitude' => -7.81234000,
            'longitude' => 110.39870000,
            'province' => 'D.I. Yogyakarta',
            'regency' => 'Kabupaten Bantul',
            'district' => 'Kecamatan Banguntapan',
            'village' => 'Banguntapan',
            'address_detail' => 'Jl. Gedongkuning Selatan No. 12',
            'selfie_sales_path' => '/assets/demo/selfie_sample4.jpg',
            'status' => 'approved',
            'token' => 'tok_' . Str::random(32),
            'nik' => '3402012008770002',
            'birth_date' => '1977-08-20',
            'gender' => 'Laki-laki',
            'emergency_contact_name' => 'Wulandari',
            'emergency_contact_phone' => '081299887700',
            'emergency_contact_relation' => 'Keluarga',
            'package_id' => $packageMap['LM-200M']->id,
            'addons' => ['WiFi Extender'],
            'billing_method' => 'Mandiri Virtual Account',
            'billing_email' => 'bambang.tri@gmail.com',
            'billing_cycle' => 'Bulanan (Setiap Tgl 1)',
            'ktp_photo_path' => '/assets/demo/ktp_sample.jpg',
            'house_photo_path' => '/assets/demo/house_sample.jpg',
            'signature_path' => 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIzMDAiIGhlaWdodD0iMTAwIj48cGF0aCBkPSJNMjAgNTAgUSA5MCAyMCAxNDAgODAgVCAyNjAgMzAiIHN0cm9rZT0iIzFhM2E1ZiIgc3Ryb2tlLXdpZHRoPSI0IiBmaWxsPSJub25lIi8+PC9zdmc+',
            'terms_agreed' => true,
            'submitted_at' => now()->subDays(2)->subHours(8),
            'verified_at' => now()->subDays(2)->subHours(7),
            'filled_at' => now()->subDays(2)->subHours(2),
            'approved_at' => now()->subDays(1)->subHours(5),
        ]);

        RegistrationProgressLog::create([
            'customer_registration_id' => $reg4->id,
            'user_id' => $sales1->id,
            'actor_name' => $sales1->name,
            'actor_role' => 'Sales (AM)',
            'from_status' => null,
            'to_status' => 'submitted',
            'notes' => 'Survey awal di Banguntapan Bantul.',
            'duration_seconds' => 0,
            'created_at' => now()->subDays(2)->subHours(8),
        ]);

        RegistrationProgressLog::create([
            'customer_registration_id' => $reg4->id,
            'user_id' => $opjUser->id,
            'actor_name' => $opjUser->name,
            'actor_role' => 'OPJ',
            'from_status' => 'submitted',
            'to_status' => 'verified',
            'notes' => 'Verifikasi OPJ disetujui.',
            'duration_seconds' => 3600,
            'created_at' => now()->subDays(2)->subHours(7),
        ]);

        RegistrationProgressLog::create([
            'customer_registration_id' => $reg4->id,
            'user_id' => null,
            'actor_name' => 'Bambang Trihatmojo (Pelanggan)',
            'actor_role' => 'Pelanggan',
            'from_status' => 'verified',
            'to_status' => 'filled',
            'notes' => 'Pelanggan mengisi kelengkapan paket Life Gamer 200M.',
            'duration_seconds' => 18000,
            'created_at' => now()->subDays(2)->subHours(2),
        ]);

        RegistrationProgressLog::create([
            'customer_registration_id' => $reg4->id,
            'user_id' => $ccareUser->id,
            'actor_name' => $ccareUser->name,
            'actor_role' => 'C-Care',
            'from_status' => 'filled',
            'to_status' => 'approved',
            'notes' => 'Data KTP, Alamat, TTD, dan Billing lengkap valid. Pendaftaran DISETUJUI untuk diterbitkan Work Order instalasi.',
            'duration_seconds' => 75600,
            'created_at' => now()->subDays(1)->subHours(5),
        ]);

        Notification::create([
            'user_id' => $sales1->id,
            'sales_am_id' => $sales1->sales_id,
            'title' => 'Pengajuan Pelanggan Disetujui (Approved)!',
            'message' => 'Selamat! Pendaftaran pelanggan Bambang Trihatmojo (REG-2026-0004) telah di-approve oleh C-Care.',
            'type' => 'registration_approved',
            'customer_registration_id' => $reg4->id,
            'link' => null,
            'action_type' => 'view_detail',
            'is_read' => true,
            'created_at' => now()->subDays(1)->subHours(5),
        ]);

        // Demo 5: REVISION (C-Care asked for KTP revision)
        $reg5 = CustomerRegistration::create([
            'registration_code' => 'REG-2026-0005',
            'sales_user_id' => $sales2->id,
            'sales_am_id' => $sales2->sales_id,
            'sales_name' => $sales2->name,
            'customer_name' => 'Anisa Prameswari',
            'phone_wa' => '081811223344',
            'email' => 'anisa.prameswari@gmail.com',
            'latitude' => -7.75620000,
            'longitude' => 110.36210000,
            'province' => 'D.I. Yogyakarta',
            'regency' => 'Kabupaten Sleman',
            'district' => 'Kecamatan Mlati',
            'village' => 'Sinduadi',
            'address_detail' => 'Kutulon Sinduadi RT 02 / RW 08',
            'selfie_sales_path' => '/assets/demo/selfie_sample5.jpg',
            'status' => 'revision',
            'token' => 'tok_' . Str::random(32),
            'nik' => '3404016503920001',
            'birth_date' => '1992-03-25',
            'gender' => 'Perempuan',
            'emergency_contact_name' => 'Bapak Subarjo',
            'emergency_contact_phone' => '081811223399',
            'emergency_contact_relation' => 'Orang Tua',
            'package_id' => $packageMap['LM-50M']->id,
            'billing_method' => 'QRIS (Gopay/ShopeePay)',
            'billing_email' => 'anisa.prameswari@gmail.com',
            'billing_cycle' => 'Bulanan (Setiap Tgl 1)',
            'ktp_photo_path' => '/assets/demo/ktp_sample.jpg',
            'house_photo_path' => '/assets/demo/house_sample.jpg',
            'signature_path' => 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIzMDAiIGhlaWdodD0iMTAwIj48cGF0aCBkPSJNMjAgODAgQyA3MCAxMCwgMTAwIDkwLCAxNTAgMzAgUyAyMjAgOTAsIDI4MCA0MCIgc3Ryb2tlPSIjMWEzYTVmIiBzdHJva2Utd2lkdGg9IjMiIGZpbGw9Im5vbmUiLz48L3N2Zz4=',
            'terms_agreed' => true,
            'rejection_notes' => 'Foto KTP buram / tidak terbaca pada bagian NIK dan Nama lengkap. Mohon Sales melakukan follow up ke pelanggan untuk mengunggah ulang foto KTP yang lebih jelas dan terang.',
            'rejection_category' => 'Dokumen KTP Tidak Terbaca',
            'submitted_at' => now()->subDays(1)->subHours(12),
            'verified_at' => now()->subDays(1)->subHours(10),
            'filled_at' => now()->subHours(6),
            'revision_at' => now()->subHours(1),
        ]);

        RegistrationProgressLog::create([
            'customer_registration_id' => $reg5->id,
            'user_id' => $sales2->id,
            'actor_name' => $sales2->name,
            'actor_role' => 'Sales (AM)',
            'from_status' => null,
            'to_status' => 'submitted',
            'notes' => 'Survey awal Sinduadi Mlati.',
            'duration_seconds' => 0,
            'created_at' => now()->subDays(1)->subHours(12),
        ]);

        RegistrationProgressLog::create([
            'customer_registration_id' => $reg5->id,
            'user_id' => $opjUser->id,
            'actor_name' => $opjUser->name,
            'actor_role' => 'OPJ',
            'from_status' => 'submitted',
            'to_status' => 'verified',
            'notes' => 'Verifikasi OPJ disetujui.',
            'duration_seconds' => 7200,
            'created_at' => now()->subDays(1)->subHours(10),
        ]);

        RegistrationProgressLog::create([
            'customer_registration_id' => $reg5->id,
            'user_id' => null,
            'actor_name' => 'Anisa Prameswari (Pelanggan)',
            'actor_role' => 'Pelanggan',
            'from_status' => 'verified',
            'to_status' => 'filled',
            'notes' => 'Pelanggan mengisi data.',
            'duration_seconds' => 14400,
            'created_at' => now()->subHours(6),
        ]);

        RegistrationProgressLog::create([
            'customer_registration_id' => $reg5->id,
            'user_id' => $ccareUser->id,
            'actor_name' => $ccareUser->name,
            'actor_role' => 'C-Care',
            'from_status' => 'filled',
            'to_status' => 'revision',
            'notes' => 'Status diubah ke Revision: Foto KTP buram / tidak terbaca pada bagian NIK dan Nama lengkap.',
            'duration_seconds' => 18000,
            'created_at' => now()->subHours(1),
        ]);

        Notification::create([
            'user_id' => $sales2->id,
            'sales_am_id' => $sales2->sales_id,
            'title' => 'Perhatian: Pengajuan Memerlukan Revisi',
            'message' => 'Pendaftaran Anisa Prameswari (REG-2026-0005) ditolak oleh C-Care dengan catatan: Foto KTP buram. Silakan follow up ke pelanggan.',
            'type' => 'registration_revision',
            'customer_registration_id' => $reg5->id,
            'link' => '/pendaftaran/' . $reg5->token,
            'action_type' => 'revise_data',
            'is_read' => false,
            'created_at' => now()->subHours(1),
        ]);

        // 5. Seed Initial Audit Logs
        AuditLog::create([
            'user_id' => $sales1->id,
            'user_name' => $sales1->name,
            'user_role' => 'sales',
            'action' => 'CREATE_SURVEY',
            'module' => 'Survey',
            'target_type' => 'CustomerRegistration',
            'target_id' => $reg1->id,
            'description' => 'Sales Budi Pratama (AM-101) mengajukan data survey baru untuk calon pelanggan Agus Setiawan.',
            'old_values' => null,
            'new_values' => ['registration_code' => 'REG-2026-0001', 'customer_name' => 'Agus Setiawan, S.T.', 'status' => 'submitted'],
            'ip_address' => '127.0.0.1',
            'user_agent' => 'LifeConnectMobileApp/2.4 (Android 14; Build/UP1A.231005.007)',
            'created_at' => now()->subHours(3),
        ]);

        AuditLog::create([
            'user_id' => $opjUser->id,
            'user_name' => $opjUser->name,
            'user_role' => 'opj',
            'action' => 'VERIFY_SURVEY',
            'module' => 'OPJ',
            'target_type' => 'CustomerRegistration',
            'target_id' => $reg2->id,
            'description' => 'OPJ memverifikasi lokasi survey dr. Siti Rahmawati (REG-2026-0002) dan mengaktifkan tautan form pelanggan.',
            'old_values' => ['status' => 'submitted'],
            'new_values' => ['status' => 'verified'],
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/128.0.0.0',
            'created_at' => now()->subHours(5),
        ]);

        AuditLog::create([
            'user_id' => $ccareUser->id,
            'user_name' => $ccareUser->name,
            'user_role' => 'c_care',
            'action' => 'APPROVE_CUSTOMER',
            'module' => 'CCare',
            'target_type' => 'CustomerRegistration',
            'target_id' => $reg4->id,
            'description' => 'C-Care menyetujui pendaftaran Bambang Trihatmojo (REG-2026-0004) dengan paket Life Gamer 200 Mbps.',
            'old_values' => ['status' => 'filled'],
            'new_values' => ['status' => 'approved'],
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/128.0.0.0',
            'created_at' => now()->subDays(1)->subHours(5),
        ]);
    }
}
