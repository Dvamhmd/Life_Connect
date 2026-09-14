@extends('layouts.app')

@section('title', 'Audit Log Rekaman CRUD & Aktivitas - Admin VAS')
@section('header_title', 'Rekaman Audit Log (Audit Trail)')
@section('header_subtitle', 'Pencatatan menyeluruh setiap tindakan pembuatan, perubahan, penghapusan data, dan verifikasi lintas modul')

@section('content')
<div class="space-y-6">
    
    <!-- Filter Bar & Search Container -->
    <div class="rounded-2xl bg-white border border-gray-200 shadow-sm overflow-hidden">
        
        <div class="p-4 sm:p-5 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white">
            <div>
                <h3 class="font-extrabold text-sm text-[#2C2C2C]">Log Aktivitas Sistem</h3>
                <p class="text-xs text-gray-500">Total {{ $logs->total() }} rekaman audit trail tercatat</p>
            </div>

            <!-- Filters -->
            <form action="{{ route('vas.audit-logs') }}" method="GET" class="flex flex-wrap items-center gap-2">
                
                <!-- Module Filter -->
                <select name="module" onchange="this.form.submit()" 
                        class="px-3 py-1.5 rounded-lg bg-white border border-gray-300 text-xs text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#9B385B]">
                    <option value="all">Semua Modul</option>
                    @foreach($modules as $m)
                        @php $mVal = is_array($m) ? ($m['module'] ?? reset($m)) : (is_object($m) ? ($m->module ?? '') : (string) $m); @endphp
                        @if($mVal)
                            <option value="{{ $mVal }}" {{ $module == $mVal ? 'selected' : '' }}>Modul: {{ $mVal }}</option>
                        @endif
                    @endforeach
                </select>

                <!-- Action Filter -->
                <select name="action" onchange="this.form.submit()" 
                        class="px-3 py-1.5 rounded-lg bg-white border border-gray-300 text-xs text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#9B385B]">
                    <option value="all">Semua Aksi</option>
                    @foreach($actions as $a)
                        @php $aVal = is_array($a) ? ($a['action'] ?? reset($a)) : (is_object($a) ? ($a->action ?? '') : (string) $a); @endphp
                        @if($aVal)
                            <option value="{{ $aVal }}" {{ $action == $aVal ? 'selected' : '' }}>Aksi: {{ $aVal }}</option>
                        @endif
                    @endforeach
                </select>

                <!-- Per Page Filter -->
                <select name="per_page" onchange="this.form.submit()" 
                        class="px-3 py-1.5 rounded-lg bg-white border border-gray-300 text-xs text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#9B385B]">
                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 / hal</option>
                    <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15 / hal</option>
                    <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25 / hal</option>
                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 / hal</option>
                    <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100 / hal</option>
                </select>

                <!-- Search -->
                <div class="relative w-44">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari user, IP, aksi..."
                           class="w-full px-3 py-1.5 rounded-lg bg-white border border-gray-300 text-xs text-[#2C2C2C] placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9B385B]">
                </div>

                <button type="submit" class="px-3.5 py-1.5 rounded-lg bg-[#9B385B] hover:bg-[#742440] text-white text-xs font-bold shadow-xs">
                    Filter
                </button>

                @if($module !== 'all' || $action !== 'all' || $search || $perPage !== 15)
                    <a href="{{ route('vas.audit-logs') }}" class="px-2.5 py-1.5 rounded-lg bg-gray-100 text-gray-600 hover:text-[#2C2C2C] text-xs">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Audit Log Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#F8F9FA] text-gray-600 font-bold uppercase tracking-wider border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3.5">Waktu &amp; Tanggal</th>
                        <th class="px-4 py-3.5">Pengguna &amp; Role</th>
                        <th class="px-4 py-3.5">Aksi / Modul</th>
                        <th class="px-5 py-3.5">Deskripsi Perubahan Data</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse($logs as $log)
                        <tr class="hover:bg-[#FEF4F0]/40 transition-colors">
                            
                            <!-- Timestamp -->
                            <td class="px-4 py-4 font-mono text-[11px] whitespace-nowrap">
                                <div class="text-[#2C2C2C] font-bold">{{ $log->created_at->format('d M Y') }}</div>
                                <div class="text-gray-400">{{ $log->created_at->format('H:i:s') }} ({{ $log->created_at->diffForHumans() }})</div>
                            </td>

                            <!-- User & Role -->
                            <td class="px-4 py-4">
                                <div class="font-extrabold text-[#2C2C2C]">{{ $log->user_name ?: 'System' }}</div>
                                <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-[#FEF4F0] text-[#9B385B] border border-[#F48C5B]/30 mt-0.5">
                                    {{ ucfirst($log->user_role ?: 'System') }}
                                </span>
                            </td>

                            <!-- Action & Module -->
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold font-mono 
                                    @if(str_contains($log->action, 'CREATE')) bg-emerald-50 text-emerald-800 border border-emerald-200
                                    @elseif(str_contains($log->action, 'VERIFY')) bg-amber-50 text-amber-800 border border-amber-200
                                    @elseif(str_contains($log->action, 'APPROVE')) bg-emerald-50 text-emerald-800 border border-emerald-200
                                    @elseif(str_contains($log->action, 'REJECT') || str_contains($log->action, 'DELETE')) bg-rose-50 text-rose-800 border border-rose-200
                                    @elseif(str_contains($log->action, 'UPDATE')) bg-blue-50 text-blue-800 border border-blue-200
                                    @else bg-gray-100 text-gray-700 border border-gray-200 @endif">
                                    {{ $log->action }}
                                </span>
                                <div class="text-[10px] text-gray-400 mt-1">Modul: <strong class="text-gray-600">{{ $log->module }}</strong></div>
                            </td>

                            <!-- Description -->
                            <td class="px-5 py-4 max-w-md">
                                <p class="text-[#2C2C2C] text-xs leading-relaxed font-medium">{{ $log->description }}</p>
                                @if($log->target_type)
                                    <div class="text-[10px] text-gray-400 mt-1 font-mono">
                                        Target: {{ $log->target_type }} #{{ $log->target_id }}
                                    </div>
                                @endif
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                                <div class="text-sm font-bold text-gray-600">Belum ada rekaman audit log.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination & Summary Footer -->
        @if($logs->total() > 0)
            <div class="p-4 border-t border-gray-100 bg-[#F8F9FA]">
                @if($logs->hasPages())
                    {{ $logs->links() }}
                @else
                    <div class="flex items-center justify-between text-xs text-gray-500">
                        <div>
                            Menampilkan
                            <span class="font-bold text-[#2C2C2C]">{{ $logs->total() }}</span>
                            dari
                            <span class="font-bold text-[#2C2C2C]">{{ $logs->total() }}</span>
                            rekaman audit log
                        </div>
                        <div class="text-[11px] text-gray-400 font-medium">
                            Halaman 1 dari 1 (Semua data ditampilkan)
                        </div>
                    </div>
                @endif
            </div>
        @endif

    </div>

</div>
@endsection
