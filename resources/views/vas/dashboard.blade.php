@extends('layouts.app')

@section('title', 'Dashboard - Verifikasi & Status Pengajuan')
@section('header_title', 'Dashboard Pengajuan Pelanggan')
@section('header_subtitle', '')

@section('content')
<div class="space-y-6">
    
    <!-- Top KPI Stats Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-6 gap-4">
        
        <div class="p-4 rounded-2xl bg-white border border-gray-200 shadow-sm">
            <div class="text-xs font-bold text-gray-500">Total Pengajuan</div>
            <div class="text-2xl font-extrabold text-[#2C2C2C] mt-1.5">{{ $stats['total'] }}</div>
            <div class="text-[10px] text-gray-400 mt-0.5">Semua survey masuk</div>
        </div>

        <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 shadow-sm">
            <div class="text-xs font-bold text-amber-800">Tahap OPJ</div>
            <div class="text-2xl font-extrabold text-amber-900 mt-1.5">{{ $stats['submitted'] }}</div>
            <div class="text-[10px] text-amber-700 mt-0.5 font-medium">Submitted (Survey)</div>
        </div>

        <div class="p-4 rounded-2xl bg-blue-50 border border-blue-200 shadow-sm">
            <div class="text-xs font-bold text-blue-800">Menunggu Pelanggan</div>
            <div class="text-2xl font-extrabold text-blue-900 mt-1.5">{{ $stats['verified'] }}</div>
            <div class="text-[10px] text-blue-700 mt-0.5 font-medium">Link WA Dibagikan</div>
        </div>

        <div class="p-4 rounded-2xl bg-indigo-50 border border-indigo-200 shadow-sm">
            <div class="text-xs font-bold text-indigo-800">Tahap C-Care</div>
            <div class="text-2xl font-extrabold text-indigo-900 mt-1.5">{{ $stats['filled'] }}</div>
            <div class="text-[10px] text-indigo-700 mt-0.5 font-medium">Filled (Review)</div>
        </div>

        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 shadow-sm">
            <div class="text-xs font-bold text-emerald-800">Approved</div>
            <div class="text-2xl font-extrabold text-emerald-900 mt-1.5">{{ $stats['approved'] }}</div>
            <div class="text-[10px] text-emerald-700 mt-0.5 font-medium">Closing Pelanggan</div>
        </div>

        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 shadow-sm">
            <div class="text-xs font-bold text-rose-800">Revisi</div>
            <div class="text-2xl font-extrabold text-rose-900 mt-1.5">{{ $stats['revision'] }}</div>
            <div class="text-[10px] text-rose-700 mt-0.5 font-medium">Perlu Follow Up Sales</div>
        </div>

    </div>

    <!-- SLA Evaluation Metrics Section (Evaluasi Dimana Proses yang Lama) -->
    <div class="rounded-3xl bg-white border border-gray-200 p-6 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-gray-100 pb-3">
            <div>
                <h3 class="text-sm font-extrabold text-[#2C2C2C] flex items-center gap-2">
                    <i class="fa-solid fa-stopwatch text-[#F48C5B]"></i>
                    <span>Analisis &amp; Evaluasi Proses (Bottleneck Tracking)</span>
                </h3>
                <p class="text-xs text-gray-500">Rata-rata waktu yang dibutuhkan pada setiap tahapan untuk evaluasi tim</p>
            </div>
            <div class="text-[11px] text-gray-400 font-mono font-medium">
                Berdasarkan Log Aktivitas Sistem
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            
            <!-- OPJ SLA -->
            <div class="p-4 rounded-2xl bg-[#FEF4F0] border border-[#F6D8CE] space-y-2">
                <div class="flex items-center justify-between text-xs">
                    <span class="font-bold text-[#333333]">1. Respon Verifikasi OPJ</span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-white text-amber-800 border border-amber-200">Submitted &rarr; Verified</span>
                </div>
                <div class="text-xl font-extrabold text-[#F48C5B]">
                    @if($avgOpjDuration)
                        @if($avgOpjDuration >= 3600)
                            {{ round($avgOpjDuration / 3600, 1) }} <span class="text-xs font-normal text-gray-500">Jam rata-rata</span>
                        @else
                            {{ max(1, round($avgOpjDuration / 60)) }} <span class="text-xs font-normal text-gray-500">Menit rata-rata</span>
                        @endif
                    @else
                        <span class="text-gray-400 text-sm font-semibold">Belum ada data</span>
                    @endif
                </div>
                <p class="text-[11px] text-gray-600 leading-relaxed">
                    Waktu tim OPJ memvalidasi titik koordinat GPS dan ketersediaan port ODP setelah Sales submit survey.
                </p>
            </div>

            <!-- Customer Fill SLA -->
            <div class="p-4 rounded-2xl bg-[#FEF4F0] border border-[#F6D8CE] space-y-2">
                <div class="flex items-center justify-between text-xs">
                    <span class="font-bold text-[#333333]">2. Pengisian Form Pelanggan</span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-white text-blue-800 border border-blue-200">Verified &rarr; Filled</span>
                </div>
                <div class="text-xl font-extrabold text-[#EF666B]">
                    @if($avgCustFillDuration)
                        @if($avgCustFillDuration >= 3600)
                            {{ round($avgCustFillDuration / 3600, 1) }} <span class="text-xs font-normal text-gray-500">Jam rata-rata</span>
                        @else
                            {{ max(1, round($avgCustFillDuration / 60)) }} <span class="text-xs font-normal text-gray-500">Menit rata-rata</span>
                        @endif
                    @else
                        <span class="text-gray-400 text-sm font-semibold">Belum ada data</span>
                    @endif
                </div>
                <p class="text-[11px] text-gray-600 leading-relaxed">
                    Waktu pelanggan melengkapi NIK, memilih paket, dan membubuhkan TTD digital sejak link WA dikirim.
                </p>
            </div>

            <!-- C-Care SLA -->
            <div class="p-4 rounded-2xl bg-[#FEF4F0] border border-[#F6D8CE] space-y-2">
                <div class="flex items-center justify-between text-xs">
                    <span class="font-bold text-[#333333]">3. Verifikasi Akhir C-Care</span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-white text-indigo-800 border border-indigo-200">Filled &rarr; Approved</span>
                </div>
                <div class="text-xl font-extrabold text-[#9B385B]">
                    @if($avgCCareDuration)
                        @if($avgCCareDuration >= 3600)
                            {{ round($avgCCareDuration / 3600, 1) }} <span class="text-xs font-normal text-gray-500">Jam rata-rata</span>
                        @else
                            {{ max(1, round($avgCCareDuration / 60)) }} <span class="text-xs font-normal text-gray-500">Menit rata-rata</span>
                        @endif
                    @else
                        <span class="text-gray-400 text-sm font-semibold">Belum ada data</span>
                    @endif
                </div>
                <p class="text-[11px] text-gray-600 leading-relaxed">
                    Waktu tim C-Care memeriksa keabsahan foto KTP &amp; TTD sebelum disetujui (Approved) untuk instalasi.
                </p>
            </div>

        </div>
    </div>

    <!-- Main Table Container -->
    <div class="rounded-2xl bg-white border border-gray-200 shadow-sm overflow-hidden">
        
        <!-- Filter Tabs & Search -->
        <div class="p-4 sm:p-5 border-b border-gray-100 flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-white">
            
            <!-- Left Side: Tab Semua Berkas, Dropdown Filter Status & Sorting -->
            <div class="flex flex-wrap items-center gap-2">
                <!-- Tab Semua Berkas -->
                <a href="{{ route('vas.dashboard', ['status' => 'all', 'search' => $search, 'per_page' => $perPage, 'sort_by' => $sortBy, 'sort_dir' => $sortDir]) }}" 
                   class="px-3.5 py-1.5 rounded-lg text-xs font-bold whitespace-nowrap transition-all {{ ($status === 'all' || empty($status)) ? 'bg-gradient-to-r from-[#F48C5B] via-[#EF666B] to-[#9B385B] text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:text-[#2C2C2C] hover:bg-gray-200' }}">
                    <i class="fa-solid fa-folder-open mr-1"></i> Semua Berkas ({{ $stats['total'] }})
                </a>

                <!-- Dropdown Filter Berdasarkan Status -->
                <form action="{{ route('vas.dashboard') }}" method="GET" class="flex items-center">
                    @if($search)
                        <input type="hidden" name="search" value="{{ $search }}">
                    @endif
                    <input type="hidden" name="per_page" value="{{ $perPage }}">
                    <input type="hidden" name="sort_by" value="{{ $sortBy }}">
                    <input type="hidden" name="sort_dir" value="{{ $sortDir }}">
                    <div class="relative">
                        <select name="status" onchange="this.form.submit()" 
                                class="pl-3 pr-8 py-1.5 rounded-lg border text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-[#F48C5B] cursor-pointer appearance-none transition-colors {{ $status !== 'all' ? 'bg-[#FEF4F0] border-[#9B385B]/40 text-[#9B385B]' : 'bg-gray-50 border-gray-300 text-gray-700' }}">
                            <option value="all" {{ ($status === 'all' || empty($status)) ? 'selected' : '' }}>Filter Status: Semua Status</option>
                            <option value="submitted" {{ $status === 'submitted' ? 'selected' : '' }}>Tahap OPJ ({{ $stats['submitted'] }})</option>
                            <option value="verified" {{ $status === 'verified' ? 'selected' : '' }}>Menunggu Pelanggan ({{ $stats['verified'] }})</option>
                            <option value="filled" {{ $status === 'filled' ? 'selected' : '' }}>Tahap C-Care ({{ $stats['filled'] }})</option>
                            <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved ({{ $stats['approved'] }})</option>
                            <option value="revision" {{ $status === 'revision' ? 'selected' : '' }}>Revisi ({{ $stats['revision'] }})</option>
                        </select>
                        <span class="absolute inset-y-0 right-0 flex items-center pr-2.5 pointer-events-none text-gray-400 text-[10px]">
                            <i class="fa-solid fa-chevron-down"></i>
                        </span>
                    </div>
                </form>

                <!-- Sorting Dropdown -->
                <form action="{{ route('vas.dashboard') }}" method="GET" class="flex items-center">
                    @if($search)
                        <input type="hidden" name="search" value="{{ $search }}">
                    @endif
                    <input type="hidden" name="status" value="{{ $status }}">
                    <input type="hidden" name="per_page" value="{{ $perPage }}">
                    <input type="hidden" name="sort_dir" value="{{ $sortDir }}">
                    <div class="relative">
                        <select name="sort_by" onchange="this.form.submit()" 
                                class="pl-3 pr-8 py-1.5 rounded-lg border bg-gray-50 border-gray-300 text-xs font-semibold text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#F48C5B] cursor-pointer appearance-none">
                            <option value="id" {{ $sortBy === 'id' ? 'selected' : '' }}>ID / Waktu Input</option>
                            <option value="customer_name" {{ $sortBy === 'customer_name' ? 'selected' : '' }}>Nama Pelanggan</option>
                            <option value="sales_name" {{ $sortBy === 'sales_name' ? 'selected' : '' }}>Sales AM</option>
                            <option value="status" {{ $sortBy === 'status' ? 'selected' : '' }}>Status</option>
                        </select>
                        <span class="absolute inset-y-0 right-0 flex items-center pr-2.5 pointer-events-none text-gray-400 text-[10px]">
                            <i class="fa-solid fa-chevron-down"></i>
                        </span>
                    </div>
                </form>

                <!-- ASC / DSC Toggle Button -->
                <a href="{{ route('vas.dashboard', ['status' => $status, 'search' => $search, 'per_page' => $perPage, 'sort_by' => $sortBy, 'sort_dir' => $sortDir === 'asc' ? 'desc' : 'asc']) }}" 
                   title="Urutan saat ini: {{ $sortDir === 'asc' ? 'ASC (A-Z / Terlama) - Klik untuk ganti ke DSC' : 'DSC (Z-A / Terbaru) - Klik untuk ganti ke ASC' }}"
                   class="px-2.5 py-1.5 rounded-lg border text-xs font-bold transition-all flex items-center gap-1.5 {{ $sortDir === 'asc' ? 'bg-[#FEF4F0] border-[#9B385B]/40 text-[#9B385B] shadow-2xs' : 'bg-gray-50 border-gray-300 text-gray-700 hover:bg-gray-100' }}">
                    <i class="fa-solid {{ $sortDir === 'asc' ? 'fa-arrow-up-wide-short text-[#9B385B]' : 'fa-arrow-down-wide-short text-gray-600' }}"></i>
                    <span>{{ $sortDir === 'asc' ? 'ASC' : 'DSC' }}</span>
                </a>
            </div>

            <!-- Search & Per Page Selector -->
            <div class="flex flex-wrap items-center gap-2.5">
                <!-- Per Page Selector -->
                <form action="{{ route('vas.dashboard') }}" method="GET" class="flex items-center gap-1.5 text-xs text-gray-500">
                    <input type="hidden" name="status" value="{{ $status }}">
                    <input type="hidden" name="sort_by" value="{{ $sortBy }}">
                    <input type="hidden" name="sort_dir" value="{{ $sortDir }}">
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
                <form action="{{ route('vas.dashboard') }}" method="GET" class="flex items-center gap-2">
                    <input type="hidden" name="status" value="{{ $status }}">
                    <input type="hidden" name="per_page" value="{{ $perPage }}">
                    <input type="hidden" name="sort_by" value="{{ $sortBy }}">
                    <input type="hidden" name="sort_dir" value="{{ $sortDir }}">
                    <div class="relative w-full sm:w-56 md:w-64">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400 text-xs">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, NIK, kode..."
                               class="w-full pl-8 pr-3 py-1.5 rounded-lg bg-white border border-gray-300 text-xs text-[#2C2C2C] placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#F48C5B]">
                    </div>
                    @if($search)
                        <a href="{{ route('vas.dashboard', ['status' => $status, 'per_page' => $perPage, 'sort_by' => $sortBy, 'sort_dir' => $sortDir]) }}" class="px-2.5 py-1.5 rounded-lg bg-gray-100 text-gray-600 hover:text-[#2C2C2C] text-xs">
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
                        <th class="px-5 py-2.5">
                            <a href="{{ route('vas.dashboard', ['status' => $status, 'search' => $search, 'per_page' => $perPage, 'sort_by' => 'customer_name', 'sort_dir' => ($sortBy === 'customer_name' && $sortDir === 'asc') ? 'desc' : 'asc']) }}" 
                                class="inline-flex items-center gap-1.5 hover:text-[#9B385B] transition-colors group" title="Urutkan berdasarkan Nama Pelanggan">
                                <span>Kode &amp; Nama Pelanggan</span>
                                @if($sortBy === 'customer_name')
                                    <i class="fa-solid {{ $sortDir === 'asc' ? 'fa-arrow-up-a-z text-[#9B385B]' : 'fa-arrow-down-z-a text-[#9B385B]' }}"></i>
                                @else
                                    <i class="fa-solid fa-sort text-gray-300 group-hover:text-gray-400 text-[10px]"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-4 py-2.5">NIK &amp; Kontak</th>
                        <th class="px-4 py-2.5">Paket Dipilih</th>
                        <th class="px-4 py-2.5">Metode Billing</th>
                        <th class="px-4 py-2.5">
                            <a href="{{ route('vas.dashboard', ['status' => $status, 'search' => $search, 'per_page' => $perPage, 'sort_by' => 'sales_name', 'sort_dir' => ($sortBy === 'sales_name' && $sortDir === 'asc') ? 'desc' : 'asc']) }}" 
                                class="inline-flex items-center gap-1.5 hover:text-[#9B385B] transition-colors group" title="Urutkan berdasarkan Sales AM">
                                <span>Sales AM</span>
                                @if($sortBy === 'sales_name')
                                    <i class="fa-solid {{ $sortDir === 'asc' ? 'fa-arrow-up-a-z text-[#9B385B]' : 'fa-arrow-down-z-a text-[#9B385B]' }}"></i>
                                @else
                                    <i class="fa-solid fa-sort text-gray-300 group-hover:text-gray-400 text-[10px]"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-4 py-2.5 text-center">
                            <a href="{{ route('vas.dashboard', ['status' => $status, 'search' => $search, 'per_page' => $perPage, 'sort_by' => 'status', 'sort_dir' => ($sortBy === 'status' && $sortDir === 'asc') ? 'desc' : 'asc']) }}" 
                                class="inline-flex items-center gap-1.5 hover:text-[#9B385B] transition-colors group" title="Urutkan berdasarkan Status">
                                <span>Status</span>
                                @if($sortBy === 'status')
                                    <i class="fa-solid {{ $sortDir === 'asc' ? 'fa-arrow-up-wide-short text-[#9B385B]' : 'fa-arrow-down-wide-short text-[#9B385B]' }}"></i>
                                @else
                                    <i class="fa-solid fa-sort text-gray-300 group-hover:text-gray-400 text-[10px]"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-5 py-2.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse($registrations as $reg)
                        <tr class="hover:bg-[#FEF4F0]/40 transition-colors {{ $reg->status === 'filled' ? 'bg-indigo-50/40' : ($reg->status === 'revision' ? 'bg-rose-50/40' : '') }}">
                            
                            <!-- Customer Info -->
                            <td class="px-5 py-2.5">
                                <div class="font-extrabold text-[#2C2C2C] text-sm leading-tight">
                                    {{ $reg->customer_name }}
                                </div>
                                <div class="font-mono text-[11px] font-bold text-[#F48C5B] mt-0.5">
                                    {{ $reg->registration_code }}
                                </div>
                            </td>

                            <!-- NIK & Contact -->
                            <td class="px-4 py-2.5">
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
                            <td class="px-4 py-2.5">
                                @if($reg->package)
                                    <div class="font-extrabold text-[#9B385B] leading-tight">{{ $reg->package->name }}</div>
                                    <div class="text-[11px] font-bold text-[#F48C5B] mt-0.5">{{ $reg->package->formatted_price }} / bln</div>
                                @else
                                    <span class="text-gray-400 italic text-[11px]">-</span>
                                @endif
                            </td>

                            <!-- Billing -->
                            <td class="px-4 py-2.5">
                                <div class="font-bold text-gray-800 leading-tight">{{ $reg->billing_method ?: '-' }}</div>
                                <div class="text-[11px] text-gray-500 mt-0.5">{{ $reg->billing_email ?: $reg->email }}</div>
                            </td>

                            <!-- Sales -->
                            <td class="px-4 py-2.5">
                                <div class="font-bold text-[#2C2C2C] leading-tight">{{ $reg->sales_name }}</div>
                                <span class="text-[10px] text-[#9B385B] font-mono font-bold mt-0.5 inline-block">{{ $reg->sales_am_id }}</span>
                            </td>

                            <!-- Status Badge -->
                            <td class="px-4 py-2.5 text-center">
                                @php $badge = $reg->status_badge; @endphp
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $badge['bg'] }}">
                                    @if($reg->status === 'filled')
                                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-ping"></span>
                                    @endif
                                    {{ $badge['label'] }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="px-5 py-2.5 text-center whitespace-nowrap">
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
