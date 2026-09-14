@extends('layouts.app')

@section('title', 'Detail Berkas #' . $registration->registration_code . ' - Admin VAS')
@section('header_title', 'Detail & Monitoring Berkas Pelanggan')
@section('header_subtitle', 'Inspeksi dokumen, pelacakan SLA timeline, dan administrasi status pengajuan (Role: Admin VAS)')

@section('content')
<div class="space-y-6">
    
    <!-- Top Navigation & Status Badge Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <a href="{{ route('vas.dashboard') }}" class="inline-flex items-center gap-2 text-xs font-bold text-gray-500 hover:text-[#2C2C2C] transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Dashboard VAS</span>
        </a>

        <div class="flex flex-wrap items-center gap-3">
            @php $badge = $registration->status_badge; @endphp
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold border {{ $badge['bg'] }}">
                @if($registration->status === 'filled')
                    <span class="w-2 h-2 rounded-full bg-indigo-500 animate-ping"></span>
                @else
                    <span class="w-2 h-2 rounded-full bg-current"></span>
                @endif
                Status: {{ $badge['label'] }}
            </span>
            <span class="text-xs font-mono font-bold text-[#F48C5B] bg-[#FEF4F0] px-3 py-1.5 rounded-lg border border-[#F48C5B]/30">
                {{ $registration->registration_code }}
            </span>
            <button type="button" onclick="openChangeStatusModal()" 
                    class="px-3.5 py-1.5 rounded-xl bg-gradient-to-r from-[#F48C5B] to-[#9B385B] hover:from-[#EF666B] hover:to-[#F48C5B] text-white text-xs font-bold shadow-xs transition-all inline-flex items-center gap-1.5 cursor-pointer">
                <i class="fa-solid fa-sliders"></i>
                <span>Ubah Status</span>
            </button>
        </div>
    </div>

    <!-- Active Rejection / Revision Alert Banner if present -->
    @if($registration->status === 'revision')
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-xs">
            <div class="space-y-1">
                <div class="font-bold flex items-center gap-1.5 text-rose-700">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span>Pengajuan Ini Berstatus REVISI</span>
                </div>
                <p class="leading-relaxed text-rose-900">
                    Kategori: <strong>{{ $registration->rejection_category ?: 'Perbaikan Dokumen' }}</strong>. Catatan: {{ $registration->rejection_notes }}
                </p>
            </div>
            <div class="text-[11px] text-rose-600 font-medium">
                Diperbarui: {{ $registration->revision_at ? $registration->revision_at->format('d M Y, H:i') : '-' }}
            </div>
        </div>
    @endif

    <!-- 2 Column Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Left Column: Documents & Timeline Logs (lg:col-span-7) -->
        <div class="lg:col-span-7 space-y-6">
            
            <!-- Dokumen & Bukti Foto -->
            <div class="rounded-3xl bg-white border border-gray-200 p-6 shadow-sm space-y-6">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <h3 class="text-sm font-extrabold text-[#2C2C2C] flex items-center gap-2">
                        <i class="fa-solid fa-images text-[#F48C5B]"></i>
                        <span>Inspeksi Dokumen &amp; Foto Bukti</span>
                    </h3>
                    <span class="text-[10px] text-gray-400 font-medium">Klik foto untuk perbesar</span>
                </div>

                <!-- Foto KTP -->
                <div class="space-y-2">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-bold text-[#333333]">1. Foto KTP Pelanggan</span>
                        <span class="font-mono font-bold text-[#F48C5B] text-[11px]">NIK: {{ $registration->identity_number ?: ($registration->nik ?: 'Belum terisi') }}</span>
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

                <!-- Grid 2: Foto Rumah & Selfie Sales -->
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
                                        <span>Zoom</span>
                                    </span>
                                </div>
                            </div>
                        @else
                            <div class="rounded-2xl border border-gray-200 bg-gray-50 h-48 flex items-center justify-center text-center text-gray-400 text-xs italic">
                                Belum ada foto rumah
                            </div>
                        @endif
                    </div>

                    <!-- Foto Selfie Sales Saat Survey -->
                    <div class="space-y-2">
                        <span class="font-bold text-[#333333] text-xs block">3. Foto Validasi Survey Sales</span>
                        @if($registration->selfie_sales_path)
                            <div class="relative group rounded-2xl overflow-hidden border-2 border-gray-200 hover:border-[#F48C5B] bg-gray-50 flex items-center justify-center h-48 p-1 cursor-pointer transition-all duration-200 shadow-xs hover:shadow-md"
                                 onclick="openImageViewer('{{ asset($registration->selfie_sales_path) }}', 'Foto Validasi Sales AM - {{ $registration->sales_name }} ({{ $registration->sales_am_id }})')">
                                <img src="{{ asset($registration->selfie_sales_path) }}" alt="Selfie Sales" class="h-full w-full object-cover rounded-xl group-hover:scale-105 transition-transform duration-200">
                                <div class="absolute inset-0 bg-black/45 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-bold gap-1.5 backdrop-blur-xs rounded-xl">
                                    <span class="bg-black/60 px-2.5 py-1 rounded-full border border-white/30 flex items-center gap-1.5 shadow-lg text-[11px]">
                                        <i class="fa-solid fa-magnifying-glass-plus text-xs text-[#F48C5B]"></i>
                                        <span>Zoom</span>
                                    </span>
                                </div>
                            </div>
                        @else
                            <div class="rounded-2xl border border-gray-200 bg-gray-50 h-48 flex items-center justify-center text-center text-gray-400 text-xs italic">
                                Belum ada foto validasi sales
                            </div>
                        @endif
                    </div>

                </div>

                <!-- Tanda Tangan Virtual -->
                <div class="space-y-2 pt-2">
                    <span class="font-bold text-[#333333] text-xs block">4. Tanda Tangan Digital Pelanggan</span>
                    @if($registration->signature_path)
                        <div class="relative group rounded-2xl overflow-hidden border border-gray-200 hover:border-[#F48C5B] bg-white flex items-center justify-center p-4 cursor-pointer transition-all shadow-xs hover:shadow-md"
                             onclick="openImageViewer('{{ asset($registration->signature_path) }}', 'Tanda Tangan Digital - {{ $registration->customer_name }}')">
                            <img src="{{ asset($registration->signature_path) }}" alt="Tanda Tangan" class="max-h-28 object-contain">
                            <div class="absolute bottom-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <span class="bg-black/70 text-white text-[10px] font-bold px-2 py-1 rounded-md flex items-center gap-1">
                                    <i class="fa-solid fa-magnifying-glass-plus"></i> Zoom
                                </span>
                            </div>
                        </div>
                    @else
                        <div class="rounded-2xl border border-gray-200 bg-gray-50 py-6 text-center text-gray-400 text-xs italic">
                            Pelanggan belum menandatangani formulir
                        </div>
                    @endif
                </div>

            </div>

            <!-- Timeline & Audit SLA Perubahan Status -->
            <div class="rounded-3xl bg-white border border-gray-200 p-6 shadow-sm space-y-6">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <h3 class="text-sm font-extrabold text-[#2C2C2C] flex items-center gap-2">
                        <i class="fa-solid fa-stopwatch text-[#9B385B]"></i>
                        <span>Audit Durasi SLA &amp; Log Perjalanan Status</span>
                    </h3>
                    <span class="text-[10px] text-gray-400 font-medium">Tracking Siklus Berkas</span>
                </div>

                <div class="relative pl-8 space-y-6 before:absolute before:left-3 before:top-2 before:bottom-2 before:w-0.5 before:bg-gray-200">
                    @forelse($registration->progressLogs as $log)
                        <div class="relative">
                            <span class="absolute -left-8 top-1 w-6 h-6 rounded-full bg-white border-2 border-[#9B385B] flex items-center justify-center text-[10px] font-bold text-[#9B385B] shadow-xs">
                                <i class="fa-solid fa-check"></i>
                            </span>
                            
                            <div class="p-4 rounded-2xl bg-[#F8F9FA] border border-gray-200 space-y-2 text-xs">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <div class="flex items-center gap-2">
                                        <span class="font-extrabold text-[#2C2C2C] text-sm">
                                            @if($log->from_status)
                                                {{ strtoupper($log->from_status) }} &rarr;
                                            @endif
                                            {{ strtoupper($log->to_status) }}
                                        </span>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#FEF4F0] text-[#9B385B] border border-[#F48C5B]/30">
                                            {{ $log->actor_role }}
                                        </span>
                                    </div>
                                    <span class="text-[11px] text-gray-400 font-mono">
                                        {{ $log->created_at->format('d M Y, H:i:s') }}
                                    </span>
                                </div>

                                <p class="text-gray-700 leading-relaxed font-normal">{{ $log->notes }}</p>

                                <div class="pt-2 border-t border-gray-200 flex flex-wrap items-center justify-between text-[11px] text-gray-500 gap-2">
                                    <div>
                                        Pelaksana: <strong class="text-[#2C2C2C]">{{ $log->actor_name }}</strong>
                                    </div>
                                    @if($log->duration_seconds)
                                        <div class="text-[#9B385B] font-bold bg-[#FEF4F0] px-2 py-0.5 rounded border border-[#F48C5B]/30">
                                            Durasi: <strong>{{ $log->formatted_duration }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-xs text-gray-400 italic py-2">Belum ada riwayat perubahan progress.</div>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- Right Column: Customer Details, Package & Sales (lg:col-span-5) -->
        <div class="lg:col-span-5 space-y-6">
            
            <!-- Profil Pelanggan -->
            <div class="rounded-3xl bg-white border border-gray-200 p-6 shadow-sm space-y-4 text-xs">
                <h4 class="font-extrabold text-sm text-[#2C2C2C] border-b border-gray-100 pb-3 flex items-center gap-2">
                    <i class="fa-solid fa-user text-[#F48C5B]"></i>
                    <span>Data Pribadi Pelanggan</span>
                </h4>
                
                <div class="space-y-3">
                    <div>
                        <span class="text-gray-500 block text-[11px] font-medium">Nama Lengkap:</span>
                        <span class="font-bold text-[#2C2C2C] text-sm">{{ $registration->customer_name }}</span>
                    </div>

                    @if($registration->brand_name)
                        <div>
                            <span class="text-gray-500 block text-[11px] font-medium">Nama Usaha / Brand:</span>
                            <span class="font-bold text-[#2C2C2C]">{{ $registration->brand_name }}</span>
                        </div>
                    @endif

                    <div class="grid grid-cols-2 gap-3 pt-1 border-t border-gray-100">
                        <div>
                            <span class="text-gray-500 block text-[11px] font-medium">No. Identitas / NIK:</span>
                            <span class="font-mono font-bold text-[#2C2C2C]">{{ $registration->identity_number ?: ($registration->nik ?: '-') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 block text-[11px] font-medium">Jenis Kelamin:</span>
                            <span class="font-bold text-[#2C2C2C]">{{ $registration->gender ? ucfirst($registration->gender) : '-' }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 pt-1 border-t border-gray-100">
                        <div>
                            <span class="text-gray-500 block text-[11px] font-medium">WhatsApp:</span>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $registration->phone_wa) }}" target="_blank" class="font-bold text-emerald-600 hover:underline flex items-center gap-1">
                                <i class="fa-brands fa-whatsapp"></i> {{ $registration->phone_wa }}
                            </a>
                        </div>
                        <div>
                            <span class="text-gray-500 block text-[11px] font-medium">No. Telepon Rumah/Kantor:</span>
                            <span class="font-bold text-[#2C2C2C]">{{ $registration->phone_telp ?: '-' }}</span>
                        </div>
                    </div>

                    <div class="pt-1 border-t border-gray-100">
                        <span class="text-gray-500 block text-[11px] font-medium">Email Pelanggan:</span>
                        <span class="font-bold text-[#2C2C2C]">{{ $registration->email ?: '-' }}</span>
                    </div>

                    <!-- Kontak Darurat -->
                    @if($registration->emergency_contact_name || $registration->emergency_contact_phone)
                        <div class="pt-2 border-t border-gray-100 bg-[#F8F9FA] p-3 rounded-2xl space-y-1.5">
                            <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider block">Kontak Darurat (Emergency)</span>
                            <div class="font-bold text-[#2C2C2C]">{{ $registration->emergency_contact_name ?: '-' }} <span class="text-gray-400 font-normal">({{ $registration->emergency_contact_relation ?: 'Kerabat' }})</span></div>
                            <div class="text-gray-600 font-mono text-[11px]">{{ $registration->emergency_contact_phone ?: '-' }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Alamat Pemasangan -->
            <div class="rounded-3xl bg-white border border-gray-200 p-6 shadow-sm space-y-4 text-xs">
                <h4 class="font-extrabold text-sm text-[#2C2C2C] border-b border-gray-100 pb-3 flex items-center gap-2">
                    <i class="fa-solid fa-location-dot text-[#EF666B]"></i>
                    <span>Lokasi &amp; Alamat Pemasangan</span>
                </h4>
                
                <div class="space-y-3">
                    <div>
                        <span class="text-gray-500 block text-[11px] font-medium">Alamat Detail:</span>
                        <p class="font-medium text-gray-800 leading-relaxed">{{ $registration->address_detail ?: '-' }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-3 pt-1 border-t border-gray-100">
                        <div>
                            <span class="text-gray-500 block text-[11px] font-medium">Kelurahan / Desa:</span>
                            <span class="font-bold text-[#2C2C2C]">{{ $registration->village ?: '-' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 block text-[11px] font-medium">Kecamatan:</span>
                            <span class="font-bold text-[#2C2C2C]">{{ $registration->district ?: '-' }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 pt-1 border-t border-gray-100">
                        <div>
                            <span class="text-gray-500 block text-[11px] font-medium">Kota / Kabupaten:</span>
                            <span class="font-bold text-[#2C2C2C]">{{ $registration->regency ?: '-' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 block text-[11px] font-medium">Provinsi:</span>
                            <span class="font-bold text-[#2C2C2C]">{{ $registration->province ?: '-' }}</span>
                        </div>
                    </div>

                    @if($registration->latitude && $registration->longitude)
                        <div class="pt-2 border-t border-gray-100">
                            <span class="text-gray-500 block text-[11px] font-medium mb-1.5">Titik Koordinat GPS:</span>
                            <a href="https://maps.google.com/?q={{ $registration->latitude }},{{ $registration->longitude }}" target="_blank" 
                               class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 font-mono text-xs font-bold border border-blue-200 transition-colors w-full justify-center">
                                <i class="fa-solid fa-map-pin"></i>
                                <span>{{ $registration->latitude }}, {{ $registration->longitude }}</span>
                                <i class="fa-solid fa-arrow-up-right-from-square text-[10px] ml-1"></i>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Paket Berlangganan & Tagihan -->
            <div class="rounded-3xl bg-white border border-gray-200 p-6 shadow-sm space-y-4 text-xs">
                <h4 class="font-extrabold text-sm text-[#2C2C2C] border-b border-gray-100 pb-3 flex items-center gap-2">
                    <i class="fa-solid fa-receipt text-[#9B385B]"></i>
                    <span>Paket &amp; Penagihan</span>
                </h4>
                
                <div class="space-y-3">
                    <div class="p-3 rounded-2xl bg-[#FEF4F0] border border-[#F48C5B]/30 space-y-1">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-[#9B385B]">Paket Pilihan:</span>
                            @if($registration->package)
                                <span class="px-2 py-0.5 rounded-md bg-white text-[#F48C5B] font-mono font-bold text-[11px] border border-[#F48C5B]/30">
                                    {{ $registration->package->speed }}
                                </span>
                            @endif
                        </div>
                        <div class="text-base font-extrabold text-[#2C2C2C]">{{ $registration->package ? $registration->package->name : 'Life Fiber' }}</div>
                        @if($registration->package)
                            <div class="text-xs font-bold text-[#F48C5B]">{{ $registration->package->formatted_price }} / bulan</div>
                        @endif
                    </div>

                    @if($registration->services_selected)
                        <div class="p-3 rounded-2xl bg-gray-50 border border-gray-200 space-y-1.5 text-xs">
                            <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider block">Detail Layanan Dipilih:</span>
                            @foreach($registration->services_selected as $srvKey => $srvVal)
                                @if(($srvVal['opt1'] ?? false) || ($srvVal['opt2'] ?? false) || !empty($srvVal['text1']) || !empty($srvVal['text2']))
                                    <div class="p-2 rounded-xl bg-white border border-gray-200 text-[11px] space-y-0.5">
                                        <div class="font-bold text-[#9B385B] uppercase">{{ str_replace('_', ' ', $srvKey) }}</div>
                                        @if(($srvVal['opt1'] ?? false) || !empty($srvVal['text1']))
                                            <div class="text-gray-700 flex items-start gap-1">
                                                <span class="text-emerald-700 font-bold">•</span>
                                                <span class="text-gray-600">{{ !empty($srvVal['text1']) ? $srvVal['text1'] : 'Aktif' }}</span>
                                            </div>
                                        @endif
                                        @if(($srvVal['opt2'] ?? false) || !empty($srvVal['text2']))
                                            <div class="text-gray-700 flex items-start gap-1">
                                                <span class="text-emerald-700 font-bold">•</span>
                                                <span class="text-gray-600">{{ !empty($srvVal['text2']) ? $srvVal['text2'] : 'Aktif' }}</span>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif

                    <div class="grid grid-cols-2 gap-3 pt-1 border-t border-gray-100">
                        <div>
                            <span class="text-gray-500 block text-[11px] font-medium">Metode Tagihan:</span>
                            <span class="font-bold text-[#2C2C2C]">{{ $registration->billing_method ?: 'Email' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 block text-[11px] font-medium">Periode Berlangganan:</span>
                            <span class="font-bold text-[#2C2C2C]">{{ $registration->subscription_period ? (str_ends_with(strtolower(trim($registration->subscription_period)), 'bulan') ? $registration->subscription_period : $registration->subscription_period . ' Bulan') : '-' }}</span>
                        </div>
                    </div>

                    <div class="pt-1 border-t border-gray-100">
                        <span class="text-gray-500 block text-[11px] font-medium">Email Penagihan (Invoice):</span>
                        <span class="font-bold text-[#2C2C2C]">{{ $registration->billing_email ?: ($registration->email ?: '-') }}</span>
                    </div>

                    @if($registration->billing_address)
                        <div class="pt-1 border-t border-gray-100">
                            <span class="text-gray-500 block text-[11px] font-medium">Alamat Penagihan:</span>
                            <span class="font-medium text-gray-700">{{ $registration->billing_address }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sales AM Info -->
            <div class="rounded-3xl bg-white border border-gray-200 p-6 shadow-sm space-y-4 text-xs">
                <h4 class="font-extrabold text-sm text-[#2C2C2C] border-b border-gray-100 pb-3 flex items-center gap-2">
                    <i class="fa-solid fa-briefcase text-gray-700"></i>
                    <span>Informasi Sales AM (Account Manager)</span>
                </h4>
                
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-gray-500 block text-[11px] font-medium">Nama Sales:</span>
                            <span class="font-bold text-[#2C2C2C] text-sm">{{ $registration->sales_name ?: ($registration->sales?->name ?: '-') }}</span>
                        </div>
                        <span class="px-2.5 py-1 rounded-lg bg-[#FEF4F0] text-[#9B385B] font-mono font-bold text-xs border border-[#F48C5B]/30">
                            {{ $registration->sales_am_id ?: ($registration->sales?->sales_id ?: '-') }}
                        </span>
                    </div>

                    @if($registration->sales?->phone)
                        <div class="pt-1 border-t border-gray-100">
                            <span class="text-gray-500 block text-[11px] font-medium">No. Telepon Sales:</span>
                            <span class="font-bold text-gray-700">{{ $registration->sales->phone }}</span>
                        </div>
                    @endif

                    <div class="pt-1 border-t border-gray-100 flex items-center justify-between text-[11px]">
                        <span class="text-gray-500">Waktu Survey Masuk:</span>
                        <span class="font-mono font-bold text-gray-700">{{ $registration->submitted_at ? $registration->submitted_at->format('d M Y, H:i') : ($registration->created_at->format('d M Y, H:i')) }}</span>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<!-- Modal Ubah Status Pengajuan (Admin VAS) -->
<div id="changeStatusModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl max-w-lg w-full overflow-hidden shadow-2xl border border-gray-100 animate-in fade-in zoom-in-95 duration-200">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-[#FEF4F0]">
            <div class="flex items-center gap-2 font-extrabold text-sm text-[#9B385B]">
                <i class="fa-solid fa-sliders text-[#F48C5B]"></i>
                <span>Ubah Status Pengajuan (Admin VAS)</span>
            </div>
            <button type="button" onclick="closeChangeStatusModal()" class="text-gray-400 hover:text-gray-600 cursor-pointer">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>
        </div>

        <form method="POST" action="{{ route('vas.registrations.update-status', $registration->id) }}">
            @csrf
            <div class="p-6 space-y-4 text-xs">
                
                <!-- Target Information -->
                <div class="p-3.5 rounded-2xl bg-[#F8F9FA] border border-gray-200 space-y-1">
                    <div class="text-[11px] text-gray-500 font-medium">Pelanggan:</div>
                    <div class="font-extrabold text-[#2C2C2C] text-sm">{{ $registration->customer_name }}</div>
                    <div class="font-mono text-[11px] font-bold text-[#F48C5B]">{{ $registration->registration_code }}</div>
                </div>

                <!-- Status Selector -->
                <div class="space-y-1.5">
                    <label for="statusSelect" class="block font-bold text-gray-700">Pilih Status Baru <span class="text-red-500">*</span></label>
                    <select id="statusSelect" name="status" required onchange="toggleRejectionCategory(this.value)"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#F48C5B] focus:border-[#F48C5B] text-xs font-semibold">
                        <option value="submitted" {{ $registration->status === 'submitted' ? 'selected' : '' }}>SUBMITTED (Menunggu Verifikasi OPJ)</option>
                        <option value="verified" {{ $registration->status === 'verified' ? 'selected' : '' }}>VERIFIED (Menunggu Pengisian Pelanggan)</option>
                        <option value="filled" {{ $registration->status === 'filled' ? 'selected' : '' }}>FILLED (Menunggu Verifikasi C-Care)</option>
                        <option value="approved" {{ $registration->status === 'approved' ? 'selected' : '' }}>APPROVED (Disetujui / Selesai)</option>
                        <option value="revision" {{ $registration->status === 'revision' ? 'selected' : '' }}>REVISION (Perlu Perbaikan)</option>
                    </select>
                </div>

                <!-- Rejection Category (Shown if revision) -->
                <div id="rejectionCategoryContainer" class="space-y-1.5 {{ $registration->status === 'revision' ? '' : 'hidden' }}">
                    <label for="rejectionCategory" class="block font-bold text-gray-700">Kategori Revisi / Penolakan</label>
                    <select id="rejectionCategory" name="rejection_category"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#F48C5B] focus:border-[#F48C5B] text-xs">
                        <option value="Foto KTP Kurang Jelas">Foto KTP Kurang Jelas / Buram</option>
                        <option value="Foto Rumah Tidak Sesuai">Foto Rumah Tidak Sesuai Titik Lokasi</option>
                        <option value="Data NIK / Identitas Tidak Valid">Data NIK / Identitas Tidak Valid</option>
                        <option value="Tanda Tangan Tidak Sesuai">Tanda Tangan Tidak Sesuai KTP</option>
                        <option value="Koreksi Paket / Tagihan">Koreksi Paket / Tagihan</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <!-- Notes -->
                <div class="space-y-1.5">
                    <label for="statusNotes" class="block font-bold text-gray-700">Catatan / Alasan Perubahan</label>
                    <textarea id="statusNotes" name="notes" rows="3" placeholder="Masukkan alasan atau catatan perubahan status..."
                              class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#F48C5B] focus:border-[#F48C5B] text-xs resize-none"></textarea>
                </div>

                <div class="p-3 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-[11px] leading-relaxed">
                    <i class="fa-solid fa-triangle-exclamation mr-1"></i> Perubahan status akan dicatat dalam riwayat SLA &amp; Audit Trail Log sistem.
                </div>

            </div>

            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-3 bg-gray-50">
                <button type="button" onclick="closeChangeStatusModal()" 
                        class="px-4 py-2 rounded-xl text-xs font-bold text-gray-600 hover:bg-gray-200 transition-colors cursor-pointer">
                    Batal
                </button>
                <button type="submit" 
                        class="px-5 py-2 rounded-xl bg-gradient-to-r from-[#F48C5B] to-[#9B385B] hover:from-[#EF666B] hover:to-[#F48C5B] text-white font-bold text-xs shadow-md transition-all cursor-pointer">
                    Simpan Perubahan Status
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Fullscreen Image Viewer with Pan & Zoom -->
<div id="imageViewerModal" class="fixed inset-0 z-50 bg-black/90 backdrop-blur-md flex flex-col hidden select-none" style="touch-action: none;">
    <!-- Top Bar Controls -->
    <div class="px-6 py-4 flex items-center justify-between text-white border-b border-white/10 z-10 bg-black/40">
        <div class="flex items-center gap-3">
            <span class="w-2.5 h-2.5 rounded-full bg-[#F48C5B] animate-pulse"></span>
            <div id="imageViewerTitle" class="font-bold text-sm tracking-wide">Pratinjau Gambar</div>
        </div>

        <div class="flex items-center gap-2">
            <!-- Zoom In -->
            <button type="button" onclick="zoomImage(0.25)" class="w-9 h-9 rounded-xl bg-white/10 hover:bg-white/20 flex items-center justify-center transition-colors cursor-pointer" title="Perbesar (+)">
                <i class="fa-solid fa-magnifying-glass-plus text-xs"></i>
            </button>
            <!-- Zoom Out -->
            <button type="button" onclick="zoomImage(-0.25)" class="w-9 h-9 rounded-xl bg-white/10 hover:bg-white/20 flex items-center justify-center transition-colors cursor-pointer" title="Perkecil (-)">
                <i class="fa-solid fa-magnifying-glass-minus text-xs"></i>
            </button>
            <!-- Reset -->
            <button type="button" onclick="resetImageViewer()" class="w-9 h-9 rounded-xl bg-white/10 hover:bg-white/20 flex items-center justify-center transition-colors cursor-pointer" title="Reset Ukuran (0)">
                <i class="fa-solid fa-arrows-rotate text-xs"></i>
            </button>
            <!-- Rotate -->
            <button type="button" onclick="rotateImage()" class="w-9 h-9 rounded-xl bg-white/10 hover:bg-white/20 flex items-center justify-center transition-colors cursor-pointer" title="Putar 90 Derajat (R)">
                <i class="fa-solid fa-rotate-right text-xs"></i>
            </button>
            <!-- Close -->
            <button type="button" onclick="closeImageViewer()" class="w-9 h-9 rounded-xl bg-rose-500/80 hover:bg-rose-600 flex items-center justify-center transition-colors ml-2 cursor-pointer" title="Tutup (Esc)">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
    </div>

    <!-- Viewer Viewport Canvas -->
    <div id="imageViewerContainer" class="flex-1 relative overflow-hidden flex items-center justify-center cursor-grab active:cursor-grabbing">
        <img id="imageViewerImg" src="" alt="Pratinjau Gambar" class="max-w-none transition-transform duration-75 origin-center pointer-events-none rounded-lg shadow-2xl">
    </div>

    <!-- Bottom Instruction Helper -->
    <div class="py-2.5 px-4 text-center text-gray-400 text-[11px] border-t border-white/10 bg-black/40 z-10 flex items-center justify-center gap-4">
        <span><i class="fa-solid fa-computer-mouse mr-1 text-[#F48C5B]"></i> Scroll mouse untuk zoom</span>
        <span><i class="fa-solid fa-hand-back-fist mr-1 text-[#F48C5B]"></i> Klik &amp; geser untuk menggerakkan</span>
        <span><i class="fa-solid fa-keyboard mr-1 text-[#F48C5B]"></i> Esc untuk keluar</span>
    </div>
</div>

@push('scripts')
<script>
    // Status Modal Controls
    function openChangeStatusModal() {
        document.getElementById('changeStatusModal').classList.remove('hidden');
    }

    function closeChangeStatusModal() {
        document.getElementById('changeStatusModal').classList.add('hidden');
    }

    function toggleRejectionCategory(status) {
        const container = document.getElementById('rejectionCategoryContainer');
        if (status === 'revision') {
            container.classList.remove('hidden');
        } else {
            container.classList.add('hidden');
        }
    }

    // Image Zoom & Pan Controls
    let currentScale = 1.0;
    let currentRotation = 0;
    let panX = 0;
    let panY = 0;
    let isPanning = false;
    let startPanX = 0;
    let startPanY = 0;
    let isModalOpen = false;

    const modal = document.getElementById('imageViewerModal');
    const viewerImg = document.getElementById('imageViewerImg');
    const viewerTitle = document.getElementById('imageViewerTitle');
    const viewerContainer = document.getElementById('imageViewerContainer');

    function applyTransform() {
        viewerImg.style.transform = `translate3d(${panX}px, ${panY}px, 0px) scale(${currentScale}) rotate(${currentRotation}deg)`;
    }

    function openImageViewer(src, title) {
        viewerImg.src = src;
        viewerTitle.textContent = title || 'Pratinjau Gambar';
        currentScale = 1.0;
        currentRotation = 0;
        panX = 0;
        panY = 0;
        applyTransform();
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        isModalOpen = true;
    }

    function closeImageViewer() {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        isModalOpen = false;
    }

    function zoomImage(step) {
        currentScale = Math.min(Math.max(0.3, currentScale + step), 5.0);
        applyTransform();
    }

    function rotateImage() {
        currentRotation = (currentRotation + 90) % 360;
        applyTransform();
    }

    function resetImageViewer() {
        currentScale = 1.0;
        currentRotation = 0;
        panX = 0;
        panY = 0;
        applyTransform();
    }

    // Mouse Pan Events
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

    window.addEventListener('pointerup', () => { isPanning = false; });
    window.addEventListener('pointercancel', () => { isPanning = false; });

    // Wheel Zoom
    viewerContainer.addEventListener('wheel', (e) => {
        if (!isModalOpen) return;
        e.preventDefault();
        const delta = e.deltaY < 0 ? 0.2 : -0.2;
        zoomImage(delta);
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
