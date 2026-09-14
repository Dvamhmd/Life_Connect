<!DOCTYPE html>
<html lang="id" class="h-full bg-[#F8F9FA]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Life Connect - Android Mobile App Simulator (Sales AM)</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}?v=2">
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}?v=2">
    <link rel="apple-touch-icon" href="{{ asset('assets/images/favicon.png') }}?v=2">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Leaflet CSS for In-App Mini Map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            sunset: '#F48C5B',
                            coral: '#EF666B',
                            plum: '#9B385B',
                            peach: '#FEF4F0',
                            charcoal: '#333333',
                            grey: '#F5F5F5',
                        }
                    }
                }
            }
        }
    </script>
    
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; color: #333333; }
        .phone-case {
            width: 390px;
            height: 800px;
            background: #FFFFFF;
            border: 10px solid #E2E8F0;
            border-radius: 48px;
            box-shadow: 0 25px 50px -12px rgba(155, 56, 91, 0.18), 0 0 0 2px #CBD5E1, 0 0 40px rgba(244, 140, 91, 0.15);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        .notch {
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 140px;
            height: 25px;
            background: #E2E8F0;
            border-bottom-left-radius: 16px;
            border-bottom-right-radius: 16px;
            z-index: 50;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .notch-camera {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #475569;
            border: 1px solid #94A3B8;
        }
        .notch-speaker {
            width: 40px;
            height: 4px;
            border-radius: 2px;
            background: #94A3B8;
        }
        .app-screen {
            flex: 1;
            overflow-y: auto;
            background: #F8F9FA;
            display: flex;
            flex-direction: column;
        }
        .app-screen::-webkit-scrollbar {
            width: 4px;
        }
        .app-screen::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 4px;
        }
    </style>
