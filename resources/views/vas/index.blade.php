@extends('layouts.app')

@section('title', 'Admin VAS - Super Admin Cockpit')
@section('header_title', 'Dashboard Admin VAS (Super Administrator)')
@section('header_subtitle', 'Pusat kendali sistem, manajemen pengguna, rekaman audit log CRUD, dan integrasi antar divisi')

@section('content')
<div class="space-y-6">
    
    <!-- Top System Metrics Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <div class="p-5 rounded-3xl bg-[#FEF4F0] border border-[#F6D8CE] shadow-sm relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-[#9B385B]">Total Akun Pengguna</span>
                <span class="w-9 h-9 rounded-xl bg-white text-[#9B385B] border border-[#9B385B]/20 flex items-center justify-center text-sm shadow-xs">
                    <i class="fa-solid fa-users-gear"></i>
                </span>
            </div>
            <div class="text-3xl font-extrabold text-[#2C2C2C] mt-3">{{ $stats['total_users'] }}</div>
            <div class="text-[11px] text-gray-500 mt-1">Multi-role aktif di sistem</div>
        </div>

        <div class="p-5 rounded-3xl bg-blue-50 border border-blue-200 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-blue-800">Total Pendaftaran</span>
                <span class="w-9 h-9 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-folder-tree"></i>
                </span>
            </div>
            <div class="text-3xl font-extrabold text-blue-900 mt-3">{{ $stats['total_registrations'] }}</div>
            <div class="text-[11px] text-blue-700 mt-1">{{ $stats['total_approved'] }} Disetujui (Approved)</div>
        </div>

        <div class="p-5 rounded-3xl bg-amber-50 border border-amber-200 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-amber-800">Antrian Berjalan</span>
                <span class="w-9 h-9 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </span>
            </div>
            <div class="text-3xl font-extrabold text-amber-900 mt-3">{{ $stats['pending_opj'] + $stats['pending_customer'] + $stats['pending_ccare'] }}</div>
            <div class="text-[11px] text-amber-700 mt-1">{{ $stats['pending_opj'] }} OPJ • {{ $stats['pending_customer'] }} Cust • {{ $stats['pending_ccare'] }} C-Care</div>
        </div>

        <div class="p-5 rounded-3xl bg-emerald-50 border border-emerald-200 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-emerald-800">Rekaman Audit Log</span>
                <span class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-shield-halved"></i>
                </span>
            </div>
            <div class="text-3xl font-extrabold text-emerald-900 mt-3">{{ $stats['total_audit_logs'] }}</div>
            <div class="text-[11px] text-emerald-700 mt-1">Audit trail keamanan &amp; CRUD</div>
        </div>

    </div>

    <!-- 2 Column Layout: Recent Registrations & Live Audit Trail Feed -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Left: Recent Registrations -->
        <div class="lg:col-span-6 rounded-3xl bg-white border border-gray-200 p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <h3 class="text-sm font-extrabold text-[#2C2C2C] flex items-center gap-2">
                    <i class="fa-solid fa-clock text-[#F48C5B]"></i>
                    <span>Pengajuan Pelanggan Terbaru</span>
                </h3>
                <a href="{{ route('admin-sales.dashboard') }}" class="text-xs font-bold text-[#F48C5B] hover:underline">Lihat Semua</a>
            </div>

            <div class="space-y-3">
                @foreach($recentRegistrations as $reg)
                    <div class="p-3 rounded-2xl bg-[#F8F9FA] border border-gray-200 flex items-center justify-between gap-3 text-xs">
                        <div class="min-w-0">
                            <div class="font-extrabold text-[#2C2C2C] truncate">{{ $reg->customer_name }}</div>
                            <div class="text-[10px] text-gray-500 font-mono">{{ $reg->registration_code }} • {{ $reg->sales_name }}</div>
                        </div>
                        <div class="text-right flex-shrink-0">
                            @php $badge = $reg->status_badge; @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $badge['bg'] }}">
                                {{ $badge['label'] }}
                            </span>
                            <div class="text-[9px] text-gray-400 mt-0.5">{{ $reg->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Right: Recent Audit Log Events -->
        <div class="lg:col-span-6 rounded-3xl bg-white border border-gray-200 p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <h3 class="text-sm font-extrabold text-[#2C2C2C] flex items-center gap-2">
                    <i class="fa-solid fa-shield-halved text-[#9B385B]"></i>
                    <span>Audit Trail Log Terkini</span>
                </h3>
                <a href="{{ route('vas.audit-logs') }}" class="text-xs font-bold text-[#9B385B] hover:underline">Lihat Semua Log</a>
            </div>

            <div class="space-y-3">
                @foreach($recentLogs as $log)
                    <div class="p-3 rounded-2xl bg-[#F8F9FA] border border-gray-200 space-y-1 text-xs">
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-1.5">
                                <span class="px-1.5 py-0.5 rounded text-[9px] font-bold font-mono bg-[#FEF4F0] text-[#9B385B] border border-[#F48C5B]/30">
                                    {{ $log->action }}
                                </span>
                                <span class="font-bold text-[#2C2C2C]">{{ $log->user_name ?: 'System' }}</span>
                            </div>
                            <span class="text-[10px] text-gray-400 font-mono">{{ $log->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-[11px] text-gray-600 truncate">{{ $log->description }}</p>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

</div>
@endsection
