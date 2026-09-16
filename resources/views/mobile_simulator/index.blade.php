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
                        <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center text-sm shadow-sm shrink-0" id="pushBannerIcon">
                            <i class="fa-brands fa-whatsapp text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0 text-left">
                            <div class="flex items-center justify-between">
                                <span class="font-extrabold text-[11px] text-[#2C2C2C] flex items-center gap-1">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping" id="pushBannerDot"></span>
                                    <span id="pushBannerTitle">Survey Terverifikasi OPJ!</span>
                                </span>
                                <span class="text-[9px] text-gray-400 font-mono">Baru saja</span>
                            </div>
                            <p class="text-[10px] text-gray-600 mt-0.5 line-clamp-2" id="pushBannerMsg">Pengajuan diverifikasi OPJ. Klik untuk bagikan link WhatsApp!</p>
                            <div class="mt-1.5 inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[10px] shadow-xs" id="pushBannerBtn">
                                <i class="fa-brands fa-whatsapp" id="pushBannerBtnIcon"></i>
                                <span id="pushBannerBtnText">Bagikan Link WA Sekarang</span>
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

                            <!-- GPS Coordinates with Auto-Locate and High Accuracy -->
                            <div class="space-y-1.5">
                                <div class="flex items-center justify-between">
                                    <label class="font-semibold text-[#333333] flex items-center gap-1.5">
                                        <span>Koordinat GPS Lokasi</span>
                                        <span class="text-rose-500">*</span>
                                    </label>
                                    <button type="button" id="btnDetectGps" onclick="autoDetectGps(true)" class="text-[#EF666B] hover:text-[#9B385B] text-[11px] font-bold flex items-center gap-1 transition-colors px-2 py-0.5 rounded-lg bg-[#FEF4F0] hover:bg-[#FEEBE3] border border-[#F48C5B]/20">
                                        <i class="fa-solid fa-location-crosshairs" id="iconDetectGps"></i>
                                        <span id="textDetectGps">Refresh GPS</span>
                                    </button>
                                </div>
                                <div class="grid grid-cols-2 gap-2 font-mono">
                                    <input type="number" step="any" id="surveyLat" value="-7.761352" required placeholder="Latitude"
                                           class="w-full px-3 py-1.5 rounded-xl bg-white border border-gray-200 text-[#EF666B] font-semibold text-[11px] focus:outline-none focus:ring-2 focus:ring-[#EF666B]/30 focus:border-[#EF666B] shadow-sm">
                                    <input type="number" step="any" id="surveyLng" value="110.385412" required placeholder="Longitude"
                                           class="w-full px-3 py-1.5 rounded-xl bg-white border border-gray-200 text-[#EF666B] font-semibold text-[11px] focus:outline-none focus:ring-2 focus:ring-[#EF666B]/30 focus:border-[#EF666B] shadow-sm">
                                </div>
                                <div class="flex items-center justify-between text-[10px] text-gray-500 px-0.5">
                                    <span id="gpsStatusBadge" class="inline-flex items-center gap-1 text-[10px] text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200 font-medium">
                                        <i class="fa-solid fa-satellite text-[9px] text-emerald-600"></i>
                                        <span id="gpsStatusText">Mendeteksi GPS otomatis...</span>
                                    </span>
                                    <span class="text-[9px] text-gray-400">Ketuk / geser pin peta</span>
                                </div>

                                <!-- Leaflet Mini Map in Simulator -->
                                <div class="relative w-full h-32 rounded-xl overflow-hidden border border-gray-200 shadow-inner mt-1">
                                    <div id="surveyMiniMap" class="w-full h-full z-10"></div>
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
                            <div class="flex items-center gap-1.5">
                                <span class="font-bold text-[#2C2C2C]">Notifikasi Masuk</span>
                                <span id="notifTabUnreadBadge" class="hidden px-1.5 py-0.5 rounded-full bg-rose-500 text-white text-[9px] font-bold shadow-xs">0 Baru</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" onclick="markAllNotificationsRead()" class="text-gray-500 hover:text-[#EF666B] text-[10px] font-semibold transition-colors flex items-center gap-1 bg-gray-100 hover:bg-gray-200 px-2 py-0.5 rounded-lg border border-gray-200 shadow-xs" title="Tandai semua telah dibaca">
                                    <i class="fa-solid fa-check-double text-[9px]"></i>
                                    <span>Baca Semua</span>
                                </button>
                                <button type="button" onclick="loadMyNotifications()" class="text-[#EF666B] hover:text-[#9B385B] text-[11px] font-bold p-0.5" title="Refresh">
                                    <i class="fa-solid fa-rotate-right"></i>
                                </button>
                            </div>
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
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <div class="flex items-center gap-2 font-bold text-emerald-600 text-sm" id="waModalHeader">
                    <i class="fa-brands fa-whatsapp text-lg" id="waModalHeaderIcon"></i>
                    <span id="waModalTitle">Bagikan Link WA ke Pelanggan</span>
                </div>
                <button type="button" onclick="closeWaModal()" class="text-gray-400 hover:text-gray-600 text-xs">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <p class="text-gray-600 leading-relaxed" id="waModalSub">
                Pesan WhatsApp resmi akan diteruskan ke nomor calon pelanggan:
            </p>

            <div class="p-3 rounded-xl bg-[#F8F9FA] border border-gray-200 text-[11px] font-mono text-gray-700 whitespace-pre-wrap max-h-48 overflow-y-auto" id="waMessagePreview"></div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="closeWaModal()" class="px-3 py-1.5 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 text-xs font-semibold transition-colors">
                    Tutup
                </button>
                <a id="waDirectLink" href="#" target="_blank" class="px-4 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md shadow-emerald-600/20 inline-flex items-center gap-1.5 transition-colors">
                    <i class="fa-brands fa-whatsapp"></i>
                    <span id="waDirectBtnText">Kirim via WhatsApp Web</span>
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
            autoDetectGps(false);
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

            if (tab === 'survey') {
                if (miniMap) setTimeout(() => miniMap.invalidateSize(), 150);
            }
            if (tab === 'history') loadMySurveys();
            if (tab === 'notif') loadMyNotifications();
        }

        // 3. Cascading Dropdowns & Auto-Fetch from GPS
        let isAutoFetchingRegions = false;

        async function onProvinceChange(provName) {
            const sel = document.getElementById('surveyProvinsi');
            const provId = sel.options[sel.selectedIndex]?.getAttribute('data-id');
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
            const regId = sel.options[sel.selectedIndex]?.getAttribute('data-id');
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
            const distId = sel.options[sel.selectedIndex]?.getAttribute('data-id');
            const kelSelect = document.getElementById('surveyKelurahan');
            kelSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';

            if (!distId) return;

            const res = await fetch(`/api/regions?type=kelurahan&parent_id=${distId}`);
            const json = await res.json();
            json.data.forEach(item => {
                kelSelect.innerHTML += `<option value="${item.name}" data-id="${item.id}">${item.name}</option>`;
            });
        }

        // Auto-select Region Dropdowns based on GPS Reverse Geocoding
        async function autoFetchRegionsFromGps(lat, lng) {
            if (isAutoFetchingRegions) return;
            isAutoFetchingRegions = true;

            try {
                const res = await fetch(`/api/reverse-geocode?lat=${lat}&lng=${lng}`);
                const json = await res.json();
                if (json.success && json.data) {
                    const d = json.data;

                    // 1. Match Province
                    const provSelect = document.getElementById('surveyProvinsi');
                    if (d.province) {
                        let matchedProv = Array.from(provSelect.options).find(o => o.value === d.province.name || (d.province.id && o.getAttribute('data-id') == d.province.id));
                        if (matchedProv) {
                            provSelect.value = matchedProv.value;
                        }
                    }

                    // 2. Fetch Regencies & Match
                    const provId = provSelect.options[provSelect.selectedIndex]?.getAttribute('data-id');
                    if (provId) {
                        const regRes = await fetch(`/api/regions?type=kabupaten&parent_id=${provId}`);
                        const regJson = await regRes.json();
                        const kabSelect = document.getElementById('surveyKabupaten');
                        kabSelect.innerHTML = '<option value="">Pilih Kab/Kota</option>';
                        regJson.data.forEach(item => {
                            kabSelect.innerHTML += `<option value="${item.name}" data-id="${item.id}">${item.name}</option>`;
                        });

                        if (d.regency) {
                            let matchedReg = Array.from(kabSelect.options).find(o => o.value === d.regency.name || (d.regency.id && o.getAttribute('data-id') == d.regency.id));
                            if (matchedReg) {
                                kabSelect.value = matchedReg.value;
                            }
                        }
                    }

                    // 3. Fetch Districts & Match
                    const kabSelect = document.getElementById('surveyKabupaten');
                    const regId = kabSelect.options[kabSelect.selectedIndex]?.getAttribute('data-id');
                    if (regId) {
                        const kecRes = await fetch(`/api/regions?type=kecamatan&parent_id=${regId}`);
                        const kecJson = await kecRes.json();
                        const kecSelect = document.getElementById('surveyKecamatan');
                        kecSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                        kecJson.data.forEach(item => {
                            kecSelect.innerHTML += `<option value="${item.name}" data-id="${item.id}">${item.name}</option>`;
                        });

                        if (d.district) {
                            let matchedDist = Array.from(kecSelect.options).find(o => o.value === d.district.name || (d.district.id && o.getAttribute('data-id') == d.district.id));
                            if (matchedDist) {
                                kecSelect.value = matchedDist.value;
                            }
                        }
                    }

                    // 4. Fetch Villages & Match
                    const kecSelect = document.getElementById('surveyKecamatan');
                    const distId = kecSelect.options[kecSelect.selectedIndex]?.getAttribute('data-id');
                    if (distId) {
                        const kelRes = await fetch(`/api/regions?type=kelurahan&parent_id=${distId}`);
                        const kelJson = await kelRes.json();
                        const kelSelect = document.getElementById('surveyKelurahan');
                        kelSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
                        kelJson.data.forEach(item => {
                            kelSelect.innerHTML += `<option value="${item.name}" data-id="${item.id}">${item.name}</option>`;
                        });

                        if (d.village) {
                            let matchedVill = Array.from(kelSelect.options).find(o => o.value === d.village.name || (d.village.id && o.getAttribute('data-id') == d.village.id));
                            if (matchedVill) {
                                kelSelect.value = matchedVill.value;
                            }
                        }
                    }

                    // 5. Optionally Suggest Road in Address Detail if empty
                    const addressField = document.getElementById('surveyAddressDetail');
                    if (d.road && (!addressField.value || addressField.value.trim() === '')) {
                        addressField.value = d.road;
                    }
                }
            } catch (e) {
                console.warn('Reverse geocode auto-fill error:', e);
            } finally {
                isAutoFetchingRegions = false;
            }
        }

        // 4. GPS & Mini-Map Integration (Ultra-High Precision & Fast Acquisition Engine)
        let miniMap = null;
        let miniMapMarker = null;
        let isDetectingGps = false;
        let gpsWatchId = null;
        let gpsLockTimeout = null;

        function setGpsStatus(state, message) {
            const badge = document.getElementById('gpsStatusBadge');
            const textEl = document.getElementById('gpsStatusText');
            if (!badge || !textEl) return;

            textEl.innerText = message;
            if (state === 'loading') {
                badge.className = 'inline-flex items-center gap-1 text-[10px] text-amber-700 bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200 font-medium animate-pulse';
                badge.querySelector('i').className = 'fa-solid fa-spinner fa-spin text-[9px] text-amber-600';
            } else if (state === 'success') {
                badge.className = 'inline-flex items-center gap-1 text-[10px] text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200 font-medium';
                badge.querySelector('i').className = 'fa-solid fa-satellite text-[9px] text-emerald-600';
            } else if (state === 'manual') {
                badge.className = 'inline-flex items-center gap-1 text-[10px] text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded-md border border-indigo-200 font-medium';
                badge.querySelector('i').className = 'fa-solid fa-location-dot text-[9px] text-indigo-600';
            } else {
                badge.className = 'inline-flex items-center gap-1 text-[10px] text-gray-600 bg-gray-100 px-2 py-0.5 rounded-md border border-gray-200 font-medium';
                badge.querySelector('i').className = 'fa-solid fa-circle-info text-[9px] text-gray-500';
            }
        }

        function initMiniMap(lat, lng) {
            const latNum = parseFloat(lat);
            const lngNum = parseFloat(lng);
            if (isNaN(latNum) || isNaN(lngNum)) return;

            const mapEl = document.getElementById('surveyMiniMap');
            if (!mapEl) return;

            if (!miniMap) {
                miniMap = L.map('surveyMiniMap', {
                    zoomControl: false,
                    attributionControl: false
                }).setView([latNum, lngNum], 17);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19
                }).addTo(miniMap);

                // Custom marker icon
                const customIcon = L.divIcon({
                    className: 'custom-survey-marker',
                    html: `<div style="background: linear-gradient(135deg, #EF666B, #9B385B); width: 24px; height: 24px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg); border: 2px solid white; box-shadow: 0 4px 10px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; margin: -12px 0 0 -12px;"><i class="fa-solid fa-location-crosshairs" style="transform: rotate(45deg); font-size: 10px; color: white;"></i></div>`,
                    iconSize: [24, 24],
                    iconAnchor: [12, 24]
                });

                miniMapMarker = L.marker([latNum, lngNum], {
                    draggable: true,
                    icon: customIcon
                }).addTo(miniMap);

                // Real-time live coordinate update during drag (every micro-movement)
                miniMapMarker.on('drag', function (e) {
                    const pos = e.target.getLatLng();
                    document.getElementById('surveyLat').value = pos.lat.toFixed(6);
                    document.getElementById('surveyLng').value = pos.lng.toFixed(6);
                    setGpsStatus('manual', `Menggeser pin (${pos.lat.toFixed(5)}, ${pos.lng.toFixed(5)})`);
                });

                // When dragging ends, finalize position & auto-fetch regions
                miniMapMarker.on('dragend', function (e) {
                    const pos = e.target.getLatLng();
                    document.getElementById('surveyLat').value = pos.lat.toFixed(6);
                    document.getElementById('surveyLng').value = pos.lng.toFixed(6);
                    setGpsStatus('manual', 'Pin disesuaikan di peta');
                    autoFetchRegionsFromGps(pos.lat, pos.lng);
                });

                // Click on map to reposition pin instantly
                miniMap.on('click', function (e) {
                    const pos = e.latlng;
                    miniMapMarker.setLatLng(pos);
                    document.getElementById('surveyLat').value = pos.lat.toFixed(6);
                    document.getElementById('surveyLng').value = pos.lng.toFixed(6);
                    setGpsStatus('manual', 'Pin disesuaikan di peta');
                    autoFetchRegionsFromGps(pos.lat, pos.lng);
                });
            } else {
                miniMap.setView([latNum, lngNum], 17);
                miniMapMarker.setLatLng([latNum, lngNum]);
                setTimeout(() => miniMap.invalidateSize(), 100);
            }
        }

        // Ultra-Fast & High Precision Dual-Phase GPS Acquisition Engine
        function autoDetectGps(isManualClick = false) {
            if (isDetectingGps) return;

            const btn = document.getElementById('btnDetectGps');
            const icon = document.getElementById('iconDetectGps');
            const text = document.getElementById('textDetectGps');

            const currentLat = parseFloat(document.getElementById('surveyLat').value) || -7.761352;
            const currentLng = parseFloat(document.getElementById('surveyLng').value) || 110.385412;

            // Ensure mini map is always rendered right away
            initMiniMap(currentLat, currentLng);

            if (!navigator.geolocation) {
                setGpsStatus('error', 'Browser tidak mendukung GPS');
                autoFetchRegionsFromGps(currentLat, currentLng);
                return;
            }

            // Clear any previous active watch / timers
            if (gpsWatchId !== null) {
                navigator.geolocation.clearWatch(gpsWatchId);
                gpsWatchId = null;
            }
            if (gpsLockTimeout !== null) {
                clearTimeout(gpsLockTimeout);
                gpsLockTimeout = null;
            }

            isDetectingGps = true;
            if (btn) {
                btn.disabled = true;
                icon.className = 'fa-solid fa-spinner fa-spin';
                text.innerText = 'Mencari GPS...';
            }
            setGpsStatus('loading', 'Mencari sinyal GPS...');

            let hasAcquiredFastFix = false;

            const resetButton = () => {
                isDetectingGps = false;
                if (btn) {
                    btn.disabled = false;
                    icon.className = 'fa-solid fa-location-crosshairs';
                    text.innerText = 'Refresh GPS';
                }
            };

            // Hard safety timeout: finalize and unblock within 3.5 seconds max
            gpsLockTimeout = setTimeout(() => {
                if (isDetectingGps) {
                    if (!hasAcquiredFastFix) {
                        setGpsStatus('manual', 'GPS Standar / Gunakan Pin Peta');
                        autoFetchRegionsFromGps(currentLat, currentLng);
                    }
                    resetButton();
                }
            }, 3500);

            // Phase 1: Fast Acquisition (Uses cached network/WiFi triangulation for instant response)
            navigator.geolocation.getCurrentPosition(
                function (position) {
                    hasAcquiredFastFix = true;
                    if (gpsLockTimeout) {
                        clearTimeout(gpsLockTimeout);
                        gpsLockTimeout = null;
                    }

                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    const accuracy = Math.round(position.coords.accuracy || 10);

                    document.getElementById('surveyLat').value = lat.toFixed(6);
                    document.getElementById('surveyLng').value = lng.toFixed(6);
                    initMiniMap(lat, lng);
                    autoFetchRegionsFromGps(lat, lng);

                    if (accuracy <= 25) {
                        setGpsStatus('success', `GPS Terkunci Presisi (±${accuracy}m)`);
                    } else {
                        setGpsStatus('success', `GPS Terkunci (±${accuracy}m)`);
                    }

                    resetButton();

                    // Phase 2: If accuracy is not optimal and hardware GPS might be available, refine in background without blocking UI
                    if (accuracy > 20) {
                        navigator.geolocation.getCurrentPosition(
                            function (refinedPos) {
                                const refLat = refinedPos.coords.latitude;
                                const refLng = refinedPos.coords.longitude;
                                const refAccuracy = Math.round(refinedPos.coords.accuracy || 10);

                                if (refAccuracy < accuracy) {
                                    document.getElementById('surveyLat').value = refLat.toFixed(6);
                                    document.getElementById('surveyLng').value = refLng.toFixed(6);
                                    initMiniMap(refLat, refLng);
                                    setGpsStatus('success', `GPS Terkunci Presisi (±${refAccuracy}m)`);
                                    autoFetchRegionsFromGps(refLat, refLng);
                                }
                            },
                            function (refErr) {
                                // Background refinement failed silently, initial fast fix is already active
                            },
                            { enableHighAccuracy: true, timeout: 3000, maximumAge: 0 }
                        );
                    }
                },
                function (error) {
                    // Fallback to high accuracy single-try or default coords
                    navigator.geolocation.getCurrentPosition(
                        function (fallbackPos) {
                            hasAcquiredFastFix = true;
                            if (gpsLockTimeout) {
                                clearTimeout(gpsLockTimeout);
                                gpsLockTimeout = null;
                            }
                            const lat = fallbackPos.coords.latitude;
                            const lng = fallbackPos.coords.longitude;
                            const accuracy = Math.round(fallbackPos.coords.accuracy || 15);

                            document.getElementById('surveyLat').value = lat.toFixed(6);
                            document.getElementById('surveyLng').value = lng.toFixed(6);
                            initMiniMap(lat, lng);
                            autoFetchRegionsFromGps(lat, lng);
                            setGpsStatus('success', `GPS Terkunci (±${accuracy}m)`);
                            resetButton();
                        },
                        function (finalErr) {
                            if (gpsLockTimeout) {
                                clearTimeout(gpsLockTimeout);
                                gpsLockTimeout = null;
                            }
                            console.warn('GPS Error / Permission:', finalErr.message);
                            initMiniMap(currentLat, currentLng);
                            autoFetchRegionsFromGps(currentLat, currentLng);

                            if (finalErr.code === finalErr.PERMISSION_DENIED) {
                                setGpsStatus('error', 'Izin Lokasi Ditolak (Mode Default)');
                            } else if (finalErr.code === finalErr.TIMEOUT) {
                                setGpsStatus('error', 'GPS Timeout (Gunakan Pin Peta)');
                            } else {
                                setGpsStatus('error', 'GPS Standar / Manual');
                            }
                            resetButton();
                        },
                        { enableHighAccuracy: true, timeout: 2500, maximumAge: 0 }
                    );
                },
                {
                    enableHighAccuracy: false,
                    timeout: 2000,
                    maximumAge: 60000 // Accept recent cached position for instant load
                }
            );
        }

        // Listen for manual coordinate input typing to keep mini-map & regions in sync
        let coordDebounceTimer = null;
        document.addEventListener('DOMContentLoaded', () => {
            ['surveyLat', 'surveyLng'].forEach(id => {
                const inputEl = document.getElementById(id);
                if (inputEl) {
                    inputEl.addEventListener('input', () => {
                        const lat = parseFloat(document.getElementById('surveyLat').value);
                        const lng = parseFloat(document.getElementById('surveyLng').value);
                        if (!isNaN(lat) && !isNaN(lng)) {
                            if (miniMap && miniMapMarker) {
                                miniMapMarker.setLatLng([lat, lng]);
                                miniMap.panTo([lat, lng]);
                            }
                            setGpsStatus('manual', 'Koordinat diubah manual');
                            clearTimeout(coordDebounceTimer);
                            coordDebounceTimer = setTimeout(() => {
                                autoFetchRegionsFromGps(lat, lng);
                            }, 800);
                        }
                    });
                }
            });
        });

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
                    autoDetectGps(false);
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
                        const custName = item.customer_name;
                        const phone = item.phone_wa || '';
                        const regCode = item.registration_code || '';
                        const tokenUrl = window.location.origin + '/pendaftaran/' + (item.token || '');

                        if (item.status === 'verified') {
                            const waText = `Halo Bapak/Ibu ${custName},\n\nTerima kasih telah mengajukan pendaftaran layanan LifeMedia. Lokasi rumah Anda telah diverifikasi oleh tim teknis kami (OPJ).\n\nSilakan lengkapi data registrasi dan tanda tangan formulir berlangganan melalui tautan resmi LifeMedia berikut:\n${tokenUrl}\n\nSalam hangat,\nTim LifeMedia`;

                            actionHtml = `
                                <div class="pt-2 border-t border-gray-100 mt-2">
                                    <button type="button" onclick="openWaModal('${custName}', '${phone}', \`${waText}\`, '${tokenUrl}', null, 'verified')"
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
                                    <span>Pelanggan sudah mengisi formulir online (Menunggu C-Care)</span>
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
                            const reason = item.rejection_category || 'Perbaikan Dokumen';
                            const notes = item.rejection_notes || 'Mohon lengkapi dan upload ulang data pendaftaran.';
                            const waText = `Halo Bapak/Ibu ${custName},\n\nMohon maaf, pengajuan pendaftaran layanan LifeMedia Anda (${regCode}) memerlukan perbaikan/revisi data oleh tim C-Care.\n\nKategori: ${reason}\nCatatan Revisi: ${notes}\n\nSilakan perbaiki data dan lengkapi dokumen melalui tautan resmi LifeMedia berikut:\n${tokenUrl}\n\nSalam hangat,\nTim LifeMedia`;

                            actionHtml = `
                                <div class="pt-2 border-t border-rose-100 mt-2 space-y-2">
                                    <div class="p-2.5 rounded-xl bg-rose-50 border border-rose-200 text-[10px] text-rose-800 space-y-0.5">
                                        <div class="font-bold flex items-center gap-1 text-rose-700">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                            <span>Revisi: ${reason}</span>
                                        </div>
                                        <p class="text-gray-600 line-clamp-2">${notes}</p>
                                    </div>
                                    <button type="button" onclick="openWaModal('${custName}', '${phone}', \`${waText}\`, '${tokenUrl}', null, 'revision', '${reason}')"
                                            class="w-full py-2 px-3 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-[11px] shadow-sm flex items-center justify-center gap-1.5 transition-colors">
                                        <i class="fa-brands fa-whatsapp text-sm"></i>
                                        <span>Kirim Link Revisi via WA</span>
                                    </button>
                                </div>
                            `;
                        }

                        html += `
                            <div class="p-3.5 rounded-2xl bg-white border ${item.status === 'revision' ? 'border-rose-300 ring-1 ring-rose-100' : (item.status === 'verified' ? 'border-blue-300 ring-1 ring-blue-100' : 'border-gray-200')} shadow-sm space-y-1.5">
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

        // 7. Load Notifications (with Read/Unread distinction & Fade Grey styling)
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
                        const isUnread = !item.is_read;
                        if (isUnread) unreadCount++;

                        let actionHtml = '';
                        const reg = item.registration;
                        const custName = reg ? reg.customer_name : 'Pelanggan';
                        const phone = reg ? reg.phone_wa : '';
                        const regCode = reg ? reg.registration_code : '';
                        const tokenUrl = window.location.origin + '/pendaftaran/' + (reg ? reg.token : '');

                        if (item.type === 'survey_verified' || item.action_type === 'share_whatsapp') {
                            const waText = `Halo Bapak/Ibu ${custName},\n\nTerima kasih telah mengajukan pendaftaran layanan LifeMedia. Lokasi rumah Anda telah diverifikasi oleh tim teknis kami (OPJ).\n\nSilakan lengkapi data registrasi dan tanda tangan formulir berlangganan melalui tautan resmi LifeMedia berikut:\n${tokenUrl}\n\nSalam hangat,\nTim LifeMedia`;

                            // Trigger push banner if unread and not yet toasted
                            if (isUnread && !notifiedIds.has(item.id)) {
                                notifiedIds.add(item.id);
                                showPushBanner('verified', custName, phone, waText, tokenUrl, item.id, item.title, item.message);
                            }

                            actionHtml = isUnread ? `
                                <div class="pt-2">
                                    <button type="button" onclick="openWaModal('${custName}', '${phone}', \`${waText}\`, '${tokenUrl}', ${item.id}, 'verified')"
                                            class="w-full py-2 px-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] shadow-sm flex items-center justify-center gap-1.5 transition-colors">
                                        <i class="fa-brands fa-whatsapp text-sm"></i>
                                        <span>Bagikan Link WA Ke Pelanggan</span>
                                    </button>
                                </div>
                            ` : `
                                <div class="pt-2">
                                    <button type="button" onclick="openWaModal('${custName}', '${phone}', \`${waText}\`, '${tokenUrl}', ${item.id}, 'verified')"
                                            class="w-full py-1.5 px-2.5 rounded-xl bg-white hover:bg-emerald-50 text-gray-500 hover:text-emerald-700 border border-gray-200 text-[10.5px] font-medium flex items-center justify-center gap-1.5 transition-colors">
                                        <i class="fa-brands fa-whatsapp text-emerald-600 text-xs"></i>
                                        <span>Kirim Ulang Link WA</span>
                                    </button>
                                </div>
                            `;
                        } else if (item.type === 'registration_revision' || item.action_type === 'revise_data') {
                            const reason = (reg && reg.rejection_category) ? reg.rejection_category : 'Perbaikan Dokumen';
                            const notes = (reg && reg.rejection_notes) ? reg.rejection_notes : item.message;
                            const waText = `Halo Bapak/Ibu ${custName},\n\nMohon maaf, pengajuan pendaftaran layanan LifeMedia Anda (${regCode}) memerlukan perbaikan/revisi data oleh tim C-Care.\n\nKategori: ${reason}\nCatatan Revisi: ${notes}\n\nSilakan perbaiki data dan lengkapi dokumen melalui tautan resmi LifeMedia berikut:\n${tokenUrl}\n\nSalam hangat,\nTim LifeMedia`;

                            // Trigger push banner if unread and not yet toasted
                            if (isUnread && !notifiedIds.has(item.id)) {
                                notifiedIds.add(item.id);
                                showPushBanner('revision', custName, phone, waText, tokenUrl, item.id, item.title, item.message, reason);
                            }

                            actionHtml = isUnread ? `
                                <div class="pt-2">
                                    <button type="button" onclick="openWaModal('${custName}', '${phone}', \`${waText}\`, '${tokenUrl}', ${item.id}, 'revision', '${reason}')"
                                            class="w-full py-2 px-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-[11px] shadow-sm flex items-center justify-center gap-1.5 transition-colors">
                                        <i class="fa-brands fa-whatsapp text-sm"></i>
                                        <span>Kirim Notifikasi Revisi ke WA Pelanggan</span>
                                    </button>
                                </div>
                            ` : `
                                <div class="pt-2">
                                    <button type="button" onclick="openWaModal('${custName}', '${phone}', \`${waText}\`, '${tokenUrl}', ${item.id}, 'revision', '${reason}')"
                                            class="w-full py-1.5 px-2.5 rounded-xl bg-white hover:bg-rose-50 text-gray-500 hover:text-rose-700 border border-gray-200 text-[10.5px] font-medium flex items-center justify-center gap-1.5 transition-colors">
                                        <i class="fa-brands fa-whatsapp text-rose-600 text-xs"></i>
                                        <span>Kirim Ulang Info Revisi via WA</span>
                                    </button>
                                </div>
                            `;
                        } else if (item.type === 'registration_approved') {
                            if (isUnread && !notifiedIds.has(item.id)) {
                                notifiedIds.add(item.id);
                                showPushBanner('approved', custName, phone, '', '', item.id, item.title, item.message);
                            }
                            actionHtml = `
                                <div class="pt-1.5 flex items-center gap-1 text-[10px] ${isUnread ? 'text-emerald-700 font-semibold' : 'text-gray-400 font-normal'}">
                                    <i class="fa-solid fa-circle-check ${isUnread ? 'text-emerald-600' : 'text-gray-400'}"></i>
                                    <span>Pendaftaran Disetujui (Siap Pasang)</span>
                                </div>
                            `;
                        } else if (item.type === 'registration_filled') {
                            if (isUnread && !notifiedIds.has(item.id)) {
                                notifiedIds.add(item.id);
                                showPushBanner('filled', custName, phone, '', '', item.id, item.title, item.message);
                            }
                            actionHtml = `
                                <div class="pt-1.5 flex items-center gap-1 text-[10px] ${isUnread ? 'text-indigo-700 font-semibold' : 'text-gray-400 font-normal'}">
                                    <i class="fa-solid fa-clock ${isUnread ? 'text-indigo-600' : 'text-gray-400'}"></i>
                                    <span>Menunggu Verifikasi C-Care</span>
                                </div>
                            `;
                        }

                        // Read vs Unread Styles & Badges
                        let cardClass = '';
                        let titleClass = '';
                        let msgClass = '';
                        let timeClass = '';
                        let statusBadgeHtml = '';

                        if (isUnread) {
                            // Unread: Highlighted border, active background glow, bold text
                            let borderAccent = 'border-amber-400 ring-2 ring-amber-100 bg-white';
                            if (item.type === 'registration_revision') {
                                borderAccent = 'border-rose-400 ring-2 ring-rose-100 bg-white';
                            } else if (item.type === 'survey_verified') {
                                borderAccent = 'border-emerald-400 ring-2 ring-emerald-100 bg-white';
                            } else if (item.type === 'registration_filled') {
                                borderAccent = 'border-indigo-400 ring-2 ring-indigo-100 bg-white';
                            }
                            cardClass = `p-3.5 rounded-2xl border-2 ${borderAccent} shadow-md space-y-1.5 transition-all`;
                            titleClass = 'text-[11.5px] font-extrabold text-[#2C2C2C]';
                            msgClass = 'text-[11px] text-gray-700 font-medium leading-relaxed';
                            timeClass = 'text-[9px] font-mono text-gray-400';
                            statusBadgeHtml = `
                                <span class="px-1.5 py-0.5 rounded text-[8px] font-extrabold bg-rose-500 text-white flex items-center gap-1 shadow-xs tracking-wider shrink-0">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white animate-ping"></span> BARU
                                </span>
                            `;
                        } else {
                            // Read: Faded Grey Effect, muted text, subtle border
                            cardClass = 'p-3 rounded-2xl border border-gray-200 bg-gray-50/70 opacity-60 hover:opacity-100 transition-opacity space-y-1.5 shadow-xs';
                            titleClass = 'text-[11px] font-semibold text-gray-400';
                            msgClass = 'text-[10.5px] text-gray-400 font-normal leading-relaxed';
                            timeClass = 'text-[9px] font-mono text-gray-300';
                            statusBadgeHtml = `
                                <span class="px-1.5 py-0.5 rounded text-[8px] font-medium bg-gray-200/80 text-gray-500 border border-gray-300/60 flex items-center gap-1 shrink-0">
                                    <i class="fa-solid fa-check-double text-[8px] text-gray-400"></i> DIBACA
                                </span>
                            `;
                        }

                        html += `
                            <div class="${cardClass}">
                                <div class="flex items-center justify-between gap-1.5 pb-1 border-b border-gray-100/80">
                                    <div class="flex items-center gap-1.5 min-w-0">
                                        ${statusBadgeHtml}
                                        <span class="${titleClass} truncate">${item.title}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 shrink-0">
                                        <span class="${timeClass}">${new Date(item.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</span>
                                        ${isUnread ? `
                                            <button type="button" onclick="markNotificationRead(${item.id})" class="text-[9px] text-[#EF666B] hover:text-[#9B385B] font-semibold underline underline-offset-2 ml-1" title="Tandai sudah dibaca">
                                                Baca
                                            </button>
                                        ` : ''}
                                    </div>
                                </div>
                                <p class="${msgClass}">${item.message}</p>
                                ${actionHtml}
                            </div>
                        `;
                    });

                    container.innerHTML = html;

                    // Update Badge Counters
                    const dot = document.getElementById('notifBadgeDot');
                    const countBadge = document.getElementById('notifBadgeCount');
                    const tabBadge = document.getElementById('notifTabUnreadBadge');

                    if (unreadCount > 0) {
                        dot.classList.remove('hidden');
                        countBadge.classList.remove('hidden');
                        countBadge.innerText = unreadCount;
                        if (tabBadge) {
                            tabBadge.classList.remove('hidden');
                            tabBadge.innerText = `${unreadCount} Baru`;
                        }
                    } else {
                        dot.classList.add('hidden');
                        countBadge.classList.add('hidden');
                        if (tabBadge) {
                            tabBadge.classList.add('hidden');
                        }
                    }
                } else {
                    container.innerHTML = '<div class="text-center py-6 text-gray-400 text-xs">Tidak ada notifikasi.</div>';
                    document.getElementById('notifBadgeDot').classList.add('hidden');
                    document.getElementById('notifBadgeCount').classList.add('hidden');
                    const tabBadge = document.getElementById('notifTabUnreadBadge');
                    if (tabBadge) tabBadge.classList.add('hidden');
                }
            } catch (err) {
                if (!isSilent) container.innerHTML = '<div class="text-center py-6 text-rose-600 text-xs">Gagal memuat notifikasi.</div>';
            }
        }

        async function markNotificationRead(notifId) {
            try {
                await fetch(`/api/notifications/${notifId}/read`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' }
                });
                loadMyNotifications(true);
            } catch (err) {
                console.error('Error marking notification read:', err);
            }
        }

        async function markAllNotificationsRead() {
            if (!currentSales) return;
            try {
                await fetch(`/api/sales/${currentSales.id}/notifications/read-all`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' }
                });
                loadMyNotifications(true);
            } catch (err) {
                console.error('Error marking all notifications read:', err);
            }
        }

        function showPushBanner(type, name, phone, text, link, notifId, title, msg, extra = '') {
            currentPushData = { type, name, phone, text, link, notifId };
            const banner = document.getElementById('pushBanner');
            const iconBox = document.getElementById('pushBannerIcon');
            const dot = document.getElementById('pushBannerDot');
            const titleEl = document.getElementById('pushBannerTitle');
            const msgEl = document.getElementById('pushBannerMsg');
            const btn = document.getElementById('pushBannerBtn');
            const btnText = document.getElementById('pushBannerBtnText');

            banner.className = 'mx-3 mt-2.5 bg-white/95 backdrop-blur-md rounded-2xl p-3 shadow-xl transition-all duration-300 cursor-pointer animate-pulse z-40';

            if (type === 'revision') {
                banner.classList.add('border-2', 'border-rose-500');
                iconBox.className = 'w-8 h-8 rounded-xl bg-rose-600 text-white flex items-center justify-center text-sm shadow-sm shrink-0';
                iconBox.innerHTML = '<i class="fa-solid fa-triangle-exclamation text-base"></i>';
                dot.className = 'w-2 h-2 rounded-full bg-rose-500 animate-ping';
                titleEl.innerText = title || ('Revisi: ' + name);
                msgEl.innerText = msg || 'Pengajuan memerlukan revisi dari C-Care. Klik untuk kirim pesan ke WhatsApp pelanggan!';
                btn.className = 'mt-1.5 inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-rose-600 hover:bg-rose-700 text-white font-bold text-[10px] shadow-xs';
                btnText.innerText = 'Kirim Revisi via WA';
            } else if (type === 'approved') {
                banner.classList.add('border-2', 'border-emerald-500');
                iconBox.className = 'w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center text-sm shadow-sm shrink-0';
                iconBox.innerHTML = '<i class="fa-solid fa-circle-check text-base"></i>';
                dot.className = 'w-2 h-2 rounded-full bg-emerald-500 animate-ping';
                titleEl.innerText = title || ('Approved: ' + name);
                msgEl.innerText = msg || 'Pengajuan telah disetujui C-Care.';
                btn.className = 'mt-1.5 inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[10px] shadow-xs';
                btnText.innerText = 'Tandai Dibaca';
            } else if (type === 'filled') {
                banner.classList.add('border-2', 'border-indigo-500');
                iconBox.className = 'w-8 h-8 rounded-xl bg-indigo-600 text-white flex items-center justify-center text-sm shadow-sm shrink-0';
                iconBox.innerHTML = '<i class="fa-solid fa-file-circle-check text-base"></i>';
                dot.className = 'w-2 h-2 rounded-full bg-indigo-500 animate-ping';
                titleEl.innerText = title || ('Form Diisi: ' + name);
                msgEl.innerText = msg || 'Pelanggan telah mengisi formulir online.';
                btn.className = 'mt-1.5 inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-[10px] shadow-xs';
                btnText.innerText = 'Lihat Status';
            } else {
                // Default verified / share_whatsapp
                banner.classList.add('border-2', 'border-emerald-500');
                iconBox.className = 'w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center text-sm shadow-sm shrink-0';
                iconBox.innerHTML = '<i class="fa-brands fa-whatsapp text-lg"></i>';
                dot.className = 'w-2 h-2 rounded-full bg-emerald-500 animate-ping';
                titleEl.innerText = title || ('Survey ' + name + ' Terverifikasi!');
                msgEl.innerText = msg || 'OPJ telah memverifikasi lokasi. Klik untuk bagikan link WhatsApp!';
                btn.className = 'mt-1.5 inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[10px] shadow-xs';
                btnText.innerText = 'Bagikan Link WA Sekarang';
            }

            banner.classList.remove('hidden');
        }

        function handlePushBannerClick() {
            if (currentPushData) {
                if (currentPushData.type === 'verified' || currentPushData.type === 'revision') {
                    openWaModal(currentPushData.name, currentPushData.phone, currentPushData.text, currentPushData.link, currentPushData.notifId, currentPushData.type);
                } else if (currentPushData.notifId) {
                    fetch(`/api/notifications/${currentPushData.notifId}/read`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' }
                    }).then(() => {
                        loadMyNotifications(true);
                        switchTab('history');
                    }).catch(() => {});
                }
                closePushBanner();
            }
        }

        function closePushBanner() {
            document.getElementById('pushBanner').classList.add('hidden');
        }

        function openWaModal(name, phone, text, link, notifId = null, type = 'verified', reason = '') {
            if (notifId) {
                fetch(`/api/notifications/${notifId}/read`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' }
                }).then(() => loadMyNotifications(true)).catch(() => {});
            }

            const header = document.getElementById('waModalHeader');
            const title = document.getElementById('waModalTitle');
            const sub = document.getElementById('waModalSub');
            const directBtn = document.getElementById('waDirectLink');

            if (type === 'revision') {
                header.className = 'flex items-center gap-2 font-bold text-rose-600 text-sm';
                title.innerText = 'Kirim Link Revisi Data ke Pelanggan';
                sub.innerText = `Kirimkan rincian revisi dokumen kepada ${name} (${phone || 'Pelanggan'}):`;
                directBtn.className = 'px-4 py-1.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-md shadow-rose-600/20 inline-flex items-center gap-1.5 transition-colors';
            } else {
                header.className = 'flex items-center gap-2 font-bold text-emerald-600 text-sm';
                title.innerText = 'Bagikan Link WA ke Pelanggan';
                sub.innerText = `Pesan WhatsApp resmi akan diteruskan ke nomor ${name} (${phone || 'Pelanggan'}):`;
                directBtn.className = 'px-4 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md shadow-emerald-600/20 inline-flex items-center gap-1.5 transition-colors';
            }

            document.getElementById('waMessagePreview').innerText = text;
            const cleanPhone = (phone || '').replace(/[^0-9]/g, '');
            const finalPhone = cleanPhone.startsWith('0') ? '62' + cleanPhone.substring(1) : cleanPhone;
            const waUrl = `https://api.whatsapp.com/send?phone=${finalPhone}&text=${encodeURIComponent(text)}`;
            directBtn.href = waUrl;
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
