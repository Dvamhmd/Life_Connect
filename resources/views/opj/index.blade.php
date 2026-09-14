@extends('layouts.app')

@section('title', 'Dashboard OPJ - Verifikasi Survey')
@section('header_title', 'Dashboard OPJ')
@section('header_subtitle', '')

@section('content')
<div class="space-y-6">
    
    <!-- Top Stats KPI Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        
        <div class="p-4 rounded-2xl bg-white border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-gray-500">Total Survey</span>
                <span class="w-8 h-8 rounded-lg bg-gray-100 text-gray-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-list-check"></i>
                </span>
            </div>
            <div class="text-2xl font-extrabold text-[#2C2C2C] mt-2">{{ $stats['total'] }}</div>
            <div class="text-[11px] text-gray-500 mt-0.5">Semua data masuk</div>
        </div>

        <div class="p-4 rounded-2xl bg-[#FEF4F0] border border-[#F48C5B]/40 shadow-sm relative overflow-hidden">
            <div class="absolute -right-2 -bottom-2 w-16 h-16 bg-[#F48C5B]/10 rounded-full blur-xl pointer-events-none"></div>
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-[#9B385B]">Perlu Verifikasi</span>
                <span class="w-8 h-8 rounded-lg bg-[#F48C5B]/20 text-[#9B385B] flex items-center justify-center text-xs">
                    <i class="fa-solid fa-hourglass-half animate-spin"></i>
                </span>
            </div>
            <div class="text-2xl font-extrabold text-[#9B385B] mt-2">{{ $stats['pending_opj'] }}</div>
            <div class="text-[11px] text-[#F48C5B] font-semibold mt-0.5">Status: Submitted</div>
        </div>

        <div class="p-4 rounded-2xl bg-blue-50 border border-blue-200 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-blue-800">Terverifikasi OPJ</span>
                <span class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-circle-check"></i>
                </span>
            </div>
            <div class="text-2xl font-extrabold text-blue-900 mt-2">{{ $stats['verified'] }}</div>
            <div class="text-[11px] text-blue-700 mt-0.5">Menunggu form pelanggan</div>
        </div>

        <div class="p-4 rounded-2xl bg-indigo-50 border border-indigo-200 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-indigo-800">Diisi Pelanggan</span>
                <span class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-file-signature"></i>
                </span>
            </div>
            <div class="text-2xl font-extrabold text-indigo-900 mt-2">{{ $stats['filled'] }}</div>
            <div class="text-[11px] text-indigo-700 mt-0.5">Menunggu C-Care</div>
        </div>

        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-emerald-800">Disetujui</span>
                <span class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-thumbs-up"></i>
                </span>
            </div>
            <div class="text-2xl font-extrabold text-emerald-900 mt-2">{{ $stats['approved'] }}</div>
            <div class="text-[11px] text-emerald-700 mt-0.5">Approved &amp; Siap pasang</div>
        </div>

    </div>

    <!-- Main Card & Data Table -->
    <div class="rounded-2xl bg-white border border-gray-200 shadow-sm overflow-hidden">
        
        <!-- Table Filter and Search Header -->
        <div class="p-4 sm:p-5 border-b border-gray-100 flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-white">
            
            <!-- Status Filter Tabs -->
            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 lg:pb-0 custom-scrollbar">
                <a href="{{ route('opj.index', ['status' => 'submitted', 'search' => $search, 'per_page' => $perPage]) }}" 
                   class="px-3.5 py-1.5 rounded-lg text-xs font-bold whitespace-nowrap transition-all {{ $status === 'submitted' ? 'bg-gradient-to-r from-[#F48C5B] via-[#EF666B] to-[#9B385B] text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:text-[#2C2C2C] hover:bg-gray-200' }}">
                    Menunggu OPJ ({{ $stats['pending_opj'] }})
                </a>
                <a href="{{ route('opj.index', ['status' => 'verified', 'search' => $search, 'per_page' => $perPage]) }}" 
                   class="px-3.5 py-1.5 rounded-lg text-xs font-bold whitespace-nowrap transition-all {{ $status === 'verified' ? 'bg-blue-600 text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:text-[#2C2C2C] hover:bg-gray-200' }}">
                    Terverifikasi ({{ $stats['verified'] }})
                </a>
                <a href="{{ route('opj.index', ['status' => 'all', 'search' => $search, 'per_page' => $perPage]) }}" 
                   class="px-3.5 py-1.5 rounded-lg text-xs font-bold whitespace-nowrap transition-all {{ $status === 'all' ? 'bg-gray-800 text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:text-[#2C2C2C] hover:bg-gray-200' }}">
                    Semua ({{ $stats['total'] }})
                </a>
            </div>

            <!-- Search Form & Per Page Selector -->
            <div class="flex flex-wrap items-center gap-2.5">
                <!-- Per Page Selector -->
                <form action="{{ route('opj.index') }}" method="GET" class="flex items-center gap-1.5 text-xs text-gray-500">
                    <input type="hidden" name="status" value="{{ $status }}">
                    @if($search)
                        <input type="hidden" name="search" value="{{ $search }}">
                    @endif
                    <span class="hidden sm:inline text-xs font-medium text-gray-500">Tampil:</span>
                    <select name="per_page" onchange="this.form.submit()" 
                            class="px-2.5 py-1.5 rounded-lg bg-gray-50 border border-gray-300 text-xs font-semibold text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#F48C5B] cursor-pointer">
                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 / hal</option>
                        <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25 / hal</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 / hal</option>
                        <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100 / hal</option>
                    </select>
                </form>

                <!-- Search Form -->
                <form action="{{ route('opj.index') }}" method="GET" class="flex items-center gap-2">
                    <input type="hidden" name="status" value="{{ $status }}">
                    <input type="hidden" name="per_page" value="{{ $perPage }}">
                    <div class="relative w-full sm:w-56 md:w-64">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400 text-xs">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, kode, sales..."
                               class="w-full pl-8 pr-3 py-1.5 rounded-lg bg-white border border-gray-300 text-xs text-[#2C2C2C] placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#F48C5B]">
                    </div>
                    @if($search)
                        <a href="{{ route('opj.index', ['status' => $status, 'per_page' => $perPage]) }}" class="px-2.5 py-1.5 rounded-lg bg-gray-100 text-gray-600 hover:text-[#2C2C2C] text-xs">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    @endif
                    <button type="submit" class="px-3.5 py-1.5 rounded-lg bg-[#F48C5B] hover:bg-[#EF666B] text-white text-xs font-bold transition-colors shadow-xs">
                        Cari
                    </button>
                </form>
            </div>
        </div>

        <!-- Table Listing -->
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-[#F8F9FA] text-gray-500 font-bold uppercase tracking-wider text-[11px] border-b border-gray-200">
                    <tr>
                        <th scope="col" class="px-5 py-3.5 text-left w-[20%]">Kode &amp; Pelanggan</th>
                        <th scope="col" class="px-4 py-3.5 text-left w-[14%]">Kontak</th>
                        <th scope="col" class="px-4 py-3.5 text-left w-[21%]">Lokasi Survey</th>
                        <th scope="col" class="px-4 py-3.5 text-left w-[13%]">Koordinat GPS</th>
                        <th scope="col" class="px-4 py-3.5 text-left w-[12%]">Sales Surveyor</th>
                        <th scope="col" class="px-4 py-3.5 text-center w-[11%]">Status</th>
                        <th scope="col" class="px-4 py-3.5 text-center w-[10%]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse($registrations as $reg)
                        <tr class="hover:bg-[#FEF4F0]/40 transition-colors {{ $reg->status === 'submitted' ? 'bg-[#FEF4F0]/25' : '' }}">
                            
                            <!-- Customer Info -->
                            <td class="px-5 py-3.5 align-middle text-left">
                                <div class="font-extrabold text-[#2C2C2C] text-sm leading-tight">
                                    {{ $reg->customer_name }}
                                </div>
                                <div class="font-mono text-[11px] font-bold text-[#F48C5B] mt-0.5">
                                    {{ $reg->registration_code }}
                                </div>
                                <div class="text-[10px] text-gray-400 mt-0.5">
                                    Submit: {{ $reg->submitted_at ? $reg->submitted_at->format('d M Y, H:i') : '-' }}
                                </div>
                            </td>

                            <!-- Contact -->
                            <td class="px-4 py-3.5 align-middle text-left whitespace-nowrap">
                                <div class="font-semibold text-[#2C2C2C] text-xs flex items-center gap-1.5">
                                    <i class="fa-brands fa-whatsapp text-emerald-600 text-sm"></i>
                                    <span>{{ $reg->phone_wa }}</span>
                                </div>
                                @if($reg->email)
                                    <div class="text-gray-400 text-[10px] mt-0.5 truncate max-w-[140px]" title="{{ $reg->email }}">
                                        {{ $reg->email }}
                                    </div>
                                @endif
                            </td>

                            <!-- Location -->
                            <td class="px-4 py-3.5 align-middle text-left">
                                <div class="font-bold text-gray-800 text-xs leading-snug">
                                    {{ $reg->village }}, {{ $reg->district }}
                                </div>
                                <div class="text-[11px] text-gray-500 mt-0.5">
                                    {{ $reg->regency }}, {{ $reg->province }}
                                </div>
                                @if($reg->address_detail)
                                    <div class="text-[10px] text-gray-400 italic truncate max-w-[210px] mt-0.5" title="{{ $reg->address_detail }}">
                                        {{ $reg->address_detail }}
                                    </div>
                                @endif
                            </td>

                            <!-- Coordinates -->
                            <td class="px-4 py-3.5 align-middle text-left font-mono text-[11px] whitespace-nowrap">
                                <div class="text-[#9B385B] font-bold flex items-center gap-1.5">
                                    <i class="fa-solid fa-location-dot text-[#EF666B]"></i>
                                    <span>{{ number_format($reg->latitude, 6) }}</span>
                                </div>
                                <div class="text-gray-400 text-[10px] pl-4 mt-0.5">
                                    {{ number_format($reg->longitude, 6) }}
                                </div>
                            </td>

                            <!-- Sales Info -->
                            <td class="px-4 py-3.5 align-middle text-left whitespace-nowrap">
                                <div class="font-bold text-[#2C2C2C] text-xs leading-tight">
                                    {{ $reg->sales_name ?: 'Sales LifeMedia' }}
                                </div>
                                <div class="mt-0.5">
                                    <span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-mono font-bold bg-[#FEF4F0] text-[#EF666B] border border-[#F48C5B]/30">
                                        {{ $reg->sales_am_id ?: 'AM' }}
                                    </span>
                                </div>
                            </td>

                            <!-- Status Badge -->
                            <td class="px-4 py-3.5 align-middle text-center whitespace-nowrap">
                                @php $badge = $reg->status_badge; @endphp
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $badge['bg'] }}">
                                    @if($reg->status === 'submitted')
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-ping"></span>
                                    @endif
                                    {{ $badge['label'] }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="px-4 py-3.5 align-middle text-center whitespace-nowrap">
                                <a href="{{ route('opj.show', $reg->id) }}" 
                                   class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all inline-flex items-center gap-1.5 {{ $reg->status === 'submitted' ? 'bg-gradient-to-r from-[#F48C5B] via-[#EF666B] to-[#9B385B] hover:from-[#EF666B] hover:to-[#F48C5B] text-white shadow-md shadow-orange-500/20' : 'bg-gray-100 hover:bg-gray-200 text-gray-700 border border-gray-200' }}">
                                    <i class="fa-solid {{ $reg->status === 'submitted' ? 'fa-magnifying-glass-location' : 'fa-eye' }}"></i>
                                    <span>Cek Detail</span>
                                </a>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                <div class="w-12 h-12 rounded-2xl bg-gray-100 text-gray-400 flex items-center justify-center mx-auto mb-3 text-xl">
                                    <i class="fa-solid fa-inbox"></i>
                                </div>
                                <div class="text-sm font-bold text-gray-600">Tidak ada data survey yang cocok</div>
                                <div class="text-xs text-gray-400 mt-1">Coba sesuaikan filter status atau kata kunci pencarian Anda.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination & Summary Footer -->
        @if($registrations->total() > 0)
            <div class="p-4 border-t border-gray-100 bg-[#F8F9FA]">
                @if($registrations->hasPages())
                    {{ $registrations->links() }}
                @else
                    <div class="flex items-center justify-between text-xs text-gray-500">
                        <div>
                            Menampilkan
                            <span class="font-bold text-[#2C2C2C]">{{ $registrations->total() }}</span>
                            dari
                            <span class="font-bold text-[#2C2C2C]">{{ $registrations->total() }}</span>
                            data survey
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

<!-- Quick Verification & OpenStreetMap Modal -->
<div id="verifyModal" class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-xs overflow-y-auto p-4 flex items-center justify-center">
    <div class="relative w-full max-w-2xl bg-white border border-gray-200 rounded-3xl shadow-2xl overflow-hidden my-8">
        
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-[#F8F9FA]">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center text-sm font-bold">
                    <i class="fa-solid fa-map-location-dot"></i>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-[#2C2C2C]" id="modalCustomerName">Verifikasi Lokasi Survey OPJ</h3>
                    <p class="text-[11px] font-mono text-[#F48C5B]" id="modalRegCode">REG-XXXX</p>
                </div>
            </div>
            <button type="button" onclick="closeVerifyModal()" class="text-gray-400 hover:text-gray-600 text-lg">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="verifyForm" method="POST" action="">
            @csrf
            <div class="p-6 space-y-4">
                
                <!-- OpenStreetMap Mini Preview Box in Modal -->
                <div class="rounded-2xl border border-gray-200 overflow-hidden relative shadow-inner">
                    <div id="modalMap" style="height: 220px; width: 100%;" class="z-10"></div>
                    <div class="absolute bottom-2 left-2 z-20 px-2.5 py-1 rounded-lg bg-white/90 border border-gray-200 text-[10px] font-mono text-[#9B385B] shadow-xs">
                        <i class="fa-solid fa-crosshairs text-[#F48C5B]"></i> Lat: <span id="modalLat"></span>, Long: <span id="modalLng"></span>
                    </div>
                </div>

                <div class="p-3.5 rounded-xl bg-[#FEF4F0] border border-[#F6D8CE] space-y-1.5 text-xs">
                    <div class="flex items-start gap-2">
                        <span class="text-gray-500 min-w-[90px]">Alamat Lengkap:</span>
                        <span class="font-bold text-[#2C2C2C]" id="modalAddress">-</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-gray-500 min-w-[90px]">Sales AM:</span>
                        <span class="font-bold text-[#F48C5B]" id="modalSalesName">-</span>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#333333] mb-1">Referensi Titik ODP / FAT Terdekat (Opsional)</label>
                    <input type="text" name="odp_reference" placeholder="Contoh: ODP-LM-CT-04 (Jarak 35 meter)"
                           class="w-full px-3.5 py-2 rounded-xl bg-white border border-gray-300 text-xs text-[#2C2C2C] placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#F48C5B]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#333333] mb-1">Catatan Hasil Verifikasi Lapangan</label>
                    <textarea name="notes" rows="2" placeholder="Tuliskan catatan teknis OPJ (Kabel fiber, jalur tiang, ketersediaan port, dll)..."
                              class="w-full px-3.5 py-2 rounded-xl bg-white border border-gray-300 text-xs text-[#2C2C2C] placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#F48C5B]">Lokasi terverifikasi oleh OPJ dan masuk ke dalam coverage area LifeMedia.</textarea>
                </div>

                <div class="p-3.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-xs flex items-start gap-2.5">
                    <i class="fa-solid fa-circle-info text-sm mt-0.5 text-amber-600"></i>
                    <p class="leading-relaxed">
                        Saat tombol <strong>"Setujui Verifikasi"</strong> ditekan, status pengajuan akan berubah menjadi <strong>Verified</strong> dan sistem akan secara otomatis mengirimkan notifikasi ke <strong>Mobile Apps Sales</strong> yang bersangkutan untuk membagikan Link WA ke pelanggan.
                    </p>
                </div>

            </div>

            <div class="px-6 py-4 border-t border-gray-100 bg-[#F8F9FA] flex items-center justify-end gap-3">
                <button type="button" onclick="closeVerifyModal()" class="px-4 py-2 rounded-xl bg-white hover:bg-gray-100 text-gray-700 text-xs font-bold border border-gray-200">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-gradient-to-r from-[#F48C5B] via-[#EF666B] to-[#9B385B] hover:from-[#EF666B] hover:to-[#F48C5B] text-white text-xs font-bold shadow-md shadow-orange-500/20 flex items-center gap-1.5">
                    <i class="fa-solid fa-check"></i>
                    <span>Setujui Verifikasi &amp; Kirim Notifikasi Sales</span>
                </button>
            </div>
        </form>

    </div>
</div>

@push('scripts')
<script>
    let modalMapInstance = null;
    let modalMarkerInstance = null;

    function openVerifyModal(id, name, code, sales, address, lat, lng) {
        document.getElementById('modalCustomerName').innerText = name;
        document.getElementById('modalRegCode').innerText = code;
        document.getElementById('modalAddress').innerText = address;
        document.getElementById('modalSalesName').innerText = sales;
        document.getElementById('modalLat').innerText = lat.toFixed(6);
        document.getElementById('modalLng').innerText = lng.toFixed(6);
        document.getElementById('verifyForm').action = '/opj/surveys/' + id + '/verify';

        document.getElementById('verifyModal').classList.remove('hidden');

        // Initialize or update Leaflet Map
        setTimeout(() => {
            if (!modalMapInstance) {
                modalMapInstance = L.map('modalMap').setView([lat, lng], 16);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(modalMapInstance);

                modalMarkerInstance = L.marker([lat, lng]).addTo(modalMapInstance)
                    .bindPopup(`<b>${name}</b><br>${address}`).openPopup();
            } else {
                modalMapInstance.invalidateSize();
                modalMapInstance.setView([lat, lng], 16);
                modalMarkerInstance.setLatLng([lat, lng])
                    .setPopupContent(`<b>${name}</b><br>${address}`).openPopup();
            }
        }, 200);
    }

    function closeVerifyModal() {
        document.getElementById('verifyModal').classList.add('hidden');
    }
</script>
@endpush
@endsection
