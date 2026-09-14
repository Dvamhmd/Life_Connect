<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Pendaftaran Berlangganan - LifeMedia Fiber</title>
    
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
    <!-- Signature Pad CDN for ultra-smooth Bezier curves and high-sensitivity drawing -->
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@5.0.4/dist/signature_pad.umd.min.js"></script>
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
        .glass-card {
            background: #FFFFFF;
            border: 1px solid #E5E7EB;
            box-shadow: 0 4px 20px -2px rgba(155, 56, 91, 0.04);
        }
        .preview-img-container img {
            max-height: 180px;
            width: 100%;
            object-fit: cover;
            border-radius: 0.75rem;
        }
    </style>
</head>
<body class="min-h-full bg-[#F8F9FA] text-[#333333] antialiased flex flex-col selection:bg-[#F48C5B] selection:text-white pb-16">

    <!-- Top Brand Header -->
    <header class="sticky top-0 z-30 bg-white/95 backdrop-blur-xl border-b border-gray-200/90 shadow-xs">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Life Media Logo" class="h-9 w-auto object-contain">
                <div class="border-l border-gray-200 pl-3">
                    <div class="font-extrabold text-sm sm:text-base text-[#2C2C2C] flex items-center gap-1.5">
                        Life Connect
                        <span class="text-[10px] font-bold text-emerald-800 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200">Coverage Verified</span>
                    </div>
                    <div class="text-[10px] sm:text-[11px] text-gray-500">Pendaftaran Berlangganan Internet Fiber LifeMedia</div>
                </div>
            </div>

            <div class="text-right">
                <div class="text-[10px] text-gray-400 font-mono">KODE PENGAJUAN:</div>
                <div class="text-xs font-bold font-mono text-[#F48C5B] bg-[#FEF4F0] px-2 py-0.5 rounded border border-[#F48C5B]/30">{{ $registration->registration_code }}</div>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="flex-1 max-w-4xl mx-auto w-full px-4 sm:px-6 py-8 space-y-8">
        
        <!-- Welcome & Status Banner -->
        <div class="p-6 sm:p-7 rounded-3xl bg-white border border-[#F6D8CE] shadow-sm relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-[#FEF4F0] rounded-full blur-2xl pointer-events-none"></div>
            <div class="relative z-10 space-y-2.5">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold">
                    <i class="fa-solid fa-circle-check text-emerald-600"></i>
                    <span>Lokasi Anda Telah Masuk Coverage Area LifeMedia</span>
                </div>
                <h2 class="text-xl sm:text-2xl font-extrabold text-[#2C2C2C]">
                    Halo, {{ $registration->customer_name }}! 👋
                </h2>
                <p class="text-xs sm:text-sm text-gray-600 leading-relaxed max-w-2xl">
                    Tim teknis kami (OPJ) telah memverifikasi lokasi tempat tinggal Anda di <strong class="text-[#2C2C2C]">{{ $registration->village }}, {{ $registration->regency }}</strong>. Silakan lengkapi formulir pendaftaran berlangganan di bawah ini.
                </p>

                @if($registration->status === 'revision' && $registration->rejection_notes)
                    <div class="mt-4 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1">
                        <div class="font-bold flex items-center gap-1.5 text-rose-700">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            <span>Catatan Perbaikan Dokumen dari Tim Customer Care:</span>
                        </div>
                        <p class="leading-relaxed">{{ $registration->rejection_notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        @if($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs">
                <div class="font-bold mb-1 flex items-center gap-1.5 text-rose-700">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span>Terdapat beberapa data yang belum lengkap:</span>
                </div>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Public Registration Multi-Step Form -->
        <form action="{{ route('customer-form.submit', $registration->token) }}" method="POST" enctype="multipart/form-data" id="customerRegistrationForm" class="space-y-8">
            @csrf

            <!-- STEP 1: DATA PRIBADI -->
            <div class="glass-card rounded-3xl p-6 sm:p-8 space-y-6">
                <div class="flex items-center gap-3 border-b border-gray-100 pb-4">
                    <div class="w-9 h-9 rounded-xl bg-[#FEF4F0] border border-[#F48C5B]/40 text-[#F48C5B] flex items-center justify-center font-extrabold text-sm shadow-xs">
                        1
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-[#2C2C2C]">Data Pribadi</h3>
                        <p class="text-xs text-gray-500">Informasi identitas pelanggan dan lokasi pemasangan</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    
                    <!-- Nama Lengkap (Editable) -->
                    <div>
                        <label for="customer_name" class="block font-bold text-[#333333] mb-1.5">
                            Nama Lengkap <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="customer_name" id="customer_name" required
                               value="{{ old('customer_name', $registration->customer_name) }}"
                               placeholder="Nama lengkap sesuai tanda pengenal..."
                               class="w-full px-4 py-2.5 rounded-xl bg-white border border-gray-300 text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#F48C5B] focus:border-[#F48C5B] font-medium">
                        <span class="text-[10px] text-gray-400 mt-1 block">Dapat diedit jika terdapat perubahan/koreksi ejaan</span>
                    </div>

                    <!-- Nama Brand -->
                    <div>
                        <label for="brand_name" class="block font-bold text-[#333333] mb-1.5">
                            Nama Brand / Usaha <span class="text-gray-400 font-normal">(Opsional)</span>
                        </label>
                        <input type="text" name="brand_name" id="brand_name"
                               value="{{ old('brand_name', $registration->brand_name) }}"
                               placeholder="Contoh: PT Surya Pratama / Kedai Kopi / Brand..."
                               class="w-full px-4 py-2.5 rounded-xl bg-white border border-gray-300 text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#F48C5B] focus:border-[#F48C5B]">
                    </div>

                    <!-- Checklist Tanda Pengenal (KTP, SIM, Paspor) -->
                    <div>
                        <label class="block font-bold text-[#333333] mb-1.5">
                            Jenis Tanda Pengenal <span class="text-rose-500">*</span>
                        </label>
                        @php
                            $selectedIdType = old('identity_type', $registration->identity_type ?: 'KTP');
                        @endphp
                        <div class="grid grid-cols-3 gap-2">
                            <label class="flex items-center justify-center gap-1.5 p-2.5 rounded-xl border cursor-pointer transition-all {{ $selectedIdType == 'KTP' ? 'bg-[#FEF4F0] border-[#F48C5B] text-[#9B385B] font-bold' : 'bg-gray-50 border-gray-200 text-gray-600 hover:bg-gray-100' }}">
                                <input type="radio" name="identity_type" value="KTP" {{ $selectedIdType == 'KTP' ? 'checked' : '' }} class="sr-only" onchange="updateIdTypeSelection(this)">
                                <i class="fa-solid fa-id-card text-xs"></i>
                                <span>KTP</span>
                            </label>
                            <label class="flex items-center justify-center gap-1.5 p-2.5 rounded-xl border cursor-pointer transition-all {{ $selectedIdType == 'SIM' ? 'bg-[#FEF4F0] border-[#F48C5B] text-[#9B385B] font-bold' : 'bg-gray-50 border-gray-200 text-gray-600 hover:bg-gray-100' }}">
                                <input type="radio" name="identity_type" value="SIM" {{ $selectedIdType == 'SIM' ? 'checked' : '' }} class="sr-only" onchange="updateIdTypeSelection(this)">
                                <i class="fa-solid fa-address-card text-xs"></i>
                                <span>SIM</span>
                            </label>
                            <label class="flex items-center justify-center gap-1.5 p-2.5 rounded-xl border cursor-pointer transition-all {{ $selectedIdType == 'Paspor' ? 'bg-[#FEF4F0] border-[#F48C5B] text-[#9B385B] font-bold' : 'bg-gray-50 border-gray-200 text-gray-600 hover:bg-gray-100' }}">
                                <input type="radio" name="identity_type" value="Paspor" {{ $selectedIdType == 'Paspor' ? 'checked' : '' }} class="sr-only" onchange="updateIdTypeSelection(this)">
                                <i class="fa-solid fa-passport text-xs"></i>
                                <span>Paspor</span>
                            </label>
                        </div>
                    </div>

                    <!-- Nomor Tanda Pengenal -->
                    <div>
                        <label for="identity_number" class="block font-bold text-[#333333] mb-1.5">
                            Nomor Tanda Pengenal <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="identity_number" id="identity_number" required
                               value="{{ old('identity_number', $registration->identity_number ?: $registration->nik) }}"
                               placeholder="Nomor KTP / SIM / Paspor..."
                               class="w-full px-4 py-2.5 rounded-xl bg-white border border-gray-300 text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#F48C5B] focus:border-[#F48C5B] font-mono">
                    </div>

                    <!-- Tanggal Lahir (dd/mm/yyyy) -->
                    <div>
                        <label for="birth_date" class="block font-bold text-[#333333] mb-1.5">
                            Tanggal Lahir (dd/mm/yyyy) <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" name="birth_date" id="birth_date" required
                               value="{{ old('birth_date', $registration->birth_date?->format('Y-m-d')) }}"
                               class="w-full px-4 py-2.5 rounded-xl bg-white border border-gray-300 text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#F48C5B] focus:border-[#F48C5B]">
                        <span class="text-[10px] text-gray-400 mt-1 block">Format: Hari / Bulan / Tahun</span>
                    </div>

                    <!-- Checklist Jenis Kelamin (P/W) -->
                    <div>
                        <label class="block font-bold text-[#333333] mb-1.5">
                            Jenis Kelamin (P/W) <span class="text-rose-500">*</span>
                        </label>
                        @php
                            $genderVal = old('gender', in_array($registration->gender, ['P', 'Laki-laki']) ? 'P' : (in_array($registration->gender, ['W', 'Perempuan']) ? 'W' : 'P'));
                        @endphp
                        <div class="grid grid-cols-2 gap-3">
                            <label class="flex items-center justify-center gap-2 p-2.5 rounded-xl border cursor-pointer transition-all {{ $genderVal == 'P' ? 'bg-[#FEF4F0] border-[#F48C5B] text-[#9B385B] font-bold' : 'bg-gray-50 border-gray-200 text-gray-600 hover:bg-gray-100' }}">
                                <input type="radio" name="gender" value="P" {{ $genderVal == 'P' ? 'checked' : '' }} class="sr-only" onchange="updateGenderSelection(this)">
                                <i class="fa-solid fa-mars text-blue-500 text-sm"></i>
                                <span>P (Pria)</span>
                            </label>
                            <label class="flex items-center justify-center gap-2 p-2.5 rounded-xl border cursor-pointer transition-all {{ $genderVal == 'W' ? 'bg-[#FEF4F0] border-[#F48C5B] text-[#9B385B] font-bold' : 'bg-gray-50 border-gray-200 text-gray-600 hover:bg-gray-100' }}">
                                <input type="radio" name="gender" value="W" {{ $genderVal == 'W' ? 'checked' : '' }} class="sr-only" onchange="updateGenderSelection(this)">
                                <i class="fa-solid fa-venus text-pink-500 text-sm"></i>
                                <span>W (Wanita)</span>
                            </label>
                        </div>
                    </div>

                    <!-- No Telepon (Fixed Line / Telp Rumah) -->
                    <div>
                        <label for="phone_telp" class="block font-bold text-[#333333] mb-1.5">
                            No. Telepon Rumah / Kantor <span class="text-gray-400 font-normal">(Opsional)</span>
                        </label>
                        <input type="text" name="phone_telp" id="phone_telp"
                               value="{{ old('phone_telp', $registration->phone_telp) }}"
                               placeholder="Contoh: 0274-123456"
                               class="w-full px-4 py-2.5 rounded-xl bg-white border border-gray-300 text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#F48C5B] focus:border-[#F48C5B]">
                    </div>

                    <!-- No HP (Editable) -->
                    <div>
                        <label for="phone_wa" class="block font-bold text-[#333333] mb-1.5">
                            No. HP / WhatsApp <span class="text-rose-500">*</span>
                        </label>
                        <input type="tel" name="phone_wa" id="phone_wa" required
                               value="{{ old('phone_wa', $registration->phone_wa) }}"
                               placeholder="Contoh: 081234567890"
                               class="w-full px-4 py-2.5 rounded-xl bg-white border border-gray-300 text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#F48C5B] focus:border-[#F48C5B]">
                        <span class="text-[10px] text-gray-400 mt-1 block">Nomor aktif untuk informasi instalasi dan tagihan</span>
                    </div>

                    <!-- Email (Editable) -->
                    <div class="sm:col-span-2">
                        <label for="email" class="block font-bold text-[#333333] mb-1.5">
                            Email <span class="text-rose-500">*</span>
                        </label>
                        <input type="email" name="email" id="email" required
                               value="{{ old('email', $registration->email) }}"
                               placeholder="nama.anda@gmail.com"
                               class="w-full px-4 py-2.5 rounded-xl bg-white border border-gray-300 text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#F48C5B] focus:border-[#F48C5B]">
                    </div>

                    <!-- Alamat Pemasangan (Editable) -->
                    <div class="sm:col-span-2 space-y-2">
                        <label for="address_detail" class="block font-bold text-[#333333]">
                            Alamat Pemasangan <span class="text-rose-500">*</span>
                        </label>
                        <textarea name="address_detail" id="address_detail" rows="2" required
                                  placeholder="Alamat lengkap lokasi pemasangan (Jalan, No. Rumah, RT/RW)..."
                                  class="w-full px-4 py-2.5 rounded-xl bg-white border border-gray-300 text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#F48C5B] focus:border-[#F48C5B] leading-relaxed">{{ old('address_detail', $registration->address_detail ?: $registration->full_address) }}</textarea>
                        
                        <!-- Coverage Area Badge -->
                        <div class="p-3 rounded-xl bg-[#FEF4F0] border border-[#F6D8CE] text-[11px] flex items-center justify-between">
                            <span class="text-[#9B385B] font-semibold">
                                <i class="fa-solid fa-location-dot mr-1"></i> Area Terverifikasi: {{ $registration->village }}, Kec. {{ $registration->district }}, {{ $registration->regency }}
                            </span>
                            <span class="text-emerald-700 font-bold">Tercover</span>
                        </div>
                    </div>

                </div>
            </div>

            <!-- STEP 2: PAKET BERLANGGANAN -->
            <div class="glass-card rounded-3xl p-6 sm:p-8 space-y-6">
                <div class="flex items-center gap-3 border-b border-gray-100 pb-4">
                    <div class="w-9 h-9 rounded-xl bg-[#FEF4F0] border border-[#F48C5B]/40 text-[#F48C5B] flex items-center justify-center font-extrabold text-sm shadow-xs">
                        2
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-[#2C2C2C]">Paket Berlangganan</h3>
                        <p class="text-xs text-gray-500">Pilih layanan yang ingin Anda nikmati beserta catatan dan jangka waktu berlangganan</p>
                    </div>
                </div>

                @php
                    $services = $registration->services_selected ?? [];
                    $tvOpt1 = old('services.tv_kabel.opt1', $services['tv_kabel']['opt1'] ?? false);
                    $tvText1 = old('services.tv_kabel.text1', $services['tv_kabel']['text1'] ?? '');
                    $tvOpt2 = old('services.tv_kabel.opt2', $services['tv_kabel']['opt2'] ?? false);
                    $tvText2 = old('services.tv_kabel.text2', $services['tv_kabel']['text2'] ?? '');

                    $netOpt1 = old('services.internet.opt1', $services['internet']['opt1'] ?? false);
                    $netText1 = old('services.internet.text1', $services['internet']['text1'] ?? '');
                    $netOpt2 = old('services.internet.opt2', $services['internet']['opt2'] ?? false);
                    $netText2 = old('services.internet.text2', $services['internet']['text2'] ?? '');

                    $telOpt1 = old('services.telepon.opt1', $services['telepon']['opt1'] ?? false);
                    $telText1 = old('services.telepon.text1', $services['telepon']['text1'] ?? '');
                    $telOpt2 = old('services.telepon.opt2', $services['telepon']['opt2'] ?? false);
                    $telText2 = old('services.telepon.text2', $services['telepon']['text2'] ?? '');
                @endphp

                <!-- Service Checklist Matrix (Vertical Checkboxes + Textfields) -->
                <div class="space-y-4">
                    <label class="block text-xs font-bold text-[#2C2C2C]">Pilihan Layanan:</label>

                    <!-- 1. TV KABEL -->
                    <div class="p-4 rounded-2xl border border-gray-200 bg-white hover:border-[#F48C5B]/50 transition-all space-y-3">
                        <div class="flex items-center gap-2.5 border-b border-gray-100 pb-2">
                            <div class="w-7 h-7 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center font-bold text-xs">
                                <i class="fa-solid fa-tv"></i>
                            </div>
                            <span class="font-extrabold text-xs text-[#2C2C2C] tracking-wide uppercase">TV KABEL</span>
                        </div>

                        <div class="space-y-2.5">
                            <!-- Checkbox 1 + Textfield -->
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="services[tv_kabel][opt1]" value="1" {{ $tvOpt1 ? 'checked' : '' }}
                                       class="w-5 h-5 rounded text-[#F48C5B] focus:ring-[#F48C5B] border-gray-300 cursor-pointer">
                                <input type="text" name="services[tv_kabel][text1]" value="{{ $tvText1 }}"
                                       placeholder="" autocomplete="off"
                                       class="flex-1 px-3.5 py-2 rounded-xl bg-gray-50 border border-gray-200 text-xs text-[#2C2C2C] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#F48C5B] focus:border-[#F48C5B]">
                            </div>

                            <!-- Checkbox 2 + Textfield -->
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="services[tv_kabel][opt2]" value="1" {{ $tvOpt2 ? 'checked' : '' }}
                                       class="w-5 h-5 rounded text-[#F48C5B] focus:ring-[#F48C5B] border-gray-300 cursor-pointer">
                                <input type="text" name="services[tv_kabel][text2]" value="{{ $tvText2 }}"
                                       placeholder="" autocomplete="off"
                                       class="flex-1 px-3.5 py-2 rounded-xl bg-gray-50 border border-gray-200 text-xs text-[#2C2C2C] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#F48C5B] focus:border-[#F48C5B]">
                            </div>
                        </div>
                    </div>

                    <!-- 2. INTERNET -->
                    <div class="p-4 rounded-2xl border border-gray-200 bg-white hover:border-[#F48C5B]/50 transition-all space-y-3">
                        <div class="flex items-center gap-2.5 border-b border-gray-100 pb-2">
                            <div class="w-7 h-7 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs">
                                <i class="fa-solid fa-wifi"></i>
                            </div>
                            <span class="font-extrabold text-xs text-[#2C2C2C] tracking-wide uppercase">INTERNET</span>
                        </div>

                        <div class="space-y-2.5">
                            <!-- Checkbox 1 + Textfield -->
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="services[internet][opt1]" value="1" {{ $netOpt1 ? 'checked' : '' }}
                                       class="w-5 h-5 rounded text-[#F48C5B] focus:ring-[#F48C5B] border-gray-300 cursor-pointer">
                                <input type="text" name="services[internet][text1]" value="{{ $netText1 }}"
                                       placeholder="" autocomplete="off"
                                       class="flex-1 px-3.5 py-2 rounded-xl bg-gray-50 border border-gray-200 text-xs text-[#2C2C2C] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#F48C5B] focus:border-[#F48C5B]">
                            </div>

                            <!-- Checkbox 2 + Textfield -->
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="services[internet][opt2]" value="1" {{ $netOpt2 ? 'checked' : '' }}
                                       class="w-5 h-5 rounded text-[#F48C5B] focus:ring-[#F48C5B] border-gray-300 cursor-pointer">
                                <input type="text" name="services[internet][text2]" value="{{ $netText2 }}"
                                       placeholder="" autocomplete="off"
                                       class="flex-1 px-3.5 py-2 rounded-xl bg-gray-50 border border-gray-200 text-xs text-[#2C2C2C] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#F48C5B] focus:border-[#F48C5B]">
                            </div>
                        </div>
                    </div>

                    <!-- 3. TELEPON -->
                    <div class="p-4 rounded-2xl border border-gray-200 bg-white hover:border-[#F48C5B]/50 transition-all space-y-3">
                        <div class="flex items-center gap-2.5 border-b border-gray-100 pb-2">
                            <div class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-xs">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                            <span class="font-extrabold text-xs text-[#2C2C2C] tracking-wide uppercase">TELEPON</span>
                        </div>

                        <div class="space-y-2.5">
                            <!-- Checkbox 1 + Textfield -->
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="services[telepon][opt1]" value="1" {{ $telOpt1 ? 'checked' : '' }}
                                       class="w-5 h-5 rounded text-[#F48C5B] focus:ring-[#F48C5B] border-gray-300 cursor-pointer">
                                <input type="text" name="services[telepon][text1]" value="{{ $telText1 }}"
                                       placeholder="" autocomplete="off"
                                       class="flex-1 px-3.5 py-2 rounded-xl bg-gray-50 border border-gray-200 text-xs text-[#2C2C2C] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#F48C5B] focus:border-[#F48C5B]">
                            </div>

                            <!-- Checkbox 2 + Textfield -->
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="services[telepon][opt2]" value="1" {{ $telOpt2 ? 'checked' : '' }}
                                       class="w-5 h-5 rounded text-[#F48C5B] focus:ring-[#F48C5B] border-gray-300 cursor-pointer">
                                <input type="text" name="services[telepon][text2]" value="{{ $telText2 }}"
                                       placeholder="" autocomplete="off"
                                       class="flex-1 px-3.5 py-2 rounded-xl bg-gray-50 border border-gray-200 text-xs text-[#2C2C2C] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#F48C5B] focus:border-[#F48C5B]">
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Textfield Jangka Waktu Berlangganan -->
                <div class="pt-2">
                    <label for="subscription_period" class="block font-bold text-xs text-[#333333] mb-1.5">
                        Jangka Waktu Berlangganan <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative w-full sm:w-64">
                        <input type="number" name="subscription_period" id="subscription_period" required min="1" step="1"
                               value="{{ old('subscription_period', preg_replace('/[^0-9]/', '', (string)($registration->subscription_period ?: '12'))) }}"
                               placeholder="12"
                               class="w-full pl-4 pr-18 py-2.5 rounded-xl bg-white border border-gray-300 text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#F48C5B] focus:border-[#F48C5B] text-xs font-semibold [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                        <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none">
                            <span class="text-xs font-bold text-gray-500 bg-gray-100 px-2.5 py-1 rounded-lg border border-gray-200">
                                Bulan
                            </span>
                        </div>
                    </div>
                    <span class="text-[10px] text-gray-400 mt-1 block">Tentukan durasi komitmen masa berlangganan layanan (dalam bulan)</span>
                </div>



            </div>

            <!-- STEP 3: INFORMASI PENAGIHAN -->
            <div class="glass-card rounded-3xl p-6 sm:p-8 space-y-6">
                <div class="flex items-center gap-3 border-b border-gray-100 pb-4">
                    <div class="w-9 h-9 rounded-xl bg-[#FEF4F0] border border-[#F48C5B]/40 text-[#F48C5B] flex items-center justify-center font-extrabold text-sm shadow-xs">
                        3
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-[#2C2C2C]">Informasi Penagihan</h3>
                        <p class="text-xs text-gray-500">Detail penerima invoice tagihan bulanan dan kontak konfirmasi</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    
                    <!-- Nama Penerima -->
                    <div>
                        <label for="billing_name" class="block font-bold text-[#333333] mb-1.5">
                            Nama Penerima <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="billing_name" id="billing_name" required
                               value="{{ old('billing_name', $registration->billing_name ?: $registration->customer_name) }}"
                               placeholder="Nama penerima / penanggung jawab tagihan..."
                               class="w-full px-4 py-2.5 rounded-xl bg-white border border-gray-300 text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#F48C5B] focus:border-[#F48C5B] font-medium">
                    </div>

                    <!-- Email Penagihan -->
                    <div>
                        <label for="billing_email" class="block font-bold text-[#333333] mb-1.5">
                            Email Penagihan (e-Invoice) <span class="text-rose-500">*</span>
                        </label>
                        <input type="email" name="billing_email" id="billing_email" required
                               value="{{ old('billing_email', $registration->billing_email ?: $registration->email) }}"
                               placeholder="email.penagihan@gmail.com"
                               class="w-full px-4 py-2.5 rounded-xl bg-white border border-gray-300 text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#F48C5B] focus:border-[#F48C5B]">
                    </div>

                    <!-- No Telepon Penagihan -->
                    <div>
                        <label for="billing_phone" class="block font-bold text-[#333333] mb-1.5">
                            No. Telepon <span class="text-gray-400 font-normal">(Opsional)</span>
                        </label>
                        <input type="text" name="billing_phone" id="billing_phone"
                               value="{{ old('billing_phone', $registration->billing_phone ?: $registration->phone_telp) }}"
                               placeholder="Contoh: 0274-123456"
                               class="w-full px-4 py-2.5 rounded-xl bg-white border border-gray-300 text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#F48C5B] focus:border-[#F48C5B]">
                    </div>

                    <!-- No HP Penagihan -->
                    <div>
                        <label for="billing_mobile" class="block font-bold text-[#333333] mb-1.5">
                            No. HP Penagihan <span class="text-rose-500">*</span>
                        </label>
                        <input type="tel" name="billing_mobile" id="billing_mobile" required
                               value="{{ old('billing_mobile', $registration->billing_mobile ?: $registration->phone_wa) }}"
                               placeholder="Contoh: 081234567890"
                               class="w-full px-4 py-2.5 rounded-xl bg-white border border-gray-300 text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#F48C5B] focus:border-[#F48C5B]">
                    </div>

                    <!-- Alamat Penagihan -->
                    <div class="sm:col-span-2">
                        <label for="billing_address" class="block font-bold text-[#333333] mb-1.5">
                            Alamat Penagihan <span class="text-rose-500">*</span>
                        </label>
                        <textarea name="billing_address" id="billing_address" rows="2" required
                                  placeholder="Alamat lengkap tujuan pengiriman invoice / surat penagihan..."
                                  class="w-full px-4 py-2.5 rounded-xl bg-white border border-gray-300 text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#F48C5B] focus:border-[#F48C5B] leading-relaxed">{{ old('billing_address', $registration->billing_address ?: ($registration->address_detail ?: $registration->full_address)) }}</textarea>
                    </div>

                </div>
            </div>

            <!-- STEP 4: KELENGKAPAN DOKUMEN FOTO (PREVIEW ENABLED) -->
            <div class="glass-card rounded-3xl p-6 sm:p-8 space-y-6">
                <div class="flex items-center gap-3 border-b border-gray-100 pb-4">
                    <div class="w-9 h-9 rounded-xl bg-[#FEF4F0] border border-[#F48C5B]/40 text-[#F48C5B] flex items-center justify-center font-extrabold text-sm shadow-xs">
                        4
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-[#2C2C2C]">Kelengkapan Dokumen Foto</h3>
                        <p class="text-xs text-gray-500">Unggah foto fisik dokumen untuk verifikasi keabsahan data registrasi</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 text-xs">
                    
                    <!-- 1. Foto KTP Asli -->
                    <div class="space-y-2">
                        <label class="block font-bold text-[#333333]">
                            Foto KTP Asli <span class="text-rose-500">*</span>
                        </label>

                        <div class="relative group border-2 border-dashed border-gray-300 hover:border-[#F48C5B] hover:bg-[#FEF4F0]/20 rounded-2xl p-4 bg-[#F8F9FA] transition-all text-center min-h-[190px] flex flex-col items-center justify-center">
                            <input type="file" name="ktp_photo" id="ktp_photo" accept="image/*"
                                   {{ $registration->ktp_photo_path ? '' : 'required' }}
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                   onchange="handleImagePreview(this, 'ktpPreviewBox', 'ktpPlaceholder')">
                            
                            <!-- Preview Box -->
                            <div id="ktpPreviewBox" class="w-full flex flex-col items-center justify-center {{ $registration->ktp_photo_path ? '' : 'hidden' }}">
                                <div class="relative w-full rounded-xl overflow-hidden shadow-xs border border-gray-200 bg-white p-1">
                                    <img id="ktpPreviewImg" src="{{ $registration->ktp_photo_path ? asset($registration->ktp_photo_path) : '' }}"
                                         alt="Preview Foto KTP" class="w-full h-36 object-contain rounded-lg"
                                         onerror="handleImgError(this, 'ktpPreviewBox', 'ktpPlaceholder')">
                                </div>
                                <span class="inline-flex items-center gap-1 text-[11px] text-[#F48C5B] font-bold mt-2">
                                    <i class="fa-solid fa-arrows-rotate"></i> Klik / seret untuk ganti foto
                                </span>
                            </div>

                            <!-- Upload Placeholder Icon -->
                            <div id="ktpPlaceholder" class="space-y-2.5 py-2 {{ $registration->ktp_photo_path ? 'hidden' : '' }}">
                                <div class="w-14 h-14 mx-auto rounded-2xl bg-[#FEF4F0] border border-[#F48C5B]/30 text-[#F48C5B] flex items-center justify-center text-2xl shadow-xs group-hover:scale-110 transition-transform duration-200">
                                    <i class="fa-solid fa-cloud-arrow-up"></i>
                                </div>
                                <div>
                                    <span class="font-extrabold text-[#2C2C2C] block text-xs group-hover:text-[#F48C5B] transition-colors">Unggah Foto KTP</span>
                                    <span class="text-[10px] text-gray-400 block mt-0.5">Klik atau seret file ke sini</span>
                                    <span class="text-[9px] text-[#F48C5B] font-semibold flex items-center justify-center gap-1 mt-0.5"><i class="fa-solid fa-bolt"></i> Auto WebP & Kompres</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Foto Rumah / Bangunan -->
                    <div class="space-y-2">
                        <label class="block font-bold text-[#333333]">
                            Foto Rumah / Bangunan <span class="text-rose-500">*</span>
                        </label>

                        <div class="relative group border-2 border-dashed border-gray-300 hover:border-[#F48C5B] hover:bg-[#FEF4F0]/20 rounded-2xl p-4 bg-[#F8F9FA] transition-all text-center min-h-[190px] flex flex-col items-center justify-center">
                            <input type="file" name="house_photo" id="house_photo" accept="image/*"
                                   {{ $registration->house_photo_path ? '' : 'required' }}
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                   onchange="handleImagePreview(this, 'housePreviewBox', 'housePlaceholder')">
                            
                            <!-- Preview Box -->
                            <div id="housePreviewBox" class="w-full flex flex-col items-center justify-center {{ $registration->house_photo_path ? '' : 'hidden' }}">
                                <div class="relative w-full rounded-xl overflow-hidden shadow-xs border border-gray-200 bg-white p-1">
                                    <img id="housePreviewImg" src="{{ $registration->house_photo_path ? asset($registration->house_photo_path) : '' }}"
                                         alt="Preview Foto Rumah" class="w-full h-36 object-contain rounded-lg"
                                         onerror="handleImgError(this, 'housePreviewBox', 'housePlaceholder')">
                                </div>
                                <span class="inline-flex items-center gap-1 text-[11px] text-[#F48C5B] font-bold mt-2">
                                    <i class="fa-solid fa-arrows-rotate"></i> Klik / seret untuk ganti foto
                                </span>
                            </div>

                            <!-- Upload Placeholder Icon -->
                            <div id="housePlaceholder" class="space-y-2.5 py-2 {{ $registration->house_photo_path ? 'hidden' : '' }}">
                                <div class="w-14 h-14 mx-auto rounded-2xl bg-[#FEF4F0] border border-[#F48C5B]/30 text-[#F48C5B] flex items-center justify-center text-2xl shadow-xs group-hover:scale-110 transition-transform duration-200">
                                    <i class="fa-solid fa-cloud-arrow-up"></i>
                                </div>
                                <div>
                                    <span class="font-extrabold text-[#2C2C2C] block text-xs group-hover:text-[#F48C5B] transition-colors">Unggah Foto Rumah</span>
                                    <span class="text-[10px] text-gray-400 block mt-0.5">Tampak depan bangunan</span>
                                    <span class="text-[9px] text-[#F48C5B] font-semibold flex items-center justify-center gap-1 mt-0.5"><i class="fa-solid fa-bolt"></i> Auto WebP & Kompres</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Foto Selfie Bersama Sales -->
                    <div class="space-y-2">
                        <label class="block font-bold text-[#333333]">
                            Foto Selfie Bersama Sales <span class="text-rose-500">*</span>
                        </label>

                        <div class="relative group border-2 border-dashed border-gray-300 hover:border-[#F48C5B] hover:bg-[#FEF4F0]/20 rounded-2xl p-4 bg-[#F8F9FA] transition-all text-center min-h-[190px] flex flex-col items-center justify-center">
                            <input type="file" name="selfie_sales_photo" id="selfie_sales_photo" accept="image/*"
                                   {{ $registration->selfie_sales_path ? '' : 'required' }}
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                   onchange="handleImagePreview(this, 'selfiePreviewBox', 'selfiePlaceholder')">
                            
                            <!-- Preview Box -->
                            <div id="selfiePreviewBox" class="w-full flex flex-col items-center justify-center {{ $registration->selfie_sales_path ? '' : 'hidden' }}">
                                <div class="relative w-full rounded-xl overflow-hidden shadow-xs border border-gray-200 bg-white p-1">
                                    <img id="selfiePreviewImg" src="{{ $registration->selfie_sales_path ? asset($registration->selfie_sales_path) : '' }}"
                                         alt="Preview Selfie Sales" class="w-full h-36 object-contain rounded-lg"
                                         onerror="handleImgError(this, 'selfiePreviewBox', 'selfiePlaceholder')">
                                </div>
                                <span class="inline-flex items-center gap-1 text-[11px] text-[#F48C5B] font-bold mt-2">
                                    <i class="fa-solid fa-arrows-rotate"></i> Klik / seret untuk ganti foto
                                </span>
                            </div>

                            <!-- Upload Placeholder Icon -->
                            <div id="selfiePlaceholder" class="space-y-2.5 py-2 {{ $registration->selfie_sales_path ? 'hidden' : '' }}">
                                <div class="w-14 h-14 mx-auto rounded-2xl bg-[#FEF4F0] border border-[#F48C5B]/30 text-[#F48C5B] flex items-center justify-center text-2xl shadow-xs group-hover:scale-110 transition-transform duration-200">
                                    <i class="fa-solid fa-cloud-arrow-up"></i>
                                </div>
                                <div>
                                    <span class="font-extrabold text-[#2C2C2C] block text-xs group-hover:text-[#F48C5B] transition-colors">Unggah Foto Selfie</span>
                                    <span class="text-[10px] text-gray-400 block mt-0.5">Bersama Petugas Sales</span>
                                    <span class="text-[9px] text-[#F48C5B] font-semibold flex items-center justify-center gap-1 mt-0.5"><i class="fa-solid fa-bolt"></i> Auto WebP & Kompres</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- STEP 5: PERSETUJUAN BERLANGGANAN & TANDA TANGAN VIRTUAL -->
            <div class="glass-card rounded-3xl p-6 sm:p-8 space-y-6">
                <div class="flex items-center gap-3 border-b border-gray-100 pb-4">
                    <div class="w-9 h-9 rounded-xl bg-[#FEF4F0] border border-[#F48C5B]/40 text-[#F48C5B] flex items-center justify-center font-extrabold text-sm shadow-xs">
                        5
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-[#2C2C2C]">Persetujuan Berlangganan &amp; Tanda Tangan</h3>
                        <p class="text-xs text-gray-500">Bubuhkan tanda tangan Anda secara virtual pada area canvas di bawah</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <!-- Virtual Signature Pad Canvas Container -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5 text-xs">
                            <span class="font-bold text-[#333333]">Canvas Tanda Tangan Digital:</span>
                            <button type="button" onclick="clearSignature()" class="text-rose-600 hover:text-rose-700 text-xs font-bold flex items-center gap-1 transition-colors">
                                <i class="fa-solid fa-rotate-left"></i> Bersihkan / Ulangi TTD
                            </button>
                        </div>
                        
                        <div class="rounded-2xl border-2 border-dashed border-gray-300 bg-white p-2 overflow-hidden shadow-inner relative">
                            <canvas id="signaturePad" class="w-full h-44 cursor-crosshair bg-white rounded-xl touch-none block"></canvas>
                            <input type="hidden" name="signature_data" id="signatureData">
                            
                            <div id="signatureGuideText" class="absolute bottom-3 right-4 text-[10px] text-gray-400 pointer-events-none transition-opacity duration-200">
                                <i class="fa-solid fa-pen-nib mr-1 text-[#F48C5B]"></i> Gunakan jari / stylus / mouse untuk menandatangani
                            </div>
                        </div>
                    </div>

                    <!-- Terms Checkbox -->
                    <div class="pt-2">
                        <label class="flex items-start gap-3 cursor-pointer text-xs text-gray-700 leading-relaxed">
                            <input type="checkbox" name="terms_agreed" value="1" required
                                   class="w-4 h-4 rounded bg-white border-gray-300 text-[#F48C5B] focus:ring-[#F48C5B] mt-0.5">
                            <span>
                                Saya menyatakan bahwa seluruh data yang saya isikan adalah benar dan valid. Saya menyetujui seluruh <strong>Syarat &amp; Ketentuan Berlangganan Layanan LifeMedia Fiber</strong> serta bersedia mematuhi kewajiban pembayaran tagihan bulanan.
                            </span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit Action Button -->
            <div class="pt-4 text-center">
                <button type="submit" id="btnSubmitForm"
                        class="w-full sm:w-auto px-10 py-4 rounded-2xl bg-gradient-to-r from-[#F48C5B] via-[#EF666B] to-[#9B385B] hover:from-[#EF666B] hover:to-[#F48C5B] text-white font-extrabold text-sm shadow-xl shadow-orange-500/25 hover:shadow-orange-500/35 transition-all duration-200 inline-flex items-center justify-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i>
                    <span>Kirim Formulir Pendaftaran Berlangganan</span>
                </button>
            </div>

        </form>

    </main>

    <!-- JavaScript Helpers -->
    <script>
        // Interactive selection helpers
        function updateIdTypeSelection(radio) {
            document.querySelectorAll('input[name="identity_type"]').forEach(r => {
                const label = r.closest('label');
                if (r.checked) {
                    label.className = 'flex items-center justify-center gap-1.5 p-2.5 rounded-xl border cursor-pointer transition-all bg-[#FEF4F0] border-[#F48C5B] text-[#9B385B] font-bold';
                } else {
                    label.className = 'flex items-center justify-center gap-1.5 p-2.5 rounded-xl border cursor-pointer transition-all bg-gray-50 border-gray-200 text-gray-600 hover:bg-gray-100';
                }
            });
        }

        function updateGenderSelection(radio) {
            document.querySelectorAll('input[name="gender"]').forEach(r => {
                const label = r.closest('label');
                if (r.checked) {
                    label.className = 'flex items-center justify-center gap-2 p-2.5 rounded-xl border cursor-pointer transition-all bg-[#FEF4F0] border-[#F48C5B] text-[#9B385B] font-bold';
                } else {
                    label.className = 'flex items-center justify-center gap-2 p-2.5 rounded-xl border cursor-pointer transition-all bg-gray-50 border-gray-200 text-gray-600 hover:bg-gray-100';
                }
            });
        }

        // Live Image Preview & Client-Side Fast WebP Converter
        async function handleImagePreview(input, previewBoxId, placeholderId) {
            if (!input.files || !input.files[0]) return;
            
            const file = input.files[0];
            const previewBox = document.getElementById(previewBoxId);
            const placeholder = document.getElementById(placeholderId);
            const img = previewBox.querySelector('img');
            
            // Show instant preview
            const objectUrl = URL.createObjectURL(file);
            img.src = objectUrl;
            previewBox.classList.remove('hidden');
            if (placeholder) placeholder.classList.add('hidden');

            // Asynchronous Client-side WebP Compression & Conversion
            try {
                const webpBlob = await compressAndConvertToWebp(file, 1600, 0.82);
                if (webpBlob && typeof DataTransfer !== 'undefined') {
                    const cleanName = file.name.replace(/\.[^/.]+$/, "") + ".webp";
                    const webpFile = new File([webpBlob], cleanName, {
                        type: "image/webp",
                        lastModified: Date.now()
                    });
                    
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(webpFile);
                    input.files = dataTransfer.files;
                    
                    // Update preview source with converted webp
                    const webpUrl = URL.createObjectURL(webpFile);
                    img.src = webpUrl;
                }
            } catch (err) {
                console.warn('Client WebP compression fallback to standard upload:', err);
            }
        }

        // Fast In-Browser WebP Image Compressor using Canvas
        function compressAndConvertToWebp(file, maxDimension = 1600, quality = 0.82) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.onerror = reject;
                reader.onload = function(e) {
                    const image = new Image();
                    image.onerror = reject;
                    image.onload = function() {
                        let width = image.width;
                        let height = image.height;

                        if (width > maxDimension || height > maxDimension) {
                            if (width > height) {
                                height = Math.round((height * maxDimension) / width);
                                width = maxDimension;
                            } else {
                                width = Math.round((width * maxDimension) / height);
                                height = maxDimension;
                            }
                        }

                        const canvas = document.createElement('canvas');
                        canvas.width = width;
                        canvas.height = height;
                        const ctx = canvas.getContext('2d');
                        ctx.imageSmoothingEnabled = true;
                        ctx.imageSmoothingQuality = 'high';
                        ctx.drawImage(image, 0, 0, width, height);

                        canvas.toBlob(function(blob) {
                            if (blob) {
                                resolve(blob);
                            } else {
                                reject(new Error('Canvas toBlob conversion failed'));
                            }
                        }, 'image/webp', quality);
                    };
                    image.src = e.target.result;
                };
                reader.readAsDataURL(file);
            });
        }

        // Image Load Error Fallback Handler
        function handleImgError(img, previewBoxId, placeholderId) {
            const previewBox = document.getElementById(previewBoxId);
            const placeholder = document.getElementById(placeholderId);
            if (previewBox) previewBox.classList.add('hidden');
            if (placeholder) placeholder.classList.remove('hidden');
        }

        // Signature Pad Logic with Real-time Stroke Stabilizer (Anti-Gemetar / Anti-Jitter)
        const canvas = document.getElementById('signaturePad');
        const signatureDataInput = document.getElementById('signatureData');
        const signatureGuideText = document.getElementById('signatureGuideText');
        
        let ctx = null;
        let isDrawing = false;
        let hasSignature = false;
        let allStrokes = []; // History for clean re-renders on window resize
        let currentStroke = [];
        
        // Stabilizer parameters
        const STABILIZER_WEIGHT = 0.38; // Lower = smoother/lazy brush, higher = more direct. 0.38 is optimal for natural handwriting without jitter
        const MIN_DISTANCE = 1.8;       // Filters out micro-tremor vibrations
        const MIN_LINE_WIDTH = 1.6;
        const MAX_LINE_WIDTH = 3.2;
        
        let smoothedPos = { x: 0, y: 0 };
        let lastPoint = null;
        let currentWidth = 2.4;
        let lastTime = 0;

        function getPointFromEvent(e) {
            const rect = canvas.getBoundingClientRect();
            const clientX = e.clientX !== undefined ? e.clientX : (e.touches && e.touches[0] ? e.touches[0].clientX : 0);
            const clientY = e.clientY !== undefined ? e.clientY : (e.touches && e.touches[0] ? e.touches[0].clientY : 0);
            return {
                x: clientX - rect.left,
                y: clientY - rect.top,
                time: Date.now()
            };
        }

        function resizeCanvas(preserveDrawing = true) {
            if (!canvas) return;
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            const rect = canvas.getBoundingClientRect();
            
            const targetWidth = Math.round(rect.width * ratio);
            const targetHeight = Math.round(rect.height * ratio);
            
            if (canvas.width === targetWidth && canvas.height === targetHeight) {
                return;
            }

            canvas.width = targetWidth;
            canvas.height = targetHeight;
            
            ctx = canvas.getContext('2d');
            ctx.scale(ratio, ratio);
            ctx.lineCap = 'round';
            ctx.lineJoin = 'round';
            ctx.strokeStyle = '#2C2C2C';

            if (preserveDrawing && allStrokes.length > 0) {
                redrawAllStrokes();
            }
        }

        function redrawAllStrokes() {
            if (!ctx) return;
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            
            for (const stroke of allStrokes) {
                if (stroke.length === 1) {
                    ctx.beginPath();
                    ctx.arc(stroke[0].x, stroke[0].y, stroke[0].width / 2, 0, Math.PI * 2);
                    ctx.fillStyle = '#2C2C2C';
                    ctx.fill();
                    continue;
                }

                for (let i = 1; i < stroke.length; i++) {
                    const p1 = stroke[i - 1];
                    const p2 = stroke[i];
                    
                    ctx.beginPath();
                    ctx.lineWidth = p2.width || 2.4;
                    ctx.strokeStyle = '#2C2C2C';
                    
                    if (i === 1) {
                        ctx.moveTo(p1.x, p1.y);
                        ctx.lineTo(p2.x, p2.y);
                    } else {
                        const p0 = stroke[i - 2];
                        const mid1 = { x: (p0.x + p1.x) / 2, y: (p0.y + p1.y) / 2 };
                        const mid2 = { x: (p1.x + p2.x) / 2, y: (p1.y + p2.y) / 2 };
                        ctx.moveTo(mid1.x, mid1.y);
                        ctx.quadraticCurveTo(p1.x, p1.y, mid2.x, mid2.y);
                    }
                    ctx.stroke();
                }
            }
        }

        function startStroke(e) {
            e.preventDefault();
            isDrawing = true;
            hasSignature = true;
            if (signatureGuideText) signatureGuideText.style.opacity = '0.15';
            
            try {
                if (e.pointerId && canvas.setPointerCapture) {
                    canvas.setPointerCapture(e.pointerId);
                }
            } catch (err) {}

            const raw = getPointFromEvent(e);
            smoothedPos = { x: raw.x, y: raw.y };
            lastPoint = { x: raw.x, y: raw.y };
            currentWidth = 2.4;
            lastTime = raw.time;
            
            currentStroke = [{ x: raw.x, y: raw.y, width: currentWidth }];
        }

        function processStrokeMove(raw) {
            if (!isDrawing) return;
            
            // 1. Apply Exponential Moving Average (EMA) Stabilizer
            // Smoothly tracks cursor to eliminate hand tremors
            smoothedPos.x = smoothedPos.x + (raw.x - smoothedPos.x) * STABILIZER_WEIGHT;
            smoothedPos.y = smoothedPos.y + (raw.y - smoothedPos.y) * STABILIZER_WEIGHT;
            
            const dx = smoothedPos.x - lastPoint.x;
            const dy = smoothedPos.y - lastPoint.y;
            const dist = Math.hypot(dx, dy);
            
            // Filter micro-jitter / trembles below minimum distance threshold
            if (dist < MIN_DISTANCE) return;
            
            const dt = Math.max(raw.time - lastTime, 1);
            const velocity = dist / dt;
            
            // Dynamic velocity stroke width tapering (realistic fountain pen feel)
            const targetWidth = Math.max(MIN_LINE_WIDTH, Math.min(MAX_LINE_WIDTH, MAX_LINE_WIDTH - (velocity * 0.4)));
            currentWidth = currentWidth * 0.75 + targetWidth * 0.25; // Smooth width transition
            
            const newPoint = { x: smoothedPos.x, y: smoothedPos.y, width: currentWidth };
            currentStroke.push(newPoint);
            
            // Render smooth quadratic curve segment
            ctx.beginPath();
            ctx.lineWidth = currentWidth;
            ctx.strokeStyle = '#2C2C2C';
            
            if (currentStroke.length === 2) {
                ctx.moveTo(lastPoint.x, lastPoint.y);
                ctx.lineTo(newPoint.x, newPoint.y);
            } else if (currentStroke.length > 2) {
                const p0 = currentStroke[currentStroke.length - 3];
                const p1 = currentStroke[currentStroke.length - 2];
                const p2 = newPoint;
                
                const mid1 = { x: (p0.x + p1.x) / 2, y: (p0.y + p1.y) / 2 };
                const mid2 = { x: (p1.x + p2.x) / 2, y: (p1.y + p2.y) / 2 };
                
                ctx.moveTo(mid1.x, mid1.y);
                ctx.quadraticCurveTo(p1.x, p1.y, mid2.x, mid2.y);
            }
            ctx.stroke();
            
            lastPoint = { x: smoothedPos.x, y: smoothedPos.y };
            lastTime = raw.time;
        }

        function moveStroke(e) {
            if (!isDrawing) return;
            e.preventDefault();
            
            // Use sub-frame coalesced events if available for ultra-smooth high-Hz devices
            if (e.getCoalescedEvents && e.getCoalescedEvents().length > 0) {
                const coalesced = e.getCoalescedEvents();
                for (let i = 0; i < coalesced.length; i++) {
                    processStrokeMove(getPointFromEvent(coalesced[i]));
                }
            } else {
                processStrokeMove(getPointFromEvent(e));
            }
        }

        function endStroke(e) {
            if (!isDrawing) return;
            isDrawing = false;
            
            // If single tap / dot
            if (currentStroke.length === 1) {
                ctx.beginPath();
                ctx.arc(currentStroke[0].x, currentStroke[0].y, currentStroke[0].width / 2, 0, Math.PI * 2);
                ctx.fillStyle = '#2C2C2C';
                ctx.fill();
            } else if (currentStroke.length > 1) {
                // Ensure tail end connects smoothly
                const p0 = currentStroke[currentStroke.length - 2];
                const p1 = currentStroke[currentStroke.length - 1];
                ctx.beginPath();
                ctx.lineWidth = p1.width;
                ctx.moveTo((p0.x + p1.x) / 2, (p0.y + p1.y) / 2);
                ctx.lineTo(p1.x, p1.y);
                ctx.stroke();
            }
            
            if (currentStroke.length > 0) {
                allStrokes.push([...currentStroke]);
            }
            currentStroke = [];
            
            // Save base64 signature to input (WebP format with fallback)
            try {
                const webpData = canvas.toDataURL('image/webp', 0.85);
                signatureDataInput.value = (webpData && webpData.startsWith('data:image/webp')) 
                    ? webpData 
                    : canvas.toDataURL('image/png');
            } catch (err) {
                signatureDataInput.value = canvas.toDataURL('image/png');
            }
        }

        function initCanvasEngine() {
            resizeCanvas(false);
            
            // Pointer events (handles Mouse, Touch, Stylus/Pen with unified smoothing)
            canvas.addEventListener('pointerdown', startStroke, { passive: false });
            window.addEventListener('pointermove', moveStroke, { passive: false });
            window.addEventListener('pointerup', endStroke);
            window.addEventListener('pointercancel', endStroke);
            
            // Touch fallbacks for older devices
            canvas.addEventListener('touchstart', startStroke, { passive: false });
            canvas.addEventListener('touchmove', moveStroke, { passive: false });
            canvas.addEventListener('touchend', endStroke);
        }

        window.addEventListener('load', initCanvasEngine);
        window.addEventListener('resize', () => resizeCanvas(true));

        function clearSignature() {
            allStrokes = [];
            currentStroke = [];
            hasSignature = false;
            if (ctx) {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
            }
            signatureDataInput.value = '';
            if (signatureGuideText) signatureGuideText.style.opacity = '1';
        }

        // Form Validation on Submit
        document.getElementById('customerRegistrationForm').addEventListener('submit', function(e) {
            const hasSigValue = signatureDataInput.value && signatureDataInput.value.length > 50;
            const hasHistory = allStrokes.length > 0;
            const existingSig = "{{ $registration->signature_path }}";
            
            if (!hasSigValue && !hasHistory && !existingSig) {
                e.preventDefault();
                alert('Silakan bubuhkan tanda tangan digital Anda pada kotak tanda tangan sebelum mengirim formulir.');
                canvas.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    </script>
</body>
</html>
