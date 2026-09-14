<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Berhasil Dikirim - LifeMedia Fiber</title>
    
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
</head>
<body class="min-h-full bg-[#F8F9FA] text-[#333333] flex items-center justify-center p-4 selection:bg-[#F48C5B] selection:text-white">

    <div class="max-w-xl w-full text-center space-y-6 my-8">
        
        <div class="flex justify-center">
            <div class="p-2.5 rounded-2xl bg-white border border-[#F0E8E4] shadow-sm inline-flex">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Life Media Logo" class="h-10 w-auto object-contain">
            </div>
        </div>

        <!-- Success Animation Icon -->
        <div class="w-16 h-16 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-600 flex items-center justify-center text-2xl mx-auto shadow-lg shadow-emerald-500/10">
            <i class="fa-solid fa-circle-check"></i>
        </div>

        <div class="space-y-2">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#2C2C2C] tracking-tight">Formulir Pendaftaran Berhasil Dikirim!</h1>
            <p class="text-xs sm:text-sm text-gray-600 max-w-md mx-auto leading-relaxed">
                Terima kasih, <strong>{{ $registration->customer_name }}</strong>. Data pendaftaran dan tanda tangan virtual Anda telah berhasil tersimpan di sistem LifeMedia.
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
                        Filled (Menunggu Review C-Care)
                    </span>
                </div>
            </div>

            <div class="p-4 rounded-2xl bg-[#FEF4F0] border border-[#F6D8CE] text-[11px] text-gray-600 space-y-1.5">
                <div class="font-bold text-[#9B385B] flex items-center gap-1.5">
                    <i class="fa-solid fa-clock text-amber-500"></i>
                    <span>Langkah Selanjutnya:</span>
                </div>
                <p class="leading-relaxed">
                    Tim Customer Care (C-Care) LifeMedia akan melakukan review berkas KTP dan persetujuan berlangganan. Anda akan menerima pesan konfirmasi WhatsApp dan informasi jadwal teknisi instalasi.
                </p>
            </div>
        </div>

        <div class="text-gray-400 text-[11px]">
            LifeMedia Fiber Internet &copy; 2026. Semua hak dilindungi.
        </div>

    </div>

</body>
</html>
