<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Sistem - Life Connect | LifeMedia</title>
    
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
            darkMode: 'class',
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
                            dark: '#2C2C2C',
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; color: #333333; }
        .glow-sphere {
            position: absolute;
            border-radius: 50%;
            filter: blur(140px);
            opacity: 0.35;
            pointer-events: none;
        }
    </style>
</head>
<body class="h-full bg-[#F8F9FA] text-[#333333] flex items-center justify-center p-4 relative overflow-x-hidden selection:bg-[#F48C5B] selection:text-white">

    <!-- Background Atmospheric Glows -->
    <div class="glow-sphere bg-[#F48C5B] w-[500px] h-[500px] -top-32 -left-32 opacity-25"></div>
    <div class="glow-sphere bg-[#EF666B] w-[450px] h-[450px] -bottom-32 -right-32 opacity-20"></div>
    <div class="glow-sphere bg-[#9B385B] w-[350px] h-[350px] top-1/3 left-1/2 -translate-x-1/2 -translate-y-1/2 opacity-15"></div>

    <div class="w-full max-w-5xl z-10 my-auto py-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            
            <!-- Left Info Banner -->
            <div class="lg:col-span-6 space-y-6 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-[#FEF4F0] border border-[#F48C5B]/40 text-[#9B385B] text-xs font-bold shadow-xs">
                    <span class="w-2 h-2 rounded-full bg-[#EF666B] animate-pulse"></span>
                    Portal Registrasi Terintegrasi LifeMedia
                </div>

                <div class="space-y-4">
                    <div class="flex items-center justify-center lg:justify-start">
                        <div class="p-3 rounded-2xl bg-white border border-gray-200 shadow-sm inline-flex">
                            <img src="{{ asset('assets/images/logo.png') }}" alt="Life Media Logo" class="h-11 sm:h-12 w-auto object-contain">
                        </div>
                    </div>
                    <div>
                        <h1 class="text-3xl sm:text-4xl font-extrabold text-[#2C2C2C] tracking-tight">Life Connect</h1>
                        <p class="text-xs font-bold text-[#F48C5B] tracking-wider uppercase mt-1">Sistem Integrasi FTTH &amp; Pendaftaran Pelanggan</p>
                    </div>
                    <p class="text-gray-600 text-sm sm:text-base leading-relaxed max-w-lg mx-auto lg:mx-0 font-normal">
                        Platform pendaftaran calon pelanggan terverifikasi area coverage, terhubung langsung dengan Mobile Apps Sales &amp; multi-role dashboard.
                    </p>
                </div>

                <!-- Role Workflow Features List -->
                <div class="grid grid-cols-2 gap-3 pt-2">
                    <div class="p-4 rounded-2xl bg-white border border-gray-200/90 shadow-sm">
                        <div class="w-8 h-8 rounded-lg bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center text-sm mb-2">
                            <i class="fa-solid fa-map-location-dot"></i>
                        </div>
                        <div class="font-bold text-xs text-[#2C2C2C]">Verifikasi OPJ</div>
                        <div class="text-[11px] text-gray-500">Peta OpenStreetMap &amp; Geotagging</div>
                    </div>

                    <div class="p-4 rounded-2xl bg-white border border-gray-200/90 shadow-sm">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-600 flex items-center justify-center text-sm mb-2">
                            <i class="fa-solid fa-signature"></i>
                        </div>
                        <div class="font-bold text-xs text-[#2C2C2C]">Form Pelanggan</div>
                        <div class="text-[11px] text-gray-500">Pilih Paket &amp; TTD Digital</div>
                    </div>

                    <div class="p-4 rounded-2xl bg-white border border-gray-200/90 shadow-sm">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 border border-indigo-200 text-indigo-600 flex items-center justify-center text-sm mb-2">
                            <i class="fa-solid fa-user-shield"></i>
                        </div>
                        <div class="font-bold text-xs text-[#2C2C2C]">C-Care Review</div>
                        <div class="text-[11px] text-gray-500">Persetujuan &amp; Revisi Data</div>
                    </div>

                    <div class="p-4 rounded-2xl bg-white border border-gray-200/90 shadow-sm">
                        <div class="w-8 h-8 rounded-lg bg-[#FEF4F0] border border-[#F48C5B]/40 text-[#9B385B] flex items-center justify-center text-sm mb-2">
                            <i class="fa-solid fa-chart-pie"></i>
                        </div>
                        <div class="font-bold text-xs text-[#2C2C2C]">Admin VAS &amp; Sales</div>
                        <div class="text-[11px] text-gray-500">Audit Trail &amp; Evaluasi SLA</div>
                    </div>
                </div>

                <!-- Simulator Direct Action -->
                <div class="pt-2">
                    <a href="{{ route('mobile-simulator') }}" target="_blank" 
                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#FEF4F0] border border-[#F48C5B]/40 text-[#9B385B] text-xs font-bold hover:bg-[#FDE8E1] transition-all shadow-xs">
                        <i class="fa-solid fa-mobile-screen-button text-[#F48C5B]"></i>
                        <span>Buka Simulator Mobile Apps Sales (Android UI)</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>

            <!-- Right Login Form Card -->
            <div class="lg:col-span-6">
                <div class="p-6 sm:p-8 rounded-3xl bg-white border border-gray-200 shadow-xl shadow-orange-500/5 relative">
                    
                    <div class="mb-6">
                        <h2 class="text-xl font-extrabold text-[#2C2C2C] tracking-tight">Masuk ke Dashboard</h2>
                        <p class="text-xs text-gray-500 mt-1">Gunakan akun kredensial Anda atau pilih demo akun di bawah.</p>
                    </div>

                    @if($errors->any())
                        <div class="p-3 mb-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('login.post') }}" method="POST" class="space-y-4" id="loginForm">
                        @csrf

                        <div>
                            <label for="email" class="block text-xs font-bold text-[#333333] mb-1.5">Alamat Email</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400 text-sm">
                                    <i class="fa-solid fa-envelope"></i>
                                </span>
                                <input type="email" name="email" id="email" value="{{ old('email', 'vas@lifemedia.id') }}" required
                                       class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-white border border-gray-300 text-[#2C2C2C] text-sm focus:outline-none focus:ring-2 focus:ring-[#F48C5B] focus:border-[#F48C5B] transition-all placeholder:text-gray-400"
                                       placeholder="nama@lifemedia.id">
                            </div>
                        </div>

                        <div>
                            <label for="password" class="block text-xs font-bold text-[#333333] mb-1.5">Kata Sandi</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400 text-sm">
                                    <i class="fa-solid fa-lock"></i>
                                </span>
                                <input type="password" name="password" id="password" value="password" required
                                       class="w-full pl-10 pr-10 py-2.5 rounded-xl bg-white border border-gray-300 text-[#2C2C2C] text-sm focus:outline-none focus:ring-2 focus:ring-[#F48C5B] focus:border-[#F48C5B] transition-all placeholder:text-gray-400"
                                       placeholder="••••••••">
                                <button type="button" onclick="togglePasswordVisibility()"
                                        class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-gray-400 hover:text-gray-600 focus:outline-none cursor-pointer"
                                        title="Tampilkan / Sembunyikan Kata Sandi">
                                    <i id="passwordToggleIcon" class="fa-solid fa-eye text-sm"></i>
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center justify-between text-xs pt-1">
                            <label class="flex items-center gap-2 cursor-pointer text-gray-600 hover:text-gray-800">
                                <input type="checkbox" name="remember" class="w-4 h-4 rounded bg-white border-gray-300 text-[#F48C5B] focus:ring-[#F48C5B]">
                                <span>Ingat Saya</span>
                            </label>
                        </div>

                        <button type="submit" 
                                class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-[#F48C5B] via-[#EF666B] to-[#9B385B] hover:from-[#EF666B] hover:to-[#F48C5B] text-white text-sm font-bold shadow-lg shadow-orange-500/20 hover:shadow-orange-500/30 transition-all duration-200 flex items-center justify-center gap-2">
                            <span>Masuk ke Dashboard</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </button>
                    </form>

                    <!-- Quick 1-Click Demo Accounts Selector -->
                    <div class="mt-6 pt-5 border-t border-gray-100">
                        <div class="text-[11px] font-bold uppercase tracking-wider text-gray-500 mb-2.5 flex items-center justify-between">
                            <span>Pilih Akun Demo (1-Click Auto Fill):</span>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                            
                            <button type="button" onclick="setDemo('vas@lifemedia.id')" 
                                    class="p-2 rounded-xl bg-[#FEF4F0] border border-[#9B385B]/25 hover:bg-[#FDE8E1] text-left transition-colors">
                                <div class="font-bold text-xs text-[#9B385B] flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#9B385B]"></span> Admin VAS
                                </div>
                                <div class="text-[10px] text-gray-500 truncate">vas@lifemedia.id</div>
                            </button>

                            <button type="button" onclick="setDemo('opj@lifemedia.id')" 
                                    class="p-2 rounded-xl bg-amber-50 border border-amber-200 hover:bg-amber-100 text-left transition-colors">
                                <div class="font-bold text-xs text-amber-800 flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> OPJ (Ops)
                                </div>
                                <div class="text-[10px] text-gray-500 truncate">opj@lifemedia.id</div>
                            </button>

                            <button type="button" onclick="setDemo('ccare@lifemedia.id')" 
                                    class="p-2 rounded-xl bg-emerald-50 border border-emerald-200 hover:bg-emerald-100 text-left transition-colors">
                                <div class="font-bold text-xs text-emerald-800 flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> C-Care
                                </div>
                                <div class="text-[10px] text-gray-500 truncate">ccare@lifemedia.id</div>
                            </button>

                            <button type="button" onclick="setDemo('salesadmin@lifemedia.id')" 
                                    class="p-2 rounded-xl bg-blue-50 border border-blue-200 hover:bg-blue-100 text-left transition-colors">
                                <div class="font-bold text-xs text-blue-800 flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Admin Sales
                                </div>
                                <div class="text-[10px] text-gray-500 truncate">salesadmin@lifemedia.id</div>
                            </button>

                            <button type="button" onclick="setDemo('sales01@lifemedia.id')" 
                                    class="p-2 rounded-xl bg-[#FEF4F0] border border-[#F48C5B]/40 hover:bg-[#FDE8E1] text-left transition-colors col-span-2 sm:col-span-2">
                                <div class="font-bold text-xs text-[#F48C5B] flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#F48C5B]"></span> Sales AM (Budi)
                                </div>
                                <div class="text-[10px] text-gray-500 truncate">sales01@lifemedia.id (AM-101)</div>
                            </button>

                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const icon = document.getElementById('passwordToggleIcon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        function setDemo(email) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = 'password';
            
            // Visual pulse feedback on button
            const btn = document.querySelector('button[type="submit"]');
            btn.classList.add('ring-4', 'ring-[#F48C5B]/40');
            setTimeout(() => btn.classList.remove('ring-4', 'ring-[#F48C5B]/40'), 500);
        }
    </script>
</body>
</html>
