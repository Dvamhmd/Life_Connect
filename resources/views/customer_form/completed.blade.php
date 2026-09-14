<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Telah Disetujui - LifeMedia Fiber</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}?v=2">
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}?v=2">
    <link rel="apple-touch-icon" href="{{ asset('assets/images/favicon.png') }}?v=2">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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

    <div class="max-w-md w-full text-center space-y-6 my-8">
        
        <div class="flex justify-center">
            <div class="p-2.5 rounded-2xl bg-white border border-[#F0E8E4] shadow-sm inline-flex">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Life Media Logo" class="h-10 w-auto object-contain">
            </div>
        </div>

        <div class="w-16 h-16 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-600 flex items-center justify-center text-2xl mx-auto shadow-lg shadow-emerald-500/10">
            <i class="fa-solid fa-circle-check"></i>
        </div>

        <div class="space-y-2">
            <h1 class="text-xl font-extrabold text-[#2C2C2C]">Pendaftaran Anda Telah Disetujui!</h1>
            <p class="text-xs text-gray-600 leading-relaxed">
                Pengajuan nomor <strong>{{ $registration->registration_code }}</strong> atas nama <strong>{{ $registration->customer_name }}</strong> telah disetujui (Approved) oleh tim Customer Care.
            </p>
        </div>

        <div class="p-5 rounded-2xl bg-white border border-gray-200 text-left text-xs space-y-2.5 shadow-sm">
            <div class="flex justify-between">
                <span class="text-gray-500">Paket:</span>
                <span class="font-bold text-[#2C2C2C]">{{ $registration->package?->name }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Waktu Disetujui:</span>
                <span class="font-semibold text-emerald-700">{{ $registration->approved_at?->format('d M Y, H:i') }}</span>
            </div>
        </div>

        <p class="text-[11px] text-gray-500">
            Teknisi instalasi kami akan segera menghubungi Anda untuk pemasangan kabel drop fiber dan modem ONT di rumah Anda.
        </p>

    </div>

</body>
</html>
