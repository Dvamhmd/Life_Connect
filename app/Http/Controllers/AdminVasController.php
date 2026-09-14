<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AuditLog;
use App\Models\CustomerRegistration;
use App\Models\SubscriptionPackage;
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
}
