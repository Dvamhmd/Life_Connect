<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerRegistration;
use App\Models\RegistrationProgressLog;
use App\Models\User;
use App\Models\SubscriptionPackage;
use Illuminate\Support\Facades\DB;

class AdminSalesController extends Controller
{
    public function index(Request $request)
    {
        $salesId = $request->query('sales_id');
        $status = $request->query('status', 'all');
        $regency = $request->query('regency');
        $search = $request->query('search');

        $query = CustomerRegistration::with(['sales', 'package', 'progressLogs']);

        if ($salesId) {
            $query->where('sales_user_id', $salesId);
        }
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        if ($regency) {
            $query->where('regency', $regency);
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('registration_code', 'like', "%{$search}%")
                  ->orWhere('phone_wa', 'like', "%{$search}%")
                  ->orWhere('sales_name', 'like', "%{$search}%");
            });
        }

        $registrations = $query->latest('submitted_at')->paginate(10)->withQueryString();

        // Summary Counts
        $total = CustomerRegistration::count();
        $submittedCount = CustomerRegistration::where('status', 'submitted')->count();
        $verifiedCount = CustomerRegistration::where('status', 'verified')->count();
        $filledCount = CustomerRegistration::where('status', 'filled')->count();
        $approvedCount = CustomerRegistration::where('status', 'approved')->count();
        $revisionCount = CustomerRegistration::where('status', 'revision')->count();

        // SLA Duration Analysis across stages (average seconds converted to hours/minutes)
        $avgOpjDuration = RegistrationProgressLog::where('to_status', 'verified')
            ->whereNotNull('duration_seconds')
            ->avg('duration_seconds');

        $avgCustFillDuration = RegistrationProgressLog::where('to_status', 'filled')
            ->whereNotNull('duration_seconds')
            ->avg('duration_seconds');

        $avgCCareDuration = RegistrationProgressLog::where('to_status', 'approved')
            ->whereNotNull('duration_seconds')
            ->avg('duration_seconds');

        // Sales Performance List
        $salesAgents = User::where('role', 'sales')->get();
        $salesPerformance = [];
        foreach ($salesAgents as $agent) {
            $agentTotal = CustomerRegistration::where('sales_user_id', $agent->id)->count();
            $agentApproved = CustomerRegistration::where('sales_user_id', $agent->id)->where('status', 'approved')->count();
            $agentPending = CustomerRegistration::where('sales_user_id', $agent->id)->whereIn('status', ['submitted', 'verified', 'filled'])->count();
            $agentRevision = CustomerRegistration::where('sales_user_id', $agent->id)->where('status', 'revision')->count();

            $salesPerformance[] = [
                'id' => $agent->id,
                'name' => $agent->name,
                'sales_id' => $agent->sales_id,
                'total' => $agentTotal,
                'approved' => $agentApproved,
                'pending' => $agentPending,
                'revision' => $agentRevision,
                'conversion_rate' => $agentTotal > 0 ? round(($agentApproved / $agentTotal) * 100, 1) : 0,
            ];
        }

        // Available Filter Options
        $regencies = CustomerRegistration::select('regency')->distinct()->pluck('regency');

        return view('admin_sales.index', compact(
            'registrations',
            'total',
            'submittedCount',
            'verifiedCount',
            'filledCount',
            'approvedCount',
            'revisionCount',
            'avgOpjDuration',
            'avgCustFillDuration',
            'avgCCareDuration',
            'salesAgents',
            'salesPerformance',
            'regencies',
            'salesId',
            'status',
            'regency',
            'search'
        ));
    }

    public function show($id)
    {
        $registration = CustomerRegistration::with(['sales', 'package', 'progressLogs.user'])->findOrFail($id);
        return view('admin_sales.show', compact('registration'));
    }

    public function exportCsv(Request $request)
    {
        $query = CustomerRegistration::with(['sales', 'package']);

        if ($request->filled('sales_id')) {
            $query->where('sales_user_id', $request->sales_id);
        }
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $items = $query->latest('submitted_at')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="life_connect_report_' . date('Ymd_His') . '.csv"',
        ];

        $callback = function () use ($items) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'Kode Registrasi',
                'Nama Pelanggan',
                'No WA',
                'Email',
                'Sales AM ID',
                'Nama Sales',
                'Status',
                'Provinsi',
                'Kabupaten',
                'Kecamatan',
                'Kelurahan',
                'Paket Berlangganan',
                'Harga Paket',
                'Waktu Submit',
                'Waktu Verifikasi OPJ',
                'Waktu Isi Pelanggan',
                'Waktu Approve C-Care',
            ]);

            foreach ($items as $row) {
                fputcsv($file, [
                    $row->registration_code,
                    $row->customer_name,
                    $row->phone_wa,
                    $row->email,
                    $row->sales_am_id,
                    $row->sales_name,
                    $row->status,
                    $row->province,
                    $row->regency,
                    $row->district,
                    $row->village,
                    $row->package ? $row->package->name : '-',
                    $row->package ? $row->package->price : '-',
                    $row->submitted_at?->format('Y-m-d H:i:s') ?? '-',
                    $row->verified_at?->format('Y-m-d H:i:s') ?? '-',
                    $row->filled_at?->format('Y-m-d H:i:s') ?? '-',
                    $row->approved_at?->format('Y-m-d H:i:s') ?? '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
