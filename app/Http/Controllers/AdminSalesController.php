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
        $search = trim((string) $request->query('search', ''));
        $perPage = (int) $request->query('per_page', 10);

        if (!in_array($perPage, [10, 25, 50, 100], true)) {
            $perPage = 10;
        }

        // Selective column projection & lightweight package eager load (no progressLogs or sales hydration needed)
        $query = CustomerRegistration::query()
            ->select([
                'id',
                'registration_code',
                'sales_user_id',
                'sales_am_id',
                'sales_name',
                'customer_name',
                'phone_wa',
                'village',
                'regency',
                'package_id',
                'status',
                'submitted_at',
            ])
            ->with(['package:id,name,price']);

        if ($salesId) {
            $query->where('sales_user_id', $salesId);
        }
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        if ($regency) {
            $query->where('regency', $regency);
        }
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('registration_code', 'like', "%{$search}%")
                  ->orWhere('phone_wa', 'like', "%{$search}%")
                  ->orWhere('sales_name', 'like', "%{$search}%");
            });
        }

        // Fast index-optimized pagination
        $registrations = $query->orderBy('submitted_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        // 1. Single aggregation query for Top Summary KPI Counts (1 round-trip vs 6 round-trips)
        $rawSummary = CustomerRegistration::query()
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status = 'submitted' THEN 1 ELSE 0 END) as submitted_count,
                SUM(CASE WHEN status = 'verified' THEN 1 ELSE 0 END) as verified_count,
                SUM(CASE WHEN status = 'filled' THEN 1 ELSE 0 END) as filled_count,
                SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved_count,
                SUM(CASE WHEN status = 'revision' THEN 1 ELSE 0 END) as revision_count
            ")
            ->first();

        $total = (int) ($rawSummary->total ?? 0);
        $submittedCount = (int) ($rawSummary->submitted_count ?? 0);
        $verifiedCount = (int) ($rawSummary->verified_count ?? 0);
        $filledCount = (int) ($rawSummary->filled_count ?? 0);
        $approvedCount = (int) ($rawSummary->approved_count ?? 0);
        $revisionCount = (int) ($rawSummary->revision_count ?? 0);

        // 2. Single aggregation query for SLA Duration Analysis (1 round-trip vs 3 round-trips)
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

        // 3. Ultra-fast Sales Performance aggregation (1 query with GROUP BY vs 4*N queries in foreach)
        $salesAgents = User::where('role', 'sales')->select(['id', 'name', 'sales_id'])->get();

        $salesMetrics = CustomerRegistration::query()
            ->whereNotNull('sales_user_id')
            ->selectRaw("
                sales_user_id,
                COUNT(*) as total,
                SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
                SUM(CASE WHEN status IN ('submitted', 'verified', 'filled') THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'revision' THEN 1 ELSE 0 END) as revision
            ")
            ->groupBy('sales_user_id')
            ->get()
            ->keyBy('sales_user_id');

        $salesPerformance = [];
        foreach ($salesAgents as $agent) {
            $metric = $salesMetrics->get($agent->id);
            $agentTotal = (int) ($metric->total ?? 0);
            $agentApproved = (int) ($metric->approved ?? 0);
            $agentPending = (int) ($metric->pending ?? 0);
            $agentRevision = (int) ($metric->revision ?? 0);

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
        $regencies = CustomerRegistration::query()
            ->whereNotNull('regency')
            ->where('regency', '!=', '')
            ->distinct()
            ->pluck('regency');

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
            'search',
            'perPage'
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
