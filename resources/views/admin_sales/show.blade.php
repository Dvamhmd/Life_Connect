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

            @if(Auth::user()->role === 'admin_vas')
                <!-- Admin VAS Status Override Control Card -->
                <div class="rounded-3xl bg-white border-2 border-[#9B385B]/30 p-6 shadow-sm space-y-4 text-xs">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                        <div class="flex items-center gap-2 font-extrabold text-sm text-[#9B385B]">
                            <i class="fa-solid fa-sliders text-[#F48C5B]"></i>
                            <span>Ubah Status Pengajuan (Admin VAS)</span>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#FEF4F0] text-[#9B385B] border border-[#9B385B]/20">
                            Super Admin
                        </span>
                    </div>

                    <form action="{{ route('vas.registrations.update-status', $registration->id) }}" method="POST" class="space-y-3.5">
                        @csrf
                        
                        <div>
                            <label class="block font-bold text-[#333333] mb-1">Status Baru <span class="text-rose-500">*</span></label>
                            <select name="status" id="adminVasStatusSelect" required onchange="toggleAdminVasRevisionBox(this.value)"
                                    class="w-full px-3 py-2 rounded-xl bg-[#FEF4F0] border border-[#F48C5B]/40 text-xs font-bold text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#9B385B]">
                                <option value="submitted" {{ $registration->status === 'submitted' ? 'selected' : '' }}>1. SUBMITTED (Survey Awal / Menunggu OPJ)</option>
                                <option value="verified" {{ $registration->status === 'verified' ? 'selected' : '' }}>2. VERIFIED (Terverifikasi OPJ / Menunggu Form Pelanggan)</option>
                                <option value="filled" {{ $registration->status === 'filled' ? 'selected' : '' }}>3. FILLED (Form Diisi Pelanggan / Menunggu C-Care)</option>
                                <option value="approved" {{ $registration->status === 'approved' ? 'selected' : '' }}>4. APPROVED (Disetujui / Selesai Closing)</option>
                                <option value="revision" {{ $registration->status === 'revision' ? 'selected' : '' }}>5. REVISION (Memerlukan Revisi Data/Berkas)</option>
                            </select>
                        </div>

                        <div id="adminVasRevisionCategoryBox" class="{{ $registration->status === 'revision' ? '' : 'hidden' }} space-y-1">
                            <label class="block font-bold text-[#333333]">Kategori Revisi</label>
                            <select name="rejection_category"
                                    class="w-full px-3 py-2 rounded-xl bg-white border border-gray-300 text-xs text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#9B385B]">
                                <option value="Foto KTP Buram / Tidak Jelas" {{ $registration->rejection_category === 'Foto KTP Buram / Tidak Jelas' ? 'selected' : '' }}>Foto KTP Buram / Tidak Jelas</option>
                                <option value="NIK / Data Identitas Tidak Sesuai" {{ $registration->rejection_category === 'NIK / Data Identitas Tidak Sesuai' ? 'selected' : '' }}>NIK / Data Identitas Tidak Sesuai</option>
                                <option value="Foto Rumah Tidak Jelas" {{ $registration->rejection_category === 'Foto Rumah Tidak Jelas' ? 'selected' : '' }}>Foto Rumah Tidak Jelas</option>
                                <option value="Tanda Tangan Tidak Sesuai / Belum Ada" {{ $registration->rejection_category === 'Tanda Tangan Tidak Sesuai / Belum Ada' ? 'selected' : '' }}>Tanda Tangan Tidak Sesuai / Belum Ada</option>
                                <option value="Paket Berlangganan Perlu Penyesuaian" {{ $registration->rejection_category === 'Paket Berlangganan Perlu Penyesuaian' ? 'selected' : '' }}>Paket Berlangganan Perlu Penyesuaian</option>
                                <option value="Perubahan Status Manual oleh Admin VAS" {{ $registration->rejection_category === 'Perubahan Status Manual oleh Admin VAS' ? 'selected' : '' }}>Perubahan Status Manual oleh Admin VAS</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-bold text-[#333333] mb-1">Catatan / Alasan Perubahan</label>
                            <textarea name="notes" rows="3" placeholder="Alasan perubahan status pengajuan (opsional)..."
                                      class="w-full px-3 py-2 rounded-xl bg-white border border-gray-300 text-xs text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#9B385B]">{{ $registration->rejection_notes }}</textarea>
                        </div>

                        <button type="submit" 
                                onclick="return confirm('Apakah Anda yakin ingin mengubah status pengajuan ini?')"
                                class="w-full py-2.5 px-4 rounded-xl bg-gradient-to-r from-[#F48C5B] via-[#EF666B] to-[#9B385B] hover:from-[#EF666B] hover:to-[#F48C5B] text-white font-bold text-xs shadow-md shadow-orange-500/20 transition-all cursor-pointer flex items-center justify-center gap-2">
                            <i class="fa-solid fa-floppy-disk"></i>
                            <span>Simpan Status Pengajuan</span>
                        </button>
                    </form>
                </div>

                @push('scripts')
                <script>
                    function toggleAdminVasRevisionBox(status) {
                        const box = document.getElementById('adminVasRevisionCategoryBox');
                        if (box) {
                            if (status === 'revision') {
                                box.classList.remove('hidden');
                            } else {
                                box.classList.add('hidden');
                            }
                        }
                    }
                </script>
                @endpush
            @endif

        </div>

    </div>

</div>
@endsection
