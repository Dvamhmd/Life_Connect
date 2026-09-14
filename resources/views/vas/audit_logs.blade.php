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
                        <option value="{{ $m }}" {{ $module == $m ? 'selected' : '' }}>Modul: {{ $m }}</option>
                    @endforeach
                </select>

                <!-- Action Filter -->
                <select name="action" onchange="this.form.submit()" 
                        class="px-3 py-1.5 rounded-lg bg-white border border-gray-300 text-xs text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#9B385B]">
                    <option value="all">Semua Aksi</option>
                    @foreach($actions as $a)
                        <option value="{{ $a }}" {{ $action == $a ? 'selected' : '' }}>Aksi: {{ $a }}</option>
                    @endforeach
                </select>

                <!-- Search -->
                <div class="relative w-44">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari user, IP, aksi..."
                           class="w-full px-3 py-1.5 rounded-lg bg-white border border-gray-300 text-xs text-[#2C2C2C] placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9B385B]">
                </div>

                <button type="submit" class="px-3.5 py-1.5 rounded-lg bg-[#9B385B] hover:bg-[#742440] text-white text-xs font-bold shadow-xs">
                    Filter
                </button>

                @if($module !== 'all' || $action !== 'all' || $search)
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
                        <th class="px-4 py-3.5">IP &amp; Device Agent</th>
                        <th class="px-4 py-3.5 text-right">Rincian Nilai</th>
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

                            <!-- IP & Agent -->
                            <td class="px-4 py-4 text-[11px] font-mono">
                                <div class="text-[#F48C5B] font-bold">{{ $log->ip_address ?: '-' }}</div>
                                <div class="text-gray-400 text-[10px] truncate max-w-[160px]" title="{{ $log->user_agent }}">
                                    {{ $log->user_agent ?: '-' }}
                                </div>
                            </td>

                            <!-- Diff Values Modal Trigger -->
                            <td class="px-4 py-4 text-right whitespace-nowrap">
                                @if($log->old_values || $log->new_values)
                                    <button type="button" 
                                            onclick="showDiffModal({{ json_encode($log->action) }}, {{ json_encode($log->description) }}, {{ json_encode($log->old_values) }}, {{ json_encode($log->new_values) }})"
                                            class="px-2.5 py-1 rounded-lg bg-[#FEF4F0] hover:bg-[#FDE8E1] text-[#9B385B] text-xs font-bold border border-[#F48C5B]/30 transition-colors inline-flex items-center gap-1">
                                        <i class="fa-solid fa-code text-[#F48C5B]"></i>
                                        <span>Lihat Diff</span>
                                    </button>
                                @else
                                    <span class="text-gray-400 text-[11px]">-</span>
                                @endif
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                <div class="text-sm font-bold text-gray-600">Belum ada rekaman audit log.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="p-4 border-t border-gray-100 bg-[#F8F9FA]">
                {{ $logs->links() }}
            </div>
        @endif

    </div>

</div>

<!-- JSON Diff Inspector Modal -->
<div id="diffModal" class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-xs overflow-y-auto p-4 flex items-center justify-center">
    <div class="relative w-full max-w-2xl bg-white border border-gray-200 rounded-3xl shadow-2xl overflow-hidden my-8">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-[#F8F9FA]">
            <div class="flex items-center gap-2 font-extrabold text-sm text-[#9B385B]">
                <i class="fa-solid fa-code text-[#F48C5B]"></i>
                <span id="diffModalTitle">Inspeksi Nilai Perubahan (Diff)</span>
            </div>
            <button type="button" onclick="closeDiffModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="p-6 space-y-4 text-xs">
            <p id="diffModalDesc" class="text-[#2C2C2C] leading-relaxed font-bold"></p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                
                <!-- Old Values Box -->
                <div class="space-y-1.5">
                    <span class="font-bold text-rose-700 text-xs block">Nilai Sebelumnya (Old Values):</span>
                    <pre id="diffOldValues" class="p-3.5 rounded-2xl bg-rose-50 border border-rose-200 font-mono text-[11px] text-rose-800 overflow-x-auto max-h-60 custom-scrollbar"></pre>
                </div>

                <!-- New Values Box -->
                <div class="space-y-1.5">
                    <span class="font-bold text-emerald-700 text-xs block">Nilai Baru (New Values):</span>
                    <pre id="diffNewValues" class="p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 font-mono text-[11px] text-emerald-800 overflow-x-auto max-h-60 custom-scrollbar"></pre>
                </div>

            </div>
        </div>

        <div class="px-6 py-3.5 border-t border-gray-100 bg-[#F8F9FA] flex justify-end">
            <button type="button" onclick="closeDiffModal()" class="px-4 py-1.5 rounded-xl bg-white hover:bg-gray-100 text-gray-700 text-xs font-bold border border-gray-200">
                Tutup
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function showDiffModal(action, desc, oldVal, newVal) {
        document.getElementById('diffModalTitle').innerText = 'Audit Log Diff: ' + action;
        document.getElementById('diffModalDesc').innerText = desc;
        document.getElementById('diffOldValues').innerText = oldVal ? JSON.stringify(oldVal, null, 2) : 'null (Data Baru)';
        document.getElementById('diffNewValues').innerText = newVal ? JSON.stringify(newVal, null, 2) : 'null (Dihapus)';
        document.getElementById('diffModal').classList.remove('hidden');
    }

    function closeDiffModal() {
        document.getElementById('diffModal').classList.add('hidden');
    }
</script>
@endpush
@endsection
