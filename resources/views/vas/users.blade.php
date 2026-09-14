@extends('layouts.app')

@section('title', 'Manajemen Pengguna - Admin VAS')
@section('header_title', 'Kelola Akun Pengguna & Hak Akses (Role)')
@section('header_subtitle', 'Tambah, perbarui, dan atur hak akses pengguna untuk Admin VAS, Admin Sales, OPJ, C-Care, dan Sales AM')

@section('content')
<div class="space-y-6">
    
    <!-- Header Controls & Add User Button -->
    <div class="rounded-2xl bg-white border border-gray-200 shadow-sm overflow-hidden">
        
        <div class="p-4 sm:p-5 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white">
            <div>
                <h3 class="font-extrabold text-sm text-[#2C2C2C]">Daftar Akun Pengguna</h3>
                <p class="text-xs text-gray-500">Total {{ $users->total() }} akun terdaftar dalam sistem Life Connect</p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <!-- Role Filter Form -->
                <form action="{{ route('vas.users') }}" method="GET" class="flex items-center gap-2">
                    <select name="role" onchange="this.form.submit()"
                            class="px-3 py-1.5 rounded-lg bg-white border border-gray-300 text-xs text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#9B385B]">
                        <option value="all">Semua Role</option>
                        <option value="admin_vas" {{ $role == 'admin_vas' ? 'selected' : '' }}>Admin VAS</option>
                        <option value="admin_sales" {{ $role == 'admin_sales' ? 'selected' : '' }}>Admin Sales</option>
                        <option value="opj" {{ $role == 'opj' ? 'selected' : '' }}>OPJ (Field Ops)</option>
                        <option value="c_care" {{ $role == 'c_care' ? 'selected' : '' }}>Customer Care</option>
                        <option value="sales" {{ $role == 'sales' ? 'selected' : '' }}>Sales AM</option>
                    </select>

                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, email..."
                           class="w-36 px-3 py-1.5 rounded-lg bg-white border border-gray-300 text-xs text-[#2C2C2C] placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9B385B]">

                    <button type="submit" class="px-3.5 py-1.5 rounded-lg bg-[#9B385B] hover:bg-[#742440] text-white text-xs font-bold shadow-xs">
                        Filter
                    </button>
                </form>

                <!-- Add User Modal Trigger -->
                <button type="button" onclick="openAddUserModal()"
                        class="px-3.5 py-1.5 rounded-lg bg-gradient-to-r from-[#F48C5B] via-[#EF666B] to-[#9B385B] hover:from-[#EF666B] hover:to-[#F48C5B] text-white text-xs font-bold shadow-md shadow-orange-500/20 transition-all inline-flex items-center gap-1.5">
                    <i class="fa-solid fa-user-plus"></i>
                    <span>Tambah Pengguna Baru</span>
                </button>
            </div>
        </div>

        <!-- Users Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#F8F9FA] text-gray-600 font-bold uppercase tracking-wider border-b border-gray-200">
                    <tr>
                        <th class="px-5 py-3.5">Nama &amp; Email</th>
                        <th class="px-4 py-3.5">Role / Jabatan</th>
                        <th class="px-4 py-3.5">ID Sales (AM)</th>
                        <th class="px-4 py-3.5">No. Telepon</th>
                        <th class="px-4 py-3.5">Status</th>
                        <th class="px-4 py-3.5">Dibuat</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse($users as $u)
                        <tr class="hover:bg-[#FEF4F0]/40 transition-colors">
                            
                            <!-- Name & Email -->
                            <td class="px-5 py-4">
                                <div class="font-extrabold text-[#2C2C2C] text-sm flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-lg bg-[#FEF4F0] text-[#9B385B] flex items-center justify-center font-bold text-xs border border-[#F48C5B]/30">
                                        {{ strtoupper(substr($u->name, 0, 1)) }}
                                    </div>
                                    <span>{{ $u->name }}</span>
                                </div>
                                <div class="text-[11px] text-gray-400 mt-0.5 ml-9">{{ $u->email }}</div>
                            </td>

                            <!-- Role Badge -->
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold 
                                    @if($u->role === 'admin_vas') bg-[#9B385B]/10 text-[#9B385B] border border-[#9B385B]/30
                                    @elseif($u->role === 'admin_sales') bg-blue-50 text-blue-800 border border-blue-200
                                    @elseif($u->role === 'opj') bg-amber-50 text-amber-800 border border-amber-200
                                    @elseif($u->role === 'c_care') bg-emerald-50 text-emerald-800 border border-emerald-200
                                    @else bg-[#FEF4F0] text-[#F48C5B] border border-[#F48C5B]/30 @endif">
                                    {{ $u->role_badge }}
                                </span>
                            </td>

                            <!-- Sales ID -->
                            <td class="px-4 py-4 font-mono font-bold text-[11px] text-[#9B385B]">
                                {{ $u->sales_id ?: '-' }}
                            </td>

                            <!-- Phone -->
                            <td class="px-4 py-4 text-gray-700 font-medium">
                                {{ $u->phone ?: '-' }}
                            </td>

                            <!-- Status -->
                            <td class="px-4 py-4">
                                @if($u->status === 'active')
                                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-gray-400">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Nonaktif
                                    </span>
                                @endif
                            </td>

                            <!-- Created -->
                            <td class="px-4 py-4 text-[11px] text-gray-400 font-mono">
                                {{ $u->created_at->format('d M Y') }}
                            </td>

                            <!-- Actions -->
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    
                                    <!-- Edit Button -->
                                    <button type="button" 
                                            onclick="openEditUserModal({{ json_encode($u) }})"
                                            class="p-1.5 rounded-lg bg-gray-50 hover:bg-gray-100 text-gray-700 hover:text-[#2C2C2C] border border-gray-200 transition-colors"
                                            title="Edit Pengguna">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>

                                    <!-- Delete Button -->
                                    @if($u->id !== Auth::id())
                                        <form action="{{ route('vas.users.delete', $u->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $u->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-700 transition-colors" title="Hapus Pengguna">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                <div class="text-sm font-bold text-gray-600">Tidak ada data pengguna ditemukan.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-4 border-t border-gray-100 bg-[#F8F9FA]">
                {{ $users->links() }}
            </div>
        @endif

    </div>

</div>

<!-- Modal Tambah User -->
<div id="addUserModal" class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-xs overflow-y-auto p-4 flex items-center justify-center">
    <div class="relative w-full max-w-lg bg-white border border-gray-200 rounded-3xl shadow-2xl overflow-hidden my-8">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-[#F8F9FA]">
            <div class="flex items-center gap-2 font-extrabold text-sm text-[#9B385B]">
                <i class="fa-solid fa-user-plus"></i>
                <span>Tambah Akun Pengguna Baru</span>
            </div>
            <button type="button" onclick="closeAddUserModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="{{ route('vas.users.store') }}" method="POST">
            @csrf
            <div class="p-6 space-y-4 text-xs">
                
                <div>
                    <label class="block font-bold text-[#333333] mb-1">Nama Lengkap</label>
                    <input type="text" name="name" required placeholder="Nama lengkap staf..."
                           class="w-full px-3.5 py-2 rounded-xl bg-white border border-gray-300 text-xs text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#9B385B]">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-[#333333] mb-1">Alamat Email (Login)</label>
                        <input type="email" name="email" required placeholder="user@lifemedia.id"
                               class="w-full px-3.5 py-2 rounded-xl bg-white border border-gray-300 text-xs text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#9B385B]">
                    </div>

                    <div>
                        <label class="block font-bold text-[#333333] mb-1">No. WhatsApp / HP</label>
                        <input type="tel" name="phone" placeholder="081234567890"
                               class="w-full px-3.5 py-2 rounded-xl bg-white border border-gray-300 text-xs text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#9B385B]">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-[#333333] mb-1">Hak Akses (Role)</label>
                        <select name="role" id="addRoleSelect" required onchange="toggleAddSalesId(this.value)"
                                class="w-full px-3.5 py-2 rounded-xl bg-white border border-gray-300 text-xs text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#9B385B]">
                            <option value="sales">Sales (AM)</option>
                            <option value="opj">OPJ (Field Ops)</option>
                            <option value="c_care">Customer Care (C-Care)</option>
                            <option value="admin_sales">Admin Sales</option>
                            <option value="admin_vas">Admin VAS (Super Admin)</option>
                        </select>
                    </div>

                    <div id="addSalesIdBox">
                        <label class="block font-bold text-[#333333] mb-1">ID Sales (Account Manager)</label>
                        <input type="text" name="sales_id" placeholder="Contoh: AM-103"
                               class="w-full px-3.5 py-2 rounded-xl bg-white border border-gray-300 text-xs text-[#2C2C2C] font-mono focus:outline-none focus:ring-2 focus:ring-[#9B385B]">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-[#333333] mb-1">Kata Sandi</label>
                        <input type="password" name="password" required minlength="6" placeholder="Minimal 6 karakter"
                               class="w-full px-3.5 py-2 rounded-xl bg-white border border-gray-300 text-xs text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#9B385B]">
                    </div>

                    <div>
                        <label class="block font-bold text-[#333333] mb-1">Status Akun</label>
                        <select name="status" required
                                class="w-full px-3.5 py-2 rounded-xl bg-white border border-gray-300 text-xs text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#9B385B]">
                            <option value="active">Aktif</option>
                            <option value="inactive">Nonaktif</option>
                        </select>
                    </div>
                </div>

            </div>

            <div class="px-6 py-4 border-t border-gray-100 bg-[#F8F9FA] flex justify-end gap-3">
                <button type="button" onclick="closeAddUserModal()" class="px-4 py-2 rounded-xl bg-white hover:bg-gray-100 text-gray-700 text-xs font-bold border border-gray-200">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-gradient-to-r from-[#F48C5B] via-[#EF666B] to-[#9B385B] hover:from-[#EF666B] hover:to-[#F48C5B] text-white text-xs font-bold shadow-md shadow-orange-500/20">
                    Simpan Pengguna
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit User -->
<div id="editUserModal" class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-xs overflow-y-auto p-4 flex items-center justify-center">
    <div class="relative w-full max-w-lg bg-white border border-gray-200 rounded-3xl shadow-2xl overflow-hidden my-8">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-[#F8F9FA]">
            <div class="flex items-center gap-2 font-extrabold text-sm text-[#9B385B]">
                <i class="fa-solid fa-pen-to-square"></i>
                <span>Perbarui Data Akun Pengguna</span>
            </div>
            <button type="button" onclick="closeEditUserModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="editUserForm" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="p-6 space-y-4 text-xs">
                
                <div>
                    <label class="block font-bold text-[#333333] mb-1">Nama Lengkap</label>
                    <input type="text" name="name" id="editName" required
                           class="w-full px-3.5 py-2 rounded-xl bg-white border border-gray-300 text-xs text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#9B385B]">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-[#333333] mb-1">Alamat Email (Login)</label>
                        <input type="email" name="email" id="editEmail" required
                               class="w-full px-3.5 py-2 rounded-xl bg-white border border-gray-300 text-xs text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#9B385B]">
                    </div>

                    <div>
                        <label class="block font-bold text-[#333333] mb-1">No. WhatsApp / HP</label>
                        <input type="tel" name="phone" id="editPhone"
                               class="w-full px-3.5 py-2 rounded-xl bg-white border border-gray-300 text-xs text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#9B385B]">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-[#333333] mb-1">Hak Akses (Role)</label>
                        <select name="role" id="editRole" required onchange="toggleEditSalesId(this.value)"
                                class="w-full px-3.5 py-2 rounded-xl bg-white border border-gray-300 text-xs text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#9B385B]">
                            <option value="sales">Sales (AM)</option>
                            <option value="opj">OPJ (Field Ops)</option>
                            <option value="c_care">Customer Care (C-Care)</option>
                            <option value="admin_sales">Admin Sales</option>
                            <option value="admin_vas">Admin VAS (Super Admin)</option>
                        </select>
                    </div>

                    <div id="editSalesIdBox">
                        <label class="block font-bold text-[#333333] mb-1">ID Sales (Account Manager)</label>
                        <input type="text" name="sales_id" id="editSalesId"
                               class="w-full px-3.5 py-2 rounded-xl bg-white border border-gray-300 text-xs text-[#2C2C2C] font-mono focus:outline-none focus:ring-2 focus:ring-[#9B385B]">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-[#333333] mb-1">Ganti Password (Opsional)</label>
                        <input type="password" name="password" minlength="6" placeholder="Biarkan kosong jika tetap"
                               class="w-full px-3.5 py-2 rounded-xl bg-white border border-gray-300 text-xs text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#9B385B]">
                    </div>

                    <div>
                        <label class="block font-bold text-[#333333] mb-1">Status Akun</label>
                        <select name="status" id="editStatus" required
                                class="w-full px-3.5 py-2 rounded-xl bg-white border border-gray-300 text-xs text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#9B385B]">
                            <option value="active">Aktif</option>
                            <option value="inactive">Nonaktif</option>
                        </select>
                    </div>
                </div>

            </div>

            <div class="px-6 py-4 border-t border-gray-100 bg-[#F8F9FA] flex justify-end gap-3">
                <button type="button" onclick="closeEditUserModal()" class="px-4 py-2 rounded-xl bg-white hover:bg-gray-100 text-gray-700 text-xs font-bold border border-gray-200">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-gradient-to-r from-[#F48C5B] via-[#EF666B] to-[#9B385B] hover:from-[#EF666B] hover:to-[#F48C5B] text-white text-xs font-bold shadow-md shadow-orange-500/20">
                    Perbarui Pengguna
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openAddUserModal() {
        document.getElementById('addUserModal').classList.remove('hidden');
    }
    function closeAddUserModal() {
        document.getElementById('addUserModal').classList.add('hidden');
    }

    function toggleAddSalesId(role) {
        document.getElementById('addSalesIdBox').style.display = (role === 'sales') ? 'block' : 'none';
    }

    function openEditUserModal(user) {
        document.getElementById('editUserForm').action = '/vas/users/' + user.id;
        document.getElementById('editName').value = user.name;
        document.getElementById('editEmail').value = user.email;
        document.getElementById('editPhone').value = user.phone || '';
        document.getElementById('editRole').value = user.role;
        document.getElementById('editSalesId').value = user.sales_id || '';
        document.getElementById('editStatus').value = user.status;
        toggleEditSalesId(user.role);
        document.getElementById('editUserModal').classList.remove('hidden');
    }
    function closeEditUserModal() {
        document.getElementById('editUserModal').classList.add('hidden');
    }

    function toggleEditSalesId(role) {
        document.getElementById('editSalesIdBox').style.display = (role === 'sales') ? 'block' : 'none';
    }
</script>
@endpush
@endsection
