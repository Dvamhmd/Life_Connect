<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerRegistration;
use App\Models\RegistrationProgressLog;
use App\Models\AuditLog;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class CCareController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'filled');
        $search = trim((string) $request->query('search', ''));
        $perPage = (int) $request->query('per_page', 10);

        if (!in_array($perPage, [10, 25, 50, 100], true)) {
            $perPage = 10;
        }

        // Selective column projection and lightweight package eager loading (excluding unused sales relation)
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

        if ($status === 'filled') {
            $query->where('status', 'filled');
        } elseif ($status === 'revision') {
            $query->where('status', 'revision');
        } elseif ($status === 'approved') {
            $query->where('status', 'approved');
        } elseif ($status === 'all') {
            $query->whereIn('status', ['filled', 'revision', 'approved']);
        } else {
            $query->where('status', 'filled');
            $status = 'filled';
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

        // Fast index-optimized pagination
        $registrations = $query->orderBy('filled_at', 'desc')
            ->orderBy('updated_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        // Single aggregation query for KPI metrics (1 database round-trip vs 4 round-trips)
        $rawStats = CustomerRegistration::query()
            ->selectRaw("
                SUM(CASE WHEN status IN ('filled', 'approved', 'revision') THEN 1 ELSE 0 END) as total,
                SUM(CASE WHEN status = 'filled' THEN 1 ELSE 0 END) as needs_action,
                SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
                SUM(CASE WHEN status = 'revision' THEN 1 ELSE 0 END) as revision
            ")
            ->first();

        $stats = [
            'total' => (int) ($rawStats->total ?? 0),
            'needs_action' => (int) ($rawStats->needs_action ?? 0),
            'approved' => (int) ($rawStats->approved ?? 0),
            'revision' => (int) ($rawStats->revision ?? 0),
        ];

        return view('ccare.index', compact('registrations', 'stats', 'status', 'search', 'perPage'));
    }

    public function show($id)
    {
        $registration = CustomerRegistration::with(['sales', 'package', 'progressLogs'])->findOrFail($id);
        return view('ccare.show', compact('registration'));
    }

    public function approve(Request $request, $id)
    {
        $request->validate([
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $registration = CustomerRegistration::findOrFail($id);

        if ($registration->status !== 'filled') {
            return back()->with('error', 'Hanya pengajuan berstatus "Filled" yang dapat di-approve.');
        }

        $user = Auth::user();
        $oldStatus = $registration->status;
        $now = now();
        $durationSeconds = $registration->filled_at ? max(0, abs((int) round($now->diffInSeconds($registration->filled_at)))) : null;
        $notes = $request->notes ?: 'Data pelanggan, identitas KTP, foto bangunan, tanda tangan virtual, dan paket berlangganan dinyatakan LENGKAP & VALID.';

        $registration->update([
            'status' => 'approved',
            'approved_at' => $now,
            'rejection_notes' => null,
        ]);

        // Progress Log
        RegistrationProgressLog::create([
            'customer_registration_id' => $registration->id,
            'user_id' => $user->id,
            'actor_name' => $user->name,
            'actor_role' => 'C-Care',
            'from_status' => $oldStatus,
            'to_status' => 'approved',
            'notes' => $notes,
            'duration_seconds' => $durationSeconds,
            'created_at' => $now,
        ]);

        // Audit Log
        AuditLog::log(
            action: 'APPROVE_CUSTOMER',
            module: 'CCare',
            description: "C-Care {$user->name} menyetujui (Approved) pendaftaran pelanggan {$registration->customer_name} ({$registration->registration_code}).",
            targetType: 'CustomerRegistration',
            targetId: $registration->id,
            oldValues: ['status' => $oldStatus],
            newValues: ['status' => 'approved', 'notes' => $notes]
        );

        // Notify Sales AM
        Notification::create([
            'user_id' => $registration->sales_user_id,
            'sales_am_id' => $registration->sales_am_id,
            'title' => 'Pengajuan Pelanggan Disetujui (Approved)!',
            'message' => "Selamat! Pengajuan pelanggan {$registration->customer_name} ({$registration->registration_code}) telah disetujui (Approved) oleh C-Care.",
            'type' => 'registration_approved',
            'customer_registration_id' => $registration->id,
            'link' => null,
            'action_type' => 'view_detail',
            'is_read' => false,
            'created_at' => $now,
        ]);

        return redirect()->route('ccare.index')->with('success', "Pengajuan {$registration->customer_name} ({$registration->registration_code}) berhasil DISETUJUI (Approved)! Notifikasi telah diteruskan ke Sales.");
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_notes' => ['required', 'string', 'min:5', 'max:1000'],
            'rejection_category' => ['required', 'string'],
        ]);

        $registration = CustomerRegistration::findOrFail($id);

        if (!in_array($registration->status, ['filled', 'submitted', 'verified'])) {
            return back()->with('error', 'Status pengajuan saat ini tidak dapat diubah ke status Revisi.');
        }

        $user = Auth::user();
        $oldStatus = $registration->status;
        $now = now();
        $durationSeconds = $registration->filled_at ? max(0, abs((int) round($now->diffInSeconds($registration->filled_at)))) : null;

        $registration->update([
            'status' => 'revision',
            'revision_at' => $now,
            'rejection_category' => $request->rejection_category,
            'rejection_notes' => $request->rejection_notes,
        ]);

        // Progress Log
        RegistrationProgressLog::create([
            'customer_registration_id' => $registration->id,
            'user_id' => $user->id,
            'actor_name' => $user->name,
            'actor_role' => 'C-Care',
            'from_status' => $oldStatus,
            'to_status' => 'revision',
            'notes' => "Kategori: {$request->rejection_category}. Catatan C-Care: {$request->rejection_notes}",
            'duration_seconds' => $durationSeconds,
            'created_at' => $now,
        ]);

        // Audit Log
        AuditLog::log(
            action: 'REJECT_CUSTOMER',
            module: 'CCare',
            description: "C-Care {$user->name} meminta revisi pendaftaran {$registration->customer_name} ({$registration->registration_code}) karena: {$request->rejection_notes}",
            targetType: 'CustomerRegistration',
            targetId: $registration->id,
            oldValues: ['status' => $oldStatus],
            newValues: ['status' => 'revision', 'rejection_notes' => $request->rejection_notes]
        );

        // Notify Sales AM to follow up
        Notification::create([
            'user_id' => $registration->sales_user_id,
            'sales_am_id' => $registration->sales_am_id,
            'title' => 'Pengajuan Memerlukan Revisi (Revision)',
            'message' => "Pengajuan {$registration->customer_name} ({$registration->registration_code}) membutuhkan revisi. Catatan C-Care: {$request->rejection_notes}. Mohon segera follow up ke pelanggan.",
            'type' => 'registration_revision',
            'customer_registration_id' => $registration->id,
            'link' => route('customer-form.show', $registration->token),
            'action_type' => 'revise_data',
            'is_read' => false,
            'created_at' => $now,
        ]);

        return redirect()->route('ccare.index')->with('warning', "Pengajuan {$registration->customer_name} telah diubah ke status REVISI. Catatan dan notifikasi telah dikirimkan ke Sales untuk tindak lanjut.");
    }
}
