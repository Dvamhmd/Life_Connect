<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerRegistration;
use App\Models\RegistrationProgressLog;
use App\Models\AuditLog;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class OpjController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');
        $search = $request->query('search');

        $query = CustomerRegistration::with(['sales', 'package']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('registration_code', 'like', "%{$search}%")
                  ->orWhere('phone_wa', 'like', "%{$search}%")
                  ->orWhere('village', 'like', "%{$search}%")
                  ->orWhere('sales_name', 'like', "%{$search}%");
            });
        }

        $registrations = $query->latest('submitted_at')->paginate(10)->withQueryString();

        $stats = [
            'total' => CustomerRegistration::count(),
            'pending_opj' => CustomerRegistration::where('status', 'submitted')->count(),
            'verified' => CustomerRegistration::where('status', 'verified')->count(),
            'filled' => CustomerRegistration::where('status', 'filled')->count(),
            'approved' => CustomerRegistration::where('status', 'approved')->count(),
        ];

        return view('opj.index', compact('registrations', 'stats', 'status', 'search'));
    }

    public function show($id)
    {
        $registration = CustomerRegistration::with(['sales', 'package', 'progressLogs'])->findOrFail($id);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $registration,
                'html' => view('opj.partials.detail_modal', compact('registration'))->render(),
            ]);
        }

        return view('opj.show', compact('registration'));
    }

    public function verify(Request $request, $id)
    {
        $request->validate([
            'notes' => ['nullable', 'string', 'max:500'],
            'odp_reference' => ['nullable', 'string', 'max:100'],
        ]);

        $registration = CustomerRegistration::findOrFail($id);

        if ($registration->status !== 'submitted') {
            return back()->with('error', 'Status pengajuan ini sudah tidak dapat diverifikasi oleh OPJ (Status saat ini: ' . $registration->status . ').');
        }

        $user = Auth::user();
        $oldStatus = $registration->status;
        $now = now();
        $durationSeconds = $registration->submitted_at ? max(0, abs((int) round($now->diffInSeconds($registration->submitted_at)))) : null;

        $odpNote = $request->odp_reference ? " [ODP/FAT: {$request->odp_reference}]" : "";
        $fullNote = ($request->notes ? $request->notes : 'Lokasi terverifikasi oleh OPJ dan masuk ke dalam coverage area LifeMedia.') . $odpNote;

        $registration->update([
            'status' => 'verified',
            'verified_at' => $now,
        ]);

        // Record Progress Log
        RegistrationProgressLog::create([
            'customer_registration_id' => $registration->id,
            'user_id' => $user->id,
            'actor_name' => $user->name,
            'actor_role' => 'OPJ',
            'from_status' => $oldStatus,
            'to_status' => 'verified',
            'notes' => $fullNote,
            'duration_seconds' => $durationSeconds,
            'created_at' => $now,
        ]);

        // Record Audit Log
        AuditLog::log(
            action: 'VERIFY_SURVEY',
            module: 'OPJ',
            description: "OPJ {$user->name} memverifikasi survey {$registration->customer_name} ({$registration->registration_code}).",
            targetType: 'CustomerRegistration',
            targetId: $registration->id,
            oldValues: ['status' => $oldStatus],
            newValues: ['status' => 'verified', 'notes' => $fullNote]
        );

        // Send Notification to Sales AM
        Notification::create([
            'user_id' => $registration->sales_user_id,
            'sales_am_id' => $registration->sales_am_id,
            'title' => 'Survey Terverifikasi OPJ!',
            'message' => "Pengajuan pelanggan {$registration->customer_name} ({$registration->registration_code}) telah diverifikasi OPJ. Segera bagikan link pendaftaran via WhatsApp!",
            'type' => 'survey_verified',
            'customer_registration_id' => $registration->id,
            'link' => route('customer-form.show', $registration->token),
            'action_type' => 'share_whatsapp',
            'is_read' => false,
            'created_at' => $now,
        ]);

        return redirect()->route('opj.index')->with('success', "Survey {$registration->customer_name} ({$registration->registration_code}) berhasil diverifikasi! Notifikasi pembagian link telah dikirimkan ke Sales ({$registration->sales_name}).");
    }
}
