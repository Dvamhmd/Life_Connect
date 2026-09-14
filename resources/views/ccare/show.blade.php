@extends('layouts.app')

@section('title', 'Review Berkas #' . $registration->registration_code . ' - C-Care')
@section('header_title', 'Review & Verifikasi Dokumen Pelanggan')
@section('header_subtitle', 'Pemeriksaan validitas identitas KTP, foto rumah, tanda tangan virtual, dan paket berlangganan')

@section('content')
<div class="space-y-6">
    
    <!-- Top Back & Status Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <a href="{{ route('ccare.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-gray-500 hover:text-[#2C2C2C] transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Antrian C-Care</span>
        </a>

        <div class="flex items-center gap-3">
            @php $badge = $registration->status_badge; @endphp
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold border {{ $badge['bg'] }}">
                <span class="w-2 h-2 rounded-full bg-current"></span>
                Status: {{ $badge['label'] }}
            </span>
            <span class="text-xs font-mono font-bold text-[#F48C5B] bg-[#FEF4F0] px-3 py-1.5 rounded-lg border border-[#F48C5B]/30">
                {{ $registration->registration_code }}
            </span>
        </div>
    </div>

    <!-- Active Rejection / Revision Warning if present -->
    @if($registration->status === 'revision')
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1">
            <div class="font-bold flex items-center gap-1.5 text-rose-700">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span>Pengajuan Ini Sedang Berstatus REVISI:</span>
            </div>
            <p class="leading-relaxed">
                Kategori: <strong>{{ $registration->rejection_category ?: 'Perbaikan Dokumen' }}</strong>.<br>
                Catatan Revisi: {{ $registration->rejection_notes }}
            </p>
        </div>
    @endif

    <!-- 2 Column Layout: Documents Visualizer & Data Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Left Column: Documents & Signature Preview -->
        <div class="lg:col-span-7 space-y-6">
            
            <!-- Documents Grid -->
            <div class="rounded-3xl bg-white border border-gray-200 p-6 shadow-sm space-y-6">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <h3 class="text-sm font-extrabold text-[#2C2C2C] flex items-center gap-2">
                        <i class="fa-solid fa-images text-[#F48C5B]"></i>
                        <span>Inspeksi Dokumen &amp; Foto Bukti</span>
                    </h3>
                    <span class="text-[10px] text-gray-400 font-medium">Pastikan NIK &amp; Foto Jelas</span>
                </div>

                <!-- Foto KTP -->
                <div class="space-y-2">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-bold text-[#333333]">1. Foto KTP Pelanggan</span>
                        <span class="font-mono font-bold text-[#F48C5B] text-[11px]">NIK: {{ $registration->nik ?: 'Belum terisi' }}</span>
                    </div>
                    @if($registration->ktp_photo_path)
                        <div class="relative group rounded-2xl overflow-hidden border-2 border-gray-200 hover:border-[#F48C5B] bg-gray-50 flex items-center justify-center p-2 cursor-pointer transition-all duration-200 shadow-xs hover:shadow-md"
                             onclick="openImageViewer('{{ asset($registration->ktp_photo_path) }}', 'Foto KTP Pelanggan - {{ $registration->customer_name }} (NIK: {{ $registration->identity_number ?: ($registration->nik ?: '-') }})')">
                            <img src="{{ asset($registration->ktp_photo_path) }}" alt="Foto KTP" class="max-h-64 object-contain rounded-xl w-full group-hover:scale-[1.02] transition-transform duration-200">
                            <div class="absolute inset-0 bg-black/45 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-bold gap-2 backdrop-blur-xs rounded-xl">
                                <span class="bg-black/60 px-3 py-1.5 rounded-full border border-white/30 flex items-center gap-1.5 shadow-lg">
                                    <i class="fa-solid fa-magnifying-glass-plus text-sm text-[#F48C5B]"></i>
                                    <span>Klik untuk Zoom &amp; Geser</span>
                                </span>
                            </div>
                        </div>
                    @else
                        <div class="rounded-2xl border border-gray-200 bg-gray-50 py-10 text-center text-gray-400 text-xs italic">
                            Pelanggan belum mengunggah foto KTP
                        </div>
                    @endif
                </div>

                <!-- Grid 2: Foto Rumah & Foto Selfie Sales -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    
                    <!-- Foto Rumah -->
                    <div class="space-y-2">
                        <span class="font-bold text-[#333333] text-xs block">2. Foto Rumah / Bangunan</span>
                        @if($registration->house_photo_path)
                            <div class="relative group rounded-2xl overflow-hidden border-2 border-gray-200 hover:border-[#F48C5B] bg-gray-50 flex items-center justify-center h-48 p-1 cursor-pointer transition-all duration-200 shadow-xs hover:shadow-md"
                                 onclick="openImageViewer('{{ asset($registration->house_photo_path) }}', 'Foto Rumah / Bangunan - {{ $registration->customer_name }}')">
                                <img src="{{ asset($registration->house_photo_path) }}" alt="Foto Rumah" class="h-full w-full object-cover rounded-xl group-hover:scale-105 transition-transform duration-200">
                                <div class="absolute inset-0 bg-black/45 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-bold gap-1.5 backdrop-blur-xs rounded-xl">
                                    <span class="bg-black/60 px-2.5 py-1 rounded-full border border-white/30 flex items-center gap-1.5 shadow-lg text-[11px]">
                                        <i class="fa-solid fa-magnifying-glass-plus text-xs text-[#F48C5B]"></i>
                                        <span>Zoom &amp; Geser</span>
                                    </span>
                                </div>
                            </div>
                        @else
                            <div class="rounded-2xl border border-gray-200 bg-gray-50 h-48 flex items-center justify-center text-gray-400 text-xs italic">
                                Belum diunggah
                            </div>
                        @endif
                    </div>

                    <!-- Foto Selfie Bersama Sales -->
                    <div class="space-y-2">
                        <span class="font-bold text-[#333333] text-xs block">3. Foto Selfie Sales &amp; Pelanggan</span>
                        @if($registration->selfie_sales_path)
                            <div class="relative group rounded-2xl overflow-hidden border-2 border-gray-200 hover:border-[#F48C5B] bg-gray-50 flex items-center justify-center h-48 p-1 cursor-pointer transition-all duration-200 shadow-xs hover:shadow-md"
                                 onclick="openImageViewer('{{ asset($registration->selfie_sales_path) }}', 'Foto Selfie Sales &amp; Pelanggan - {{ $registration->customer_name }} bersama {{ $registration->sales_name }}')">
                                <img src="{{ asset($registration->selfie_sales_path) }}" alt="Selfie Sales" class="h-full w-full object-cover rounded-xl group-hover:scale-105 transition-transform duration-200">
                                <div class="absolute inset-0 bg-black/45 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-bold gap-1.5 backdrop-blur-xs rounded-xl">
                                    <span class="bg-black/60 px-2.5 py-1 rounded-full border border-white/30 flex items-center gap-1.5 shadow-lg text-[11px]">
                                        <i class="fa-solid fa-magnifying-glass-plus text-xs text-[#F48C5B]"></i>
                                        <span>Zoom &amp; Geser</span>
                                    </span>
                                </div>
                            </div>
                        @else
                            <div class="rounded-2xl border border-gray-200 bg-gray-50 h-48 flex items-center justify-center text-gray-400 text-xs italic">
                                Belum diunggah
                            </div>
                        @endif
                    </div>

                </div>

                <!-- Tanda Tangan Virtual -->
                <div class="pt-4 border-t border-gray-100 space-y-2">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-bold text-[#333333]">4. Tanda Tangan Digital (TTD Virtual) Pelanggan</span>
                        <span class="text-emerald-700 text-[10px] font-bold flex items-center gap-1">
                            <i class="fa-solid fa-circle-check"></i> Disetujui secara digital
                        </span>
                    </div>
                    @if($registration->signature_path)
                        <div class="relative group rounded-2xl border-2 border-gray-200 hover:border-[#F48C5B] bg-white p-4 flex items-center justify-center h-36 cursor-pointer transition-all duration-200 shadow-xs hover:shadow-md"
                             onclick="openImageViewer('{{ asset($registration->signature_path) }}', 'Tanda Tangan Digital - {{ $registration->customer_name }}', true)">
                            <div class="w-full h-full flex items-center justify-center bg-white rounded-xl">
                                <img src="{{ asset($registration->signature_path) }}" alt="Tanda Tangan Digital" class="max-h-28 object-contain group-hover:scale-105 transition-transform duration-200">
                            </div>
                            <div class="absolute inset-0 bg-black/45 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-bold gap-1.5 backdrop-blur-xs rounded-2xl">
                                <span class="bg-black/60 px-2.5 py-1 rounded-full border border-white/30 flex items-center gap-1.5 shadow-lg text-[11px]">
                                    <i class="fa-solid fa-magnifying-glass-plus text-xs text-[#F48C5B]"></i>
                                    <span>Zoom &amp; Geser TTD</span>
                                </span>
                            </div>
                        </div>
                    @else
                        <div class="rounded-2xl border border-gray-200 bg-white p-4 flex items-center justify-center h-36 text-gray-400 text-xs italic">
                            Belum dibubuhkan tanda tangan
                        </div>
                    @endif
                </div>

            </div>

            <!-- Progress History Log (Expandable) -->
            <div class="rounded-3xl bg-white border border-gray-200 shadow-sm overflow-hidden transition-all duration-200">
                <button type="button" onclick="toggleProgressLog()" 
                        class="w-full p-5 sm:p-6 flex items-center justify-between gap-3 text-left hover:bg-gray-50/80 transition-colors focus:outline-none cursor-pointer">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-[#FEF4F0] border border-[#F48C5B]/30 text-[#F48C5B] flex items-center justify-center text-xs shadow-xs">
                            <i class="fa-solid fa-timeline"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-sm font-extrabold text-[#2C2C2C]">Log Aktivitas &amp; Progress Pengajuan</h3>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-[#FEF4F0] text-[#9B385B] border border-[#F48C5B]/20">
                                    {{ count($registration->progressLogs) }} Riwayat
                                </span>
                            </div>
                            <p class="text-[11px] text-gray-400 mt-0.5" id="progressLogSummary">
                                @if($registration->progressLogs->isNotEmpty())
                                    Terakhir: <strong class="text-gray-700">{{ strtoupper($registration->progressLogs->last()->to_status) }}</strong> oleh {{ $registration->progressLogs->last()->actor_name }} ({{ $registration->progressLogs->last()->created_at->diffForHumans() }})
                                @else
                                    Belum ada catatan aktivitas
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-1.5 text-xs font-bold text-gray-500 bg-gray-100 hover:bg-gray-200 px-3 py-1.5 rounded-xl transition-all">
                        <span id="toggleLogText">Buka</span>
                        <i id="toggleLogChevron" class="fa-solid fa-chevron-down text-[10px] transition-transform duration-300"></i>
                    </div>
                </button>

                <!-- Expandable Timeline Body -->
                <div id="progressLogBody" class="hidden border-t border-gray-100 p-6 pt-5 bg-[#FAFAFB]">
                    <div class="relative pl-6 space-y-5 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-gray-200">
                        @forelse($registration->progressLogs as $log)
                            <div class="relative">
                                <span class="absolute -left-6 top-1 w-4 h-4 rounded-full bg-white border-2 border-[#F48C5B] flex items-center justify-center">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#EF666B]"></span>
                                </span>
                                <div class="text-xs bg-white p-3.5 rounded-2xl border border-gray-200 shadow-xs space-y-1.5">
                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                        <div class="flex items-center gap-2">
                                            <span class="font-extrabold text-[#2C2C2C]">
                                                @if($log->from_status)
                                                    <span class="text-gray-400 font-semibold">{{ strtoupper($log->from_status) }}</span> &rarr;
                                                @endif
                                                <span class="text-[#9B385B]">{{ strtoupper($log->to_status) }}</span>
                                            </span>
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#FEF4F0] text-[#9B385B] border border-[#F48C5B]/20">
                                                {{ $log->actor_role }}
                                            </span>
                                        </div>
                                        <span class="text-[10px] font-mono text-gray-400">
                                            {{ $log->created_at->format('d M Y, H:i:s') }}
                                        </span>
                                    </div>
                                    
                                    <div class="text-gray-600 leading-relaxed">{{ $log->notes }}</div>
                                    
                                    <div class="pt-1.5 border-t border-gray-100 flex flex-wrap items-center justify-between text-[10px] text-gray-500 gap-2">
                                        <div>
                                            Pelaksana: <strong class="text-gray-700">{{ $log->actor_name }}</strong>
                                        </div>
                                        @if($log->duration_seconds)
                                            <div class="text-[#F48C5B] font-bold bg-[#FEF4F0] px-2 py-0.5 rounded border border-[#F48C5B]/30">
                                                Durasi: <strong>{{ $log->formatted_duration }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-xs text-gray-400 italic">Belum ada riwayat progress.</div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>

        <!-- Right Column: Registration Data & Action Box -->
        <div class="lg:col-span-5 space-y-6">
            
            <!-- Subscription Package Chosen Card -->
            <div class="rounded-3xl bg-[#FEF4F0] border border-[#F6D8CE] p-6 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-[#F6D8CE] pb-3">
                    <span class="text-xs font-bold text-[#9B385B]">Paket Berlangganan Dipilih</span>
                    <span class="px-2.5 py-0.5 rounded-full bg-white text-[#F48C5B] border border-[#F48C5B]/30 text-xs font-bold font-mono">
                        {{ $registration->package ? $registration->package->speed : '-' }}
                    </span>
                </div>

                @if($registration->package)
                    <div>
                        <h4 class="text-lg font-extrabold text-[#2C2C2C]">{{ $registration->package->name }}</h4>
                        <div class="text-2xl font-black text-[#F48C5B] mt-1">
                            {{ $registration->package->formatted_price }} <span class="text-xs font-normal text-gray-500">/ bulan</span>
                        </div>
                    </div>

                    @if($registration->addons && count($registration->addons) > 0)
                        <div class="pt-2 border-t border-[#F6D8CE]">
                            <span class="text-[11px] font-bold text-gray-600 block mb-1">Add-on Tambahan:</span>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($registration->addons as $addon)
                                    <span class="px-2 py-0.5 rounded-lg bg-white text-[#9B385B] border border-[#9B385B]/20 text-[11px] font-semibold">
                                        <i class="fa-solid fa-plus text-[9px] mr-1 text-[#F48C5B]"></i> {{ $addon }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @else
                    <p class="text-xs text-gray-400 italic">Pelanggan belum memilih paket.</p>
                @endif

                    @if($registration->services_selected)
                        <div class="pt-2 border-t border-[#F6D8CE] space-y-1.5 text-xs">
                            <span class="text-[11px] font-bold text-gray-700 block">Layanan Terpilih:</span>
                            @foreach($registration->services_selected as $srvKey => $srvVal)
                                @if(($srvVal['opt1'] ?? false) || ($srvVal['opt2'] ?? false) || !empty($srvVal['text1']) || !empty($srvVal['text2']) || !empty($srvVal['notes']))
                                    <div class="p-2 rounded-xl bg-white border border-[#F6D8CE] text-[11px] space-y-1">
                                        <div class="font-bold text-[#9B385B] uppercase">{{ str_replace('_', ' ', $srvKey) }}</div>
                                        @if(($srvVal['opt1'] ?? false) || !empty($srvVal['text1']))
                                            <div class="text-gray-700 flex items-start gap-1">
                                                <span class="text-emerald-700 font-bold">• Baris 1:</span>
                                                <span class="text-gray-600">{{ !empty($srvVal['text1']) ? $srvVal['text1'] : 'Diceklis' }}</span>
                                            </div>
                                        @endif
                                        @if(($srvVal['opt2'] ?? false) || !empty($srvVal['text2']))
                                            <div class="text-gray-700 flex items-start gap-1">
                                                <span class="text-emerald-700 font-bold">• Baris 2:</span>
                                                <span class="text-gray-600">{{ !empty($srvVal['text2']) ? $srvVal['text2'] : 'Diceklis' }}</span>
                                            </div>
                                        @endif
                                        @if(!empty($srvVal['notes']))
                                            <div class="text-gray-500 italic text-[10px]">Catatan: {{ $srvVal['notes'] }}</div>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif

                    @if($registration->subscription_period)
                        <div class="flex justify-between pt-2 border-t border-[#F6D8CE] text-xs">
                            <span class="text-gray-500">Jangka Waktu Kontrak:</span>
                            <span class="font-bold text-[#F48C5B]">{{ str_ends_with(strtolower(trim($registration->subscription_period)), 'bulan') ? $registration->subscription_period : $registration->subscription_period . ' Bulan' }}</span>
                        </div>
                    @endif

                    <div class="pt-2 border-t border-[#F6D8CE] text-xs space-y-1.5">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Penerima Tagihan:</span>
                            <span class="font-bold text-[#2C2C2C]">{{ $registration->billing_name ?: $registration->customer_name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">No. HP Penagihan:</span>
                            <span class="font-semibold text-gray-700">{{ $registration->billing_mobile ?: $registration->phone_wa }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Email e-Invoice:</span>
                            <span class="font-semibold text-gray-700">{{ $registration->billing_email ?: $registration->email }}</span>
                        </div>
                        @if($registration->billing_address)
                            <div class="text-gray-500 text-[11px]">
                                <span>Alamat Penagihan:</span>
                                <p class="text-gray-700 font-medium mt-0.5">{{ $registration->billing_address }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Customer Identity Data -->
                <div class="rounded-3xl bg-white border border-gray-200 p-6 shadow-sm space-y-4 text-xs">
                    <h4 class="font-extrabold text-sm text-[#2C2C2C] flex items-center gap-2 border-b border-gray-100 pb-3">
                        <i class="fa-solid fa-id-card text-[#F48C5B]"></i>
                        <span>Rincian Data Identitas &amp; Kontak</span>
                    </h4>

                    <div class="space-y-2.5">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Nama Lengkap:</span>
                            <span class="font-bold text-[#2C2C2C] text-right">{{ $registration->customer_name }}</span>
                        </div>

                        @if($registration->brand_name)
                            <div class="flex justify-between">
                                <span class="text-gray-500">Nama Brand / Usaha:</span>
                                <span class="font-semibold text-[#9B385B] text-right">{{ $registration->brand_name }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between">
                            <span class="text-gray-500">Tanda Pengenal ({{ $registration->identity_type ?: 'KTP' }}):</span>
                            <span class="font-mono font-bold text-[#F48C5B] text-right">{{ $registration->identity_number ?: ($registration->nik ?: '-') }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-500">Tgl Lahir / Gender:</span>
                            <span class="text-gray-800 font-medium text-right">
                                {{ $registration->birth_date?->format('d M Y') ?: '-' }} ({{ in_array($registration->gender, ['P', 'Laki-laki']) ? 'P (Pria)' : (in_array($registration->gender, ['W', 'Perempuan']) ? 'W (Wanita)' : '-') }})
                            </span>
                        </div>

                        @if($registration->phone_telp)
                            <div class="flex justify-between">
                                <span class="text-gray-500">No. Telepon:</span>
                                <span class="text-gray-700 font-medium text-right">{{ $registration->phone_telp }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between">
                            <span class="text-gray-500">Nomor WhatsApp:</span>
                            <span class="text-emerald-600 font-bold text-right">{{ $registration->phone_wa }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-500">Email:</span>
                            <span class="text-gray-700 font-medium text-right">{{ $registration->email ?: '-' }}</span>
                        </div>

                        <div class="pt-2 border-t border-gray-100 space-y-1">
                            <span class="text-gray-500 block">Alamat Pemasangan:</span>
                            <p class="font-semibold text-[#2C2C2C] leading-relaxed">{{ $registration->address_detail ?: $registration->full_address }}</p>
                        </div>

                        <div class="pt-2 border-t border-gray-100 flex justify-between items-center text-[11px]">
                            <span class="text-gray-500">Sales AM:</span>
                            <span class="font-bold text-[#F48C5B]">{{ $registration->sales_name }} ({{ $registration->sales_am_id }})</span>
                        </div>
                    </div>
                </div>

            <!-- C-Care Action Decision Box or Status Notice -->
            @if($registration->status === 'approved')
                <div class="rounded-3xl bg-emerald-50 border border-emerald-200 p-6 shadow-sm space-y-3">
                    <div class="flex items-center gap-2.5 text-emerald-800 font-extrabold text-sm">
                        <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-sm shadow-xs">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                        <div>
                            <div class="text-[#2C2C2C]">Pendaftaran Telah Disetujui (Approved)</div>
                            <div class="text-[11px] font-normal text-emerald-700">Berkas dan data pendaftaran valid</div>
                        </div>
                    </div>
                    
                    <p class="text-xs text-gray-600 leading-relaxed pt-1">
                        Seluruh berkas identitas, foto bangunan, tanda tangan digital, dan paket berlangganan telah dinyatakan lengkap. Pengajuan telah selesai diverifikasi oleh C-Care.
                    </p>

                    @if($registration->approved_at)
                        <div class="text-[11px] text-emerald-800 font-bold pt-2 border-t border-emerald-200/80 flex items-center justify-between">
                            <span>Waktu Persetujuan:</span>
                            <span class="font-mono">{{ $registration->approved_at->format('d M Y, H:i') }} WIB</span>
                        </div>
                    @endif
                </div>
            @elseif($registration->status === 'filled')
                <!-- C-Care Action Decision Box -->
                <div class="rounded-3xl bg-white border-2 border-gray-200 p-6 shadow-sm space-y-4">
                    <h4 class="font-extrabold text-sm text-[#2C2C2C] flex items-center gap-2">
                        <i class="fa-solid fa-gavel text-[#9B385B]"></i>
                        <span>Keputusan Verifikasi C-Care</span>
                    </h4>

                    <p class="text-xs text-gray-500 leading-relaxed">
                        Pastikan seluruh berkas KTP, kesesuaian nama &amp; tanda tangan, serta lokasi pemasangan telah valid sebelum menyetujui pengajuan.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                        
                        <!-- Trigger Approve Modal -->
                        <button type="button" onclick="openApproveModal()" 
                                class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-xs shadow-md shadow-emerald-500/20 transition-all flex items-center justify-center gap-2">
                            <i class="fa-solid fa-thumbs-up"></i>
                            <span>Setujui (Approve)</span>
                        </button>

                        <!-- Trigger Revision Modal -->
                        <button type="button" onclick="openRejectModal()" 
                                class="w-full py-3 px-4 rounded-xl bg-rose-50 hover:bg-rose-100 border border-rose-300 text-rose-700 font-bold text-xs transition-all flex items-center justify-center gap-2">
                            <i class="fa-solid fa-rotate-left"></i>
                            <span>Minta Revisi Data</span>
                        </button>

                    </div>
                </div>
            @elseif($registration->status === 'revision')
                <div class="rounded-3xl bg-rose-50 border border-rose-200 p-6 shadow-sm space-y-3">
                    <div class="flex items-center gap-2.5 text-rose-800 font-extrabold text-sm">
                        <div class="w-8 h-8 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center text-sm shadow-xs">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <div>
                            <div class="text-rose-900">Menunggu Tindak Lanjut Revisi</div>
                            <div class="text-[11px] font-normal text-rose-700">Notifikasi telah dikirim ke Sales</div>
                        </div>
                    </div>
                    <p class="text-xs text-rose-800 leading-relaxed">
                        Pengajuan ini sedang menunggu pelanggan/sales untuk memperbarui dokumen yang diminta sebelum dapat di-review kembali.
                    </p>
                </div>
            @endif

        </div>

    </div>

</div>

<!-- Approve Confirmation Modal -->
<div id="approveModal" class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-xs overflow-y-auto p-4 flex items-center justify-center">
    <div class="relative w-full max-w-lg bg-white border border-gray-200 rounded-3xl shadow-2xl overflow-hidden my-8">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-[#F8F9FA]">
            <div class="flex items-center gap-2 font-bold text-sm text-emerald-700">
                <i class="fa-solid fa-circle-check"></i>
                <span>Konfirmasi Persetujuan (Approve)</span>
            </div>
            <button type="button" onclick="closeApproveModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="{{ route('ccare.approve', $registration->id) }}" method="POST">
            @csrf
            <div class="p-6 space-y-4 text-xs">
                <p class="text-gray-700 leading-relaxed">
                    Apakah Anda yakin ingin <strong>MENYETUJUI (APPROVE)</strong> pendaftaran calon pelanggan <strong class="text-[#2C2C2C]">{{ $registration->customer_name }}</strong> ({{ $registration->registration_code }})?
                </p>

                <div class="p-3.5 rounded-xl bg-[#FEF4F0] border border-[#F6D8CE] text-[11px] space-y-1">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Paket:</span>
                        <span class="font-bold text-[#2C2C2C]">{{ $registration->package?->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Sales AM:</span>
                        <span class="font-bold text-[#F48C5B]">{{ $registration->sales_name }}</span>
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-[#333333] mb-1">Catatan Persetujuan (Opsional)</label>
                    <textarea name="notes" rows="2" placeholder="Tuliskan catatan kelengkapan..."
                              class="w-full px-3.5 py-2 rounded-xl bg-white border border-gray-300 text-xs text-[#2C2C2C] placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500">Data pelanggan, identitas KTP, foto bangunan, tanda tangan virtual, dan paket berlangganan dinyatakan LENGKAP &amp; VALID.</textarea>
                </div>

                <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-[11px] flex items-start gap-2">
                    <i class="fa-solid fa-bell text-sm mt-0.5 text-emerald-600"></i>
                    <span>Sistem akan secara otomatis mengirimkan notifikasi persetujuan (Approved) ke <strong>Mobile Apps Sales ({{ $registration->sales_name }})</strong>.</span>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 bg-[#F8F9FA] flex items-center justify-end gap-3">
                <button type="button" onclick="closeApproveModal()" class="px-4 py-2 rounded-xl bg-white hover:bg-gray-100 text-gray-700 text-xs font-bold border border-gray-200">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white text-xs font-bold shadow-md shadow-emerald-500/20 flex items-center gap-1.5">
                    <i class="fa-solid fa-check"></i>
                    <span>Ya, Approve Pendaftaran</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Reject / Revision Modal -->
<div id="rejectModal" class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-xs overflow-y-auto p-4 flex items-center justify-center">
    <div class="relative w-full max-w-lg bg-white border border-gray-200 rounded-3xl shadow-2xl overflow-hidden my-8">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-[#F8F9FA]">
            <div class="flex items-center gap-2 font-bold text-sm text-rose-700">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span>Permintaan Revisi / Penolakan Data</span>
            </div>
            <button type="button" onclick="closeRejectModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="{{ route('ccare.reject', $registration->id) }}" method="POST">
            @csrf
            <div class="p-6 space-y-4 text-xs">
                <div>
                    <label for="rejection_category" class="block font-bold text-[#333333] mb-1.5">
                        Kategori Alasan Revisi <span class="text-rose-500">*</span>
                    </label>
                    <select name="rejection_category" id="rejection_category" required
                            class="w-full px-3.5 py-2 rounded-xl bg-white border border-gray-300 text-xs text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-rose-500">
                        <option value="">Pilih Kategori Alasan</option>
                        <option value="Foto KTP Buram / Tidak Terbaca">Foto KTP Buram / Tidak Terbaca</option>
                        <option value="NIK Tidak Sesuai dengan KTP">NIK Tidak Sesuai dengan KTP</option>
                        <option value="Foto Rumah Tidak Jelas / Bukan Tampak Depan">Foto Rumah Tidak Jelas / Bukan Tampak Depan</option>
                        <option value="Tanda Tangan Tidak Sesuai KTP">Tanda Tangan Tidak Sesuai KTP</option>
                        <option value="Data Kontak Darurat Tidak Valid">Data Kontak Darurat Tidak Valid</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <div>
                    <label for="rejection_notes" class="block font-bold text-[#333333] mb-1.5">
                        Rincian Catatan Revisi untuk Sales &amp; Pelanggan <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="rejection_notes" id="rejection_notes" rows="4" required
                              placeholder="Jelaskan secara detail bagian data apa saja yang perlu diperbaiki oleh Sales dan Pelanggan..."
                              class="w-full px-3.5 py-2 rounded-xl bg-white border border-gray-300 text-xs text-[#2C2C2C] placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-rose-500"></textarea>
                </div>

                <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-[11px] flex items-start gap-2">
                    <i class="fa-solid fa-bell text-sm mt-0.5 text-rose-600"></i>
                    <span>Status pengajuan akan berubah menjadi <strong>Revision</strong> dan sistem akan mengirimkan notifikasi tindak lanjut ke <strong>Mobile Apps Sales ({{ $registration->sales_name }})</strong>.</span>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 bg-[#F8F9FA] flex items-center justify-end gap-3">
                <button type="button" onclick="closeRejectModal()" class="px-4 py-2 rounded-xl bg-white hover:bg-gray-100 text-gray-700 text-xs font-bold border border-gray-200">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold shadow-md shadow-rose-600/20 flex items-center gap-1.5">
                    <i class="fa-solid fa-rotate-left"></i>
                    <span>Kirim Catatan Revisi ke Sales</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Full Interactive Image Viewer Modal (Zoom, Pan, Drag, Rotate) -->
<div id="imageViewerModal" class="fixed inset-0 z-50 hidden bg-black/90 backdrop-blur-md flex flex-col select-none opacity-0 transition-opacity duration-200">
    
    <!-- Top Floating Toolbar -->
    <div class="h-16 px-4 sm:px-6 bg-gradient-to-b from-black/80 via-black/40 to-transparent flex items-center justify-between z-20 text-white">
        
        <!-- Left: Image Title & Counter -->
        <div class="flex items-center gap-3 overflow-hidden">
            <div class="w-8 h-8 rounded-xl bg-white/10 flex items-center justify-center text-sm text-[#F48C5B]">
                <i class="fa-solid fa-image"></i>
            </div>
            <div class="truncate">
                <h4 id="viewerImageTitle" class="text-xs sm:text-sm font-bold text-white truncate">Preview Dokumen</h4>
                <p class="text-[10px] sm:text-[11px] text-gray-400">Gunakan mouse scroll / cubit untuk zoom, klik &amp; geser untuk menggeser</p>
            </div>
        </div>

        <!-- Right: Action Buttons -->
        <div class="flex items-center gap-1 sm:gap-2">
            
            <!-- Zoom Controls Group -->
            <div class="flex items-center bg-white/10 backdrop-blur-md rounded-xl p-1 border border-white/15">
                <button type="button" onclick="zoomImage(-0.25)" title="Zoom Out (-)" class="w-8 h-8 rounded-lg hover:bg-white/20 text-white flex items-center justify-center text-xs transition-colors">
                    <i class="fa-solid fa-magnifying-glass-minus"></i>
                </button>
                
                <span id="viewerZoomLevel" class="text-[11px] font-bold font-mono px-2 text-gray-200 min-w-[50px] text-center">100%</span>
                
                <button type="button" onclick="zoomImage(0.25)" title="Zoom In (+)" class="w-8 h-8 rounded-lg hover:bg-white/20 text-white flex items-center justify-center text-xs transition-colors">
                    <i class="fa-solid fa-magnifying-glass-plus"></i>
                </button>
            </div>

            <!-- Rotate Button -->
            <button type="button" onclick="rotateImage()" title="Putar Gambar 90° (R)" class="w-10 h-10 rounded-xl bg-white/10 hover:bg-white/20 text-white flex items-center justify-center text-xs border border-white/15 transition-colors">
                <i class="fa-solid fa-rotate-right"></i>
            </button>

            <!-- Reset Button -->
            <button type="button" onclick="resetImageViewer()" title="Reset Tampilan (0)" class="w-10 h-10 rounded-xl bg-white/10 hover:bg-white/20 text-white flex items-center justify-center text-xs border border-white/15 transition-colors">
                <i class="fa-solid fa-arrows-rotate"></i>
            </button>

            <!-- Open Original / Download -->
            <a id="viewerDownloadBtn" href="#" target="_blank" title="Buka Tab Baru" class="w-10 h-10 rounded-xl bg-white/10 hover:bg-white/20 text-white flex items-center justify-center text-xs border border-white/15 transition-colors">
                <i class="fa-solid fa-arrow-up-right-from-square"></i>
            </a>

            <!-- Close Button -->
            <button type="button" onclick="closeImageViewer()" title="Tutup Preview (Esc)" class="w-10 h-10 rounded-xl bg-rose-600/80 hover:bg-rose-600 text-white flex items-center justify-center text-sm border border-rose-400/30 transition-colors ml-1">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

    </div>

    <!-- Main Image Viewport Area (Pan & Zoom Canvas) -->
    <div id="viewerContainer" class="flex-1 w-full h-full relative overflow-hidden flex items-center justify-center cursor-grab active:cursor-grabbing touch-none">
        <img id="viewerImage" src="" alt="Preview Dokumen" class="max-h-[85vh] max-w-[90vw] object-contain transition-transform duration-75 ease-out will-change-transform shadow-2xl rounded-lg pointer-events-auto">
    </div>

    <!-- Bottom Helper Bar -->
    <div class="h-12 px-4 bg-gradient-to-t from-black/80 to-transparent flex items-center justify-center z-20 text-[11px] text-gray-400 gap-4">
        <span><i class="fa-solid fa-hand-pointer mr-1 text-[#F48C5B]"></i> Klik &amp; drag untuk geser</span>
        <span class="hidden sm:inline">•</span>
        <span class="hidden sm:inline"><i class="fa-solid fa-mouse mr-1 text-[#F48C5B]"></i> Scroll mouse untuk zoom</span>
        <span class="hidden sm:inline">•</span>
        <span class="hidden sm:inline"><i class="fa-solid fa-computer-mouse mr-1 text-[#F48C5B]"></i> Double klik untuk zoom cepat</span>
        <span class="hidden sm:inline">•</span>
        <span class="hidden sm:inline"><kbd class="bg-white/15 px-1.5 py-0.5 rounded text-[10px] text-white">ESC</kbd> untuk keluar</span>
    </div>

</div>

@push('scripts')
<script>
    function toggleProgressLog() {
        const body = document.getElementById('progressLogBody');
        const chevron = document.getElementById('toggleLogChevron');
        const text = document.getElementById('toggleLogText');

        if (body.classList.contains('hidden')) {
            body.classList.remove('hidden');
            chevron.classList.add('rotate-180');
            text.textContent = 'Tutup';
        } else {
            body.classList.add('hidden');
            chevron.classList.remove('rotate-180');
            text.textContent = 'Buka';
        }
    }

    function openApproveModal() {
        document.getElementById('approveModal').classList.remove('hidden');
    }
    function closeApproveModal() {
        document.getElementById('approveModal').classList.add('hidden');
    }

    function openRejectModal() {
        document.getElementById('rejectModal').classList.remove('hidden');
    }
    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
    }

    // ==========================================
    // Interactive Image Viewer Engine (Pan & Zoom)
    // ==========================================
    const viewerModal = document.getElementById('imageViewerModal');
    const viewerContainer = document.getElementById('viewerContainer');
    const viewerImage = document.getElementById('viewerImage');
    const viewerTitle = document.getElementById('viewerImageTitle');
    const viewerZoomLevel = document.getElementById('viewerZoomLevel');
    const viewerDownloadBtn = document.getElementById('viewerDownloadBtn');

    let currentScale = 1.0;
    let currentRotation = 0;
    let panX = 0;
    let panY = 0;
    let isPanning = false;
    let startPanX = 0;
    let startPanY = 0;
    let isModalOpen = false;

    // Touch pinch state
    let initialPinchDist = 0;
    let initialPinchScale = 1;

    function applyTransform() {
        viewerImage.style.transform = `translate(${panX}px, ${panY}px) scale(${currentScale}) rotate(${currentRotation}deg)`;
        viewerZoomLevel.textContent = Math.round(currentScale * 100) + '%';
    }

    function openImageViewer(src, title = 'Preview Dokumen', isSignature = false) {
        if (!src) return;
        
        viewerImage.src = src;
        viewerTitle.textContent = title;
        viewerDownloadBtn.href = src;
        
        // If signature, give it a clean white paper canvas background with subtle shadow
        if (isSignature || title.toLowerCase().includes('tanda tangan') || title.toLowerCase().includes('ttd')) {
            viewerImage.className = "max-h-[80vh] max-w-[85vw] object-contain transition-transform duration-75 ease-out will-change-transform shadow-2xl rounded-3xl pointer-events-auto bg-white p-8 sm:p-12 border border-gray-200";
        } else {
            viewerImage.className = "max-h-[85vh] max-w-[90vw] object-contain transition-transform duration-75 ease-out will-change-transform shadow-2xl rounded-xl pointer-events-auto bg-transparent";
        }
        
        resetImageViewer(false);
        
        viewerModal.classList.remove('hidden');
        // Smooth fade-in
        requestAnimationFrame(() => {
            viewerModal.classList.remove('opacity-0');
            viewerModal.classList.add('opacity-100');
        });
        
        document.body.style.overflow = 'hidden';
        isModalOpen = true;
    }

    function closeImageViewer() {
        viewerModal.classList.remove('opacity-100');
        viewerModal.classList.add('opacity-0');
        setTimeout(() => {
            viewerModal.classList.add('hidden');
            viewerImage.src = '';
            document.body.style.overflow = '';
            isModalOpen = false;
        }, 200);
    }

    function resetImageViewer(apply = true) {
        currentScale = 1.0;
        currentRotation = 0;
        panX = 0;
        panY = 0;
        if (apply) applyTransform();
    }

    function zoomImage(delta) {
        const newScale = Math.min(Math.max(0.5, currentScale + delta), 5.0);
        currentScale = Math.round(newScale * 100) / 100;
        applyTransform();
    }

    function rotateImage() {
        currentRotation = (currentRotation + 90) % 360;
        applyTransform();
    }

    // Mouse Drag / Pointer Panning
    viewerContainer.addEventListener('pointerdown', (e) => {
        if (!isModalOpen) return;
        if (e.target.closest('button') || e.target.closest('a')) return;
        
        isPanning = true;
        startPanX = e.clientX - panX;
        startPanY = e.clientY - panY;
        try {
            viewerContainer.setPointerCapture(e.pointerId);
        } catch (err) {}
    });

    window.addEventListener('pointermove', (e) => {
        if (!isPanning || !isModalOpen) return;
        panX = e.clientX - startPanX;
        panY = e.clientY - startPanY;
        applyTransform();
    });

    window.addEventListener('pointerup', () => {
        isPanning = false;
    });
    window.addEventListener('pointercancel', () => {
        isPanning = false;
    });

    // Mouse Scroll Wheel Zoom (Centered)
    viewerContainer.addEventListener('wheel', (e) => {
        if (!isModalOpen) return;
        e.preventDefault();
        
        const delta = e.deltaY < 0 ? 0.2 : -0.2;
        zoomImage(delta);
    }, { passive: false });

    // Double-click toggle zoom
    viewerContainer.addEventListener('dblclick', (e) => {
        if (!isModalOpen) return;
        if (e.target.closest('button') || e.target.closest('a')) return;
        
        if (currentScale > 1.2) {
            currentScale = 1.0;
            panX = 0;
            panY = 0;
        } else {
            currentScale = 2.2;
        }
        applyTransform();
    });

    // Touch Pinch Zoom
    viewerContainer.addEventListener('touchstart', (e) => {
        if (e.touches.length === 2) {
            initialPinchDist = Math.hypot(
                e.touches[0].clientX - e.touches[1].clientX,
                e.touches[0].clientY - e.touches[1].clientY
            );
            initialPinchScale = currentScale;
        }
    });

    viewerContainer.addEventListener('touchmove', (e) => {
        if (e.touches.length === 2 && initialPinchDist > 0) {
            e.preventDefault();
            const currentDist = Math.hypot(
                e.touches[0].clientX - e.touches[1].clientX,
                e.touches[0].clientY - e.touches[1].clientY
            );
            const pinchFactor = currentDist / initialPinchDist;
            currentScale = Math.min(Math.max(0.5, initialPinchScale * pinchFactor), 5.0);
            applyTransform();
        }
    }, { passive: false });

    // Keyboard Shortcuts
    window.addEventListener('keydown', (e) => {
        if (!isModalOpen) return;
        
        if (e.key === 'Escape') {
            closeImageViewer();
        } else if (e.key === '+' || e.key === '=') {
            zoomImage(0.25);
        } else if (e.key === '-' || e.key === '_') {
            zoomImage(-0.25);
        } else if (e.key === '0') {
            resetImageViewer();
        } else if (e.key === 'r' || e.key === 'R') {
            rotateImage();
        }
    });
</script>
@endpush
@endsection
