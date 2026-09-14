<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AuditLog;
use App\Models\CustomerRegistration;
use App\Models\SubscriptionPackage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

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

        $query = AuditLog::with('user')->whereNotIn('action', ['LOGIN', 'LOGOUT']);

        if ($module !== 'all') {
            $query->where('module', $module);
        }
        if ($action !== 'all') {
            $query->where('action', $action);
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('user_name', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhere('action', 'like', "%{$search}%");
            });
        }

        $logs = $query->latest('created_at')->paginate(15)->withQueryString();

        $modules = AuditLog::whereNotIn('action', ['LOGIN', 'LOGOUT'])->select('module')->distinct()->pluck('module');
        $actions = AuditLog::whereNotIn('action', ['LOGIN', 'LOGOUT'])->select('action')->distinct()->pluck('action');

        return view('vas.audit_logs', compact('logs', 'modules', 'actions', 'module', 'action', 'search'));
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
