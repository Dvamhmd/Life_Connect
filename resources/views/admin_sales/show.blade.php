@extends('layouts.app')

@section('title', 'Evaluasi SLA #' . $registration->registration_code . ' - Admin Sales')
@section('header_title', 'Detail Evaluasi SLA & Tracking Progress')
@section('header_subtitle', 'Analisis durasi tahapan proses dan identifikasi titik keterlambatan (bottleneck)')

@section('content')
<div class="space-y-6">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <a href="{{ route('admin-sales.dashboard') }}" class="inline-flex items-center gap-2 text-xs font-bold text-gray-500 hover:text-[#2C2C2C] transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Dashboard Admin Sales</span>
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

    <!-- 2 Column Layout: SLA Breakdown & Customer Profile -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Left: Full Progress Timeline & SLA Duration Analysis -->
        <div class="lg:col-span-8 space-y-6">
            
            <div class="rounded-3xl bg-white border border-gray-200 p-6 shadow-sm space-y-6">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <h3 class="text-sm font-extrabold text-[#2C2C2C] flex items-center gap-2">
                        <i class="fa-solid fa-stopwatch text-[#F48C5B]"></i>
                        <span>Audit Durasi Tahapan &amp; Log Perubahan Status</span>
                    </h3>
                    <span class="text-[10px] text-gray-400 font-medium">Evaluasi Waktu Proses</span>
                </div>

                <!-- Timeline Logs -->
                <div class="relative pl-8 space-y-8 before:absolute before:left-3 before:top-2 before:bottom-2 before:w-0.5 before:bg-gray-200">
                    @forelse($registration->progressLogs as $log)
                        <div class="relative">
                            <span class="absolute -left-8 top-1 w-6 h-6 rounded-full bg-white border-2 border-[#F48C5B] flex items-center justify-center text-[10px] font-bold text-[#F48C5B] shadow-xs">
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
                                            Durasi dari tahap sebelumnya: <strong>{{ $log->formatted_duration }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-xs text-gray-400 italic">Belum ada riwayat perubahan progress.</div>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- Right: Registration Summary Profile -->
        <div class="lg:col-span-4 space-y-6">
            
            <div class="rounded-3xl bg-white border border-gray-200 p-6 shadow-sm space-y-4 text-xs">
                <h4 class="font-extrabold text-sm text-[#2C2C2C] border-b border-gray-100 pb-3">Ringkasan Pelanggan</h4>
                
                <div class="space-y-2.5">
                    <div>
                        <span class="text-gray-500 block text-[11px] font-medium">Nama:</span>
                        <span class="font-bold text-[#2C2C2C] text-sm">{{ $registration->customer_name }}</span>
                    </div>

                    <div>
                        <span class="text-gray-500 block text-[11px] font-medium">WhatsApp &amp; Email:</span>
                        <span class="font-bold text-emerald-600 block">{{ $registration->phone_wa }}</span>
                        <span class="text-gray-500 text-[11px]">{{ $registration->email ?: '-' }}</span>
                    </div>

                    <div>
                        <span class="text-gray-500 block text-[11px] font-medium">Alamat Lengkap:</span>
                        <p class="text-gray-700 leading-relaxed font-medium">{{ $registration->full_address }}</p>
                    </div>

                    <div class="pt-2 border-t border-gray-100">
                        <span class="text-gray-500 block text-[11px] font-medium">Paket Berlangganan:</span>
                        <span class="font-bold text-[#2C2C2C] block">{{ $registration->package ? $registration->package->name : 'Survey Awal' }}</span>
                        <span class="font-extrabold text-[#F48C5B]">{{ $registration->package ? $registration->package->formatted_price . ' / bln' : '-' }}</span>
                    </div>

                    <div class="pt-2 border-t border-gray-100">
                        <span class="text-gray-500 block text-[11px] font-medium">Sales AM Penanggung Jawab:</span>
                        <span class="font-bold text-[#F48C5B] block">{{ $registration->sales_name }}</span>
                        <span class="text-gray-400 font-mono text-[10px]">({{ $registration->sales_am_id }})</span>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
