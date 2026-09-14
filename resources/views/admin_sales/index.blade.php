@extends('layouts.app')

@section('title', 'Dashboard Admin Sales - Tracking Progress & SLA')
@section('header_title', 'Dashboard Admin Sales & Evaluasi Tim')
@section('header_subtitle', 'Monitoring pergerakan pengajuan calon pelanggan, analisis bottleneck proses, dan performa Sales AM')

@section('content')
<div class="space-y-6">
    
    <!-- Top KPI Stats & Conversion Overview -->
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-6 gap-4">
        
        <div class="p-4 rounded-2xl bg-white border border-gray-200 shadow-sm">
            <div class="text-xs font-bold text-gray-500">Total Pengajuan</div>
            <div class="text-2xl font-extrabold text-[#2C2C2C] mt-1.5">{{ $total }}</div>
            <div class="text-[10px] text-gray-400 mt-0.5">Semua survey masuk</div>
        </div>

        <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 shadow-sm">
            <div class="text-xs font-bold text-amber-800">Tahap OPJ</div>
            <div class="text-2xl font-extrabold text-amber-900 mt-1.5">{{ $submittedCount }}</div>
            <div class="text-[10px] text-amber-700 mt-0.5 font-medium">Submitted (Survey)</div>
        </div>

        <div class="p-4 rounded-2xl bg-blue-50 border border-blue-200 shadow-sm">
            <div class="text-xs font-bold text-blue-800">Menunggu Pelanggan</div>
            <div class="text-2xl font-extrabold text-blue-900 mt-1.5">{{ $verifiedCount }}</div>
            <div class="text-[10px] text-blue-700 mt-0.5 font-medium">Link WA Dibagikan</div>
        </div>

        <div class="p-4 rounded-2xl bg-indigo-50 border border-indigo-200 shadow-sm">
            <div class="text-xs font-bold text-indigo-800">Tahap C-Care</div>
            <div class="text-2xl font-extrabold text-indigo-900 mt-1.5">{{ $filledCount }}</div>
            <div class="text-[10px] text-indigo-700 mt-0.5 font-medium">Filled (Review)</div>
        </div>

        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 shadow-sm">
            <div class="text-xs font-bold text-emerald-800">Approved</div>
            <div class="text-2xl font-extrabold text-emerald-900 mt-1.5">{{ $approvedCount }}</div>
            <div class="text-[10px] text-emerald-700 mt-0.5 font-medium">Closing Pelanggan</div>
        </div>

        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 shadow-sm">
            <div class="text-xs font-bold text-rose-800">Revisi</div>
            <div class="text-2xl font-extrabold text-rose-900 mt-1.5">{{ $revisionCount }}</div>
            <div class="text-[10px] text-rose-700 mt-0.5 font-medium">Perlu Follow Up Sales</div>
        </div>

    </div>

    <!-- SLA Evaluation Metrics Section (Evaluasi Dimana Proses yang Lama) -->
    <div class="rounded-3xl bg-white border border-gray-200 p-6 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-gray-100 pb-3">
            <div>
                <h3 class="text-sm font-extrabold text-[#2C2C2C] flex items-center gap-2">
                    <i class="fa-solid fa-stopwatch text-[#F48C5B]"></i>
                    <span>Analisis SLA &amp; Evaluasi Kecepatan Proses (Bottleneck Tracking)</span>
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
                        {{ round($avgOpjDuration / 3600, 1) }} <span class="text-xs font-normal text-gray-500">Jam rata-rata</span>
                    @else
                        <span class="text-gray-500 text-sm">~ 45 Menit</span>
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
                        {{ round($avgCustFillDuration / 3600, 1) }} <span class="text-xs font-normal text-gray-500">Jam rata-rata</span>
                    @else
                        <span class="text-gray-500 text-sm">~ 3.2 Jam</span>
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
                        {{ round($avgCCareDuration / 3600, 1) }} <span class="text-xs font-normal text-gray-500">Jam rata-rata</span>
                    @else
                        <span class="text-gray-500 text-sm">~ 1.5 Jam</span>
                    @endif
                </div>
                <p class="text-[11px] text-gray-600 leading-relaxed">
                    Waktu tim C-Care memeriksa keabsahan foto KTP &amp; TTD sebelum disetujui (Approved) untuk instalasi.
                </p>
            </div>

        </div>
    </div>

    <!-- Sales Leaderboard Performance Grid -->
    <div class="rounded-3xl bg-white border border-gray-200 p-6 shadow-sm space-y-4">
        <h3 class="text-sm font-extrabold text-[#2C2C2C] flex items-center gap-2">
            <i class="fa-solid fa-trophy text-amber-500"></i>
            <span>Performa Sales Account Manager (AM)</span>
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($salesPerformance as $perf)
                <div class="p-4 rounded-2xl bg-gray-50 border border-gray-200 space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-[#FEF4F0] border border-[#F48C5B]/30 text-[#F48C5B] flex items-center justify-center font-extrabold text-xs">
                                {{ $perf['sales_id'] }}
                            </div>
                            <div>
                                <div class="font-bold text-[#2C2C2C] text-xs">{{ $perf['name'] }}</div>
                                <div class="text-[10px] text-gray-400">{{ $perf['sales_id'] }}</div>
                            </div>
                        </div>
                        <span class="text-xs font-extrabold text-emerald-700">{{ $perf['conversion_rate'] }}% <span class="text-[10px] text-gray-400 font-normal">Conv</span></span>
                    </div>

                    <div class="grid grid-cols-4 gap-1 text-center text-[10px] pt-1 border-t border-gray-200">
                        <div class="p-1.5 rounded bg-white border border-gray-200">
                            <span class="text-gray-400 block">Total</span>
                            <span class="font-bold text-[#2C2C2C]">{{ $perf['total'] }}</span>
                        </div>
                        <div class="p-1.5 rounded bg-emerald-50 text-emerald-800 border border-emerald-200">
                            <span class="block">Approve</span>
                            <span class="font-bold">{{ $perf['approved'] }}</span>
                        </div>
                        <div class="p-1.5 rounded bg-blue-50 text-blue-800 border border-blue-200">
                            <span class="block">Proses</span>
                            <span class="font-bold">{{ $perf['pending'] }}</span>
                        </div>
                        <div class="p-1.5 rounded bg-rose-50 text-rose-800 border border-rose-200">
                            <span class="block">Revisi</span>
                            <span class="font-bold">{{ $perf['revision'] }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Comprehensive Tracking Table -->
    <div class="rounded-2xl bg-white border border-gray-200 shadow-sm overflow-hidden">
        
        <!-- Filter & Search Toolbar -->
        <div class="p-4 sm:p-5 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white">
            <div>
                <h3 class="font-extrabold text-sm text-[#2C2C2C]">Tracking Status Pengajuan Pelanggan</h3>
                <p class="text-xs text-gray-500">Pantau tahapan proses dari survey hingga closing</p>
            </div>

            <!-- Export & Filter Controls -->
            <div class="flex flex-wrap items-center gap-2">
                <form action="{{ route('admin-sales.dashboard') }}" method="GET" class="flex flex-wrap items-center gap-2">
                    
                    <!-- Per Page Selector -->
                    <select name="per_page" onchange="this.form.submit()" 
                            class="px-2.5 py-1.5 rounded-lg bg-gray-50 border border-gray-300 text-xs font-semibold text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#F48C5B] cursor-pointer">
                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 / hal</option>
                        <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25 / hal</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 / hal</option>
                        <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100 / hal</option>
                    </select>

                    <!-- Sales Filter -->
                    <select name="sales_id" onchange="this.form.submit()" 
                            class="px-3 py-1.5 rounded-lg bg-white border border-gray-300 text-xs text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#F48C5B]">
                        <option value="">Semua Sales AM</option>
                        @foreach($salesAgents as $agent)
                            <option value="{{ $agent->id }}" {{ $salesId == $agent->id ? 'selected' : '' }}>
                                {{ $agent->name }} ({{ $agent->sales_id }})
                            </option>
                        @endforeach
                    </select>

                    <!-- Status Filter -->
                    <select name="status" onchange="this.form.submit()"
                            class="px-3 py-1.5 rounded-lg bg-white border border-gray-300 text-xs text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#F48C5B]">
                        <option value="all" {{ $status == 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="submitted" {{ $status == 'submitted' ? 'selected' : '' }}>Submitted (OPJ)</option>
                        <option value="verified" {{ $status == 'verified' ? 'selected' : '' }}>Verified (Link WA)</option>
                        <option value="filled" {{ $status == 'filled' ? 'selected' : '' }}>Filled (C-Care)</option>
                        <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Approved (Closing)</option>
                        <option value="revision" {{ $status == 'revision' ? 'selected' : '' }}>Revision (Perlu Revisi)</option>
                    </select>

                    <!-- Search Input -->
                    <div class="relative">
                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, kode..."
                               class="px-3 py-1.5 rounded-lg bg-white border border-gray-300 text-xs text-[#2C2C2C] placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#F48C5B] w-36 sm:w-44">
                    </div>

                    @if($salesId || ($status && $status !== 'all') || $search || $regency)
                        <a href="{{ route('admin-sales.dashboard', ['per_page' => $perPage]) }}" class="px-2.5 py-1.5 rounded-lg bg-gray-100 text-gray-600 hover:text-[#2C2C2C] text-xs font-semibold" title="Reset Filter">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    @endif

                    <button type="submit" class="px-3.5 py-1.5 rounded-lg bg-[#F48C5B] hover:bg-[#EF666B] text-white text-xs font-bold shadow-xs">
                        Filter
                    </button>
                </form>

                <!-- Export CSV Button -->
                <a href="{{ route('admin-sales.export', ['sales_id' => $salesId, 'status' => $status]) }}" 
                   class="px-3.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold shadow-xs transition-colors inline-flex items-center gap-1.5">
                    <i class="fa-solid fa-file-csv"></i>
                    <span>Export CSV</span>
                </a>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#F8F9FA] text-gray-600 font-bold uppercase tracking-wider border-b border-gray-200">
                    <tr>
                        <th class="px-5 py-3.5">Kode &amp; Pelanggan</th>
                        <th class="px-4 py-3.5">Sales AM</th>
                        <th class="px-4 py-3.5">Wilayah</th>
                        <th class="px-4 py-3.5">Paket &amp; Nilai</th>
                        <th class="px-5 py-3.5">Progress Workflow Pipeline</th>
                        <th class="px-4 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse($registrations as $reg)
                        <tr class="hover:bg-[#FEF4F0]/40 transition-colors">
                            
                            <!-- Customer & Code -->
                            <td class="px-5 py-4">
                                <div class="font-extrabold text-[#2C2C2C] text-sm">{{ $reg->customer_name }}</div>
                                <div class="font-mono font-bold text-[11px] text-[#F48C5B] mt-0.5">{{ $reg->registration_code }}</div>
                                <div class="text-[10px] text-gray-400 mt-0.5">{{ $reg->phone_wa }}</div>
                            </td>

                            <!-- Sales AM -->
                            <td class="px-4 py-4">
                                <div class="font-bold text-[#2C2C2C]">{{ $reg->sales_name }}</div>
                                <span class="text-[10px] text-[#9B385B] font-mono font-bold">{{ $reg->sales_am_id }}</span>
                            </td>

                            <!-- Region -->
                            <td class="px-4 py-4">
                                <div class="font-bold text-gray-800">{{ $reg->village }}</div>
                                <div class="text-[11px] text-gray-500">{{ $reg->regency }}</div>
                            </td>

                            <!-- Package -->
                            <td class="px-4 py-4">
                                @if($reg->package)
                                    <div class="font-extrabold text-[#9B385B]">{{ $reg->package->name }}</div>
                                    <div class="text-[11px] font-bold text-[#F48C5B]">{{ $reg->package->formatted_price }}</div>
                                @else
                                    <span class="text-gray-400 italic">Survey Awal</span>
                                @endif
                            </td>

                            <!-- Visual Workflow Pipeline Progress Bar -->
                            <td class="px-5 py-4">
                                <div class="space-y-1.5 min-w-[280px]">
                                    <!-- 4 Stages Steps Indicator -->
                                    <div class="flex items-center justify-between text-[10px] font-bold">
                                        
                                        <!-- Step 1: Survey -->
                                        <div class="flex items-center gap-1 {{ in_array($reg->status, ['submitted', 'verified', 'filled', 'approved', 'revision']) ? 'text-[#F48C5B]' : 'text-gray-300' }}">
                                            <i class="fa-solid fa-circle-check"></i>
                                            <span>Survey</span>
                                        </div>

                                        <span class="text-gray-300">&rarr;</span>

                                        <!-- Step 2: OPJ Verified -->
                                        <div class="flex items-center gap-1 {{ in_array($reg->status, ['verified', 'filled', 'approved']) ? 'text-blue-600' : ($reg->status === 'submitted' ? 'text-gray-400' : 'text-gray-300') }}">
                                            <i class="fa-solid fa-circle-check"></i>
                                            <span>OPJ</span>
                                        </div>

                                        <span class="text-gray-300">&rarr;</span>

                                        <!-- Step 3: Customer Form -->
                                        <div class="flex items-center gap-1 {{ in_array($reg->status, ['filled', 'approved']) ? 'text-indigo-600' : ($reg->status === 'revision' ? 'text-rose-600' : 'text-gray-300') }}">
                                            <i class="fa-solid fa-circle-check"></i>
                                            <span>Form</span>
                                        </div>

                                        <span class="text-gray-300">&rarr;</span>

                                        <!-- Step 4: C-Care Closing -->
                                        <div class="flex items-center gap-1 {{ $reg->status === 'approved' ? 'text-emerald-600' : 'text-gray-300' }}">
                                            <i class="fa-solid fa-circle-check"></i>
                                            <span>Closing</span>
                                        </div>

                                    </div>

                                    <!-- Status Badge -->
                                    @php $badge = $reg->status_badge; @endphp
                                    <div class="flex items-center justify-between text-[11px]">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full font-bold border {{ $badge['bg'] }}">
                                            {{ $badge['label'] }}
                                        </span>
                                        <span class="text-[10px] text-gray-500">
                                            @if($reg->status === 'submitted') Menunggu verifikasi OPJ
                                            @elseif($reg->status === 'verified') Menunggu link diisi pelanggan
                                            @elseif($reg->status === 'filled') Menunggu review C-Care
                                            @elseif($reg->status === 'approved') Selesai disetujui
                                            @elseif($reg->status === 'revision') Follow up revisi berkas
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <!-- Action -->
                            <td class="px-4 py-4 text-right whitespace-nowrap">
                                <a href="{{ route('admin-sales.show', $reg->id) }}" 
                                   class="px-3 py-1.5 rounded-lg bg-gray-50 hover:bg-gray-100 text-gray-700 text-xs font-bold border border-gray-200 transition-colors inline-flex items-center gap-1">
                                    <i class="fa-solid fa-timeline text-[#F48C5B]"></i>
                                    <span>Log SLA</span>
                                </a>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                <div class="text-sm font-bold text-gray-600">Tidak ada data pendaftaran ditemukan.</div>
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
                            data pendaftaran pelanggan
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
