@extends('layouts.app')

@section('title', 'Dashboard C-Care - Verifikasi Berkas Pelanggan')
@section('header_title', 'Dashboard Customer Care (C-Care)')
@section('header_subtitle', 'Verifikasi kelengkapan dokumen KTP, foto rumah, tanda tangan digital, dan persetujuan berlangganan')

@section('content')
<div class="space-y-6">
    
    <!-- Top Stats Cards -->
    <!-- Top Stats Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <div class="p-4 rounded-2xl bg-indigo-50 border border-indigo-200 shadow-sm relative overflow-hidden">
            <div class="absolute -right-2 -bottom-2 w-16 h-16 bg-indigo-500/10 rounded-full blur-xl pointer-events-none"></div>
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-indigo-800">Perlu Verifikasi</span>
                <span class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-file-signature animate-pulse"></i>
                </span>
            </div>
            <div class="text-2xl font-extrabold text-indigo-900 mt-2">{{ $stats['needs_action'] }}</div>
            <div class="text-[11px] text-indigo-700 font-semibold mt-0.5">Status: Filled (Diisi Pelanggan)</div>
        </div>

        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-rose-800">Perlu Revisi</span>
                <span class="w-8 h-8 rounded-lg bg-rose-100 text-rose-700 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </span>
            </div>
            <div class="text-2xl font-extrabold text-rose-900 mt-2">{{ $stats['revision'] }}</div>
            <div class="text-[11px] text-rose-700 font-semibold mt-0.5">Menunggu follow up Sales</div>
        </div>

        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-emerald-800">Disetujui (Approved)</span>
                <span class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-thumbs-up"></i>
                </span>
            </div>
            <div class="text-2xl font-extrabold text-emerald-900 mt-2">{{ $stats['approved'] }}</div>
            <div class="text-[11px] text-emerald-700 font-semibold mt-0.5">Siap diterbitkan SPK Pasang</div>
        </div>

        <div class="p-4 rounded-2xl bg-white border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-gray-500">Total Berkas Pelanggan</span>
                <span class="w-8 h-8 rounded-lg bg-gray-100 text-gray-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-folder-open"></i>
                </span>
            </div>
            <div class="text-2xl font-extrabold text-[#2C2C2C] mt-2">{{ $stats['total'] }}</div>
            <div class="text-[11px] text-gray-500 mt-0.5">Filled, Revisi &amp; Approved</div>
        </div>

    </div>

    <!-- Main Table Container -->
    <div class="rounded-2xl bg-white border border-gray-200 shadow-sm overflow-hidden">
        
        <!-- Filter Tabs & Search -->
        <div class="p-4 sm:p-5 border-b border-gray-100 flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-white">
            
            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 lg:pb-0 custom-scrollbar">
                <a href="{{ route('ccare.index', ['status' => 'filled', 'search' => $search, 'per_page' => $perPage]) }}" 
                   class="px-3.5 py-1.5 rounded-lg text-xs font-bold whitespace-nowrap transition-all {{ $status === 'filled' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:text-[#2C2C2C] hover:bg-gray-200' }}">
                    <i class="fa-solid fa-file-signature mr-1"></i> Perlu Review C-Care ({{ $stats['needs_action'] }})
                </a>
                <a href="{{ route('ccare.index', ['status' => 'revision', 'search' => $search, 'per_page' => $perPage]) }}" 
                   class="px-3.5 py-1.5 rounded-lg text-xs font-bold whitespace-nowrap transition-all {{ $status === 'revision' ? 'bg-rose-600 text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:text-[#2C2C2C] hover:bg-gray-200' }}">
                    Revisi Data ({{ $stats['revision'] }})
                </a>
                <a href="{{ route('ccare.index', ['status' => 'approved', 'search' => $search, 'per_page' => $perPage]) }}" 
                   class="px-3.5 py-1.5 rounded-lg text-xs font-bold whitespace-nowrap transition-all {{ $status === 'approved' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:text-[#2C2C2C] hover:bg-gray-200' }}">
                    Disetujui ({{ $stats['approved'] }})
                </a>
                <a href="{{ route('ccare.index', ['status' => 'all', 'search' => $search, 'per_page' => $perPage]) }}" 
                   class="px-3.5 py-1.5 rounded-lg text-xs font-bold whitespace-nowrap transition-all {{ $status === 'all' ? 'bg-gradient-to-r from-[#F48C5B] via-[#EF666B] to-[#9B385B] text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:text-[#2C2C2C] hover:bg-gray-200' }}">
                    Semua Berkas ({{ $stats['total'] }})
                </a>
            </div>

            <!-- Search & Per Page Selector -->
            <div class="flex flex-wrap items-center gap-2.5">
                <!-- Per Page Selector -->
                <form action="{{ route('ccare.index') }}" method="GET" class="flex items-center gap-1.5 text-xs text-gray-500">
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

                <!-- Search -->
                <form action="{{ route('ccare.index') }}" method="GET" class="flex items-center gap-2">
                    <input type="hidden" name="status" value="{{ $status }}">
                    <input type="hidden" name="per_page" value="{{ $perPage }}">
                    <div class="relative w-full sm:w-56 md:w-64">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400 text-xs">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, NIK, kode..."
                               class="w-full pl-8 pr-3 py-1.5 rounded-lg bg-white border border-gray-300 text-xs text-[#2C2C2C] placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#F48C5B]">
                    </div>
                    @if($search)
                        <a href="{{ route('ccare.index', ['status' => $status, 'per_page' => $perPage]) }}" class="px-2.5 py-1.5 rounded-lg bg-gray-100 text-gray-600 hover:text-[#2C2C2C] text-xs">
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
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#F8F9FA] text-gray-600 font-bold uppercase tracking-wider border-b border-gray-200">
                    <tr>
                        <th class="px-5 py-3.5">Kode &amp; Nama Pelanggan</th>
                        <th class="px-4 py-3.5">NIK &amp; Kontak</th>
                        <th class="px-4 py-3.5">Paket Dipilih</th>
                        <th class="px-4 py-3.5">Metode Billing</th>
                        <th class="px-4 py-3.5">Sales AM</th>
                        <th class="px-4 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Aksi C-Care</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse($registrations as $reg)
                        <tr class="hover:bg-[#FEF4F0]/40 transition-colors {{ $reg->status === 'filled' ? 'bg-indigo-50/40' : ($reg->status === 'revision' ? 'bg-rose-50/40' : '') }}">
                            
                            <!-- Customer Info -->
                            <td class="px-5 py-4">
                                <div class="font-extrabold text-[#2C2C2C] text-sm">
                                    {{ $reg->customer_name }}
                                </div>
                                <div class="font-mono text-[11px] font-bold text-[#F48C5B] mt-0.5">
                                    {{ $reg->registration_code }}
                                </div>
                                <div class="text-[10px] text-gray-400 mt-0.5">
                                    Diisi: {{ $reg->filled_at ? $reg->filled_at->format('d M Y, H:i') : ($reg->submitted_at ? $reg->submitted_at->format('d M Y, H:i') : '-') }}
                                </div>
                            </td>

                            <!-- NIK & Contact -->
                            <td class="px-4 py-4">
                                @if($reg->nik)
                                    <div class="font-mono font-bold text-gray-800">{{ $reg->nik }}</div>
                                @else
                                    <span class="text-gray-400 italic text-[11px]">Belum diisi NIK</span>
                                @endif
                                <div class="text-gray-600 text-[11px] mt-0.5 flex items-center gap-1 font-medium">
                                    <i class="fa-brands fa-whatsapp text-emerald-600"></i> {{ $reg->phone_wa }}
                                </div>
                            </td>

                            <!-- Package -->
                            <td class="px-4 py-4">
                                @if($reg->package)
                                    <div class="font-extrabold text-[#9B385B]">{{ $reg->package->name }}</div>
                                    <div class="text-[11px] font-bold text-[#F48C5B]">{{ $reg->package->formatted_price }} / bln</div>
                                @else
                                    <span class="text-gray-400 italic text-[11px]">-</span>
                                @endif
                            </td>

                            <!-- Billing -->
                            <td class="px-4 py-4">
                                <div class="font-bold text-gray-800">{{ $reg->billing_method ?: '-' }}</div>
                                <div class="text-[11px] text-gray-500">{{ $reg->billing_email ?: $reg->email }}</div>
                            </td>

                            <!-- Sales -->
                            <td class="px-4 py-4">
                                <div class="font-bold text-[#2C2C2C]">{{ $reg->sales_name }}</div>
                                <span class="text-[10px] text-[#9B385B] font-mono font-bold">{{ $reg->sales_am_id }}</span>
                            </td>

                            <!-- Status Badge -->
                            <td class="px-4 py-4">
                                @php $badge = $reg->status_badge; @endphp
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $badge['bg'] }}">
                                    @if($reg->status === 'filled')
                                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-ping"></span>
                                    @endif
                                    {{ $badge['label'] }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <a href="{{ route('ccare.show', $reg->id) }}" 
                                   class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all inline-flex items-center gap-1.5 {{ $reg->status === 'filled' ? 'bg-gradient-to-r from-[#F48C5B] via-[#EF666B] to-[#9B385B] hover:from-[#EF666B] hover:to-[#F48C5B] text-white shadow-md shadow-orange-500/20' : 'bg-gray-100 hover:bg-gray-200 text-gray-700 border border-gray-200' }}">
                                    <i class="fa-solid {{ $reg->status === 'filled' ? 'fa-user-check' : 'fa-eye' }}"></i>
                                    <span>{{ $reg->status === 'filled' ? 'Review &amp; Verifikasi' : 'Lihat Detail' }}</span>
                                </a>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                <div class="w-12 h-12 rounded-2xl bg-gray-100 text-gray-400 flex items-center justify-center mx-auto mb-3 text-xl">
                                    <i class="fa-solid fa-inbox"></i>
                                </div>
                                <div class="text-sm font-bold text-gray-600">Tidak ada pengajuan yang ditemukan</div>
                                <div class="text-xs text-gray-400 mt-1">Coba sesuaikan filter atau kata kunci pencarian Anda.</div>
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
                            data berkas pelanggan
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
