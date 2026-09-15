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
        select {
            min-width: 0;
            max-width: 100%;
        }
    </style>
</head>
<body class="min-h-full bg-[#F8F9FA] text-[#333333] antialiased flex flex-col selection:bg-[#F48C5B] selection:text-white pb-16">

    <!-- Top Brand Header -->
    <header class="sticky top-0 z-30 bg-white/95 backdrop-blur-md border-b border-gray-200/90 shadow-xs">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between gap-3">
            <div class="flex items-center gap-2.5 sm:gap-3 min-w-0">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Life Media Logo" class="h-8 sm:h-9 w-auto object-contain shrink-0">
                <div class="border-l border-gray-200 pl-2.5 sm:pl-3 min-w-0">
                    <div class="font-extrabold text-sm sm:text-base text-[#2C2C2C] truncate">
                        Life Connect
                    </div>
                    <div class="text-[10px] sm:text-xs text-gray-500 truncate">Pendaftaran Berlangganan Internet Fiber</div>
                </div>
            </div>

            <div class="text-right shrink-0">
                <div class="text-[9px] sm:text-[10px] text-gray-400 font-mono tracking-wider uppercase">KODE PENGAJUAN:</div>
                <div class="text-xs sm:text-sm font-bold font-mono text-[#F48C5B] bg-[#FEF4F0] px-2 sm:px-2.5 py-0.5 rounded-lg border border-[#F48C5B]/30 whitespace-nowrap">{{ $registration->registration_code }}</div>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="flex-1 max-w-4xl mx-auto w-full px-4 sm:px-6 py-6 sm:py-8 space-y-6 sm:space-y-8">
        
        @if($registration->status === 'revision' && $registration->rejection_notes)
            <div class="p-4 sm:p-5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1">
                <div class="font-bold flex items-center gap-1.5 text-rose-700 text-xs sm:text-sm">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span>Catatan Perbaikan Dokumen dari Tim Customer Care:</span>
                </div>
                <p class="leading-relaxed text-xs sm:text-sm">{{ $registration->rejection_notes }}</p>
            </div>
        @endif

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

        @php
            $initialStep = 1;
            if ($errors->any()) {
                if ($errors->hasAny(['terms_agreed', 'signature_data'])) {
                    $initialStep = 5;
                } elseif ($errors->hasAny(['ktp_photo', 'house_photo', 'selfie_sales_photo'])) {
                    $initialStep = 4;
                } elseif ($errors->hasAny(['billing_name', 'billing_address', 'billing_phone', 'billing_mobile', 'billing_email', 'billing_method'])) {
                    $initialStep = 3;
                } elseif ($errors->hasAny(['services', 'subscription_period', 'package_id', 'addons'])) {
                    $initialStep = 2;
                } elseif ($errors->hasAny(['customer_name', 'brand_name', 'identity_type', 'identity_number', 'birth_date', 'gender', 'phone_telp', 'phone_wa', 'email', 'address_detail'])) {
                    $initialStep = 1;
                }
            }
        @endphp

        <!-- Multi-Step Stepper Progress Bar -->
        <div class="glass-card rounded-2xl p-4 sm:p-5 shadow-xs border border-gray-200/90 bg-white" id="stepperContainer">
            <!-- Mobile Step Indicator -->
            <div class="sm:hidden space-y-2.5">
                <div class="flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2 min-w-0">
                        <span id="mobileStepBadge" class="w-6 h-6 rounded-full bg-[#FEF4F0] border border-[#F48C5B]/40 text-[#F48C5B] font-extrabold flex items-center justify-center text-xs shrink-0">1</span>
                        <span id="mobileStepTitle" class="font-extrabold text-[#2C2C2C] truncate">Data Pribadi</span>
                    </div>
                    <span class="text-gray-400 font-medium shrink-0 ml-2">Langkah <span id="mobileStepNum" class="font-bold text-[#F48C5B]">1</span> / 5</span>
                </div>
                <!-- Progress bar line -->
                <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                    <div id="mobileProgressBar" class="bg-gradient-to-r from-[#F48C5B] to-[#EF666B] h-2 rounded-full transition-all duration-300" style="width: 20%;"></div>
                </div>
            </div>

            <!-- Desktop & Tablet Step Indicator (5 Steps) -->
            <div class="hidden sm:grid sm:grid-cols-5 gap-2.5">
                <!-- Step 1 Tab -->
                <button type="button" onclick="goToStep(1)" id="stepTab1" class="step-tab text-left p-2.5 rounded-xl border transition-all flex items-center gap-2.5 bg-[#FEF4F0] border-[#F48C5B] shadow-xs cursor-pointer">
                    <div id="stepBadge1" class="w-7 h-7 rounded-lg bg-gradient-to-br from-[#F48C5B] to-[#EF666B] text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-xs">
                        1
                    </div>
                    <div class="min-w-0">
                        <div class="text-[9px] text-gray-400 uppercase font-mono tracking-wider">Langkah 1</div>
                        <div class="text-xs font-bold text-[#2C2C2C] truncate">Data Pribadi</div>
                    </div>
                </button>

                <!-- Step 2 Tab -->
                <button type="button" onclick="goToStep(2)" id="stepTab2" class="step-tab text-left p-2.5 rounded-xl border border-gray-200 bg-gray-50/50 hover:bg-gray-100/80 transition-all flex items-center gap-2.5 cursor-pointer">
                    <div id="stepBadge2" class="w-7 h-7 rounded-lg bg-gray-200 text-gray-500 flex items-center justify-center font-bold text-xs shrink-0">
                        2
                    </div>
                    <div class="min-w-0">
                        <div class="text-[9px] text-gray-400 uppercase font-mono tracking-wider">Langkah 2</div>
                        <div class="text-xs font-bold text-gray-600 truncate">Layanan &amp; Paket</div>
                    </div>
                </button>

                <!-- Step 3 Tab -->
                <button type="button" onclick="goToStep(3)" id="stepTab3" class="step-tab text-left p-2.5 rounded-xl border border-gray-200 bg-gray-50/50 hover:bg-gray-100/80 transition-all flex items-center gap-2.5 cursor-pointer">
                    <div id="stepBadge3" class="w-7 h-7 rounded-lg bg-gray-200 text-gray-500 flex items-center justify-center font-bold text-xs shrink-0">
                        3
                    </div>
                    <div class="min-w-0">
                        <div class="text-[9px] text-gray-400 uppercase font-mono tracking-wider">Langkah 3</div>
                        <div class="text-xs font-bold text-gray-600 truncate">Penagihan</div>
                    </div>
                </button>

                <!-- Step 4 Tab -->
                <button type="button" onclick="goToStep(4)" id="stepTab4" class="step-tab text-left p-2.5 rounded-xl border border-gray-200 bg-gray-50/50 hover:bg-gray-100/80 transition-all flex items-center gap-2.5 cursor-pointer">
                    <div id="stepBadge4" class="w-7 h-7 rounded-lg bg-gray-200 text-gray-500 flex items-center justify-center font-bold text-xs shrink-0">
                        4
                    </div>
                    <div class="min-w-0">
                        <div class="text-[9px] text-gray-400 uppercase font-mono tracking-wider">Langkah 4</div>
                        <div class="text-xs font-bold text-gray-600 truncate">Foto Dokumen</div>
                    </div>
                </button>

                <!-- Step 5 Tab -->
                <button type="button" onclick="goToStep(5)" id="stepTab5" class="step-tab text-left p-2.5 rounded-xl border border-gray-200 bg-gray-50/50 hover:bg-gray-100/80 transition-all flex items-center gap-2.5 cursor-pointer">
                    <div id="stepBadge5" class="w-7 h-7 rounded-lg bg-gray-200 text-gray-500 flex items-center justify-center font-bold text-xs shrink-0">
                        5
                    </div>
                    <div class="min-w-0">
                        <div class="text-[9px] text-gray-400 uppercase font-mono tracking-wider">Langkah 5</div>
                        <div class="text-xs font-bold text-gray-600 truncate">Persetujuan</div>
                    </div>
                </button>
            </div>
        </div>

        <!-- Public Registration Multi-Step Form -->
        <form action="{{ route('customer-form.submit', $registration->token) }}" method="POST" enctype="multipart/form-data" id="customerRegistrationForm" class="space-y-6 sm:space-y-8" novalidate>
            @csrf
            <input type="hidden" name="package_id" id="package_id" value="{{ old('package_id', $registration->package_id) }}">

            <!-- STEP 1: DATA PRIBADI -->
            <div id="step-1" class="step-pane transition-all duration-300">
                <div class="glass-card rounded-3xl p-6 sm:p-8 space-y-6">
                    <div class="flex items-center gap-3 border-b border-gray-300 pb-4">
                        <div class="w-9 h-9 rounded-xl bg-[#FEF4F0] border border-[#F48C5B]/40 text-[#F48C5B] flex items-center justify-center font-extrabold text-sm shadow-xs">
                            1
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-[#2C2C2C]">Data Pribadi</h3>
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
                                   class="w-full px-4 py-2.5 rounded-xl bg-white border border-gray-300 text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#F48C5B] focus:border-[#F48C5B] font-medium transition-colors">
                            <p id="err_customer_name" class="field-error-text text-rose-500 text-[11px] font-semibold mt-1 hidden flex items-center gap-1.5">
                                <i class="fa-solid fa-circle-exclamation text-xs"></i>
                                <span>Nama lengkap wajib diisi (minimal 2 karakter).</span>
                            </p>
                        </div>

                        <!-- Nama Brand -->
                        <div>
                            <label for="brand_name" class="block font-bold text-[#333333] mb-1.5">
                                Nama Brand / Usaha <span class="text-gray-400 font-normal">(Opsional)</span>
                            </label>
                            <input type="text" name="brand_name" id="brand_name"
                                   value="{{ old('brand_name', $registration->brand_name) }}"
                                   placeholder="Contoh: PT Surya Pratama / Kedai Kopi / Brand..."
                                   class="w-full px-4 py-2.5 rounded-xl bg-white border border-gray-300 text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#F48C5B] focus:border-[#F48C5B] transition-colors">
                        </div>

                        <!-- Checklist Tanda Pengenal (KTP, SIM, Paspor) -->
                        <div id="container_identity_type">
                            <label class="block font-bold text-[#333333] mb-1.5">
                                Jenis Tanda Pengenal <span class="text-rose-500">*</span>
                            </label>
                            @php
                                $selectedIdType = old('identity_type', $registration->identity_type ?: 'KTP');
                            @endphp
                            <div class="grid grid-cols-3 gap-2" id="identityTypeGroup">
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
                            <p id="err_identity_type" class="field-error-text text-rose-500 text-[11px] font-semibold mt-1.5 hidden flex items-center gap-1.5">
                                <i class="fa-solid fa-circle-exclamation text-xs"></i>
                                <span>Pilih jenis tanda pengenal.</span>
                            </p>
                        </div>

                        <!-- Nomor Tanda Pengenal -->
                        <div>
                            <label for="identity_number" class="block font-bold text-[#333333] mb-1.5">
                                Nomor Tanda Pengenal <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="identity_number" id="identity_number" required
                                   value="{{ old('identity_number', $registration->identity_number ?: $registration->nik) }}"
                                   placeholder="Nomor KTP / SIM / Paspor..."
                                   class="w-full px-4 py-2.5 rounded-xl bg-white border border-gray-300 text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#F48C5B] focus:border-[#F48C5B] font-mono transition-colors">
                            <p id="err_identity_number" class="field-error-text text-rose-500 text-[11px] font-semibold mt-1 hidden flex items-center gap-1.5">
                                <i class="fa-solid fa-circle-exclamation text-xs"></i>
                                <span>Nomor tanda pengenal wajib diisi (minimal 6 karakter).</span>
                            </p>
                        </div>

                        <!-- Tanggal Lahir (dd/mm/yyyy) -->
                        <div>
                            <label for="birth_date" class="block font-bold text-[#333333] mb-1.5">
                                Tanggal Lahir (dd/mm/yyyy) <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" name="birth_date" id="birth_date" required
                                   max="{{ date('Y-m-d') }}"
                                   value="{{ old('birth_date', $registration->birth_date?->format('Y-m-d')) }}"
                                   class="w-full px-4 py-2.5 rounded-xl bg-white border border-gray-300 text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#F48C5B] focus:border-[#F48C5B] transition-colors">
                            <span class="text-[10px] text-gray-400 mt-1 block">Format: Hari / Bulan / Tahun</span>
                            <p id="err_birth_date" class="field-error-text text-rose-500 text-[11px] font-semibold mt-1 hidden flex items-center gap-1.5">
                                <i class="fa-solid fa-circle-exclamation text-xs"></i>
                                <span id="err_birth_date_text">Tanggal lahir wajib dipilih dan tidak boleh melebihi hari ini.</span>
                            </p>
                        </div>

                        <!-- Checklist Jenis Kelamin (P/W) -->
                        <div id="container_gender">
                            <label class="block font-bold text-[#333333] mb-1.5">
                                Jenis Kelamin (P/W) <span class="text-rose-500">*</span>
                            </label>
                            @php
                                $genderVal = old('gender', in_array($registration->gender, ['P', 'Laki-laki']) ? 'P' : (in_array($registration->gender, ['W', 'Perempuan']) ? 'W' : 'P'));
                            @endphp
                            <div class="grid grid-cols-2 gap-3" id="genderGroup">
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
                            <p id="err_gender" class="field-error-text text-rose-500 text-[11px] font-semibold mt-1.5 hidden flex items-center gap-1.5">
                                <i class="fa-solid fa-circle-exclamation text-xs"></i>
                                <span>Pilih jenis kelamin.</span>
                            </p>
                        </div>

                        <!-- No Telepon (Fixed Line / Telp Rumah) -->
                        <div>
                            <label for="phone_telp" class="block font-bold text-[#333333] mb-1.5">
                                No. Telepon Rumah / Kantor <span class="text-gray-400 font-normal">(Opsional)</span>
                            </label>
                            <input type="text" name="phone_telp" id="phone_telp"
                                   value="{{ old('phone_telp', $registration->phone_telp) }}"
                                   placeholder="Contoh: 0274-123456"
                                   class="w-full px-4 py-2.5 rounded-xl bg-white border border-gray-300 text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#F48C5B] focus:border-[#F48C5B] transition-colors">
                        </div>

                        <!-- No HP (Editable) -->
                        <div>
                            <label for="phone_wa" class="block font-bold text-[#333333] mb-1.5">
                                No. HP / WhatsApp <span class="text-rose-500">*</span>
                            </label>
                            <input type="tel" name="phone_wa" id="phone_wa" required
                                   value="{{ old('phone_wa', $registration->phone_wa) }}"
                                   placeholder="Contoh: 081234567890"
                                   class="w-full px-4 py-2.5 rounded-xl bg-white border border-gray-300 text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#F48C5B] focus:border-[#F48C5B] transition-colors">
                            <p id="err_phone_wa" class="field-error-text text-rose-500 text-[11px] font-semibold mt-1 hidden flex items-center gap-1.5">
                                <i class="fa-solid fa-circle-exclamation text-xs"></i>
                                <span>Nomor HP / WhatsApp wajib diisi (minimal 8 digit).</span>
                            </p>
                        </div>

                        <!-- Email (Editable) -->
                        <div class="sm:col-span-2">
                            <label for="email" class="block font-bold text-[#333333] mb-1.5">
                                Email <span class="text-rose-500">*</span>
                            </label>
                            <input type="email" name="email" id="email" required
                                   value="{{ old('email', $registration->email) }}"
                                   placeholder="nama.anda@gmail.com"
                                   class="w-full px-4 py-2.5 rounded-xl bg-white border border-gray-300 text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#F48C5B] focus:border-[#F48C5B] transition-colors">
                            <p id="err_email" class="field-error-text text-rose-500 text-[11px] font-semibold mt-1 hidden flex items-center gap-1.5">
                                <i class="fa-solid fa-circle-exclamation text-xs"></i>
                                <span>Alamat email wajib diisi dengan format valid (contoh: nama@domain.com).</span>
                            </p>
                        </div>

                        <!-- Alamat Pemasangan (Editable) -->
                        <div class="sm:col-span-2 space-y-2">
                            <label for="address_detail" class="block font-bold text-[#333333]">
                                Alamat Pemasangan <span class="text-rose-500">*</span>
                            </label>
                            <textarea name="address_detail" id="address_detail" rows="2" required
                                      placeholder="Alamat lengkap lokasi pemasangan (Jalan, No. Rumah, RT/RW)..."
                                      class="w-full px-4 py-2.5 rounded-xl bg-white border border-gray-300 text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#F48C5B] focus:border-[#F48C5B] leading-relaxed transition-colors">{{ old('address_detail', $registration->address_detail ?: $registration->full_address) }}</textarea>
                            <p id="err_address_detail" class="field-error-text text-rose-500 text-[11px] font-semibold mt-0.5 hidden flex items-center gap-1.5">
                                <i class="fa-solid fa-circle-exclamation text-xs"></i>
                                <span>Alamat lengkap pemasangan wajib diisi.</span>
                            </p>
                        </div>

                    </div>
                </div>
            </div>

            <!-- STEP 2: PAKET BERLANGGANAN -->
            <div id="step-2" class="step-pane hidden transition-all duration-300">
                <div class="glass-card rounded-3xl p-6 sm:p-8 space-y-6">
                    <div class="flex items-center gap-3 border-b border-gray-300 pb-4">
                        <div class="w-9 h-9 rounded-xl bg-[#FEF4F0] border border-[#F48C5B]/40 text-[#F48C5B] flex items-center justify-center font-extrabold text-sm shadow-xs">
                            2
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-[#2C2C2C]">Paket Berlangganan</h3>
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

                    <!-- Service Checklist Matrix (Clean Responsive 3-Card Grid) -->
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-bold text-[#2C2C2C]">
                                Pilihan Layanan <span class="text-rose-500">*</span> <span class="text-gray-400 font-normal text-[11px]">(Pilih minimal 1 layanan)</span>
                            </label>
                        </div>

                        <div id="box_services_selection" class="grid grid-cols-1 md:grid-cols-3 gap-4 transition-all">

                            <!-- 1. TV KABEL -->
                            <div class="p-4 rounded-2xl border border-gray-200 bg-white hover:border-[#F48C5B]/60 hover:shadow-xs transition-all space-y-3 flex flex-col justify-between min-w-0 overflow-hidden">
                                <div class="min-w-0 w-full">
                                    <div class="flex items-center gap-2.5 border-b border-gray-200 pb-2.5">
                                        <div class="w-8 h-8 rounded-xl bg-orange-50 text-[#F48C5B] border border-orange-100 flex items-center justify-center font-bold text-sm shadow-2xs">
                                            <i class="fa-solid fa-tv"></i>
                                        </div>
                                        <div>
                                            <span class="font-extrabold text-xs text-[#2C2C2C] tracking-wide uppercase block">TV KABEL</span>
                                            <span class="text-[10px] text-gray-400 block">Layanan TV kabel interaktif</span>
                                        </div>
                                    </div>

                                    <div class="space-y-2.5 mt-3 min-w-0 w-full">
                                        <!-- Checkbox 1 + Textfield -->
                                        <div class="flex items-center gap-2 min-w-0 w-full">
                                            <input type="checkbox" name="services[tv_kabel][opt1]" id="tv_opt1" value="1" {{ $tvOpt1 ? 'checked' : '' }}
                                                   class="service-checkbox w-4.5 h-4.5 rounded text-[#F48C5B] focus:ring-[#F48C5B] border-gray-300 cursor-pointer shrink-0">
                                            <input type="text" name="services[tv_kabel][text1]" id="tv_text1" value="{{ $tvText1 }}"
                                                   placeholder="Catatan / Paket 1..." autocomplete="off"
                                                   class="w-0 min-w-0 flex-1 px-2.5 py-2 rounded-xl bg-gray-50 border border-gray-200 text-xs text-[#2C2C2C] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#F48C5B] focus:border-[#F48C5B] transition-colors">
                                        </div>

                                        <!-- Checkbox 2 + Textfield -->
                                        <div class="flex items-center gap-2 min-w-0 w-full">
                                            <input type="checkbox" name="services[tv_kabel][opt2]" id="tv_opt2" value="1" {{ $tvOpt2 ? 'checked' : '' }}
                                                   class="service-checkbox w-4.5 h-4.5 rounded text-[#F48C5B] focus:ring-[#F48C5B] border-gray-300 cursor-pointer shrink-0">
                                            <input type="text" name="services[tv_kabel][text2]" id="tv_text2" value="{{ $tvText2 }}"
                                                   placeholder="Catatan / Paket 2..." autocomplete="off"
                                                   class="w-0 min-w-0 flex-1 px-2.5 py-2 rounded-xl bg-gray-50 border border-gray-200 text-xs text-[#2C2C2C] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#F48C5B] focus:border-[#F48C5B] transition-colors">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 2. INTERNET -->
                            <div class="p-4 rounded-2xl border border-gray-200 bg-white hover:border-[#F48C5B]/60 hover:shadow-xs transition-all space-y-3 flex flex-col justify-between min-w-0 overflow-hidden">
                                <div class="min-w-0 w-full">
                                    <div class="flex items-center gap-2.5 border-b border-gray-200 pb-2.5">
                                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center font-bold text-sm shadow-2xs">
                                            <i class="fa-solid fa-wifi"></i>
                                        </div>
                                        <div>
                                            <span class="font-extrabold text-xs text-[#2C2C2C] tracking-wide uppercase block">INTERNET</span>
                                            <span class="text-[10px] text-gray-400 block">Koneksi fiber optik ultra cepat</span>
                                        </div>
                                    </div>

                                    <div class="space-y-2.5 mt-3 min-w-0 w-full">
                                        <!-- Checkbox 1 + Dropdown -->
                                        <div class="flex items-center gap-2 min-w-0 w-full">
                                            <input type="checkbox" name="services[internet][opt1]" id="net_opt1" value="1" {{ $netOpt1 ? 'checked' : '' }}
                                                   class="service-checkbox w-4.5 h-4.5 rounded text-[#F48C5B] focus:ring-[#F48C5B] border-gray-300 cursor-pointer shrink-0">
                                            <select name="services[internet][text1]" id="net_text1"
                                                    class="w-0 min-w-0 flex-1 px-2 py-2 rounded-xl bg-gray-50 border border-gray-200 text-xs text-[#2C2C2C] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#F48C5B] focus:border-[#F48C5B] transition-colors cursor-pointer truncate">
                                                <option value="" data-package-id="" {{ empty($netText1) && empty($registration->package_id) ? 'selected' : '' }}>-- Pilih Paket Internet --</option>
                                                @foreach($packages as $pkg)
                                                    @php
                                                        $optVal = $pkg->name . ' (' . $pkg->speed . ') - ' . $pkg->formatted_price . '/bulan';
                                                        $isPkgSel = (old('package_id', $registration->package_id) == $pkg->id) || ($netText1 == $optVal) || (str_contains(strtolower($netText1), strtolower($pkg->name))) || (str_contains($netText1, $pkg->speed));
                                                    @endphp
                                                    <option value="{{ $optVal }}" data-package-id="{{ $pkg->id }}" {{ $isPkgSel ? 'selected' : '' }}>
                                                        {{ $pkg->name }} ({{ $pkg->speed }}) - {{ $pkg->formatted_price }}/bulan
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Checkbox 2 + Dropdown -->
                                        <div class="flex items-center gap-2 min-w-0 w-full">
                                            <input type="checkbox" name="services[internet][opt2]" id="net_opt2" value="1" {{ $netOpt2 ? 'checked' : '' }}
                                                   class="service-checkbox w-4.5 h-4.5 rounded text-[#F48C5B] focus:ring-[#F48C5B] border-gray-300 cursor-pointer shrink-0">
                                            <select name="services[internet][text2]" id="net_text2"
                                                    class="w-0 min-w-0 flex-1 px-2 py-2 rounded-xl bg-gray-50 border border-gray-200 text-xs text-[#2C2C2C] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#F48C5B] focus:border-[#F48C5B] transition-colors cursor-pointer truncate">
                                                <option value="" {{ empty($netText2) ? 'selected' : '' }}>-- Pilih Paket Tambahan --</option>
                                                @foreach($packages as $pkg)
                                                    @php
                                                        $optVal2 = $pkg->name . ' (' . $pkg->speed . ') - ' . $pkg->formatted_price . '/bulan';
                                                        $isPkgSel2 = ($netText2 == $optVal2) || (str_contains(strtolower($netText2), strtolower($pkg->name)));
                                                    @endphp
                                                    <option value="{{ $optVal2 }}" {{ $isPkgSel2 ? 'selected' : '' }}>
                                                        {{ $pkg->name }} ({{ $pkg->speed }}) - {{ $pkg->formatted_price }}/bulan
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 3. TELEPON -->
                            <div class="p-4 rounded-2xl border border-gray-200 bg-white hover:border-[#F48C5B]/60 hover:shadow-xs transition-all space-y-3 flex flex-col justify-between min-w-0 overflow-hidden">
                                <div class="min-w-0 w-full">
                                    <div class="flex items-center gap-2.5 border-b border-gray-200 pb-2.5">
                                        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center font-bold text-sm shadow-2xs">
                                            <i class="fa-solid fa-phone"></i>
                                        </div>
                                        <div>
                                            <span class="font-extrabold text-xs text-[#2C2C2C] tracking-wide uppercase block">TELEPON</span>
                                            <span class="text-[10px] text-gray-400 block">Layanan suara kabel / VoIP</span>
                                        </div>
                                    </div>

                                    <div class="space-y-2.5 mt-3 min-w-0 w-full">
                                        <!-- Checkbox 1 + Textfield -->
                                        <div class="flex items-center gap-2 min-w-0 w-full">
                                            <input type="checkbox" name="services[telepon][opt1]" id="tel_opt1" value="1" {{ $telOpt1 ? 'checked' : '' }}
                                                   class="service-checkbox w-4.5 h-4.5 rounded text-[#F48C5B] focus:ring-[#F48C5B] border-gray-300 cursor-pointer shrink-0">
                                            <input type="text" name="services[telepon][text1]" id="tel_text1" value="{{ $telText1 }}"
                                                   placeholder="Nomor / Paket 1..." autocomplete="off"
                                                   class="w-0 min-w-0 flex-1 px-2.5 py-2 rounded-xl bg-gray-50 border border-gray-200 text-xs text-[#2C2C2C] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#F48C5B] focus:border-[#F48C5B] transition-colors">
                                        </div>

                                        <!-- Checkbox 2 + Textfield -->
                                        <div class="flex items-center gap-2 min-w-0 w-full">
                                            <input type="checkbox" name="services[telepon][opt2]" id="tel_opt2" value="1" {{ $telOpt2 ? 'checked' : '' }}
                                                   class="service-checkbox w-4.5 h-4.5 rounded text-[#F48C5B] focus:ring-[#F48C5B] border-gray-300 cursor-pointer shrink-0">
                                            <input type="text" name="services[telepon][text2]" id="tel_text2" value="{{ $telText2 }}"
                                                   placeholder="Nomor / Paket 2..." autocomplete="off"
                                                   class="w-0 min-w-0 flex-1 px-2.5 py-2 rounded-xl bg-gray-50 border border-gray-200 text-xs text-[#2C2C2C] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#F48C5B] focus:border-[#F48C5B] transition-colors">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <p id="err_services_selected" class="field-error-text text-rose-500 text-[11px] font-semibold mt-1.5 hidden flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-exclamation text-xs"></i>
                            <span>Pilih minimal 1 paket layanan berlangganan.</span>
                        </p>
                    </div>

                    <!-- Textfield Jangka Waktu Berlangganan -->
                    <div class="pt-4 border-t border-gray-200">
                        <label for="subscription_period" class="block font-bold text-xs text-[#333333] mb-1.5">
                            Jangka Waktu Berlangganan <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative w-full sm:w-64">
                            <input type="number" name="subscription_period" id="subscription_period" required min="1" step="1"
                                   value="{{ old('subscription_period', preg_replace('/[^0-9]/', '', (string)($registration->subscription_period ?: '12'))) }}"
                                   placeholder="12"
                                   class="w-full pl-4 pr-18 py-2.5 rounded-xl bg-white border border-gray-300 text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#F48C5B] focus:border-[#F48C5B] text-xs font-semibold [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none transition-colors">
                            <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none">
                                <span class="text-xs font-bold text-gray-500 bg-gray-100 px-2.5 py-1 rounded-lg border border-gray-200">
                                    Bulan
                                </span>
                            </div>
                        </div>
                        <span class="text-[10px] text-gray-400 mt-1 block">Tentukan durasi komitmen masa berlangganan layanan (dalam bulan)</span>
                        <p id="err_subscription_period" class="field-error-text text-rose-500 text-[11px] font-semibold mt-1 hidden flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-exclamation text-xs"></i>
                            <span>Jangka waktu berlangganan wajib diisi (minimal 1 bulan).</span>
                        </p>
                    </div>

                </div>
            </div>

            <!-- STEP 3: INFORMASI PENAGIHAN -->
            <div id="step-3" class="step-pane hidden transition-all duration-300">
                <div class="glass-card rounded-3xl p-6 sm:p-8 space-y-6">
                    <div class="flex items-center gap-3 border-b border-gray-300 pb-4">
                        <div class="w-9 h-9 rounded-xl bg-[#FEF4F0] border border-[#F48C5B]/40 text-[#F48C5B] flex items-center justify-center font-extrabold text-sm shadow-xs">
                            3
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-[#2C2C2C]">Informasi Penagihan</h3>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        
                        <!-- Nama Penerima -->
                        <div>
                            <label for="billing_name" class="block font-bold text-[#333333] mb-1.5">
                                Nama Penerima <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="billing_name" id="billing_name" required
                                   value="{{ old('billing_name', $registration->billing_name) }}"
                                   placeholder="Nama penerima / penanggung jawab tagihan..."
                                   class="w-full px-4 py-2.5 rounded-xl bg-white border border-gray-300 text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#F48C5B] focus:border-[#F48C5B] font-medium transition-colors">
                            <p id="err_billing_name" class="field-error-text text-rose-500 text-[11px] font-semibold mt-1 hidden flex items-center gap-1.5">
                                <i class="fa-solid fa-circle-exclamation text-xs"></i>
                                <span>Nama penerima tagihan wajib diisi.</span>
                            </p>
                        </div>

                        <!-- Email Penagihan -->
                        <div>
                            <label for="billing_email" class="block font-bold text-[#333333] mb-1.5">
                                Email Penagihan (e-Invoice) <span class="text-rose-500">*</span>
                            </label>
                            <input type="email" name="billing_email" id="billing_email" required
                                   value="{{ old('billing_email', $registration->billing_email) }}"
                                   placeholder="email.penagihan@gmail.com"
                                   class="w-full px-4 py-2.5 rounded-xl bg-white border border-gray-300 text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#F48C5B] focus:border-[#F48C5B] transition-colors">
                            <p id="err_billing_email" class="field-error-text text-rose-500 text-[11px] font-semibold mt-1 hidden flex items-center gap-1.5">
                                <i class="fa-solid fa-circle-exclamation text-xs"></i>
                                <span>Email penagihan wajib diisi dengan format valid.</span>
                            </p>
                        </div>

                        <!-- No Telepon Penagihan -->
                        <div>
                            <label for="billing_phone" class="block font-bold text-[#333333] mb-1.5">
                                No. Telepon <span class="text-gray-400 font-normal">(Opsional)</span>
                            </label>
                            <input type="text" name="billing_phone" id="billing_phone"
                                   value="{{ old('billing_phone', $registration->billing_phone) }}"
                                   placeholder="Contoh: 0274-123456"
                                   class="w-full px-4 py-2.5 rounded-xl bg-white border border-gray-300 text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#F48C5B] focus:border-[#F48C5B] transition-colors">
                        </div>

                        <!-- No HP Penagihan -->
                        <div>
                            <label for="billing_mobile" class="block font-bold text-[#333333] mb-1.5">
                                No. HP Penagihan <span class="text-rose-500">*</span>
                            </label>
                            <input type="tel" name="billing_mobile" id="billing_mobile" required
                                   value="{{ old('billing_mobile', $registration->billing_mobile) }}"
                                   placeholder="Contoh: 081234567890"
                                   class="w-full px-4 py-2.5 rounded-xl bg-white border border-gray-300 text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#F48C5B] focus:border-[#F48C5B] transition-colors">
                            <p id="err_billing_mobile" class="field-error-text text-rose-500 text-[11px] font-semibold mt-1 hidden flex items-center gap-1.5">
                                <i class="fa-solid fa-circle-exclamation text-xs"></i>
                                <span>Nomor HP penagihan wajib diisi (minimal 8 digit).</span>
                            </p>
                        </div>

                        <!-- Alamat Penagihan -->
                        <div class="sm:col-span-2">
                            <label for="billing_address" class="block font-bold text-[#333333] mb-1.5">
                                Alamat Penagihan <span class="text-rose-500">*</span>
                            </label>
                            <textarea name="billing_address" id="billing_address" rows="2" required
                                      placeholder="Alamat lengkap tujuan pengiriman invoice / surat penagihan..."
                                      class="w-full px-4 py-2.5 rounded-xl bg-white border border-gray-300 text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#F48C5B] focus:border-[#F48C5B] leading-relaxed transition-colors">{{ old('billing_address', $registration->billing_address) }}</textarea>
                            <p id="err_billing_address" class="field-error-text text-rose-500 text-[11px] font-semibold mt-0.5 hidden flex items-center gap-1.5">
                                <i class="fa-solid fa-circle-exclamation text-xs"></i>
                                <span>Alamat penagihan lengkap wajib diisi.</span>
                            </p>
                        </div>

                    </div>
                </div>
            </div>

            <!-- STEP 4: KELENGKAPAN DOKUMEN FOTO (PREVIEW ENABLED) -->
            <div id="step-4" class="step-pane hidden transition-all duration-300">
                <div class="glass-card rounded-3xl p-6 sm:p-8 space-y-6">
                    <div class="flex items-center gap-3 border-b border-gray-300 pb-4">
                        <div class="w-9 h-9 rounded-xl bg-[#FEF4F0] border border-[#F48C5B]/40 text-[#F48C5B] flex items-center justify-center font-extrabold text-sm shadow-xs">
                            4
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-[#2C2C2C]">Kelengkapan Dokumen Foto</h3>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 text-xs">
                        
                        <!-- 1. Foto KTP Asli -->
                        <div class="space-y-1.5" id="wrapper_ktp_photo">
                            <label class="block font-bold text-[#333333]">
                                Foto KTP Asli <span class="text-rose-500">*</span>
                            </label>

                            <div id="box_ktp_photo" class="relative group border-2 border-dashed border-gray-300 hover:border-[#F48C5B] hover:bg-[#FEF4F0]/20 rounded-2xl p-3 bg-[#F8F9FA] transition-all text-center min-h-[120px] flex flex-col items-center justify-center">
                                <input type="file" name="ktp_photo" id="ktp_photo" accept="image/*"
                                       {{ $registration->ktp_photo_path ? '' : 'required' }}
                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                       onchange="handleImagePreview(this, 'ktpPreviewBox', 'ktpPlaceholder')">
                                
                                <!-- Preview Box -->
                                <div id="ktpPreviewBox" class="w-full flex flex-col items-center justify-center {{ $registration->ktp_photo_path ? '' : 'hidden' }}">
                                    <div class="relative w-full rounded-xl overflow-hidden shadow-xs border border-gray-200 bg-white p-1">
                                        <img id="ktpPreviewImg" src="{{ $registration->ktp_photo_path ? asset($registration->ktp_photo_path) : '' }}"
                                             alt="Preview Foto KTP" class="w-full h-24 sm:h-28 object-contain rounded-lg"
                                             onerror="handleImgError(this, 'ktpPreviewBox', 'ktpPlaceholder')">
                                    </div>
                                    <span class="inline-flex items-center gap-1 text-[10px] text-[#F48C5B] font-bold mt-1">
                                        <i class="fa-solid fa-arrows-rotate"></i> Klik untuk ganti foto
                                    </span>
                                </div>

                                <!-- Upload Placeholder Icon -->
                                <div id="ktpPlaceholder" class="space-y-1.5 py-1 {{ $registration->ktp_photo_path ? 'hidden' : '' }}">
                                    <div class="w-10 h-10 mx-auto rounded-xl bg-[#FEF4F0] border border-[#F48C5B]/30 text-[#F48C5B] flex items-center justify-center text-lg shadow-xs group-hover:scale-110 transition-transform duration-200">
                                        <i class="fa-solid fa-cloud-arrow-up"></i>
                                    </div>
                                    <div>
                                        <span class="font-extrabold text-[#2C2C2C] block text-xs group-hover:text-[#F48C5B] transition-colors">Unggah Foto KTP</span>
                                        <span class="text-[10px] text-gray-400 block mt-0.5">Klik atau seret file ke sini</span>
                                    </div>
                                </div>
                            </div>
                            <p id="err_ktp_photo" class="field-error-text text-rose-500 text-[11px] font-semibold mt-1 hidden flex items-center gap-1.5">
                                <i class="fa-solid fa-circle-exclamation text-xs"></i>
                                <span>Foto KTP asli wajib diunggah.</span>
                            </p>
                        </div>

                        <!-- 2. Foto Rumah / Bangunan -->
                        <div class="space-y-1.5" id="wrapper_house_photo">
                            <label class="block font-bold text-[#333333]">
                                Foto Rumah / Bangunan <span class="text-rose-500">*</span>
                            </label>

                            <div id="box_house_photo" class="relative group border-2 border-dashed border-gray-300 hover:border-[#F48C5B] hover:bg-[#FEF4F0]/20 rounded-2xl p-3 bg-[#F8F9FA] transition-all text-center min-h-[120px] flex flex-col items-center justify-center">
                                <input type="file" name="house_photo" id="house_photo" accept="image/*"
                                       {{ $registration->house_photo_path ? '' : 'required' }}
                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                       onchange="handleImagePreview(this, 'housePreviewBox', 'housePlaceholder')">
                                
                                <!-- Preview Box -->
                                <div id="housePreviewBox" class="w-full flex flex-col items-center justify-center {{ $registration->house_photo_path ? '' : 'hidden' }}">
                                    <div class="relative w-full rounded-xl overflow-hidden shadow-xs border border-gray-200 bg-white p-1">
                                        <img id="housePreviewImg" src="{{ $registration->house_photo_path ? asset($registration->house_photo_path) : '' }}"
                                             alt="Preview Foto Rumah" class="w-full h-24 sm:h-28 object-contain rounded-lg"
                                             onerror="handleImgError(this, 'housePreviewBox', 'housePlaceholder')">
                                    </div>
                                    <span class="inline-flex items-center gap-1 text-[10px] text-[#F48C5B] font-bold mt-1">
                                        <i class="fa-solid fa-arrows-rotate"></i> Klik untuk ganti foto
                                    </span>
                                </div>

                                <!-- Upload Placeholder Icon -->
                                <div id="housePlaceholder" class="space-y-1.5 py-1 {{ $registration->house_photo_path ? 'hidden' : '' }}">
                                    <div class="w-10 h-10 mx-auto rounded-xl bg-[#FEF4F0] border border-[#F48C5B]/30 text-[#F48C5B] flex items-center justify-center text-lg shadow-xs group-hover:scale-110 transition-transform duration-200">
                                        <i class="fa-solid fa-cloud-arrow-up"></i>
                                    </div>
                                    <div>
                                        <span class="font-extrabold text-[#2C2C2C] block text-xs group-hover:text-[#F48C5B] transition-colors">Unggah Foto Rumah</span>
                                        <span class="text-[10px] text-gray-400 block mt-0.5">Tampak depan bangunan</span>
                                    </div>
                                </div>
                            </div>
                            <p id="err_house_photo" class="field-error-text text-rose-500 text-[11px] font-semibold mt-1 hidden flex items-center gap-1.5">
                                <i class="fa-solid fa-circle-exclamation text-xs"></i>
                                <span>Foto rumah tampak depan wajib diunggah.</span>
                            </p>
                        </div>

                        <!-- 3. Foto Selfie Bersama Sales -->
                        <div class="space-y-1.5" id="wrapper_selfie_sales_photo">
                            <label class="block font-bold text-[#333333]">
                                Foto Selfie Bersama Sales <span class="text-rose-500">*</span>
                            </label>

                            <div id="box_selfie_sales_photo" class="relative group border-2 border-dashed border-gray-300 hover:border-[#F48C5B] hover:bg-[#FEF4F0]/20 rounded-2xl p-3 bg-[#F8F9FA] transition-all text-center min-h-[120px] flex flex-col items-center justify-center">
                                <input type="file" name="selfie_sales_photo" id="selfie_sales_photo" accept="image/*"
                                       {{ $registration->selfie_sales_path ? '' : 'required' }}
                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                       onchange="handleImagePreview(this, 'selfiePreviewBox', 'selfiePlaceholder')">
                                
                                <!-- Preview Box -->
                                <div id="selfiePreviewBox" class="w-full flex flex-col items-center justify-center {{ $registration->selfie_sales_path ? '' : 'hidden' }}">
                                    <div class="relative w-full rounded-xl overflow-hidden shadow-xs border border-gray-200 bg-white p-1">
                                        <img id="selfiePreviewImg" src="{{ $registration->selfie_sales_path ? asset($registration->selfie_sales_path) : '' }}"
                                             alt="Preview Selfie Sales" class="w-full h-24 sm:h-28 object-contain rounded-lg"
                                             onerror="handleImgError(this, 'selfiePreviewBox', 'selfiePlaceholder')">
                                    </div>
                                    <span class="inline-flex items-center gap-1 text-[10px] text-[#F48C5B] font-bold mt-1">
                                        <i class="fa-solid fa-arrows-rotate"></i> Klik untuk ganti foto
                                    </span>
                                </div>

                                <!-- Upload Placeholder Icon -->
                                <div id="selfiePlaceholder" class="space-y-1.5 py-1 {{ $registration->selfie_sales_path ? 'hidden' : '' }}">
                                    <div class="w-10 h-10 mx-auto rounded-xl bg-[#FEF4F0] border border-[#F48C5B]/30 text-[#F48C5B] flex items-center justify-center text-lg shadow-xs group-hover:scale-110 transition-transform duration-200">
                                        <i class="fa-solid fa-cloud-arrow-up"></i>
                                    </div>
                                    <div>
                                        <span class="font-extrabold text-[#2C2C2C] block text-xs group-hover:text-[#F48C5B] transition-colors">Unggah Foto Selfie</span>
                                        <span class="text-[10px] text-gray-400 block mt-0.5">Bersama Petugas Sales</span>
                                    </div>
                                </div>
                            </div>
                            <p id="err_selfie_sales_photo" class="field-error-text text-rose-500 text-[11px] font-semibold mt-1 hidden flex items-center gap-1.5">
                                <i class="fa-solid fa-circle-exclamation text-xs"></i>
                                <span>Foto selfie bersama sales wajib diunggah.</span>
                            </p>
                        </div>

                    </div>
                </div>
            </div>

            <!-- STEP 5: PERSETUJUAN BERLANGGANAN & TANDA TANGAN VIRTUAL -->
            <div id="step-5" class="step-pane hidden transition-all duration-300">
                <div class="glass-card rounded-3xl p-6 sm:p-8 space-y-6">
                    <div class="flex items-center gap-3 border-b border-gray-300 pb-4">
                        <div class="w-9 h-9 rounded-xl bg-[#FEF4F0] border border-[#F48C5B]/40 text-[#F48C5B] flex items-center justify-center font-extrabold text-sm shadow-xs">
                            5
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-[#2C2C2C]">Persetujuan Berlangganan</h3>
                            <p class="hidden sm:block text-xs text-gray-500">Bubuhkan tanda tangan Anda secara virtual pada area canvas di bawah</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <!-- Virtual Signature Pad Canvas Container -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5 text-xs gap-2">
                                <span class="font-bold text-[#333333] truncate">Canvas Tanda Tangan Digital: <span class="text-rose-500">*</span></span>
                                <button type="button" onclick="clearSignature()" class="text-rose-600 hover:text-rose-700 text-xs font-bold flex items-center gap-1 transition-colors cursor-pointer shrink-0">
                                    <i class="fa-solid fa-rotate-left"></i> <span>Bersihkan TTD</span>
                                </button>
                            </div>
                            
                            <div id="box_signature_pad" class="rounded-2xl border-2 border-dashed border-gray-300 bg-white p-2 overflow-hidden shadow-inner relative transition-colors">
                                <canvas id="signaturePad" class="w-full h-40 sm:h-44 cursor-crosshair bg-white rounded-xl touch-none block"></canvas>
                                <input type="hidden" name="signature_data" id="signatureData">
                                
                                <div id="signatureGuideText" class="absolute bottom-2.5 right-3 left-3 sm:left-auto sm:right-4 text-center sm:text-right text-[10px] text-gray-400 pointer-events-none transition-opacity duration-200">
                                    <i class="fa-solid fa-pen-nib mr-1 text-[#F48C5B]"></i> Gunakan jari / stylus / mouse untuk menandatangani
                                </div>
                            </div>
                            <p id="err_signature_data" class="field-error-text text-rose-500 text-[11px] font-semibold mt-1 hidden flex items-center gap-1.5">
                                <i class="fa-solid fa-circle-exclamation text-xs"></i>
                                <span>Silakan bubuhkan tanda tangan digital Anda pada area canvas di atas.</span>
                            </p>
                        </div>

                        <!-- Terms Checkbox -->
                        <div class="pt-2" id="wrapper_terms_agreed">
                            <label class="flex items-start gap-2.5 sm:gap-3 cursor-pointer text-xs text-gray-700 leading-relaxed">
                                <input type="checkbox" name="terms_agreed" id="terms_agreed" value="1" required
                                       class="w-4 h-4 rounded bg-white border-gray-300 text-[#F48C5B] focus:ring-[#F48C5B] mt-0.5 cursor-pointer shrink-0">
                                <span class="text-[11px] sm:text-xs text-justify">
                                    Saya menyatakan bahwa seluruh data yang saya isikan adalah benar dan valid. Saya menyetujui seluruh <strong>Syarat &amp; Ketentuan Berlangganan Layanan LifeMedia Fiber</strong> serta bersedia mematuhi kewajiban pembayaran tagihan bulanan. <span class="text-rose-500">*</span>
                                </span>
                            </label>
                            <p id="err_terms_agreed" class="field-error-text text-rose-500 text-[11px] font-semibold mt-1.5 hidden flex items-center gap-1.5">
                                <i class="fa-solid fa-circle-exclamation text-xs"></i>
                                <span>Anda harus mencentang persetujuan syarat &amp; ketentuan untuk melanjutkan.</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step Navigation Controls (Previous & Next / Submit) -->
            <div class="glass-card rounded-2xl p-2.5 sm:p-4 shadow-sm border border-gray-200 bg-white sticky bottom-3 z-20 backdrop-blur-md">
                <div class="flex items-center justify-between gap-2 sm:gap-3">
                    
                    <!-- Left: Tombol Sebelumnya -->
                    <div class="shrink-0">
                        <button type="button" id="prevBtn" onclick="changeStep(-1)"
                                class="hidden px-3 sm:px-5 py-2.5 sm:py-3 rounded-xl border border-gray-300 bg-white hover:bg-gray-50 text-[#333333] font-bold text-xs sm:text-sm inline-flex items-center gap-1.5 transition-all cursor-pointer">
                            <i class="fa-solid fa-arrow-left text-xs"></i>
                            <span class="hidden sm:inline">Sebelumnya</span>
                        </button>
                    </div>

                    <!-- Center / Samping Kiri Tombol Lanjut: Pesan Panduan Pengisian -->
                    <div id="navValidationStatus" class="flex-1 min-w-0 flex items-center justify-center gap-1.5 sm:gap-2 px-2.5 py-2 sm:px-4 sm:py-2.5 rounded-xl text-[10px] sm:text-xs font-semibold text-center transition-all bg-amber-50 border border-amber-200/80 text-amber-800 leading-snug">
                        <i id="navStatusIcon" class="fa-solid fa-circle-info text-amber-600 shrink-0 text-xs"></i>
                        <span id="navStatusText" class="truncate sm:whitespace-normal">Pastikan kolom wajib (*) terisi</span>
                    </div>

                    <!-- Right: Tombol Lanjut / Kirim -->
                    <div class="shrink-0 flex items-center justify-end gap-2">
                        <button type="button" id="nextBtn" onclick="changeStep(1)"
                                class="px-4 sm:px-7 py-2.5 sm:py-3 rounded-xl font-bold text-xs sm:text-sm inline-flex items-center justify-center gap-1.5 transition-all cursor-pointer">
                            <span>Lanjut</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </button>

                        <button type="submit" id="submitBtn"
                                class="hidden px-4 sm:px-7 py-2.5 sm:py-3 rounded-xl font-bold text-xs sm:text-sm inline-flex items-center justify-center transition-all cursor-pointer">
                            <span>{{ $registration->status === 'revision' ? 'Kirim Revisi' : 'Kirim' }}</span>
                        </button>
                    </div>

                </div>
            </div>

        </form>

    </main>

    <!-- JavaScript Helpers -->
    <script>
        // Multi-Step Form Controller & Realtime Validator
        const storageKeyStep = 'lc_step_{{ $registration->token }}';
        const storageKeyDraft = 'lc_draft_{{ $registration->token }}';
        const hasServerErrors = {{ $errors->any() ? 'true' : 'false' }};
        const serverInitialStep = {{ $initialStep }};

        let currentStep = serverInitialStep;
        if (!hasServerErrors) {
            try {
                const savedStep = localStorage.getItem(storageKeyStep);
                if (savedStep) {
                    const parsed = parseInt(savedStep, 10);
                    if (parsed >= 1 && parsed <= 5) {
                        currentStep = parsed;
                    }
                }
            } catch(e) {}
        }

        const totalSteps = 5;
        const stepTitles = [
            "Data Pribadi",
            "Layanan & Paket",
            "Penagihan",
            "Foto Dokumen",
            "Persetujuan"
        ];
        const existingSigPath = @json($registration->signature_path);
        const existingKtpPath = @json($registration->ktp_photo_path);
        const existingHousePath = @json($registration->house_photo_path);
        const existingSelfiePath = @json($registration->selfie_sales_path);

        let isRestoring = false;

        // Save form draft data to localStorage
        function saveFormDataToLocal() {
            if (isRestoring) return;
            try {
                const formData = {
                    customer_name: document.getElementById('customer_name')?.value || '',
                    brand_name: document.getElementById('brand_name')?.value || '',
                    identity_type: document.querySelector('input[name="identity_type"]:checked')?.value || '',
                    identity_number: document.getElementById('identity_number')?.value || '',
                    birth_date: document.getElementById('birth_date')?.value || '',
                    gender: document.querySelector('input[name="gender"]:checked')?.value || '',
                    phone_telp: document.getElementById('phone_telp')?.value || '',
                    phone_wa: document.getElementById('phone_wa')?.value || '',
                    email: document.getElementById('email')?.value || '',
                    address_detail: document.getElementById('address_detail')?.value || '',
                    subscription_period: document.getElementById('subscription_period')?.value || '',

                    tv_opt1: document.getElementById('tv_opt1')?.checked || false,
                    tv_text1: document.getElementById('tv_text1')?.value || '',
                    tv_opt2: document.getElementById('tv_opt2')?.checked || false,
                    tv_text2: document.getElementById('tv_text2')?.value || '',

                    net_opt1: document.getElementById('net_opt1')?.checked || false,
                    net_text1: document.getElementById('net_text1')?.value || '',
                    net_opt2: document.getElementById('net_opt2')?.checked || false,
                    net_text2: document.getElementById('net_text2')?.value || '',

                    tel_opt1: document.getElementById('tel_opt1')?.checked || false,
                    tel_text1: document.getElementById('tel_text1')?.value || '',
                    tel_opt2: document.getElementById('tel_opt2')?.checked || false,
                    tel_text2: document.getElementById('tel_text2')?.value || '',

                    billing_name: document.getElementById('billing_name')?.value || '',
                    billing_email: document.getElementById('billing_email')?.value || '',
                    billing_phone: document.getElementById('billing_phone')?.value || '',
                    billing_mobile: document.getElementById('billing_mobile')?.value || '',
                    billing_address: document.getElementById('billing_address')?.value || '',

                    signature_data: document.getElementById('signatureData')?.value || '',
                    terms_agreed: document.getElementById('terms_agreed')?.checked || false
                };
                localStorage.setItem(storageKeyDraft, JSON.stringify(formData));
            } catch (err) {
                console.warn('LocalStorage save error:', err);
            }
        }

        // Restore form draft data from localStorage
        function restoreFormDataFromLocal() {
            isRestoring = true;
            try {
                const savedRaw = localStorage.getItem(storageKeyDraft);
                if (!savedRaw) return;
                const saved = JSON.parse(savedRaw);
                if (!saved || typeof saved !== 'object') return;

                const setVal = (id, val) => {
                    const el = document.getElementById(id);
                    if (el && val !== undefined && val !== null) {
                        el.value = val;
                    }
                };

                if (saved.customer_name !== undefined) setVal('customer_name', saved.customer_name);
                if (saved.brand_name !== undefined) setVal('brand_name', saved.brand_name);
                if (saved.identity_number !== undefined) setVal('identity_number', saved.identity_number);
                if (saved.birth_date !== undefined) setVal('birth_date', saved.birth_date);
                if (saved.phone_telp !== undefined) setVal('phone_telp', saved.phone_telp);
                if (saved.phone_wa !== undefined) setVal('phone_wa', saved.phone_wa);
                if (saved.email !== undefined) setVal('email', saved.email);
                if (saved.address_detail !== undefined) setVal('address_detail', saved.address_detail);
                if (saved.subscription_period !== undefined) setVal('subscription_period', saved.subscription_period);

                if (saved.billing_name !== undefined) setVal('billing_name', saved.billing_name);
                if (saved.billing_email !== undefined) setVal('billing_email', saved.billing_email);
                if (saved.billing_phone !== undefined) setVal('billing_phone', saved.billing_phone);
                if (saved.billing_mobile !== undefined) setVal('billing_mobile', saved.billing_mobile);
                if (saved.billing_address !== undefined) setVal('billing_address', saved.billing_address);

                // Restore Radios
                if (saved.identity_type) {
                    const r = document.querySelector(`input[name="identity_type"][value="${saved.identity_type}"]`);
                    if (r) {
                        r.checked = true;
                        updateIdTypeSelection(r);
                    }
                }
                if (saved.gender) {
                    const r = document.querySelector(`input[name="gender"][value="${saved.gender}"]`);
                    if (r) {
                        r.checked = true;
                        updateGenderSelection(r);
                    }
                }

                // Restore Services
                if (saved.tv_opt1 !== undefined) {
                    const cb = document.getElementById('tv_opt1');
                    if (cb) cb.checked = !!saved.tv_opt1;
                }
                if (saved.tv_text1 !== undefined) setVal('tv_text1', saved.tv_text1);

                if (saved.tv_opt2 !== undefined) {
                    const cb = document.getElementById('tv_opt2');
                    if (cb) cb.checked = !!saved.tv_opt2;
                }
                if (saved.tv_text2 !== undefined) setVal('tv_text2', saved.tv_text2);

                if (saved.net_opt1 !== undefined) {
                    const cb = document.getElementById('net_opt1');
                    if (cb) cb.checked = !!saved.net_opt1;
                }
                if (saved.net_text1 !== undefined) setVal('net_text1', saved.net_text1);

                if (saved.net_opt2 !== undefined) {
                    const cb = document.getElementById('net_opt2');
                    if (cb) cb.checked = !!saved.net_opt2;
                }
                if (saved.net_text2 !== undefined) setVal('net_text2', saved.net_text2);

                if (saved.tel_opt1 !== undefined) {
                    const cb = document.getElementById('tel_opt1');
                    if (cb) cb.checked = !!saved.tel_opt1;
                }
                if (saved.tel_text1 !== undefined) setVal('tel_text1', saved.tel_text1);

                if (saved.tel_opt2 !== undefined) {
                    const cb = document.getElementById('tel_opt2');
                    if (cb) cb.checked = !!saved.tel_opt2;
                }
                if (saved.tel_text2 !== undefined) setVal('tel_text2', saved.tel_text2);

                // Auto-sync checkboxes if text/select is filled
                [
                    { cb: document.getElementById('tv_opt1'), text: document.getElementById('tv_text1') },
                    { cb: document.getElementById('tv_opt2'), text: document.getElementById('tv_text2') },
                    { cb: document.getElementById('net_opt1'), text: document.getElementById('net_text1') },
                    { cb: document.getElementById('net_opt2'), text: document.getElementById('net_text2') },
                    { cb: document.getElementById('tel_opt1'), text: document.getElementById('tel_text1') },
                    { cb: document.getElementById('tel_opt2'), text: document.getElementById('tel_text2') }
                ].forEach(p => {
                    if (p.cb && p.text && p.text.value.trim() !== '') {
                        p.cb.checked = true;
                    }
                });

                // Restore Terms Agreement
                if (saved.terms_agreed !== undefined) {
                    const terms = document.getElementById('terms_agreed');
                    if (terms) terms.checked = !!saved.terms_agreed;
                }

                // Restore Virtual Signature
                if (saved.signature_data && saved.signature_data.length > 50) {
                    const sigInput = document.getElementById('signatureData');
                    if (sigInput) sigInput.value = saved.signature_data;
                    hasSignature = true;
                    if (signatureGuideText) signatureGuideText.style.opacity = '0.15';
                }
            } catch (err) {
                console.warn('LocalStorage restore error:', err);
            } finally {
                isRestoring = false;
            }
        }

        // Validation Rules Definition
        function validateSingleField(fieldId, showMessage = false) {
            let isValid = true;
            let el = document.getElementById(fieldId);
            let errEl = document.getElementById(`err_${fieldId}`);

            switch (fieldId) {
                case 'customer_name':
                    isValid = el && el.value.trim().length >= 2;
                    break;
                case 'identity_type': {
                    const checked = document.querySelector('input[name="identity_type"]:checked');
                    isValid = !!checked;
                    el = document.getElementById('identityTypeGroup');
                    break;
                }
                case 'identity_number':
                    isValid = el && el.value.trim().length >= 6;
                    break;
                case 'birth_date': {
                    const errSpan = document.getElementById('err_birth_date_text');
                    if (!el || !el.value || el.value.trim() === '') {
                        isValid = false;
                        if (errSpan) errSpan.textContent = "Tanggal lahir wajib dipilih.";
                    } else {
                        const today = new Date();
                        const todayStr = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;
                        if (el.value > todayStr) {
                            isValid = false;
                            if (errSpan) errSpan.textContent = "Tanggal lahir tidak boleh melebihi tanggal hari ini.";
                        } else {
                            isValid = true;
                        }
                    }
                    break;
                }
                case 'gender': {
                    const checked = document.querySelector('input[name="gender"]:checked');
                    isValid = !!checked;
                    el = document.getElementById('genderGroup');
                    break;
                }
                case 'phone_wa': {
                    const clean = el ? el.value.replace(/[^0-9]/g, '') : '';
                    isValid = el && clean.length >= 8;
                    break;
                }
                case 'email': {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    isValid = el && emailRegex.test(el.value.trim());
                    break;
                }
                case 'address_detail':
                    isValid = el && el.value.trim().length >= 5;
                    break;
                case 'services_selected': {
                    const pairs = [
                        { cb: document.getElementById('tv_opt1'), text: document.getElementById('tv_text1') },
                        { cb: document.getElementById('tv_opt2'), text: document.getElementById('tv_text2') },
                        { cb: document.getElementById('net_opt1'), text: document.getElementById('net_text1') },
                        { cb: document.getElementById('net_opt2'), text: document.getElementById('net_text2') },
                        { cb: document.getElementById('tel_opt1'), text: document.getElementById('tel_text1') },
                        { cb: document.getElementById('tel_opt2'), text: document.getElementById('tel_text2') }
                    ];

                    let checkedCount = 0;
                    let hasMissingText = false;
                    let firstMissingTextEl = null;

                    pairs.forEach(p => {
                        if (p.cb && p.cb.checked) {
                            checkedCount++;
                            const val = p.text ? p.text.value.trim() : '';
                            if (val.length === 0) {
                                hasMissingText = true;
                                if (!firstMissingTextEl) firstMissingTextEl = p.text;
                                if (showMessage && p.text) {
                                    p.text.classList.add('border-rose-400', 'bg-rose-50/20', 'focus:ring-rose-400', 'focus:border-rose-400');
                                    p.text.classList.remove('border-gray-200', 'bg-gray-50', 'focus:ring-[#F48C5B]', 'focus:border-[#F48C5B]');
                                }
                            } else if (p.text) {
                                p.text.classList.remove('border-rose-400', 'bg-rose-50/20', 'focus:ring-rose-400', 'focus:border-rose-400');
                                p.text.classList.add('border-gray-200', 'bg-gray-50', 'focus:ring-[#F48C5B]', 'focus:border-[#F48C5B]');
                            }
                        } else if (p.text) {
                            p.text.classList.remove('border-rose-400', 'bg-rose-50/20', 'focus:ring-rose-400', 'focus:border-rose-400');
                            p.text.classList.add('border-gray-200', 'bg-gray-50', 'focus:ring-[#F48C5B]', 'focus:border-[#F48C5B]');
                        }
                    });

                    isValid = checkedCount > 0 && !hasMissingText;
                    el = firstMissingTextEl || document.getElementById('box_services_selection');
                    errEl = document.getElementById('err_services_selected');

                    const errSpan = errEl ? errEl.querySelector('span') : null;
                    if (errSpan) {
                        if (checkedCount === 0) {
                            errSpan.textContent = "Pilih minimal 1 paket layanan berlangganan.";
                        } else if (hasMissingText) {
                            errSpan.textContent = "Mohon isi keterangan/paket untuk setiap layanan yang Anda centang.";
                        }
                    }
                    break;
                }
                case 'subscription_period':
                    isValid = el && parseInt(el.value) >= 1;
                    break;
                case 'billing_name':
                    isValid = el && el.value.trim().length >= 2;
                    break;
                case 'billing_email': {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    isValid = el && emailRegex.test(el.value.trim());
                    break;
                }
                case 'billing_mobile': {
                    const clean = el ? el.value.replace(/[^0-9]/g, '') : '';
                    isValid = el && clean.length >= 8;
                    break;
                }
                case 'billing_address':
                    isValid = el && el.value.trim().length >= 5;
                    break;
                case 'ktp_photo': {
                    const hasFile = el && el.files && el.files.length > 0;
                    isValid = hasFile || !!existingKtpPath;
                    el = document.getElementById('box_ktp_photo');
                    break;
                }
                case 'house_photo': {
                    const hasFile = el && el.files && el.files.length > 0;
                    isValid = hasFile || !!existingHousePath;
                    el = document.getElementById('box_house_photo');
                    break;
                }
                case 'selfie_sales_photo': {
                    const hasFile = el && el.files && el.files.length > 0;
                    isValid = hasFile || !!existingSelfiePath;
                    el = document.getElementById('box_selfie_sales_photo');
                    break;
                }
                case 'signature_data': {
                    const hasSig = (signatureDataInput && signatureDataInput.value.length > 50) || (allStrokes && allStrokes.length > 0) || !!existingSigPath;
                    isValid = hasSig;
                    el = document.getElementById('box_signature_pad');
                    break;
                }
                case 'terms_agreed': {
                    isValid = el && el.checked;
                    el = document.getElementById('wrapper_terms_agreed');
                    break;
                }
            }

            // Apply visual styles
            if (errEl) {
                if (isValid) {
                    errEl.classList.add('hidden');
                } else if (showMessage) {
                    errEl.classList.remove('hidden');
                }
            }

            if (el) {
                if (isValid) {
                    if (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA') {
                        el.classList.remove('border-rose-400', 'bg-rose-50/20', 'focus:ring-rose-400', 'focus:border-rose-400');
                        el.classList.add('border-gray-300', 'bg-white', 'focus:ring-[#F48C5B]', 'focus:border-[#F48C5B]');
                    } else if (el.id && el.id.startsWith('box_')) {
                        el.classList.remove('border-rose-400', 'bg-rose-50/30');
                        el.classList.add('border-gray-300', 'bg-[#F8F9FA]');
                    }
                } else if (showMessage) {
                    if (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA') {
                        el.classList.add('border-rose-400', 'bg-rose-50/20', 'focus:ring-rose-400', 'focus:border-rose-400');
                        el.classList.remove('border-gray-300', 'bg-white', 'focus:ring-[#F48C5B]', 'focus:border-[#F48C5B]');
                    } else if (el.id && el.id.startsWith('box_')) {
                        el.classList.add('border-rose-400', 'bg-rose-50/30');
                        el.classList.remove('border-gray-300', 'bg-[#F8F9FA]');
                    }
                }
            }

            return isValid;
        }

        // Get fields for specific step
        function getStepFields(step) {
            switch (step) {
                case 1:
                    return ['customer_name', 'identity_type', 'identity_number', 'birth_date', 'gender', 'phone_wa', 'email', 'address_detail'];
                case 2:
                    return ['services_selected', 'subscription_period'];
                case 3:
                    return ['billing_name', 'billing_email', 'billing_mobile', 'billing_address'];
                case 4:
                    return ['ktp_photo', 'house_photo', 'selfie_sales_photo'];
                case 5:
                    return ['signature_data', 'terms_agreed'];
                default:
                    return [];
            }
        }

        // Evaluate entire step validity
        function checkStepValidity(step, showErrors = false) {
            const fields = getStepFields(step);
            let isAllValid = true;
            let firstInvalidEl = null;

            for (const fieldId of fields) {
                const valid = validateSingleField(fieldId, showErrors);
                if (!valid) {
                    isAllValid = false;
                    if (!firstInvalidEl) {
                        let target = document.getElementById(fieldId);
                        if (!target || target.type === 'radio' || target.type === 'file' || target.type === 'hidden') {
                            target = document.getElementById(`container_${fieldId}`) || 
                                     document.getElementById(`wrapper_${fieldId}`) || 
                                     document.getElementById(`box_${fieldId}`) || 
                                     target;
                        }
                        firstInvalidEl = target;
                    }
                }
            }

            return { isValid: isAllValid, firstInvalidElement: firstInvalidEl };
        }

        // Real-time button state switcher (disabled grey vs vibrant gradient)
        function refreshButtonStates() {
            const { isValid } = checkStepValidity(currentStep, false);
            const nextBtn = document.getElementById('nextBtn');
            const submitBtn = document.getElementById('submitBtn');

            const validNextClass = "px-4 sm:px-7 py-2.5 sm:py-3 rounded-xl bg-gradient-to-r from-[#F48C5B] to-[#EF666B] hover:from-[#EF666B] hover:to-[#F48C5B] text-white font-extrabold text-xs sm:text-sm inline-flex items-center justify-center gap-1.5 shadow-md shadow-orange-500/20 hover:shadow-orange-500/30 transition-all cursor-pointer";
            const invalidNextClass = "px-4 sm:px-7 py-2.5 sm:py-3 rounded-xl bg-gray-200 hover:bg-gray-300 text-gray-400 hover:text-gray-600 font-bold text-xs sm:text-sm inline-flex items-center justify-center gap-1.5 shadow-none transition-all cursor-pointer border border-gray-300/80";

            const validSubmitClass = "px-4 sm:px-7 py-2.5 sm:py-3 rounded-xl bg-gradient-to-r from-[#F48C5B] via-[#EF666B] to-[#9B385B] hover:from-[#EF666B] hover:to-[#F48C5B] text-white font-extrabold text-xs sm:text-sm inline-flex items-center justify-center shadow-xl shadow-orange-500/25 hover:shadow-orange-500/35 transition-all cursor-pointer";
            const invalidSubmitClass = "px-4 sm:px-7 py-2.5 sm:py-3 rounded-xl bg-gray-200 hover:bg-gray-300 text-gray-400 hover:text-gray-600 font-bold text-xs sm:text-sm inline-flex items-center justify-center shadow-none transition-all cursor-pointer border border-gray-300/80";

            if (nextBtn) {
                nextBtn.className = (isValid ? validNextClass : invalidNextClass) + (currentStep === totalSteps ? ' hidden' : '');
            }
            if (submitBtn) {
                submitBtn.className = (isValid ? validSubmitClass : invalidSubmitClass) + (currentStep !== totalSteps ? ' hidden' : '');
            }

            // Update Validation Status Reminder Banner
            const navStatusBox = document.getElementById('navValidationStatus');
            const navStatusIcon = document.getElementById('navStatusIcon');
            const navStatusText = document.getElementById('navStatusText');

            if (navStatusBox && navStatusText) {
                if (isValid) {
                    navStatusBox.className = "flex-1 min-w-0 flex items-center justify-center gap-1.5 sm:gap-2 px-2.5 py-2 sm:px-4 sm:py-2.5 rounded-xl text-[10px] sm:text-xs font-semibold text-center transition-all bg-emerald-50 border border-emerald-200 text-emerald-800 leading-snug";
                    if (navStatusIcon) navStatusIcon.className = "fa-solid fa-circle-check text-emerald-600 shrink-0 text-xs";
                    navStatusText.textContent = (currentStep === totalSteps) 
                        ? "Data & TTD lengkap. Klik Kirim." 
                        : "Data lengkap. Klik Lanjut.";
                } else {
                    navStatusBox.className = "flex-1 min-w-0 flex items-center justify-center gap-1.5 sm:gap-2 px-2.5 py-2 sm:px-4 sm:py-2.5 rounded-xl text-[10px] sm:text-xs font-semibold text-center transition-all bg-amber-50 border border-amber-200/80 text-amber-800 leading-snug";
                    if (navStatusIcon) navStatusIcon.className = "fa-solid fa-circle-info text-amber-600 shrink-0 text-xs";
                    navStatusText.textContent = "Pastikan kolom wajib (*) terisi";
                }
            }
        }

        // Jump to element with smooth highlight animation
        function jumpToElement(element) {
            if (!element) return;
            
            const headerOffset = 95;
            const elementPosition = element.getBoundingClientRect().top;
            const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

            window.scrollTo({
                top: Math.max(0, offsetPosition),
                behavior: 'smooth'
            });

            // Focus if focusable
            if (element.tagName === 'INPUT' || element.tagName === 'TEXTAREA' || element.tagName === 'SELECT') {
                setTimeout(() => element.focus(), 300);
            }

            // Pulse highlight ring
            element.classList.add('ring-4', 'ring-rose-400/50', 'transition-all', 'duration-300');
            setTimeout(() => {
                element.classList.remove('ring-4', 'ring-rose-400/50');
            }, 1800);
        }

        function updateStepUI() {
            // Save current step to localStorage
            try {
                localStorage.setItem(storageKeyStep, currentStep);
            } catch(e) {}

            // 1. Show / Hide Step Panes
            for (let i = 1; i <= totalSteps; i++) {
                const pane = document.getElementById(`step-${i}`);
                if (pane) {
                    if (i === currentStep) {
                        pane.classList.remove('hidden');
                    } else {
                        pane.classList.add('hidden');
                    }
                }
            }

            // 2. Update Mobile Step Indicator
            const mobileBadge = document.getElementById('mobileStepBadge');
            const mobileTitle = document.getElementById('mobileStepTitle');
            const mobileNum = document.getElementById('mobileStepNum');
            const mobileProgressBar = document.getElementById('mobileProgressBar');

            if (mobileBadge) mobileBadge.textContent = currentStep;
            if (mobileTitle) mobileTitle.textContent = stepTitles[currentStep - 1];
            if (mobileNum) mobileNum.textContent = currentStep;
            if (mobileProgressBar) {
                mobileProgressBar.style.width = `${(currentStep / totalSteps) * 100}%`;
            }

            // 3. Update Desktop Step Tabs
            for (let i = 1; i <= totalSteps; i++) {
                const tab = document.getElementById(`stepTab${i}`);
                const badge = document.getElementById(`stepBadge${i}`);
                if (!tab || !badge) continue;

                if (i === currentStep) {
                    tab.className = 'step-tab text-left p-2.5 rounded-xl border transition-all flex items-center gap-2.5 bg-[#FEF4F0] border-[#F48C5B] shadow-xs cursor-pointer';
                    badge.className = 'w-7 h-7 rounded-lg bg-gradient-to-br from-[#F48C5B] to-[#EF666B] text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-xs';
                    badge.innerHTML = `${i}`;
                    const label = tab.querySelector('.text-xs');
                    if (label) label.className = 'text-xs font-bold text-[#2C2C2C] truncate';
                } else if (i < currentStep) {
                    tab.className = 'step-tab text-left p-2.5 rounded-xl border transition-all flex items-center gap-2.5 bg-emerald-50/60 border-emerald-300 hover:bg-emerald-50 shadow-2xs cursor-pointer';
                    badge.className = 'w-7 h-7 rounded-lg bg-emerald-600 text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-xs';
                    badge.innerHTML = '<i class="fa-solid fa-check text-xs"></i>';
                    const label = tab.querySelector('.text-xs');
                    if (label) label.className = 'text-xs font-bold text-emerald-800 truncate';
                } else {
                    tab.className = 'step-tab text-left p-2.5 rounded-xl border border-gray-200 bg-gray-50/50 hover:bg-gray-100/80 transition-all flex items-center gap-2.5 cursor-pointer';
                    badge.className = 'w-7 h-7 rounded-lg bg-gray-200 text-gray-500 flex items-center justify-center font-bold text-xs shrink-0';
                    badge.innerHTML = `${i}`;
                    const label = tab.querySelector('.text-xs');
                    if (label) label.className = 'text-xs font-bold text-gray-600 truncate';
                }
            }

            // 4. Update Navigation Buttons
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const submitBtn = document.getElementById('submitBtn');
            const currentStepText = document.getElementById('currentStepText');

            if (currentStepText) currentStepText.textContent = currentStep;

            if (prevBtn) {
                if (currentStep === 1) {
                    prevBtn.disabled = true;
                    prevBtn.classList.add('hidden');
                } else {
                    prevBtn.disabled = false;
                    prevBtn.classList.remove('hidden');
                }
            }

            if (currentStep === totalSteps) {
                if (nextBtn) nextBtn.classList.add('hidden');
                if (submitBtn) submitBtn.classList.remove('hidden');
            } else {
                if (nextBtn) nextBtn.classList.remove('hidden');
                if (submitBtn) submitBtn.classList.add('hidden');
            }

            // 5. If Step 5 is active, resize signature canvas so it calculates proper dimensions
            if (currentStep === 5) {
                setTimeout(() => {
                    resizeCanvas(true);
                }, 80);
            }

            // 6. Realtime validator: immediately evaluate & show warnings for active step
            checkStepValidity(currentStep, true);
            refreshButtonStates();
        }

        function changeStep(delta) {
            saveFormDataToLocal();
            if (delta > 0) {
                const { isValid, firstInvalidElement } = checkStepValidity(currentStep, true);
                if (!isValid) {
                    refreshButtonStates();
                    jumpToElement(firstInvalidElement);
                    return;
                }
            }
            const newStep = currentStep + delta;
            if (newStep >= 1 && newStep <= totalSteps) {
                currentStep = newStep;
                updateStepUI();
                saveFormDataToLocal();
                scrollToTopStep();
            }
        }

        function goToStep(targetStep) {
            saveFormDataToLocal();
            if (targetStep < currentStep) {
                currentStep = targetStep;
                updateStepUI();
                saveFormDataToLocal();
                scrollToTopStep();
            } else if (targetStep > currentStep) {
                for (let s = currentStep; s < targetStep; s++) {
                    const { isValid, firstInvalidElement } = checkStepValidity(s, true);
                    if (!isValid) {
                        currentStep = s;
                        updateStepUI();
                        saveFormDataToLocal();
                        jumpToElement(firstInvalidElement);
                        return;
                    }
                }
                currentStep = targetStep;
                updateStepUI();
                saveFormDataToLocal();
                scrollToTopStep();
            }
        }

        function scrollToTopStep() {
            const stepper = document.getElementById('stepperContainer');
            if (stepper) {
                const rect = stepper.getBoundingClientRect();
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                window.scrollTo({
                    top: Math.max(0, scrollTop + rect.top - 80),
                    behavior: 'smooth'
                });
            }
        }

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
            validateSingleField('identity_type', true);
            refreshButtonStates();
            saveFormDataToLocal();
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
            validateSingleField('gender', true);
            refreshButtonStates();
            saveFormDataToLocal();
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

            validateSingleField(input.id, true);
            refreshButtonStates();

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
        let allStrokes = [];
        let currentStroke = [];
        
        // Stabilizer parameters
        const STABILIZER_WEIGHT = 0.38;
        const MIN_DISTANCE = 1.8;
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
            
            if (rect.width === 0 || rect.height === 0) return;

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

            if (preserveDrawing) {
                if (allStrokes.length > 0) {
                    redrawAllStrokes();
                } else if (signatureDataInput && signatureDataInput.value && signatureDataInput.value.startsWith('data:image')) {
                    const img = new Image();
                    img.onload = function() {
                        if (ctx && canvas) {
                            const ratio = Math.max(window.devicePixelRatio || 1, 1);
                            ctx.drawImage(img, 0, 0, canvas.width / ratio, canvas.height / ratio);
                        }
                    };
                    img.src = signatureDataInput.value;
                }
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
            
            smoothedPos.x = smoothedPos.x + (raw.x - smoothedPos.x) * STABILIZER_WEIGHT;
            smoothedPos.y = smoothedPos.y + (raw.y - smoothedPos.y) * STABILIZER_WEIGHT;
            
            const dx = smoothedPos.x - lastPoint.x;
            const dy = smoothedPos.y - lastPoint.y;
            const dist = Math.hypot(dx, dy);
            
            if (dist < MIN_DISTANCE) return;
            
            const dt = Math.max(raw.time - lastTime, 1);
            const velocity = dist / dt;
            
            const targetWidth = Math.max(MIN_LINE_WIDTH, Math.min(MAX_LINE_WIDTH, MAX_LINE_WIDTH - (velocity * 0.4)));
            currentWidth = currentWidth * 0.75 + targetWidth * 0.25;
            
            const newPoint = { x: smoothedPos.x, y: smoothedPos.y, width: currentWidth };
            currentStroke.push(newPoint);
            
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
            
            if (currentStroke.length === 1) {
                ctx.beginPath();
                ctx.arc(currentStroke[0].x, currentStroke[0].y, currentStroke[0].width / 2, 0, Math.PI * 2);
                ctx.fillStyle = '#2C2C2C';
                ctx.fill();
            } else if (currentStroke.length > 1) {
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
            
            try {
                const webpData = canvas.toDataURL('image/webp', 0.85);
                signatureDataInput.value = (webpData && webpData.startsWith('data:image/webp')) 
                    ? webpData 
                    : canvas.toDataURL('image/png');
            } catch (err) {
                signatureDataInput.value = canvas.toDataURL('image/png');
            }

            validateSingleField('signature_data', false);
            refreshButtonStates();
            saveFormDataToLocal();
        }

        function initCanvasEngine() {
            if (currentStep === 5) {
                resizeCanvas(false);
            }
            
            if (canvas) {
                canvas.addEventListener('pointerdown', startStroke, { passive: false });
                window.addEventListener('pointermove', moveStroke, { passive: false });
                window.addEventListener('pointerup', endStroke);
                window.addEventListener('pointercancel', endStroke);
                
                canvas.addEventListener('touchstart', startStroke, { passive: false });
                canvas.addEventListener('touchmove', moveStroke, { passive: false });
                canvas.addEventListener('touchend', endStroke);
            }
        }

        function clearSignature() {
            allStrokes = [];
            currentStroke = [];
            hasSignature = false;
            if (ctx) {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
            }
            signatureDataInput.value = '';
            if (signatureGuideText) signatureGuideText.style.opacity = '1';

            validateSingleField('signature_data', true);
            refreshButtonStates();
            saveFormDataToLocal();
        }

        // Setup real-time input event listeners and auto-saving
        function initRealtimeValidator() {
            const allFieldIds = [
                'customer_name', 'brand_name', 'identity_number', 'birth_date', 'phone_telp', 'phone_wa', 'email', 'address_detail',
                'subscription_period',
                'billing_name', 'billing_email', 'billing_phone', 'billing_mobile', 'billing_address',
                'ktp_photo', 'house_photo', 'selfie_sales_photo',
                'terms_agreed'
            ];

            allFieldIds.forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    const eventType = (el.type === 'checkbox' || el.type === 'radio' || el.type === 'date' || el.type === 'file') ? 'change' : 'input';
                    el.addEventListener(eventType, () => {
                        validateSingleField(id, true);
                        refreshButtonStates();
                        saveFormDataToLocal();
                    });
                    el.addEventListener('blur', () => {
                        validateSingleField(id, true);
                        refreshButtonStates();
                        saveFormDataToLocal();
                    });
                }
            });

            // Service checkboxes & note input fields event listeners
            const servicePairs = [
                { cb: document.getElementById('tv_opt1'), text: document.getElementById('tv_text1') },
                { cb: document.getElementById('tv_opt2'), text: document.getElementById('tv_text2') },
                { cb: document.getElementById('net_opt1'), text: document.getElementById('net_text1') },
                { cb: document.getElementById('net_opt2'), text: document.getElementById('net_text2') },
                { cb: document.getElementById('tel_opt1'), text: document.getElementById('tel_text1') },
                { cb: document.getElementById('tel_opt2'), text: document.getElementById('tel_text2') }
            ];

            servicePairs.forEach(p => {
                if (p.cb) {
                    p.cb.addEventListener('change', () => {
                        if (p.cb.checked && p.text && p.text.value.trim() === '') {
                            p.text.focus();
                        }
                        validateSingleField('services_selected', true);
                        refreshButtonStates();
                        saveFormDataToLocal();
                    });
                }
                if (p.text) {
                    ['input', 'change', 'blur'].forEach(evt => {
                        p.text.addEventListener(evt, () => {
                            if (p.cb && p.text.value.trim() !== '') {
                                p.cb.checked = true;
                            }
                            if (p.text.id === 'net_text1') {
                                const selectedOpt = p.text.options ? p.text.options[p.text.selectedIndex] : null;
                                const pkgId = selectedOpt ? selectedOpt.getAttribute('data-package-id') : '';
                                const pkgIdInput = document.getElementById('package_id');
                                if (pkgIdInput && pkgId) {
                                    pkgIdInput.value = pkgId;
                                }
                            }
                            validateSingleField('services_selected', true);
                            refreshButtonStates();
                            saveFormDataToLocal();
                        });
                    });
                }
            });

            // Radio buttons event listeners
            document.querySelectorAll('input[name="identity_type"], input[name="gender"]').forEach(radio => {
                radio.addEventListener('change', () => {
                    saveFormDataToLocal();
                });
            });

            // Initial immediate check on current step so validators are visible right away
            checkStepValidity(currentStep, true);
            refreshButtonStates();
        }

        window.addEventListener('load', () => {
            restoreFormDataFromLocal();
            initCanvasEngine();
            updateStepUI();
            initRealtimeValidator();
        });

        window.addEventListener('resize', () => {
            if (currentStep === 5) {
                resizeCanvas(true);
            }
        });

        // Form Validation on Submit
        document.getElementById('customerRegistrationForm').addEventListener('submit', function(e) {
            const { isValid, firstInvalidElement } = checkStepValidity(5, true);
            
            if (!isValid) {
                e.preventDefault();
                refreshButtonStates();
                jumpToElement(firstInvalidElement);
            } else {
                // Clear localStorage draft on valid form submission
                try {
                    localStorage.removeItem(storageKeyStep);
                    localStorage.removeItem(storageKeyDraft);
                } catch(err) {}
            }
        });
    </script>
</body>
</html>
