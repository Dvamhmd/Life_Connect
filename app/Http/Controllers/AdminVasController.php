<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AuditLog;
use App\Models\CustomerRegistration;
use App\Models\SubscriptionPackage;
use App\Models\RegistrationProgressLog;
use App\Models\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class AdminVasController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_registrations' => CustomerRegistration::count(),
            'total_audit_logs' => AuditLog::whereNotIn('action', ['LOGIN', 'LOGOUT'])->count(),
            'total_approved' => CustomerRegistration::where('status', 'approved')->count(),
            'pending_opj' => CustomerRegistration::where('status', 'submitted')->count(),
            'pending_customer' => CustomerRegistration::where('status', 'verified')->count(),
            'pending_ccare' => CustomerRegistration::where('status', 'filled')->count(),
            'active_packages' => SubscriptionPackage::where('is_active', true)->count(),
        ];

        $recentLogs = AuditLog::with('user')->whereNotIn('action', ['LOGIN', 'LOGOUT'])->latest('created_at')->limit(8)->get();
        $recentRegistrations = CustomerRegistration::with(['sales', 'package'])->latest('created_at')->limit(6)->get();

        return view('vas.index', compact('stats', 'recentLogs', 'recentRegistrations'));
    }

    public function dashboard(Request $request)
    {
        $status = $request->query('status', 'all');
        $search = trim((string) $request->query('search', ''));
        $perPage = (int) $request->query('per_page', 10);

        if (!in_array($perPage, [10, 25, 50, 100], true)) {
            $perPage = 10;
        }

        $query = CustomerRegistration::query()
            ->select([
                'id',
                'registration_code',
                'customer_name',
                'nik',
                'phone_wa',
                'email',
                'package_id',
                'billing_method',
                'billing_email',
                'sales_name',
                'sales_am_id',
                'status',
                'submitted_at',
                'filled_at',
                'updated_at',
            ])
            ->with(['package:id,name,price']);

        if ($status === 'submitted') {
            $query->where('status', 'submitted');
        } elseif ($status === 'verified') {
            $query->where('status', 'verified');
        } elseif ($status === 'filled') {
            $query->where('status', 'filled');
        } elseif ($status === 'revision') {
            $query->where('status', 'revision');
        } elseif ($status === 'approved') {
            $query->where('status', 'approved');
        } else {
            $status = 'all';
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('registration_code', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('phone_wa', 'like', "%{$search}%")
                  ->orWhere('sales_name', 'like', "%{$search}%");
            });
        }

        $sortBy = (string) $request->query('sort_by', 'id');
        $sortDir = strtolower((string) $request->query('sort_dir', 'desc'));

        if (!in_array($sortDir, ['asc', 'desc'], true)) {
            $sortDir = 'desc';
        }

        $allowedSorts = [
            'id' => 'id',
            'created_at' => 'created_at',
            'submitted_at' => 'submitted_at',
            'filled_at' => 'filled_at',
            'customer_name' => 'customer_name',
            'registration_code' => 'registration_code',
            'status' => 'status',
            'sales_name' => 'sales_name',
        ];

        $sortColumn = $allowedSorts[$sortBy] ?? 'id';

        if ($sortColumn === 'filled_at') {
            $query->orderBy('filled_at', $sortDir)->orderBy('id', $sortDir);
        } elseif ($sortColumn === 'submitted_at' || $sortColumn === 'created_at') {
            $query->orderBy('submitted_at', $sortDir)->orderBy('id', $sortDir);
        } else {
            $query->orderBy($sortColumn, $sortDir);
            if ($sortColumn !== 'id') {
                $query->orderBy('id', $sortDir);
            }
        }

        $registrations = $query->paginate($perPage)->withQueryString();

        $rawStats = CustomerRegistration::query()
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status = 'submitted' THEN 1 ELSE 0 END) as submitted,
                SUM(CASE WHEN status = 'verified' THEN 1 ELSE 0 END) as verified,
                SUM(CASE WHEN status = 'filled' THEN 1 ELSE 0 END) as filled,
                SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
                SUM(CASE WHEN status = 'revision' THEN 1 ELSE 0 END) as revision
            ")
            ->first();

        $stats = [
            'total' => (int) ($rawStats->total ?? 0),
            'submitted' => (int) ($rawStats->submitted ?? 0),
            'verified' => (int) ($rawStats->verified ?? 0),
            'filled' => (int) ($rawStats->filled ?? 0),
            'needs_action' => (int) ($rawStats->filled ?? 0),
            'approved' => (int) ($rawStats->approved ?? 0),
            'revision' => (int) ($rawStats->revision ?? 0),
        ];

        // SLA Duration Analysis (Bottleneck Tracking)
        $slaStats = RegistrationProgressLog::query()
            ->whereNotNull('duration_seconds')
            ->whereIn('to_status', ['verified', 'filled', 'approved'])
            ->selectRaw("
                AVG(CASE WHEN to_status = 'verified' THEN duration_seconds END) as avg_opj,
                AVG(CASE WHEN to_status = 'filled' THEN duration_seconds END) as avg_cust_fill,
                AVG(CASE WHEN to_status = 'approved' THEN duration_seconds END) as avg_ccare
            ")
            ->first();

        $avgOpjDuration = $slaStats->avg_opj ? (float) $slaStats->avg_opj : null;
        $avgCustFillDuration = $slaStats->avg_cust_fill ? (float) $slaStats->avg_cust_fill : null;
        $avgCCareDuration = $slaStats->avg_ccare ? (float) $slaStats->avg_ccare : null;

        return view('vas.dashboard', compact('registrations', 'stats', 'status', 'search', 'perPage', 'sortBy', 'sortDir', 'avgOpjDuration', 'avgCustFillDuration', 'avgCCareDuration'));
    }

    public function ccareDashboard(Request $request)
    {
        return $this->dashboard($request);
    }

    public function auditLogs(Request $request)
    {
        $module = $request->query('module', 'all');
        $action = $request->query('action', 'all');
        $search = $request->query('search');
        $perPage = (int) $request->query('per_page', 15);
        if (!in_array($perPage, [10, 15, 25, 50, 100], true)) {
            $perPage = 15;
        }

        $query = AuditLog::query()
            ->select([
                'id',
                'user_name',
                'user_role',
                'action',
                'module',
                'target_type',
                'target_id',
                'description',
                'old_values',
                'new_values',
                'ip_address',
                'user_agent',
                'created_at',
            ])
            ->whereNotIn('action', ['LOGIN', 'LOGOUT']);

        if ($module !== 'all' && filled($module)) {
            $query->where('module', $module);
        }
        if ($action !== 'all' && filled($action)) {
            $query->where('action', $action);
        }
        if (filled($search)) {
            $searchTerm = trim($search);
            $query->where(function ($q) use ($searchTerm) {
                $q->where('description', 'like', "%{$searchTerm}%")
                  ->orWhere('user_name', 'like', "%{$searchTerm}%")
                  ->orWhere('ip_address', 'like', "%{$searchTerm}%")
                  ->orWhere('action', 'like', "%{$searchTerm}%")
                  ->orWhere('module', 'like', "%{$searchTerm}%");
            });
        }

        // Fast index-optimized ordering and pagination
        $logs = $query->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        // Cache distinct modules and actions to eliminate full table scans on every pagination request
        $modules = Cache::remember('audit_logs_distinct_modules_v2', 300, function () {
            return AuditLog::whereNotIn('action', ['LOGIN', 'LOGOUT'])
                ->whereNotNull('module')
                ->distinct()
                ->pluck('module')
                ->map(fn($item) => is_array($item) ? ($item['module'] ?? reset($item)) : (is_object($item) ? ($item->module ?? '') : (string) $item))
                ->filter()
                ->unique()
                ->values();
        });

        $actions = Cache::remember('audit_logs_distinct_actions_v2', 300, function () {
            return AuditLog::whereNotIn('action', ['LOGIN', 'LOGOUT'])
                ->whereNotNull('action')
                ->distinct()
                ->pluck('action')
                ->map(fn($item) => is_array($item) ? ($item['action'] ?? reset($item)) : (is_object($item) ? ($item->action ?? '') : (string) $item))
                ->filter()
                ->unique()
                ->values();
        });

        return view('vas.audit_logs', compact('logs', 'modules', 'actions', 'module', 'action', 'search', 'perPage'));
    }

    public function users(Request $request)
    {
        $role = $request->query('role', 'all');
        $search = $request->query('search');

        $query = User::query();

        if ($role !== 'all') {
            $query->where('role', $role);
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('sales_id', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        return view('vas.users', compact('users', 'role', 'search'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'unique:users,email'],
            'role' => ['required', 'in:admin_vas,admin_sales,opj,c_care,sales'],
            'sales_id' => ['nullable', 'string', 'max:50'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:6'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'sales_id' => $request->role === 'sales' ? ($request->sales_id ?: 'AM-' . rand(100, 999)) : null,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'status' => $request->status,
        ]);

        AuditLog::log(
            action: 'CREATE_USER',
            module: 'UserManagement',
            description: "Admin VAS " . Auth::user()->name . " membuat akun pengguna baru: {$user->name} ({$user->role}).",
            targetType: 'User',
            targetId: $user->id,
            newValues: ['name' => $user->name, 'email' => $user->email, 'role' => $user->role]
        );

        return redirect()->route('vas.users')->with('success', "Pengguna {$user->name} berhasil ditambahkan.");
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'role' => ['required', 'in:admin_vas,admin_sales,opj,c_care,sales'],
            'sales_id' => ['nullable', 'string', 'max:50'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'string', 'min:6'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $oldValues = $user->only(['name', 'email', 'role', 'status', 'sales_id', 'phone']);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'sales_id' => $request->role === 'sales' ? ($request->sales_id ?: $user->sales_id ?: 'AM-' . rand(100, 999)) : null,
            'phone' => $request->phone,
            'status' => $request->status,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        AuditLog::log(
            action: 'UPDATE_USER',
            module: 'UserManagement',
            description: "Admin VAS " . Auth::user()->name . " memperbarui data pengguna: {$user->name}.",
            targetType: 'User',
            targetId: $user->id,
            oldValues: $oldValues,
            newValues: $user->only(['name', 'email', 'role', 'status', 'sales_id', 'phone'])
        );

        return redirect()->route('vas.users')->with('success', "Data pengguna {$user->name} berhasil diperbarui.");
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $userName = $user->name;
        $userRole = $user->role;
        $userId = $user->id;

        $user->delete();

        AuditLog::log(
            action: 'DELETE_USER',
            module: 'UserManagement',
            description: "Admin VAS " . Auth::user()->name . " menghapus akun pengguna: {$userName} ({$userRole}).",
            targetType: 'User',
            targetId: $userId
        );

        return redirect()->route('vas.users')->with('success', "Pengguna {$userName} berhasil dihapus.");
    }

    public function updateRegistrationStatus(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin_vas') {
            abort(403, 'Akses Ditolak: Hanya Admin VAS yang memiliki wewenang untuk mengubah status pengajuan.');
        }

        $request->validate([
            'status' => ['required', 'in:submitted,verified,filled,approved,revision'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'rejection_category' => ['nullable', 'string', 'max:100'],
        ]);

        $registration = CustomerRegistration::findOrFail($id);
        $user = Auth::user();
        $oldStatus = $registration->status;
        $newStatus = $request->status;
        $now = now();

        $updateData = [
            'status' => $newStatus,
        ];

        if ($newStatus === 'submitted') {
            if (!$registration->submitted_at) {
                $updateData['submitted_at'] = $now;
            }
            $updateData['rejection_notes'] = null;
            $updateData['rejection_category'] = null;
        } elseif ($newStatus === 'verified') {
            $updateData['verified_at'] = $now;
            $updateData['rejection_notes'] = null;
            $updateData['rejection_category'] = null;
        } elseif ($newStatus === 'filled') {
            $updateData['filled_at'] = $now;
            $updateData['rejection_notes'] = null;
            $updateData['rejection_category'] = null;
        } elseif ($newStatus === 'approved') {
            $updateData['approved_at'] = $now;
            $updateData['rejection_notes'] = null;
            $updateData['rejection_category'] = null;
        } elseif ($newStatus === 'revision') {
            $updateData['revision_at'] = $now;
            $updateData['rejection_category'] = $request->rejection_category ?: 'Perubahan Status Admin VAS';
            $updateData['rejection_notes'] = $request->notes ?: 'Status diubah ke Revisi oleh Admin VAS';
        }

        $registration->update($updateData);

        // Progress Log
        $notes = $request->notes ?: ("Perubahan status langsung oleh Super Admin VAS dari " . strtoupper($oldStatus) . " menjadi " . strtoupper($newStatus));
        if ($newStatus === 'revision' && $request->rejection_category) {
            $notes = "Kategori: {$request->rejection_category}. " . $notes;
        }

        RegistrationProgressLog::create([
            'customer_registration_id' => $registration->id,
            'user_id' => $user->id,
            'actor_name' => $user->name,
            'actor_role' => 'Admin VAS',
            'from_status' => $oldStatus,
            'to_status' => $newStatus,
            'notes' => $notes,
            'duration_seconds' => null,
            'created_at' => $now,
        ]);

        // Audit Log
        AuditLog::log(
            action: 'UPDATE_REGISTRATION_STATUS',
            module: 'AdminVAS',
            description: "Admin VAS {$user->name} mengubah status pendaftaran {$registration->customer_name} ({$registration->registration_code}) dari '{$oldStatus}' menjadi '{$newStatus}'." . ($request->notes ? " Catatan: {$request->notes}" : ""),
            targetType: 'CustomerRegistration',
            targetId: $registration->id,
            oldValues: ['status' => $oldStatus],
            newValues: ['status' => $newStatus, 'notes' => $request->notes]
        );

        // Notify Sales AM if status is verified, approved, or revision
        $salesUserId = $registration->sales_user_id;
        $salesAmId = $registration->sales_am_id;
        if (!$salesUserId && $salesAmId) {
            $salesUser = User::where('sales_id', $salesAmId)->first();
            $salesUserId = $salesUser?->id;
        }

        if ($newStatus === 'verified') {
            Notification::create([
                'user_id' => $salesUserId,
                'sales_am_id' => $salesAmId,
                'title' => 'Status Pengajuan Diubah: Terverifikasi OPJ',
                'message' => "Pengajuan pelanggan {$registration->customer_name} ({$registration->registration_code}) telah diubah statusnya menjadi Terverifikasi oleh Admin VAS. Segera bagikan link pendaftaran ke pelanggan!",
                'type' => 'survey_verified',
                'customer_registration_id' => $registration->id,
                'link' => route('customer-form.show', $registration->token),
                'action_type' => 'share_whatsapp',
                'is_read' => false,
                'created_at' => $now,
            ]);
        } elseif ($newStatus === 'approved') {
            Notification::create([
                'user_id' => $salesUserId,
                'sales_am_id' => $salesAmId,
                'title' => 'Pengajuan Disetujui (Approved) oleh Admin VAS!',
                'message' => "Pengajuan pelanggan {$registration->customer_name} ({$registration->registration_code}) telah disetujui (Approved) oleh Admin VAS.",
                'type' => 'registration_approved',
                'customer_registration_id' => $registration->id,
                'link' => null,
                'action_type' => 'view_detail',
                'is_read' => false,
                'created_at' => $now,
            ]);
        } elseif ($newStatus === 'revision') {
            Notification::create([
                'user_id' => $salesUserId,
                'sales_am_id' => $salesAmId,
                'title' => 'Status Pengajuan Diubah: Revisi (Revision)',
                'message' => "Pengajuan {$registration->customer_name} ({$registration->registration_code}) diubah ke status Revisi oleh Admin VAS. " . ($request->notes ? "Catatan: {$request->notes}" : "Mohon periksa kembali data pelanggan."),
                'type' => 'registration_revision',
                'customer_registration_id' => $registration->id,
                'link' => route('customer-form.show', $registration->token),
                'action_type' => 'revise_data',
                'is_read' => false,
                'created_at' => $now,
            ]);
        }

        return back()->with('success', "Status pendaftaran {$registration->customer_name} ({$registration->registration_code}) berhasil diubah menjadi " . strtoupper($newStatus) . ".");
    }
}
