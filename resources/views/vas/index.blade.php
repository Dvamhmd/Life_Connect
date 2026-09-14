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
                @forelse($recentRegistrations as $reg)
                    <div class="p-3.5 rounded-2xl bg-[#F8F9FA] border border-gray-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs hover:border-[#F48C5B]/40 transition-colors">
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2">
                                <span class="font-extrabold text-[#2C2C2C] truncate text-sm">{{ $reg->customer_name }}</span>
                                @php $badge = $reg->status_badge; @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $badge['bg'] }}">
                                    {{ $badge['label'] }}
                                </span>
                            </div>
                            <div class="text-[11px] text-gray-500 font-mono mt-0.5">
                                <span class="text-[#F48C5B] font-bold">{{ $reg->registration_code }}</span> • {{ $reg->sales_name }} ({{ $reg->sales_am_id }})
                            </div>
                            <div class="text-[10px] text-gray-400 mt-0.5">
                                {{ $reg->village ? $reg->village . ', ' . $reg->regency : '-' }} • {{ $reg->created_at->diffForHumans() }}
                            </div>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0 self-end sm:self-center">
                            <button type="button" 
                                    onclick="openChangeStatusModal({{ $reg->id }}, '{{ addslashes($reg->customer_name) }}', '{{ $reg->registration_code }}', '{{ $reg->status }}')"
                                    class="px-2.5 py-1.5 rounded-lg bg-[#FEF4F0] hover:bg-[#F6D8CE] text-[#9B385B] border border-[#F48C5B]/30 text-xs font-bold transition-all inline-flex items-center gap-1.5 cursor-pointer shadow-2xs">
                                <i class="fa-solid fa-pen-to-square text-[#F48C5B]"></i>
                                <span>Ubah Status</span>
                            </button>
                            <a href="{{ route('admin-sales.show', $reg->id) }}" 
                               class="px-2.5 py-1.5 rounded-lg bg-white hover:bg-gray-100 text-gray-700 border border-gray-300 text-xs font-bold transition-all inline-flex items-center gap-1">
                                <i class="fa-solid fa-arrow-up-right-from-square text-gray-400 text-[10px]"></i>
                                <span>Detail</span>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-xs text-gray-400 italic text-center py-6">Belum ada data pendaftaran pelanggan.</div>
                @endforelse
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

        <form id="changeStatusForm" method="POST" action="">
            @csrf
            <div class="p-6 space-y-4 text-xs">
                
                <!-- Target Information -->
                <div class="p-3.5 rounded-2xl bg-[#F8F9FA] border border-gray-200 space-y-1">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500 font-medium">Pelanggan:</span>
                        <span id="modalCustomerName" class="font-extrabold text-[#2C2C2C] text-sm">-</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500 font-medium">Kode Pendaftaran:</span>
                        <span id="modalRegCode" class="font-mono font-bold text-[#F48C5B]">-</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500 font-medium">Status Saat Ini:</span>
                        <span id="modalCurrentStatus" class="font-bold text-gray-700 uppercase">-</span>
                    </div>
                </div>

                <!-- Status Selector -->
                <div>
                    <label class="block font-bold text-[#333333] mb-1.5">Pilih Status Baru <span class="text-rose-500">*</span></label>
                    <select name="status" id="modalStatusSelect" required onchange="handleModalStatusChange(this.value)"
                            class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-gray-300 text-xs font-bold text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#9B385B]">
                        <option value="submitted">1. SUBMITTED (Survey Awal / Menunggu OPJ)</option>
                        <option value="verified">2. VERIFIED (Terverifikasi OPJ / Menunggu Form Pelanggan)</option>
                        <option value="filled">3. FILLED (Form Diisi Pelanggan / Menunggu C-Care)</option>
                        <option value="approved">4. APPROVED (Disetujui / Selesai Closing)</option>
                        <option value="revision">5. REVISION (Memerlukan Revisi Dokumen/Data)</option>
                    </select>
                </div>

                <!-- Category field if Revision -->
                <div id="modalRevisionCategoryBox" class="hidden space-y-1">
                    <label class="block font-bold text-[#333333]">Kategori Revisi</label>
                    <select name="rejection_category" id="modalRejectionCategory"
                            class="w-full px-3.5 py-2 rounded-xl bg-white border border-gray-300 text-xs text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#9B385B]">
                        <option value="Foto KTP Buram / Tidak Jelas">Foto KTP Buram / Tidak Jelas</option>
                        <option value="NIK / Data Identitas Tidak Sesuai">NIK / Data Identitas Tidak Sesuai</option>
                        <option value="Foto Rumah Tidak Jelas">Foto Rumah Tidak Jelas</option>
                        <option value="Tanda Tangan Tidak Sesuai / Belum Ada">Tanda Tangan Tidak Sesuai / Belum Ada</option>
                        <option value="Paket Berlangganan Perlu Penyesuaian">Paket Berlangganan Perlu Penyesuaian</option>
                        <option value="Perubahan Status Manual oleh Admin VAS">Perubahan Status Manual oleh Admin VAS</option>
                    </select>
                </div>

                <!-- Notes / Reason -->
                <div>
                    <label class="block font-bold text-[#333333] mb-1">Catatan / Alasan Perubahan Status</label>
                    <textarea name="notes" id="modalStatusNotes" rows="3" placeholder="Tuliskan catatan perubahan status atau instruksi lanjutan (opsional)..."
                              class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-gray-300 text-xs text-[#2C2C2C] placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9B385B]"></textarea>
                </div>

                <div class="p-3 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-[11px] flex items-start gap-2">
                    <i class="fa-solid fa-triangle-exclamation text-amber-600 mt-0.5"></i>
                    <span>Tindakan ini dicatat dalam <strong>Audit Trail Log</strong> &amp; <strong>Tracking SLA</strong> sistem sebagai tindakan Super Admin VAS.</span>
                </div>

            </div>

            <div class="px-6 py-4 border-t border-gray-100 bg-[#F8F9FA] flex justify-end gap-3">
                <button type="button" onclick="closeChangeStatusModal()" class="px-4 py-2 rounded-xl bg-white hover:bg-gray-100 text-gray-700 text-xs font-bold border border-gray-200 cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-gradient-to-r from-[#F48C5B] via-[#EF666B] to-[#9B385B] hover:from-[#EF666B] hover:to-[#F48C5B] text-white text-xs font-bold shadow-md shadow-orange-500/20 cursor-pointer">
                    Simpan Perubahan Status
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openChangeStatusModal(id, name, code, currentStatus) {
        const form = document.getElementById('changeStatusForm');
        form.action = "{{ url('/vas/registrations') }}/" + id + "/status";
        
        document.getElementById('modalCustomerName').textContent = name;
        document.getElementById('modalRegCode').textContent = code;
        document.getElementById('modalCurrentStatus').textContent = currentStatus;
        
        const statusSelect = document.getElementById('modalStatusSelect');
        statusSelect.value = currentStatus;
        handleModalStatusChange(currentStatus);
        
        document.getElementById('modalStatusNotes').value = '';
        document.getElementById('changeStatusModal').classList.remove('hidden');
    }

    function closeChangeStatusModal() {
        document.getElementById('changeStatusModal').classList.add('hidden');
    }

    function handleModalStatusChange(status) {
        const catBox = document.getElementById('modalRevisionCategoryBox');
        if (status === 'revision') {
            catBox.classList.remove('hidden');
        } else {
            catBox.classList.add('hidden');
        }
    }
</script>
@endpush
@endsection
