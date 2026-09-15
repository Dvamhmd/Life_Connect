<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ ($isRevision ?? false) ? 'Revisi Data Formulir Berhasil Dikirim' : 'Pendaftaran Berhasil Dikirim' }} - LifeMedia Fiber</title>
    
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
        
        @keyframes naturalBounce {
            0%, 15%, 85%, 100% {
                transform: translateY(0) scale(1, 1);
            }
            18% {
                /* Anticipation */
                transform: translateY(2px) scale(1.12, 0.88);
            }
            30% {
                /* Launching upwards */
                transform: translateY(-22px) scale(0.92, 1.08);
            }
            42% {
                /* Apex (gravity slow point) */
                transform: translateY(-26px) scale(1, 1);
            }
            54% {
                /* Floor impact squash */
                transform: translateY(0) scale(1.18, 0.82);
            }
            64% {
                /* Second small rebound */
                transform: translateY(-10px) scale(0.96, 1.04);
            }
            74% {
                /* Second landing */
                transform: translateY(0) scale(1.06, 0.94);
            }
            80% {
                /* Subtle settle */
                transform: translateY(-2px) scale(0.99, 1.01);
            }
        }

        @keyframes naturalShadow {
            0%, 15%, 85%, 100% {
                transform: scale(1);
                opacity: 0.35;
            }
            18% {
                transform: scale(1.12);
                opacity: 0.45;
            }
            30% {
                transform: scale(0.7);
                opacity: 0.18;
            }
            42% {
                transform: scale(0.55);
                opacity: 0.12;
            }
            54% {
                transform: scale(1.2);
                opacity: 0.5;
            }
            64% {
                transform: scale(0.8);
                opacity: 0.22;
            }
            74% {
                transform: scale(1.06);
                opacity: 0.4;
            }
            80% {
                transform: scale(0.98);
                opacity: 0.33;
            }
        }

        .animate-tuing {
            animation: naturalBounce 2.2s cubic-bezier(0.25, 1, 0.5, 1) infinite;
            transform-origin: bottom center;
        }

        .animate-shadow {
            animation: naturalShadow 2.2s cubic-bezier(0.25, 1, 0.5, 1) infinite;
        }
    </style>
</head>
<body class="min-h-full bg-[#F8F9FA] text-[#333333] flex items-center justify-center p-4 selection:bg-[#F48C5B] selection:text-white">

    <div class="max-w-xl w-full text-center space-y-6 my-8">
        
        <div class="flex justify-center">
            <div class="p-2.5 rounded-2xl bg-white border border-[#F0E8E4] shadow-sm inline-flex">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Life Media Logo" class="h-10 w-auto object-contain">
            </div>
        </div>

        <!-- Success Animation Icon with Solid Green & Natural Bounce -->
        <div class="relative flex flex-col items-center justify-center mx-auto pt-2 pb-1">
            <div class="animate-tuing w-20 h-20 rounded-full bg-emerald-500 text-white flex items-center justify-center text-3xl sm:text-4xl shadow-lg shadow-emerald-500/25 border-4 border-emerald-100">
                <i class="fa-solid fa-check"></i>
            </div>
            <div class="animate-shadow w-14 h-2.5 bg-emerald-950/20 rounded-full blur-[2px] mt-2"></div>
        </div>

        <div class="space-y-2">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#2C2C2C] tracking-tight">
                {{ ($isRevision ?? false) ? 'Revisi Data Formulir Berhasil Dikirim!' : 'Formulir Pendaftaran Berhasil Dikirim!' }}
            </h1>
            <p class="text-xs sm:text-sm text-gray-600 max-w-md mx-auto leading-relaxed">
                @if($isRevision ?? false)
                    Terima kasih, <strong>{{ $registration->customer_name }}</strong>. Revisi Data telah berhasil terkirim di sistem LifeMedia.
                @else
                    Terima kasih, <strong>{{ $registration->customer_name }}</strong>. Data pendaftaran dan tanda tangan virtual Anda telah berhasil tersimpan di sistem LifeMedia.
                @endif
            </p>
        </div>

        <!-- Registration Summary Card -->
        <div class="p-6 sm:p-7 rounded-3xl bg-white border border-gray-200 text-left space-y-4 shadow-xl shadow-orange-500/5">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3 text-xs">
                <span class="text-gray-500 font-medium">Kode Registrasi:</span>
                <span class="font-mono font-bold text-[#F48C5B] bg-[#FEF4F0] px-3 py-1 rounded-lg border border-[#F48C5B]/30">{{ $registration->registration_code }}</span>
            </div>

            <div class="space-y-2.5 text-xs">
                <div class="flex justify-between">
                    <span class="text-gray-500">Paket Berlangganan:</span>
                    <span class="font-bold text-[#2C2C2C]">{{ $registration->package ? $registration->package->name : 'Life Fiber' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Tarif Bulanan:</span>
                    <span class="font-bold text-[#F48C5B]">{{ $registration->package ? $registration->package->formatted_price : '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Metode Tagihan:</span>
                    <span class="font-semibold text-gray-700">{{ $registration->billing_method }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Status Saat Ini:</span>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                        Filled
                    </span>
                </div>
            </div>

            <div class="p-4 rounded-2xl bg-[#FEF4F0] border border-[#F6D8CE] text-[11px] text-gray-600 space-y-1.5">
                <div class="font-bold text-[#9B385B] flex items-center gap-1.5">
                    <i class="fa-solid fa-clock text-amber-500"></i>
                    <span>Langkah Selanjutnya:</span>
                </div>
                <p class="leading-relaxed">
                    Kami akan melakukan review berkas. Silahkan tunggu pesan konfirmasi WhatsApp dari Sales kami.
                </p>
            </div>
        </div>

        <div class="text-gray-400 text-[11px]">
            LifeMedia Fiber Internet &copy; 2026. Semua hak dilindungi.
        </div>

    </div>

</body>
</html>
