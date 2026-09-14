<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Life Connect | LifeMedia</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}?v=2">
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}?v=2">
    <link rel="apple-touch-icon" href="{{ asset('assets/images/favicon.png') }}?v=2">
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Leaflet CSS for OpenStreetMap -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    
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
                            50: '#FEF4F0',
                            100: '#FDE8E1',
                            200: '#FBD1C3',
                            300: '#F8B198',
                            400: '#F69A75',
                            500: '#F48C5B',
                            600: '#EF666B',
                            700: '#C94B62',
                            800: '#9B385B',
                            900: '#742440',
                        },
                        neutral: {
                            charcoal: '#333333',
                            dark: '#2C2C2C',
                            muted: '#6B7280',
                            peach: '#FEF4F0',
                            grey: '#F8F9FA',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    
    <!-- Alpine.js for lightweight UI reactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; color: #333333; }
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid #E5E7EB;
            box-shadow: 0 4px 20px -2px rgba(155, 56, 91, 0.04);
        }
        .glass-header {
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid #E5E7EB;
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #F8F9FA;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 9999px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94A3B8;
        }
    </style>
    @stack('styles')
</head>
<body class="h-full bg-[#F8F9FA] text-[#333333] antialiased flex flex-col selection:bg-[#F48C5B] selection:text-white" x-data="{ sidebarOpen: false }">

    <!-- Flash Messages Container -->
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
             class="fixed top-5 right-5 z-50 flex items-center p-4 mb-4 text-emerald-800 bg-white border border-emerald-200 border-l-4 border-l-emerald-500 rounded-xl shadow-xl transition-all duration-300">
            <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-emerald-600 bg-emerald-50 rounded-lg mr-3">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div class="text-sm font-medium mr-3">{{ session('success') }}</div>
            <button @click="show = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    @if(session('warning'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 7000)" 
             class="fixed top-5 right-5 z-50 flex items-center p-4 mb-4 text-amber-800 bg-white border border-amber-200 border-l-4 border-l-amber-500 rounded-xl shadow-xl transition-all duration-300">
            <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-amber-600 bg-amber-50 rounded-lg mr-3">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div class="text-sm font-medium mr-3">{{ session('warning') }}</div>
            <button @click="show = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    @if(session('error') || $errors->any())
        <div x-data="{ show: true }" x-show="show" 
             class="fixed top-5 right-5 z-50 flex items-start p-4 mb-4 text-rose-800 bg-white border border-rose-200 border-l-4 border-l-rose-500 rounded-xl shadow-xl transition-all duration-300 max-w-md">
            <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-rose-600 bg-rose-50 rounded-lg mr-3 mt-0.5">
                <i class="fa-solid fa-circle-xmark"></i>
            </div>
            <div class="text-sm font-medium mr-3 flex-1">
                @if(session('error'))
                    <div>{{ session('error') }}</div>
                @endif
                @if($errors->any())
                    <ul class="list-disc list-inside space-y-1 mt-1">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
            <button @click="show = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar Navigation -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
               class="fixed inset-y-0 left-0 z-40 w-64 bg-white border-r border-gray-200/80 transition-transform duration-300 ease-in-out md:translate-x-0 md:static md:inset-0 flex flex-col shadow-sm">
            
            <!-- App Brand Logo -->
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <a href="{{ url('/') }}" class="flex items-center gap-2.5 group">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Life Media Logo" class="h-8 w-auto object-contain group-hover:scale-105 transition-transform">
                    <div class="border-l border-gray-200 pl-2.5">
                        <div class="font-extrabold text-sm leading-tight text-[#2C2C2C] flex items-center gap-1">
                            <span>Connect</span>
                            <span class="w-1.5 h-1.5 rounded-full bg-[#EF666B] animate-pulse"></span>
                        </div>
                        <div class="text-[9px] font-bold tracking-wider text-[#F48C5B] uppercase">Portal FTTH</div>
                    </div>
                </a>
                <button @click="sidebarOpen = false" class="md:hidden text-gray-400 hover:text-gray-600">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- User Info Card -->
            <div class="p-3.5 mx-4 my-3 rounded-xl bg-[#FEF4F0] border border-[#F6D8CE]/70">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-[#F48C5B] to-[#9B385B] flex items-center justify-center text-white font-bold text-sm shadow-sm">
                        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-[#2C2C2C] truncate">{{ Auth::user()->name ?? 'User' }}</p>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold 
                            @if(Auth::user()->role === 'admin_vas') bg-[#9B385B]/10 text-[#9B385B] border border-[#9B385B]/25
                            @elseif(Auth::user()->role === 'admin_sales') bg-blue-100 text-blue-800 border border-blue-200
                            @elseif(Auth::user()->role === 'opj') bg-amber-100 text-amber-800 border border-amber-200
                            @elseif(Auth::user()->role === 'c_care') bg-emerald-100 text-emerald-800 border border-emerald-200
                            @else bg-gray-100 text-gray-700 border border-gray-200 @endif">
                            {{ Auth::user()->role_badge ?? 'User' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-4 space-y-1.5 overflow-y-auto custom-scrollbar">
                
                <!-- Role Specific Navigation -->
                <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wider px-3 pt-3 pb-1">
                    Menu Utama
                </div>

                @if(Auth::user()->role === 'admin_vas' || Auth::user()->role === 'opj')
                    <a href="{{ route('opj.index') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('opj.*') ? 'bg-[#FEF4F0] text-[#F48C5B] border border-[#F48C5B]/40 shadow-xs' : 'text-gray-600 hover:bg-gray-100 hover:text-[#2C2C2C]' }}">
                        <i class="fa-solid fa-map-location-dot w-5 text-center {{ request()->routeIs('opj.*') ? 'text-[#F48C5B]' : 'text-gray-400' }}"></i>
                        <span>Dashboard OPJ</span>
                        @php $pendOpj = \App\Models\CustomerRegistration::where('status', 'submitted')->count(); @endphp
                        @if($pendOpj > 0)
                            <span class="ml-auto bg-amber-100 text-amber-800 border border-amber-200 text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $pendOpj }}</span>
                        @endif
                    </a>
                @endif

                @if(Auth::user()->role === 'admin_vas' || Auth::user()->role === 'c_care')
                    <a href="{{ route('ccare.index') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('ccare.*') ? 'bg-[#FEF4F0] text-[#EF666B] border border-[#EF666B]/40 shadow-xs' : 'text-gray-600 hover:bg-gray-100 hover:text-[#2C2C2C]' }}">
                        <i class="fa-solid fa-user-check w-5 text-center {{ request()->routeIs('ccare.*') ? 'text-[#EF666B]' : 'text-gray-400' }}"></i>
                        <span>Dashboard C-Care</span>
                        @php $pendCC = \App\Models\CustomerRegistration::where('status', 'filled')->count(); @endphp
                        @if($pendCC > 0)
                            <span class="ml-auto bg-emerald-100 text-emerald-800 border border-emerald-200 text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $pendCC }}</span>
                        @endif
                    </a>
                @endif

                @if(Auth::user()->role === 'admin_vas' || Auth::user()->role === 'admin_sales')
                    <a href="{{ route('admin-sales.dashboard') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('admin-sales.*') ? 'bg-[#FEF4F0] text-[#9B385B] border border-[#9B385B]/40 shadow-xs' : 'text-gray-600 hover:bg-gray-100 hover:text-[#2C2C2C]' }}">
                        <i class="fa-solid fa-chart-line w-5 text-center {{ request()->routeIs('admin-sales.*') ? 'text-[#9B385B]' : 'text-gray-400' }}"></i>
                        <span>Dashboard Admin Sales</span>
                    </a>
                @endif

                @if(Auth::user()->role === 'admin_vas')
                    <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wider px-3 pt-4 pb-1">
                        Sistem &amp; Administrasi VAS
                    </div>

                    <a href="{{ route('vas.dashboard') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('vas.dashboard') ? 'bg-[#FEF4F0] text-[#9B385B] border border-[#9B385B]/40 shadow-xs' : 'text-gray-600 hover:bg-gray-100 hover:text-[#2C2C2C]' }}">
                        <i class="fa-solid fa-gauge-high w-5 text-center {{ request()->routeIs('vas.dashboard') ? 'text-[#9B385B]' : 'text-gray-400' }}"></i>
                        <span>Ringkasan Super Admin</span>
                    </a>

                    <a href="{{ route('vas.users') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('vas.users*') ? 'bg-[#FEF4F0] text-[#9B385B] border border-[#9B385B]/40 shadow-xs' : 'text-gray-600 hover:bg-gray-100 hover:text-[#2C2C2C]' }}">
                        <i class="fa-solid fa-users-gear w-5 text-center {{ request()->routeIs('vas.users*') ? 'text-[#9B385B]' : 'text-gray-400' }}"></i>
                        <span>Kelola Akun User</span>
                    </a>

                    <a href="{{ route('vas.audit-logs') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('vas.audit-logs*') ? 'bg-[#FEF4F0] text-[#9B385B] border border-[#9B385B]/40 shadow-xs' : 'text-gray-600 hover:bg-gray-100 hover:text-[#2C2C2C]' }}">
                        <i class="fa-solid fa-clock-rotate-left w-5 text-center {{ request()->routeIs('vas.audit-logs*') ? 'text-[#9B385B]' : 'text-gray-400' }}"></i>
                        <span>Audit Log Aktivitas</span>
                    </a>
                @endif

                <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wider px-3 pt-4 pb-1">
                    Simulator &amp; Integrasi
                </div>

                <a href="{{ route('mobile-simulator') }}" target="_blank"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#9B385B] bg-[#FEF4F0] border border-[#F48C5B]/40 hover:bg-[#FDE8E1] transition-all">
                    <i class="fa-solid fa-mobile-screen-button w-5 text-center text-[#F48C5B]"></i>
                    <span>Mobile Apps Simulator</span>
                    <i class="fa-solid fa-arrow-up-right-from-square ml-auto text-xs text-[#9B385B]/70"></i>
                </a>
            </nav>

            <!-- Logout Button -->
            <div class="p-4 border-t border-gray-100">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-bold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 rounded-xl transition-colors">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Keluar Akun</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-[#F8F9FA]">
            
            <!-- Top Navbar -->
            <header class="glass-header z-30 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = true" class="md:hidden text-gray-500 hover:text-[#2C2C2C] p-1">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    <div>
                        <h1 class="text-lg md:text-xl font-extrabold text-[#2C2C2C] tracking-tight">@yield('header_title', 'Dashboard')</h1>
                        <p class="text-xs text-gray-500 hidden sm:block">@yield('header_subtitle', 'Sistem Pendaftaran Calon Pelanggan LifeMedia')</p>
                    </div>
                </div>


            </header>

            <!-- Page Content Body -->
            <main class="flex-1 overflow-y-auto custom-scrollbar p-4 md:p-6 lg:p-8">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Modals and Scripts Stack -->
    @stack('modals')
    @stack('scripts')
</body>
</html>
