@extends('layouts.app')

@section('title', 'Detail Survey #' . $registration->registration_code . ' - OPJ')
@section('header_title', 'Verifikasi Lapangan OPJ')
@section('header_subtitle', 'Pemeriksaan koordinat, coverage area, dan validasi data survey sales')

@section('content')
<div class="space-y-6">
    
    <!-- Top Back & Status Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <a href="{{ route('opj.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-gray-500 hover:text-[#2C2C2C] transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Daftar Survey</span>
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

    <!-- 2 Column Layout: Map & Data Card -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Left Column: OpenStreetMap Geolocation Visualizer -->
        <div class="lg:col-span-7 space-y-6">
            <div class="rounded-3xl bg-white border border-gray-200 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-gray-100 flex items-center justify-between bg-[#F8F9FA]">
                    <div class="flex items-center gap-2 font-bold text-sm text-[#2C2C2C]">
                        <i class="fa-solid fa-map-location-dot text-amber-500"></i>
                        <span>Peta Lokasi Survey (OpenStreetMap)</span>
                    </div>
                    <div class="text-[11px] font-mono font-bold text-[#9B385B] bg-white px-2.5 py-1 rounded-lg border border-gray-200">
                        Lat: {{ number_format($registration->latitude, 6) }}, Long: {{ number_format($registration->longitude, 6) }}
                    </div>
                </div>

                <!-- Leaflet Map Container -->
                <div id="surveyMap" style="height: 420px; width: 100%;" class="z-10"></div>

                <div class="p-4 bg-gray-50 border-t border-gray-100 flex flex-wrap items-center justify-between gap-3 text-xs">
                    <div class="flex items-center gap-4 text-gray-600">
                        <div class="flex items-center gap-1.5 font-medium">
                            <span class="w-3 h-3 rounded-full bg-red-500 border border-white shadow-xs"></span>
                            <span>Titik Rumah Pelanggan</span>
                        </div>
                        <div class="flex items-center gap-1.5 font-medium">
                            <span class="w-3 h-3 rounded-full bg-[#F48C5B]/40 border border-[#F48C5B]"></span>
                            <span>Radius ODP Coverage (150m)</span>
                        </div>
                    </div>
                    <a href="https://www.google.com/maps?q={{ $registration->latitude }},{{ $registration->longitude }}" 
                       target="_blank" 
                       class="inline-flex items-center gap-1 text-[#F48C5B] hover:text-[#EF666B] font-bold">
                        <span>Buka di Google Maps</span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                    </a>
                </div>
            </div>

            <!-- Progress History Log -->
            <div class="rounded-3xl bg-white border border-gray-200 p-6 shadow-sm">
                <h3 class="text-sm font-extrabold text-[#2C2C2C] mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-timeline text-[#F48C5B]"></i>
                    <span>Riwayat &amp; Log Progress Pengajuan</span>
                </h3>

                <div class="relative pl-6 space-y-6 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-gray-200">
                    @forelse($registration->progressLogs as $log)
                        <div class="relative group">
                            <span class="absolute -left-6 top-1 w-4 h-4 rounded-full bg-white border-2 border-[#F48C5B] flex items-center justify-center">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#EF666B]"></span>
                            </span>
                            <div class="text-xs">
                                <div class="flex items-center gap-2 font-bold text-[#2C2C2C]">
                                    <span>{{ strtoupper($log->to_status) }}</span>
                                    <span class="text-[10px] font-normal text-gray-400">• {{ $log->created_at->format('d M Y, H:i') }} ({{ $log->created_at->diffForHumans() }})</span>
                                </div>
                                <div class="text-gray-600 mt-1 leading-relaxed font-normal">{{ $log->notes }}</div>
                                <div class="text-[10px] text-gray-500 mt-1 flex items-center gap-2">
                                    <span>Oleh: <strong class="text-gray-700">{{ $log->actor_name }} ({{ $log->actor_role }})</strong></span>
                                    @if($log->duration_seconds)
                                        <span>• Durasi proses: <strong class="text-[#F48C5B] font-bold">{{ $log->formatted_duration }}</strong></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-xs text-gray-400 italic">Belum ada log progress tambahan.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Column: Survey Information & Verification Card -->
        <div class="lg:col-span-5 space-y-6">
            
            <!-- Customer & Survey Data Card -->
            <div class="rounded-3xl bg-white border border-gray-200 p-6 shadow-sm space-y-5">
                <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                    <h3 class="text-sm font-extrabold text-[#2C2C2C] flex items-center gap-2">
                        <i class="fa-solid fa-user-tag text-amber-500"></i>
                        <span>Data Calon Pelanggan</span>
                    </h3>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-gray-100 text-gray-600 border border-gray-200">
                        Survey Sales
                    </span>
                </div>

                <div class="space-y-3 text-xs">
                    <div>
                        <span class="text-gray-500 block text-[11px] font-medium">Nama Calon Pelanggan:</span>
                        <span class="font-extrabold text-[#2C2C2C] text-base">{{ $registration->customer_name }}</span>
                    </div>

                    <div class="grid grid-cols-2 gap-3 pt-1">
                        <div>
                            <span class="text-gray-500 block text-[11px] font-medium">Nomor WhatsApp:</span>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $registration->phone_wa) }}" target="_blank" class="font-bold text-emerald-600 hover:underline flex items-center gap-1">
                                <i class="fa-brands fa-whatsapp"></i> {{ $registration->phone_wa }}
                            </a>
                        </div>
                        <div>
                            <span class="text-gray-500 block text-[11px] font-medium">Email:</span>
                            <span class="font-semibold text-gray-800 truncate block">{{ $registration->email ?: '-' }}</span>
                        </div>
                    </div>

                    <div class="pt-2 border-t border-gray-100 space-y-1.5">
                        <span class="text-gray-500 block text-[11px] font-medium">Alamat Lengkap &amp; Wilayah:</span>
                        <p class="font-semibold text-[#2C2C2C] leading-relaxed">{{ $registration->full_address }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-2 pt-2 text-[11px] bg-[#FEF4F0] p-3 rounded-xl border border-[#F6D8CE]">
                        <div>
                            <span class="text-gray-500 block">Kelurahan:</span>
                            <span class="font-bold text-gray-800">{{ $registration->village }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 block">Kecamatan:</span>
                            <span class="font-bold text-gray-800">{{ $registration->district }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 block">Kab/Kota:</span>
                            <span class="font-bold text-gray-800">{{ $registration->regency }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 block">Provinsi:</span>
                            <span class="font-bold text-gray-800">{{ $registration->province }}</span>
                        </div>
                    </div>

                    <div class="pt-2 border-t border-gray-100 flex items-center justify-between">
                        <div>
                            <span class="text-gray-500 block text-[11px] font-medium">Sales Account Manager (AM):</span>
                            <span class="font-bold text-[#F48C5B]">{{ $registration->sales_name }}</span>
                            <span class="text-gray-400 text-[10px]">({{ $registration->sales_am_id }})</span>
                        </div>
                        <div class="text-right">
                            <span class="text-gray-500 block text-[11px] font-medium">Waktu Survey:</span>
                            <span class="text-gray-700 text-[11px] font-semibold">{{ $registration->submitted_at?->format('d M Y, H:i') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Selfie with Customer Photo Preview -->
                @if($registration->selfie_sales_path)
                    <div class="pt-3 border-t border-gray-100">
                        <span class="text-gray-700 block text-xs font-bold mb-2 flex items-center justify-between">
                            <span>Foto Selfie Sales &amp; Pelanggan:</span>
                            <span class="text-[10px] text-emerald-600 font-bold"><i class="fa-solid fa-circle-check"></i> Terunggah</span>
                        </span>
                        <div class="relative group rounded-2xl overflow-hidden border-2 border-gray-200 hover:border-[#F48C5B] bg-gray-50 flex items-center justify-center h-48 p-1 cursor-pointer transition-all duration-200 shadow-xs hover:shadow-md"
                             onclick="openImageViewer('{{ asset($registration->selfie_sales_path) }}', 'Foto Selfie Sales &amp; Pelanggan - {{ $registration->customer_name }} bersama {{ $registration->sales_name }}')">
                            <img src="{{ asset($registration->selfie_sales_path) }}" 
                                 alt="Foto Selfie Sales" 
                                 class="h-full w-full object-cover rounded-xl group-hover:scale-105 transition-transform duration-200">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-bold gap-1.5 backdrop-blur-xs rounded-xl">
                                <span class="bg-black/60 px-2.5 py-1 rounded-full border border-white/30 flex items-center gap-1.5 shadow-lg text-[11px]">
                                    <i class="fa-solid fa-magnifying-glass-plus text-xs text-[#F48C5B]"></i>
                                    <span>Zoom &amp; Geser Foto</span>
                                </span>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="pt-3 border-t border-gray-100">
                        <span class="text-gray-700 block text-xs font-bold mb-1.5 flex items-center justify-between">
                            <span>Foto Selfie Sales &amp; Pelanggan:</span>
                            <span class="text-[10px] text-amber-600 font-bold bg-amber-50 px-2 py-0.5 rounded border border-amber-200">Tahap Formulir Pelanggan</span>
                        </span>
                        <div class="p-3.5 rounded-xl bg-gray-50 border border-gray-200 text-center text-xs text-gray-500">
                            <i class="fa-solid fa-image-portrait text-gray-400 text-xl mb-1 block"></i>
                            <span>Foto selfie bersama sales akan diunggah oleh pelanggan saat pengisian formulir kelengkapan data.</span>
                        </div>
                    </div>
                @endif
            </div>

            <!-- OPJ Verification Action Box -->
            @if($registration->status === 'submitted')
                <div class="rounded-3xl bg-white border-2 border-amber-300 p-6 shadow-md space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center font-bold text-lg shadow-sm">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-extrabold text-[#2C2C2C]">Form Verifikasi OPJ</h4>
                            <p class="text-xs text-amber-700">Konfirmasi ketersediaan jalur &amp; coverage</p>
                        </div>
                    </div>

                    <form action="{{ route('opj.verify', $registration->id) }}" method="POST" class="space-y-3.5">
                        @csrf
                        
                        <div>
                            <label class="block text-xs font-bold text-[#333333] mb-1">Titik ODP / FAT Terdekat (Opsional)</label>
                            <input type="text" name="odp_reference" placeholder="Contoh: ODP-LM-CT-09 (Jarak 45 meter)"
                                   class="w-full px-3.5 py-2 rounded-xl bg-white border border-gray-300 text-xs text-[#2C2C2C] placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#F48C5B]">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#333333] mb-1">Catatan Teknis Verifikasi</label>
                            <textarea name="notes" rows="3" placeholder="Masukkan catatan teknis lapangan..."
                                      class="w-full px-3.5 py-2 rounded-xl bg-white border border-gray-300 text-xs text-[#2C2C2C] placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#F48C5B]">Koordinat valid dan berada dalam jangkauan ODP LifeMedia. Lokasi siap diproses ke tahap pengisian data pelanggan.</textarea>
                        </div>

                        <button type="submit" 
                                class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-[#F48C5B] via-[#EF666B] to-[#9B385B] hover:from-[#EF666B] hover:to-[#F48C5B] text-white font-bold text-xs shadow-md shadow-orange-500/20 transition-all flex items-center justify-center gap-2">
                            <i class="fa-solid fa-check-double"></i>
                            <span>Verifikasi Survey &amp; Kirim Notifikasi ke Sales</span>
                        </button>
                    </form>
                </div>
            @else
                <div class="rounded-3xl bg-white border border-gray-200 p-5 shadow-sm text-xs space-y-3">
                    <div class="flex items-center gap-2 font-bold text-emerald-700">
                        <i class="fa-solid fa-circle-check text-base"></i>
                        <span>Survey Telah Diverifikasi OPJ</span>
                    </div>
                    <p class="text-gray-600 leading-relaxed">
                        Survey ini telah diverifikasi pada <strong>{{ $registration->verified_at?->format('d M Y, H:i') }}</strong>. 
                        Tautan pendaftaran online telah diteruskan ke Sales untuk dibagikan ke calon pelanggan.
                    </p>
                </div>
            @endif

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
                            <select name="status" id="adminVasStatusSelectOpj" required onchange="toggleAdminVasOpjRevisionBox(this.value)"
                                    class="w-full px-3 py-2 rounded-xl bg-[#FEF4F0] border border-[#F48C5B]/40 text-xs font-bold text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#9B385B]">
                                <option value="submitted" {{ $registration->status === 'submitted' ? 'selected' : '' }}>1. SUBMITTED (Survey Awal / Menunggu OPJ)</option>
                                <option value="verified" {{ $registration->status === 'verified' ? 'selected' : '' }}>2. VERIFIED (Terverifikasi OPJ / Menunggu Form Pelanggan)</option>
                                <option value="filled" {{ $registration->status === 'filled' ? 'selected' : '' }}>3. FILLED (Form Diisi Pelanggan / Menunggu C-Care)</option>
                                <option value="approved" {{ $registration->status === 'approved' ? 'selected' : '' }}>4. APPROVED (Disetujui / Selesai Closing)</option>
                                <option value="revision" {{ $registration->status === 'revision' ? 'selected' : '' }}>5. REVISION (Memerlukan Revisi Data/Berkas)</option>
                            </select>
                        </div>

                        <div id="adminVasOpjRevisionCategoryBox" class="{{ $registration->status === 'revision' ? '' : 'hidden' }} space-y-1">
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

                <script>
                    function toggleAdminVasOpjRevisionBox(status) {
                        const box = document.getElementById('adminVasOpjRevisionCategoryBox');
                        if (box) {
                            if (status === 'revision') {
                                box.classList.remove('hidden');
                            } else {
                                box.classList.add('hidden');
                            }
                        }
                    }
                </script>
            @endif

        </div>

    </div>

</div>

<!-- Full Interactive Image Viewer Modal (Zoom, Pan, Drag, Rotate) -->
<div id="imageViewerModal" class="fixed inset-0 z-50 hidden bg-black/90 backdrop-blur-md flex flex-col select-none opacity-0 transition-opacity duration-200">
    
    <!-- Top Floating Toolbar -->
    <div class="h-16 px-4 sm:px-6 bg-gradient-to-b from-black/80 via-black/40 to-transparent flex items-center justify-between z-20 text-white">
        
        <!-- Left: Image Title & Info -->
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
    document.addEventListener('DOMContentLoaded', function() {
        const lat = {{ $registration->latitude }};
        const lng = {{ $registration->longitude }};
        const custName = "{{ addslashes($registration->customer_name) }}";
        const address = "{{ addslashes($registration->full_address) }}";

        const map = L.map('surveyMap').setView([lat, lng], 16);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Marker for customer home
        const customerMarker = L.marker([lat, lng]).addTo(map)
            .bindPopup(`
                <div style="font-family: sans-serif; font-size: 12px;">
                    <strong style="color: #F48C5B;">${custName}</strong><br>
                    <span>${address}</span><br>
                    <small style="color: #64748b;">Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}</small>
                </div>
            `).openPopup();

        // Coverage radius circle (150m)
        const coverageCircle = L.circle([lat, lng], {
            color: '#F48C5B',
            fillColor: '#EF666B',
            fillOpacity: 0.15,
            radius: 150
        }).addTo(map);
    });

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

    function openImageViewer(src, title = 'Preview Dokumen') {
        if (!src) return;
        
        viewerImage.src = src;
        viewerTitle.textContent = title;
        viewerDownloadBtn.href = src;
        
        viewerImage.className = "max-h-[85vh] max-w-[90vw] object-contain transition-transform duration-75 ease-out will-change-transform shadow-2xl rounded-xl pointer-events-auto bg-transparent";
        
        resetImageViewer(false);
        
        viewerModal.classList.remove('hidden');
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