</head>
<body class="min-h-full bg-[#F8F9FA] text-[#333333] flex flex-col lg:flex-row items-center justify-center p-4 lg:p-8 gap-8 selection:bg-[#EF666B] selection:text-white">

    <!-- Background Decorative Glows -->
    <div class="fixed top-0 left-0 w-96 h-96 bg-[#F48C5B]/10 rounded-full blur-3xl pointer-events-none -z-10"></div>
    <div class="fixed bottom-0 right-0 w-96 h-96 bg-[#9B385B]/10 rounded-full blur-3xl pointer-events-none -z-10"></div>

    <!-- Left Simulator Control & Documentation Panel -->
    <div class="max-w-md w-full space-y-5 text-center lg:text-left">
        <div class="inline-flex items-center gap-2 p-2 rounded-2xl bg-white border border-[#F0E8E4] shadow-xs">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Life Media Logo" class="h-6 w-auto object-contain">
            <span class="text-[11px] font-bold text-[#EF666B] pr-2 border-l border-gray-200 pl-2">Mobile Simulator</span>
        </div>

        <div class="space-y-2">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#2C2C2C] tracking-tight">Simulator Android Sales (AM)</h1>
            <p class="text-xs sm:text-sm text-gray-600 leading-relaxed">
                Simulator interaktif ini merepresentasikan aplikasi Android Life Connect yang digunakan oleh tim Sales di lapangan untuk survey lokasi, penentuan titik GPS, dan follow up pendaftaran via WhatsApp.
            </p>
        </div>

        <!-- Quick Switch AM User Controls -->
        <div class="p-4 rounded-2xl bg-white border border-[#F0E8E4] shadow-sm space-y-2 text-xs text-left">
            <span class="font-bold text-[#333333] block">Pilih Akun Sales (AM) Aktif:</span>
            <div class="grid grid-cols-2 gap-2">
                @foreach($salesUsers as $s)
                    <button type="button" onclick="quickLoginSales('{{ $s->sales_id }}', 'password', '{{ $s->id }}', '{{ $s->name }}', '{{ $s->email }}', '{{ $s->phone }}')"
                            class="p-2.5 rounded-xl bg-[#FEF4F0]/60 border border-[#F0E8E4] hover:border-[#EF666B] text-left transition-all hover:bg-white hover:shadow-sm group">
                        <div class="font-bold text-[#2C2C2C] text-xs truncate group-hover:text-[#EF666B] transition-colors">{{ $s->name }}</div>
                        <div class="text-[10px] text-[#F48C5B] font-mono font-semibold">{{ $s->sales_id }}</div>
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Flow Instructions -->
        <div class="p-4 rounded-2xl bg-white border border-[#F0E8E4] shadow-sm text-xs text-left space-y-2 text-gray-600">
            <div class="font-bold text-[#2C2C2C] flex items-center gap-2">
                <i class="fa-solid fa-circle-info text-[#F48C5B]"></i>
                <span>Alur Penggunaan Simulator:</span>
            </div>
            <ol class="list-decimal list-inside space-y-1 text-[11px] leading-relaxed text-gray-600">
                <li>Sales Login menggunakan ID Sales (AM) &amp; Password.</li>
                <li>Data Sales terisi otomatis di form survey.</li>
                <li>Isi Nama, No WA, Email, Lokasi Wilayah &amp; Deteksi Titik GPS.</li>
                <li>Klik <strong>Submit</strong> &rarr; data terkirim ke Database status <span class="font-semibold text-amber-600">Submitted</span>.</li>
                <li>Buka Dashboard OPJ untuk verifikasi &rarr; status berubah <span class="font-semibold text-blue-600">Verified</span>.</li>
                <li>Cek Tab Notifikasi Simulator &rarr; klik <strong>"Bagikan Link WA"</strong>.</li>
            </ol>
        </div>

        <div class="pt-2">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-xs font-bold text-[#EF666B] hover:text-[#9B385B] transition-colors">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Kembali ke Dashboard Web</span>
            </a>
        </div>
    </div>

    <!-- Right Android Smartphone Shell -->
    <div class="phone-case">
        
        <!-- Hardware Notch -->
        <div class="notch">
            <div class="notch-speaker"></div>
            <div class="notch-camera"></div>
        </div>

        <!-- Android Status Bar -->
        <div class="pt-7 px-6 pb-2 flex items-center justify-between text-[11px] font-semibold text-gray-700 select-none bg-white border-b border-gray-100 z-20">
            <span id="statusBarTime" class="font-bold">09:41</span>
            <div class="flex items-center gap-2 text-xs">
                <i class="fa-solid fa-wifi text-[10px] text-gray-700"></i>
                <span class="text-[9px] font-bold text-gray-700">5G</span>
                <i class="fa-solid fa-battery-full text-emerald-500 text-xs"></i>
            </div>
        </div>

        <!-- Main Screen Body (Switches between Login & App Tabs) -->
        <div class="app-screen relative" id="appScreenContainer">

            <!-- 1. SALES LOGIN SCREEN (Shown if not logged in) -->
            <div id="screenLogin" class="p-6 my-auto space-y-6 text-center">
                <div class="p-3 bg-white rounded-2xl border border-gray-200 shadow-sm mx-auto inline-block">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Life Media Logo" class="h-10 w-auto object-contain">
                </div>

                <div>
                    <h2 class="text-xl font-extrabold text-[#2C2C2C]">Life Connect Mobile</h2>
                    <p class="text-xs text-gray-500 mt-1">Field Sales &amp; Survey Application</p>
                </div>

                <div id="loginAlert" class="hidden p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs text-left font-medium"></div>

                <form id="mobileLoginForm" class="space-y-4 text-left text-xs">
                    <div>
                        <label class="block font-semibold text-[#333333] mb-1">ID Sales (AM)</label>
                        <input type="text" id="loginSalesId" value="AM-101" required placeholder="Contoh: AM-101"
                               class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-gray-200 text-[#333333] font-mono focus:outline-none focus:ring-2 focus:ring-[#EF666B]/30 focus:border-[#EF666B] shadow-sm">
                    </div>

                    <div>
                        <label class="block font-semibold text-[#333333] mb-1">Password</label>
                        <input type="password" id="loginPassword" value="password" required
                               class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-gray-200 text-[#333333] focus:outline-none focus:ring-2 focus:ring-[#EF666B]/30 focus:border-[#EF666B] shadow-sm">
                    </div>

                    <button type="submit" 
                            class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-[#F48C5B] via-[#EF666B] to-[#9B385B] text-white font-bold text-xs shadow-md shadow-[#EF666B]/25 hover:opacity-95 transition-opacity">
                        Masuk Aplikasi Sales
                    </button>
                </form>
            </div>

            <!-- 2. MAIN LOGGED-IN APP SHELL -->
            <div id="screenApp" class="hidden flex-1 flex flex-col min-h-0">
                
                <!-- App Header Bar -->
                <div class="px-4 py-3 bg-gradient-to-r from-[#F48C5B] via-[#EF666B] to-[#9B385B] text-white flex items-center justify-between sticky top-0 z-30 shadow-md">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-white/20 backdrop-blur-sm border border-white/30 text-white flex items-center justify-center font-bold text-xs">
                            <i class="fa-solid fa-bolt"></i>
                        </div>
                        <div>
                            <div class="font-bold text-xs text-white" id="appHeaderSalesName">Budi Pratama</div>
                            <div class="text-[10px] text-white/90 font-mono font-semibold" id="appHeaderSalesId">AM-101</div>
                        </div>
                    </div>

                    <button type="button" onclick="logoutMobile()" class="text-white/80 hover:text-white text-xs p-1 transition-colors" title="Logout">
                        <i class="fa-solid fa-power-off"></i>
                    </button>
                </div>

                <!-- In-App Push Notification Dropdown Banner -->
                <div id="pushBanner" class="hidden mx-3 mt-2.5 bg-white/95 backdrop-blur-md rounded-2xl p-3 border-2 border-emerald-500 shadow-xl transition-all duration-300 cursor-pointer animate-pulse z-40" onclick="handlePushBannerClick()">
                    <div class="flex items-start gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center text-sm shadow-sm shrink-0">
                            <i class="fa-brands fa-whatsapp text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0 text-left">
                            <div class="flex items-center justify-between">
                                <span class="font-extrabold text-[11px] text-[#2C2C2C] flex items-center gap-1">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                                    <span id="pushBannerTitle">Survey Terverifikasi OPJ!</span>
                                </span>
                                <span class="text-[9px] text-gray-400 font-mono">Baru saja</span>
                            </div>
                            <p class="text-[10px] text-gray-600 mt-0.5 line-clamp-2" id="pushBannerMsg">Pengajuan diverifikasi OPJ. Klik untuk bagikan link WhatsApp!</p>
                            <div class="mt-1.5 inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[10px] shadow-xs">
                                <i class="fa-brands fa-whatsapp"></i>
                                <span>Bagikan Link WA Sekarang</span>
                            </div>
                        </div>
                        <button type="button" onclick="event.stopPropagation(); closePushBanner();" class="text-gray-400 hover:text-gray-600 text-xs p-1">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                </div>

                <!-- Active Tab Contents Container -->
                <div class="flex-1 overflow-y-auto p-4 space-y-4">
                    
                    <!-- TAB 1: FORM SURVEY LAPANGAN -->
                    <div id="tabSurvey" class="space-y-4">
                        
                        <div class="p-3.5 rounded-2xl bg-gradient-to-r from-[#FEF4F0] to-white border border-[#F48C5B]/30 text-xs shadow-sm">
                            <span class="font-bold text-[#2C2C2C] block">Form Survey Lokasi Calon Pelanggan</span>
                            <span class="text-[11px] text-gray-500">Pastikan titik GPS dan alamat calon pelanggan sesuai</span>
                        </div>

                        <form id="mobileSurveyForm" class="space-y-3.5 text-xs">
                            
                            <!-- Auto-filled Sales Info -->
                            <div class="p-2.5 rounded-xl bg-white border border-[#F0E8E4] text-[11px] space-y-1 shadow-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Petugas Survey:</span>
                                    <span class="font-bold text-[#333333]" id="surveySalesNameDisplay">Budi Pratama</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">ID Account Manager:</span>
                                    <span class="font-mono font-bold text-[#EF666B]" id="surveySalesIdDisplay">AM-101</span>
                                </div>
                            </div>

                            <!-- Customer Name -->
                            <div>
                                <label class="block font-semibold text-[#333333] mb-1">Nama Calon Pelanggan <span class="text-rose-500">*</span></label>
                                <input type="text" id="surveyCustName" required placeholder="Nama lengkap pelanggan..."
                                       class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-[#333333] focus:outline-none focus:ring-2 focus:ring-[#EF666B]/30 focus:border-[#EF666B] shadow-sm">
                            </div>

                            <!-- WhatsApp & Email -->
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block font-semibold text-[#333333] mb-1">Nomor WhatsApp <span class="text-rose-500">*</span></label>
                                    <input type="tel" id="surveyCustPhone" required placeholder="08123456789"
                                           class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-[#333333] focus:outline-none focus:ring-2 focus:ring-[#EF666B]/30 focus:border-[#EF666B] shadow-sm">
                                </div>
                                <div>
                                    <label class="block font-semibold text-[#333333] mb-1">Email Pelanggan</label>
                                    <input type="email" id="surveyCustEmail" placeholder="opsional@gmail.com"
                                           class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-[#333333] focus:outline-none focus:ring-2 focus:ring-[#EF666B]/30 focus:border-[#EF666B] shadow-sm">
                                </div>
                            </div>

                            <!-- GPS Coordinates with Auto-Locate Button -->
                            <div class="space-y-1.5">
                                <div class="flex items-center justify-between">
                                    <label class="font-semibold text-[#333333]">Koordinat GPS Lokasi <span class="text-rose-500">*</span></label>
                                    <button type="button" onclick="autoDetectGps()" class="text-[#EF666B] hover:text-[#9B385B] text-[11px] font-bold flex items-center gap-1 transition-colors">
                                        <i class="fa-solid fa-location-crosshairs"></i> Ambil GPS
                                    </button>
                                </div>
                                <div class="grid grid-cols-2 gap-2 font-mono">
                                    <input type="number" step="any" id="surveyLat" value="-7.761352" required placeholder="Latitude"
                                           class="w-full px-3 py-1.5 rounded-xl bg-white border border-gray-200 text-[#EF666B] font-semibold text-[11px] focus:outline-none focus:ring-2 focus:ring-[#EF666B]/30 focus:border-[#EF666B] shadow-sm">
                                    <input type="number" step="any" id="surveyLng" value="110.385412" required placeholder="Longitude"
                                           class="w-full px-3 py-1.5 rounded-xl bg-white border border-gray-200 text-[#EF666B] font-semibold text-[11px] focus:outline-none focus:ring-2 focus:ring-[#EF666B]/30 focus:border-[#EF666B] shadow-sm">
                                </div>
                            </div>

                            <!-- Cascading Region Dropdowns -->
                            <div class="space-y-2">
                                <label class="font-semibold text-[#333333] block">Wilayah Administratif Coverage <span class="text-rose-500">*</span></label>
                                
                                <div class="grid grid-cols-2 gap-2">
                                    <!-- Provinsi -->
                                    <div>
                                        <select id="surveyProvinsi" required onchange="onProvinceChange(this.value)"
                                                class="w-full px-2.5 py-1.5 rounded-xl bg-white border border-gray-200 text-[#333333] text-[11px] focus:outline-none focus:ring-2 focus:ring-[#EF666B]/30 shadow-sm">
                                            <option value="">Pilih Provinsi</option>
                                            @foreach($provinces as $prov)
                                                <option value="{{ $prov->name }}" data-id="{{ $prov->id }}">{{ $prov->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Kabupaten/Kota -->
                                    <div>
                                        <select id="surveyKabupaten" required onchange="onRegencyChange(this.value)"
                                                class="w-full px-2.5 py-1.5 rounded-xl bg-white border border-gray-200 text-[#333333] text-[11px] focus:outline-none focus:ring-2 focus:ring-[#EF666B]/30 shadow-sm">
                                            <option value="">Pilih Kab/Kota</option>
                                        </select>
                                    </div>

                                    <!-- Kecamatan -->
                                    <div>
                                        <select id="surveyKecamatan" required onchange="onDistrictChange(this.value)"
                                                class="w-full px-2.5 py-1.5 rounded-xl bg-white border border-gray-200 text-[#333333] text-[11px] focus:outline-none focus:ring-2 focus:ring-[#EF666B]/30 shadow-sm">
                                            <option value="">Pilih Kecamatan</option>
                                        </select>
                                    </div>

                                    <!-- Kelurahan -->
                                    <div>
                                        <select id="surveyKelurahan" required
                                                class="w-full px-2.5 py-1.5 rounded-xl bg-white border border-gray-200 text-[#333333] text-[11px] focus:outline-none focus:ring-2 focus:ring-[#EF666B]/30 shadow-sm">
                                            <option value="">Pilih Kelurahan</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Detail Address -->
                            <div>
                                <label class="block font-semibold text-[#333333] mb-1">Alamat Detail (RT/RW / Patokan)</label>
                                <textarea id="surveyAddressDetail" rows="2" placeholder="Jl. Mawar No. 12, RT 02 / RW 05 (Pagar hitam)..."
                                          class="w-full px-3 py-1.5 rounded-xl bg-white border border-gray-200 text-[#333333] text-xs focus:outline-none focus:ring-2 focus:ring-[#EF666B]/30 focus:border-[#EF666B] shadow-sm"></textarea>
                            </div>

                            <button type="submit" id="btnSubmitSurvey"
                                    class="w-full py-3 rounded-xl bg-gradient-to-r from-[#F48C5B] via-[#EF666B] to-[#9B385B] text-white font-bold text-xs shadow-md shadow-[#EF666B]/25 hover:opacity-95 transition-opacity flex items-center justify-center gap-2">
                                <i class="fa-solid fa-paper-plane"></i>
                                <span>Kirim Data Survey ke Database</span>
                            </button>

                        </form>
                    </div>

                    <!-- TAB 2: RIWAYAT SURVEY SAYA -->
                    <div id="tabHistory" class="hidden space-y-3">
                        <div class="flex items-center justify-between text-xs pb-1 border-b border-gray-200">
                            <span class="font-bold text-[#2C2C2C]">Riwayat Survey Saya</span>
                            <button type="button" onclick="loadMySurveys()" class="text-[#EF666B] hover:text-[#9B385B] text-[11px] font-bold">
                                <i class="fa-solid fa-rotate-right mr-1"></i> Refresh
                            </button>
                        </div>

                        <div id="surveyListContainer" class="space-y-2.5 text-xs">
                            <!-- Populated dynamically via JS -->
                            <div class="text-center py-8 text-gray-500 text-xs">Memuat data survey...</div>
                        </div>
                    </div>

                    <!-- TAB 3: NOTIFIKASI MASUK -->
                    <div id="tabNotif" class="hidden space-y-3">
                        <div class="flex items-center justify-between text-xs pb-1 border-b border-gray-200">
                            <span class="font-bold text-[#2C2C2C]">Notifikasi &amp; Aksi Tindak Lanjut</span>
                            <button type="button" onclick="loadMyNotifications()" class="text-[#EF666B] hover:text-[#9B385B] text-[11px] font-bold">
                                <i class="fa-solid fa-rotate-right mr-1"></i> Refresh
                            </button>
                        </div>

                        <div id="notifListContainer" class="space-y-2.5 text-xs">
                            <!-- Populated dynamically via JS -->
                            <div class="text-center py-8 text-gray-500 text-xs">Memuat notifikasi...</div>
                        </div>
                    </div>

                    <!-- TAB 4: PROFIL SALES -->
                    <div id="tabProfile" class="hidden space-y-4 text-xs">
                        <div class="p-4 rounded-2xl bg-white border border-[#F0E8E4] shadow-sm text-center space-y-2">
                            <div class="w-14 h-14 rounded-2xl bg-[#FEF4F0] border border-[#F48C5B]/30 text-[#EF666B] flex items-center justify-center text-xl font-bold mx-auto">
                                <i class="fa-solid fa-user-tie"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-[#2C2C2C] text-sm" id="profileName">-</h3>
                                <p class="text-[11px] text-[#F48C5B] font-mono font-bold" id="profileSalesId">-</p>
                                <p class="text-[10px] text-gray-500" id="profileEmail">-</p>
                            </div>
                        </div>

                        <div class="p-3.5 rounded-2xl bg-white border border-gray-200 shadow-sm space-y-2 text-[11px]">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Status Keaktifan:</span>
                                <span class="text-emerald-600 font-bold">Aktif Lapangan</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Versi Aplikasi:</span>
                                <span class="text-gray-700 font-mono">v2.4.0 (LifeConnect-AM)</span>
                            </div>
                        </div>

                        <button type="button" onclick="logoutMobile()" 
                                class="w-full py-2.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 font-bold text-xs hover:bg-rose-100 transition-colors">
                            <i class="fa-solid fa-right-from-bracket mr-1"></i> Keluar Akun Sales
                        </button>
                    </div>

                </div>

                <!-- Bottom Android App Navigation Tabs -->
                <div class="px-2 py-2 bg-white border-t border-gray-200 flex items-center justify-around text-[10px] text-gray-500 select-none z-30 shadow-sm">
                    
                    <button type="button" onclick="switchTab('survey')" id="btnTabSurvey" class="flex flex-col items-center gap-1 text-[#EF666B] font-bold p-1">
                        <i class="fa-solid fa-pen-to-square text-base"></i>
                        <span>Survey</span>
                    </button>

                    <button type="button" onclick="switchTab('history')" id="btnTabHistory" class="flex flex-col items-center gap-1 text-gray-400 hover:text-gray-700 p-1">
                        <i class="fa-solid fa-clock-rotate-left text-base"></i>
                        <span>Riwayat</span>
                    </button>

                    <button type="button" onclick="switchTab('notif')" id="btnTabNotif" class="flex flex-col items-center gap-1 text-gray-400 hover:text-gray-700 p-1 relative">
                        <i class="fa-solid fa-bell text-base"></i>
                        <span>Notifikasi</span>
                        <span id="notifBadgeDot" class="hidden absolute top-0 right-2 w-2.5 h-2.5 rounded-full bg-rose-500 animate-ping"></span>
                        <span id="notifBadgeCount" class="hidden absolute -top-1 right-0.5 min-w-[16px] h-4 px-1 bg-rose-600 text-white text-[9px] font-bold rounded-full border border-white flex items-center justify-center">0</span>
                    </button>

                    <button type="button" onclick="switchTab('profile')" id="btnTabProfile" class="flex flex-col items-center gap-1 text-gray-400 hover:text-gray-700 p-1">
                        <i class="fa-solid fa-user text-base"></i>
                        <span>Profil</span>
                    </button>

                </div>

            </div>

        </div>

    </div>

    <!-- WhatsApp Share Confirmation Modal inside Simulator -->
    <div id="waModal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm p-4 flex items-center justify-center">
        <div class="bg-white border border-gray-200 rounded-3xl p-5 max-w-sm w-full space-y-4 text-xs shadow-2xl">
            <div class="flex items-center gap-2 font-bold text-emerald-600 text-sm border-b border-gray-100 pb-3">
                <i class="fa-brands fa-whatsapp text-lg"></i>
                <span>Bagikan Link WA ke Pelanggan</span>
            </div>

            <p class="text-gray-600 leading-relaxed">
                Pesan WhatsApp resmi akan diteruskan ke nomor calon pelanggan:
            </p>

            <div class="p-3 rounded-xl bg-[#F8F9FA] border border-gray-200 text-[11px] font-mono text-gray-700 whitespace-pre-wrap max-h-40 overflow-y-auto" id="waMessagePreview"></div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="closeWaModal()" class="px-3 py-1.5 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 text-xs font-semibold transition-colors">
                    Tutup
                </button>
                <a id="waDirectLink" href="#" target="_blank" class="px-4 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md shadow-emerald-600/20 inline-flex items-center gap-1.5 transition-colors">
                    <i class="fa-brands fa-whatsapp"></i>
                    <span>Kirim via WhatsApp Web</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Simulator Logic Scripts -->
    <script>
        // State
        let currentSales = null;
        let currentPushData = null;
        let notifiedIds = new Set();

        // Update Phone Clock
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const mins = String(now.getMinutes()).padStart(2, '0');
            document.getElementById('statusBarTime').innerText = `${hours}:${mins}`;
        }
        setInterval(updateClock, 1000);
        updateClock();

        // 1. Login Handler
        document.getElementById('mobileLoginForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const salesId = document.getElementById('loginSalesId').value;
            const password = document.getElementById('loginPassword').value;

            try {
                const res = await fetch('/api/sales/login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ sales_id: salesId, password: password })
                });
                const data = await res.json();
                if (data.success) {
                    setLoggedInUser(data.data.user);
                } else {
                    showLoginError(data.message || 'Login gagal.');
                }
            } catch (err) {
                showLoginError('Terjadi kesalahan koneksi server.');
            }
        });

        function showLoginError(msg) {
            const box = document.getElementById('loginAlert');
            box.innerText = msg;
            box.classList.remove('hidden');
        }

        function quickLoginSales(salesId, pass, id, name, email, phone) {
            document.getElementById('loginSalesId').value = salesId;
            document.getElementById('loginPassword').value = pass;
            setLoggedInUser({ id: id, name: name, sales_id: salesId, email: email, phone: phone });
        }

        function setLoggedInUser(user) {
            currentSales = user;
            document.getElementById('screenLogin').classList.add('hidden');
            document.getElementById('screenApp').classList.remove('hidden');

            document.getElementById('appHeaderSalesName').innerText = user.name;
            document.getElementById('appHeaderSalesId').innerText = user.sales_id;
            document.getElementById('surveySalesNameDisplay').innerText = user.name;
            document.getElementById('surveySalesIdDisplay').innerText = user.sales_id;

            document.getElementById('profileName').innerText = user.name;
            document.getElementById('profileSalesId').innerText = user.sales_id;
            document.getElementById('profileEmail').innerText = user.email;

            switchTab('survey');
            loadMySurveys();
            loadMyNotifications();
        }

        function logoutMobile() {
            currentSales = null;
            document.getElementById('screenApp').classList.add('hidden');
            document.getElementById('screenLogin').classList.remove('hidden');
        }

        // 2. Tab Navigation
        function switchTab(tab) {
            const tabs = ['survey', 'history', 'notif', 'profile'];
            tabs.forEach(t => {
                document.getElementById('tab' + t.charAt(0).toUpperCase() + t.slice(1)).classList.add('hidden');
                const btn = document.getElementById('btnTab' + t.charAt(0).toUpperCase() + t.slice(1));
                btn.classList.remove('text-[#EF666B]', 'font-bold');
                btn.classList.add('text-gray-400');
            });

            const activeTab = document.getElementById('tab' + tab.charAt(0).toUpperCase() + tab.slice(1));
            const activeBtn = document.getElementById('btnTab' + tab.charAt(0).toUpperCase() + tab.slice(1));
            activeTab.classList.remove('hidden');
            activeBtn.classList.add('text-[#EF666B]', 'font-bold');
            activeBtn.classList.remove('text-gray-400');

            if (tab === 'history') loadMySurveys();
            if (tab === 'notif') loadMyNotifications();
        }

        // 3. Cascading Dropdowns
        async function onProvinceChange(provName) {
            const sel = document.getElementById('surveyProvinsi');
            const provId = sel.options[sel.selectedIndex].getAttribute('data-id');
            const kabSelect = document.getElementById('surveyKabupaten');
            kabSelect.innerHTML = '<option value="">Pilih Kab/Kota</option>';
            document.getElementById('surveyKecamatan').innerHTML = '<option value="">Pilih Kecamatan</option>';
            document.getElementById('surveyKelurahan').innerHTML = '<option value="">Pilih Kelurahan</option>';

            if (!provId) return;

            const res = await fetch(`/api/regions?type=kabupaten&parent_id=${provId}`);
            const json = await res.json();
            json.data.forEach(item => {
                kabSelect.innerHTML += `<option value="${item.name}" data-id="${item.id}">${item.name}</option>`;
            });
        }

        async function onRegencyChange(regName) {
            const sel = document.getElementById('surveyKabupaten');
            const regId = sel.options[sel.selectedIndex].getAttribute('data-id');
            const kecSelect = document.getElementById('surveyKecamatan');
            kecSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
            document.getElementById('surveyKelurahan').innerHTML = '<option value="">Pilih Kelurahan</option>';

            if (!regId) return;

            const res = await fetch(`/api/regions?type=kecamatan&parent_id=${regId}`);
            const json = await res.json();
            json.data.forEach(item => {
                kecSelect.innerHTML += `<option value="${item.name}" data-id="${item.id}">${item.name}</option>`;
            });
        }

        async function onDistrictChange(distName) {
            const sel = document.getElementById('surveyKecamatan');
            const distId = sel.options[sel.selectedIndex].getAttribute('data-id');
            const kelSelect = document.getElementById('surveyKelurahan');
            kelSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';

            if (!distId) return;

            const res = await fetch(`/api/regions?type=kelurahan&parent_id=${distId}`);
            const json = await res.json();
            json.data.forEach(item => {
                kelSelect.innerHTML += `<option value="${item.name}" data-id="${item.id}">${item.name}</option>`;
            });
        }

        // 4. GPS Auto-detect Simulator
        function autoDetectGps() {
            // Preset coordinates around Sleman / Jogja with slight jitter
            const lat = -7.761352 + (Math.random() - 0.5) * 0.02;
            const lng = 110.385412 + (Math.random() - 0.5) * 0.02;
            document.getElementById('surveyLat').value = lat.toFixed(6);
            document.getElementById('surveyLng').value = lng.toFixed(6);
            alert('Titik koordinat GPS berhasil diperoleh dari sensor HP!');
        }

        // 5. Submit Survey Form
        document.getElementById('mobileSurveyForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            if (!currentSales) return;

            const submitBtn = document.getElementById('btnSubmitSurvey');
            submitBtn.disabled = true;
            submitBtn.innerText = 'Mengirim data...';

            const payload = {
                sales_user_id: currentSales.id,
                customer_name: document.getElementById('surveyCustName').value,
                phone_wa: document.getElementById('surveyCustPhone').value,
                email: document.getElementById('surveyCustEmail').value,
                latitude: document.getElementById('surveyLat').value,
                longitude: document.getElementById('surveyLng').value,
                province: document.getElementById('surveyProvinsi').value,
                regency: document.getElementById('surveyKabupaten').value,
                district: document.getElementById('surveyKecamatan').value,
                village: document.getElementById('surveyKelurahan').value,
                address_detail: document.getElementById('surveyAddressDetail').value,
            };

            try {
                const res = await fetch('/api/sales/survey', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();
                if (data.success) {
                    alert('BERHASIL!\n\nData survey ' + payload.customer_name + ' berhasil disubmit ke database Life Connect dengan status: SUBMITTED.\n\nSilakan buka Dashboard OPJ di web browser untuk melakukan verifikasi lokasi.');
                    document.getElementById('mobileSurveyForm').reset();
                    switchTab('history');
                } else {
                    alert('Gagal: ' + (data.message || 'Terjadi kesalahan'));
                }
            } catch (err) {
                alert('Terjadi kesalahan saat submit survey.');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fa-solid fa-paper-plane mr-1"></i> Kirim Data Survey ke Database';
            }
        });

        // 6. Load Surveys History
        async function loadMySurveys(isSilent = false) {
            if (!currentSales) return;
            const container = document.getElementById('surveyListContainer');
            if (!isSilent && container.innerHTML.trim() === '') {
                container.innerHTML = '<div class="text-center py-6 text-gray-400 text-xs">Memuat data survey...</div>';
            }

            try {
                const res = await fetch(`/api/sales/${currentSales.id}/surveys`);
                const json = await res.json();
                if (json.data && json.data.length > 0) {
                    let html = '';
                    json.data.forEach(item => {
                        let badgeColor = 'bg-gray-100 text-gray-700 border-gray-200';
                        if (item.status === 'submitted') badgeColor = 'bg-amber-50 text-amber-700 border-amber-200';
                        if (item.status === 'verified') badgeColor = 'bg-blue-50 text-blue-700 border-blue-200';
                        if (item.status === 'filled') badgeColor = 'bg-purple-50 text-purple-700 border-purple-200';
                        if (item.status === 'approved') badgeColor = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                        if (item.status === 'revision') badgeColor = 'bg-rose-50 text-rose-700 border-rose-200';

                        let actionHtml = '';
                        if (item.status === 'verified') {
                            const custName = item.customer_name;
                            const phone = item.phone_wa || '';
                            const tokenUrl = window.location.origin + '/pendaftaran/' + (item.token || '');
                            const waText = `Halo Bapak/Ibu ${custName},\n\nTerima kasih telah mengajukan pendaftaran layanan LifeMedia. Lokasi rumah Anda telah diverifikasi oleh tim teknis kami (OPJ).\n\nSilakan lengkapi data registrasi dan tanda tangan formulir berlangganan melalui tautan resmi LifeMedia berikut:\n${tokenUrl}\n\nSalam hangat,\nTim LifeMedia`;

                            actionHtml = `
                                <div class="pt-2 border-t border-gray-100 mt-2">
                                    <button type="button" onclick="openWaModal('${custName}', '${phone}', \`${waText}\`, '${tokenUrl}')"
                                            class="w-full py-2 px-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] shadow-sm flex items-center justify-center gap-1.5 transition-colors">
                                        <i class="fa-brands fa-whatsapp text-sm"></i>
                                        <span>Bagikan Link WA ke Pelanggan</span>
                                    </button>
                                </div>
                            `;
                        } else if (item.status === 'filled') {
                            actionHtml = `
                                <div class="pt-1.5 border-t border-gray-100 mt-1.5 flex items-center gap-1 text-[10px] text-indigo-700 font-semibold">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <span>Pelanggan sudah mengisi formulir online</span>
                                </div>
                            `;
                        } else if (item.status === 'approved') {
                            actionHtml = `
                                <div class="pt-1.5 border-t border-gray-100 mt-1.5 flex items-center gap-1 text-[10px] text-emerald-700 font-semibold">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <span>Pengajuan disetujui C-Care (Siap Pasang)</span>
                                </div>
                            `;
                        } else if (item.status === 'revision') {
                            actionHtml = `
                                <div class="pt-1.5 border-t border-gray-100 mt-1.5 flex items-center gap-1 text-[10px] text-rose-700 font-semibold">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                    <span>Perlu perbaikan data / revisi</span>
                                </div>
                            `;
                        }

                        html += `
                            <div class="p-3.5 rounded-2xl bg-white border ${item.status === 'verified' ? 'border-blue-300 ring-1 ring-blue-100' : 'border-gray-200'} shadow-sm space-y-1.5">
                                <div class="flex items-start justify-between gap-2">
                                    <div>
                                        <div class="font-bold text-[#2C2C2C]">${item.customer_name}</div>
                                        <div class="text-[10px] text-[#EF666B] font-mono font-semibold">${item.registration_code}</div>
                                    </div>
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold border ${badgeColor}">
                                        ${item.status.toUpperCase()}
                                    </span>
                                </div>
                                <div class="text-[10px] text-gray-500 flex items-center justify-between pt-1 border-t border-gray-100">
                                    <span>${item.village}, ${item.district}</span>
                                    <span>${item.phone_wa}</span>
                                </div>
                                ${actionHtml}
                            </div>
                        `;
                    });
                    container.innerHTML = html;
                } else {
                    container.innerHTML = '<div class="text-center py-6 text-gray-400 text-xs">Belum ada survey yang diajukan.</div>';
                }
            } catch (err) {
                if (!isSilent) container.innerHTML = '<div class="text-center py-6 text-rose-600 text-xs">Gagal memuat survey.</div>';
            }
        }

        // 7. Load Notifications
        async function loadMyNotifications(isSilent = false) {
            if (!currentSales) return;
            const container = document.getElementById('notifListContainer');
            if (!isSilent && container.innerHTML.trim() === '') {
                container.innerHTML = '<div class="text-center py-6 text-gray-400 text-xs">Memuat notifikasi...</div>';
            }

            try {
                const res = await fetch(`/api/sales/${currentSales.id}/notifications`);
                const json = await res.json();
                if (json.data && json.data.length > 0) {
                    let html = '';
                    let unreadCount = 0;

                    json.data.forEach(item => {
                        if (!item.is_read) unreadCount++;

                        let actionHtml = '';
                        if (item.action_type === 'share_whatsapp' || item.type === 'survey_verified') {
                            const reg = item.registration;
                            const custName = reg ? reg.customer_name : 'Pelanggan';
                            const phone = reg ? reg.phone_wa : '';
                            const tokenUrl = window.location.origin + '/pendaftaran/' + (reg ? reg.token : '');
                            const waText = `Halo Bapak/Ibu ${custName},\n\nTerima kasih telah mengajukan pendaftaran layanan LifeMedia. Lokasi rumah Anda telah diverifikasi oleh tim teknis kami (OPJ).\n\nSilakan lengkapi data registrasi dan tanda tangan formulir berlangganan melalui tautan resmi LifeMedia berikut:\n${tokenUrl}\n\nSalam hangat,\nTim LifeMedia`;

                            // Trigger push banner if unread and not yet toasted
                            if (!item.is_read && !notifiedIds.has(item.id)) {
                                notifiedIds.add(item.id);
                                showPushBanner(custName, phone, waText, tokenUrl, item.id);
                            }

                            actionHtml = `
                                <div class="pt-2">
                                    <button type="button" onclick="openWaModal('${custName}', '${phone}', \`${waText}\`, '${tokenUrl}', ${item.id})"
                                            class="w-full py-2 px-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] shadow-sm flex items-center justify-center gap-1.5 transition-colors">
                                        <i class="fa-brands fa-whatsapp text-sm"></i>
                                        <span>Bagikan Link WA Ke Pelanggan</span>
                                    </button>
                                </div>
                            `;
                        } else if (item.type === 'registration_revision') {
                            actionHtml = `
                                <div class="pt-2">
                                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                        Perlu Follow Up Revisi
                                    </span>
                                </div>
                            `;
                        }

                        html += `
                            <div class="p-3 rounded-2xl bg-white border ${item.is_read ? 'border-gray-200' : 'border-amber-300 bg-amber-50/50'} shadow-sm space-y-1.5">
                                <div class="flex items-center justify-between text-[11px] font-bold ${item.is_read ? 'text-[#333333]' : 'text-amber-800'}">
                                    <span class="flex items-center gap-1.5">
                                        ${!item.is_read ? '<span class="w-2 h-2 rounded-full bg-rose-500"></span>' : ''}
                                        ${item.title}
                                    </span>
                                    <span class="text-[9px] font-normal text-gray-400">${new Date(item.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</span>
                                </div>
                                <p class="text-[11px] text-gray-600 leading-relaxed">${item.message}</p>
                                ${actionHtml}
                            </div>
                        `;
                    });

                    container.innerHTML = html;

                    const dot = document.getElementById('notifBadgeDot');
                    const countBadge = document.getElementById('notifBadgeCount');
                    if (unreadCount > 0) {
                        dot.classList.remove('hidden');
                        countBadge.classList.remove('hidden');
                        countBadge.innerText = unreadCount;
                    } else {
                        dot.classList.add('hidden');
                        countBadge.classList.add('hidden');
                    }
                } else {
                    container.innerHTML = '<div class="text-center py-6 text-gray-400 text-xs">Tidak ada notifikasi baru.</div>';
                    document.getElementById('notifBadgeDot').classList.add('hidden');
                    document.getElementById('notifBadgeCount').classList.add('hidden');
                }
            } catch (err) {
                if (!isSilent) container.innerHTML = '<div class="text-center py-6 text-rose-600 text-xs">Gagal memuat notifikasi.</div>';
            }
        }

        function showPushBanner(name, phone, text, link, notifId) {
            currentPushData = { name, phone, text, link, notifId };
            document.getElementById('pushBannerTitle').innerText = 'Survey ' + name + ' Terverifikasi!';
            document.getElementById('pushBannerMsg').innerText = 'OPJ telah memverifikasi lokasi. Klik untuk bagikan link WhatsApp!';
            const banner = document.getElementById('pushBanner');
            banner.classList.remove('hidden');
        }

        function handlePushBannerClick() {
            if (currentPushData) {
                openWaModal(currentPushData.name, currentPushData.phone, currentPushData.text, currentPushData.link, currentPushData.notifId);
                closePushBanner();
            }
        }

        function closePushBanner() {
            document.getElementById('pushBanner').classList.add('hidden');
        }

        function openWaModal(name, phone, text, link, notifId = null) {
            if (notifId) {
                fetch(`/api/sales/notifications/${notifId}/read`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' }
                }).then(() => loadMyNotifications(true)).catch(() => {});
            }
            document.getElementById('waMessagePreview').innerText = text;
            const cleanPhone = phone.replace(/[^0-9]/g, '');
            const finalPhone = cleanPhone.startsWith('0') ? '62' + cleanPhone.substring(1) : cleanPhone;
            const waUrl = `https://api.whatsapp.com/send?phone=${finalPhone}&text=${encodeURIComponent(text)}`;
            document.getElementById('waDirectLink').href = waUrl;
            document.getElementById('waModal').classList.remove('hidden');
        }

        function closeWaModal() {
            document.getElementById('waModal').classList.add('hidden');
        }

        // Realtime Polling every 3 seconds
        setInterval(() => {
            if (currentSales) {
                loadMySurveys(true);
                loadMyNotifications(true);
            }
        }, 3000);

        // Auto-login default user on page load
        window.addEventListener('DOMContentLoaded', () => {
            quickLoginSales('AM-101', 'password', 5, 'Budi Pratama (AM)', 'sales01@lifemedia.id', '081234567801');
        });
    </script>
</body>
</html>
