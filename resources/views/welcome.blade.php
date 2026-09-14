<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#F8F9FA]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Life Connect - Solusi Registrasi &amp; Survey Terpadu LifeMedia</title>

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
</head>
<body class="min-h-full bg-[#F8F9FA] text-[#333333] font-sans antialiased selection:bg-[#EF666B] selection:text-white flex flex-col justify-between">

    <!-- Background Decorative Gradient Glows -->
    <div class="fixed top-0 left-1/4 w-[600px] h-[600px] bg-[#F48C5B]/10 rounded-full blur-3xl pointer-events-none -z-10"></div>
    <div class="fixed bottom-0 right-1/4 w-[600px] h-[600px] bg-[#9B385B]/10 rounded-full blur-3xl pointer-events-none -z-10"></div>

    <!-- Navigation Header -->
    <header class="w-full bg-white/80 backdrop-blur-md border-b border-[#F0E8E4] sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ url('/') }}" class="flex items-center gap-2.5 group">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Life Media Logo" class="h-9 w-auto object-contain group-hover:scale-105 transition-transform">
                    <div class="border-l border-gray-200 pl-2.5 hidden sm:block">
                        <div class="font-extrabold text-sm tracking-tight text-[#2C2C2C] flex items-center gap-1">
                            <span>Life</span>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#F48C5B] to-[#EF666B]">Connect</span>
                        </div>
                        <div class="text-[9px] text-gray-500 font-semibold tracking-wider uppercase">FTTH Management System</div>
                    </div>
                </a>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('mobile_simulator.index') }}" 
                   class="hidden sm:inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-[#FEF4F0] hover:bg-[#F48C5B]/15 text-[#EF666B] border border-[#F48C5B]/30 text-xs font-bold transition-all">
                    <i class="fa-solid fa-mobile-screen-button"></i>
                    <span>Simulator Android (AM)</span>
                </a>

                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/opj') }}" 
                           class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-gradient-to-r from-[#F48C5B] via-[#EF666B] to-[#9B385B] text-white font-bold text-xs shadow-md shadow-[#EF666B]/25 hover:opacity-95 transition-opacity">
                            <i class="fa-solid fa-gauge-high"></i>
                            <span>Buka Portal</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                           class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-gradient-to-r from-[#F48C5B] via-[#EF666B] to-[#9B385B] text-white font-bold text-xs shadow-md shadow-[#EF666B]/25 hover:opacity-95 transition-opacity">
                            <i class="fa-solid fa-right-to-bracket"></i>
                            <span>Masuk Portal</span>
                        </a>
                    @endauth
                @endif
            </div>
        </div>
    </header>

    <!-- Main Hero & Feature Grid -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16 space-y-16 flex-1">
        
        <!-- Hero Section -->
        <div class="text-center max-w-3xl mx-auto space-y-6">
            <div class="flex items-center justify-center gap-3">
                <div class="p-3 bg-white rounded-2xl border border-[#F0E8E4] shadow-md inline-flex items-center">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Life Media Logo" class="h-12 sm:h-14 w-auto object-contain">
                </div>
            </div>

            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-[#FEF4F0] border border-[#F48C5B]/30 text-[#EF666B] text-xs font-bold shadow-sm">
                <i class="fa-solid fa-wifi text-[#F48C5B]"></i>
                <span>Platform Operasional Registrasi &amp; Survey Terpadu</span>
            </div>

            <h1 class="text-3xl sm:text-5xl font-extrabold text-[#2C2C2C] tracking-tight leading-tight">
                Transformasi Digital Layanan <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#F48C5B] via-[#EF666B] to-[#9B385B]">
                    Internet LifeMedia
                </span>
            </h1>

            <p class="text-sm sm:text-base text-gray-600 leading-relaxed max-w-2xl mx-auto">
                Kelola alur survey lapangan tim Sales, verifikasi teknis jaringan OPJ, persetujuan administrasi C-Care, pemantauan SLA Admin Sales, dan tata kelola sistem VAS secara realtime.
            </p>

            <div class="flex flex-wrap items-center justify-center gap-3 pt-3">
                <a href="{{ route('login') }}" 
                   class="px-6 py-3 rounded-xl bg-gradient-to-r from-[#F48C5B] via-[#EF666B] to-[#9B385B] text-white font-bold text-sm shadow-lg shadow-[#EF666B]/25 hover:opacity-95 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-arrow-right-to-bracket"></i>
                    <span>Masuk ke Dashboard Sistem</span>
                </a>
                <a href="{{ route('mobile_simulator.index') }}" 
                   class="px-6 py-3 rounded-xl bg-white border border-[#F0E8E4] hover:border-[#EF666B] text-[#2C2C2C] hover:text-[#EF666B] font-bold text-sm shadow-sm transition-all flex items-center gap-2">
                    <i class="fa-solid fa-mobile-screen-button text-[#F48C5B]"></i>
                    <span>Coba Simulator Mobile Sales</span>
                </a>
            </div>
        </div>

        <!-- Role Modules Grid -->
        <div class="space-y-6">
            <div class="text-center">
                <h2 class="text-xl font-bold text-[#2C2C2C]">Modul &amp; Peran Terintegrasi</h2>
                <p class="text-xs text-gray-500 mt-1">Pilih modul operasional sesuai dengan hak akses Anda</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- Module 1: Sales Mobile -->
                <div class="p-5 rounded-2xl bg-white border border-[#F0E8E4] hover:border-[#EF666B] shadow-sm hover:shadow-md transition-all space-y-3 group">
                    <div class="w-10 h-10 rounded-xl bg-[#FEF4F0] border border-[#F48C5B]/30 text-[#EF666B] flex items-center justify-center font-bold text-base group-hover:scale-105 transition-transform">
                        <i class="fa-solid fa-mobile-screen"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-[#2C2C2C] text-sm group-hover:text-[#EF666B] transition-colors">Sales Lapangan (AM)</h3>
                        <p class="text-xs text-gray-500 mt-1 leading-relaxed">Survey calon pelanggan, penentuan titik GPS, dan follow up pendaftaran via WA.</p>
                    </div>
                    <a href="{{ route('mobile_simulator.index') }}" class="inline-flex items-center gap-1 text-xs font-bold text-[#EF666B] hover:text-[#9B385B] pt-1">
                        <span>Buka Simulator</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>

                <!-- Module 2: OPJ Technical -->
                <div class="p-5 rounded-2xl bg-white border border-[#F0E8E4] hover:border-[#EF666B] shadow-sm hover:shadow-md transition-all space-y-3 group">
                    <div class="w-10 h-10 rounded-xl bg-[#FEF4F0] border border-[#F48C5B]/30 text-[#EF666B] flex items-center justify-center font-bold text-base group-hover:scale-105 transition-transform">
                        <i class="fa-solid fa-tower-broadcast"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-[#2C2C2C] text-sm group-hover:text-[#EF666B] transition-colors">Teknis Jaringan (OPJ)</h3>
                        <p class="text-xs text-gray-500 mt-1 leading-relaxed">Verifikasi coverage ODP / FAT terdekat, penentuan jarak kabel drop core, dan kelayakan teknis.</p>
                    </div>
                    <a href="{{ route('opj.index') }}" class="inline-flex items-center gap-1 text-xs font-bold text-[#EF666B] hover:text-[#9B385B] pt-1">
                        <span>Portal OPJ</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>

                <!-- Module 3: Customer Care -->
                <div class="p-5 rounded-2xl bg-white border border-[#F0E8E4] hover:border-[#EF666B] shadow-sm hover:shadow-md transition-all space-y-3 group">
                    <div class="w-10 h-10 rounded-xl bg-[#FEF4F0] border border-[#F48C5B]/30 text-[#EF666B] flex items-center justify-center font-bold text-base group-hover:scale-105 transition-transform">
                        <i class="fa-solid fa-headset"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-[#2C2C2C] text-sm group-hover:text-[#EF666B] transition-colors">Customer Care (C-Care)</h3>
                        <p class="text-xs text-gray-500 mt-1 leading-relaxed">Verifikasi kelengkapan identitas KTP/NPWP, validasi tanda tangan digital, dan approval registrasi.</p>
                    </div>
                    <a href="{{ route('ccare.index') }}" class="inline-flex items-center gap-1 text-xs font-bold text-[#EF666B] hover:text-[#9B385B] pt-1">
                        <span>Portal C-Care</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>

                <!-- Module 4: Super Admin VAS -->
                <div class="p-5 rounded-2xl bg-white border border-[#F0E8E4] hover:border-[#EF666B] shadow-sm hover:shadow-md transition-all space-y-3 group">
                    <div class="w-10 h-10 rounded-xl bg-[#FEF4F0] border border-[#F48C5B]/30 text-[#EF666B] flex items-center justify-center font-bold text-base group-hover:scale-105 transition-transform">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-[#2C2C2C] text-sm group-hover:text-[#EF666B] transition-colors">Admin &amp; VAS Master</h3>
                        <p class="text-xs text-gray-500 mt-1 leading-relaxed">Pemantauan SLA alur kerja, audit log forensik transaksi, dan manajemen pengguna terpusat.</p>
                    </div>
                    <a href="{{ route('vas.index') }}" class="inline-flex items-center gap-1 text-xs font-bold text-[#EF666B] hover:text-[#9B385B] pt-1">
                        <span>Portal VAS Admin</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="w-full bg-white border-t border-[#F0E8E4] py-6 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-gray-500">
            <div class="flex items-center gap-2">
                <span class="font-bold text-[#2C2C2C]">LifeMedia</span> &bull;
                <span>Life Connect Platform &copy; {{ date('Y') }}</span>
            </div>
            <div class="flex items-center gap-4 text-gray-500">
                <span class="font-medium text-[#EF666B]">Sunset Orange &bull; Coral Pink &bull; Plum Palette</span>
            </div>
        </div>
    </footer>

</body>
</html>
