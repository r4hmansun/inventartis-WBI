<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetHistory;
use App\Models\Department;
use App\Models\MutationForm;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the main dashboard.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Core asset metrics
        $totalAssets = Asset::count();
        $activeAssets = Asset::active()->count();
        $inStorageAssets = Asset::inStorage()->count();
        $underRepairAssets = Asset::where('status', 'under_repair')->count();
        $disposedAssets = Asset::where('status', 'disposed')->count();
        $totalValuation = (float) Asset::sum('purchase_price');

        // Mutation metrics
        $pendingMutations = MutationForm::whereIn('status', ['draft', 'waiting_receiver', 'ready_for_execution'])->count();
        $waitingReceiverMutations = MutationForm::where('status', 'waiting_receiver')->count();
        $readyExecutionMutations = MutationForm::where('status', 'ready_for_execution')->count();
        $archivedMutations = MutationForm::where('status', 'archived')->count();

        // Department allocation breakdown
        $departments = Department::withCount('assets')
            ->where('is_active', true)
            ->orderBy('assets_count', 'desc')
            ->orderBy('name', 'asc')
            ->get();

        $stats = [
            'total_assets' => $totalAssets,
            'total_valuation' => $totalValuation,
            'active_assets' => $activeAssets,
            'in_storage' => $inStorageAssets,
            'under_repair' => $underRepairAssets,
            'disposed' => $disposedAssets,
            'pending_mutations' => $pendingMutations,
            'waiting_receiver' => $waitingReceiverMutations,
            'ready_execution' => $readyExecutionMutations,
            'archived_mutations' => $archivedMutations,
            'total_departments' => $departments->count(),
        ];

        // Recent assets
        $recentAssets = Asset::with(['currentDepartment', 'creator'])
            ->latest()
            ->take(6)
            ->get();

        // Recent mutations
        $recentMutations = MutationForm::with(['fromDepartment', 'toDepartment', 'sender', 'receiver'])
            ->latest()
            ->take(6)
            ->get();

        // Recent audit trail (FR-LOG-01)
        $recentAuditLogs = AssetHistory::with(['asset', 'fromDepartment', 'toDepartment', 'actor'])
            ->latest()
            ->take(6)
            ->get();

        // Department-specific metrics for regular users / staff
        $userDepartment = $user->department;
        $deptTotalAssets = 0;
        $deptActiveAssets = 0;
        $deptRepairAssets = 0;
        $deptValuation = 0.0;
        $deptAssets = collect();
        $deptMutations = collect();

        if ($user->department_id) {
            $deptTotalAssets = Asset::where('current_department_id', $user->department_id)->count();
            $deptActiveAssets = Asset::where('current_department_id', $user->department_id)->where('status', 'active')->count();
            $deptRepairAssets = Asset::where('current_department_id', $user->department_id)->where('status', 'under_repair')->count();
            $deptValuation = (float) Asset::where('current_department_id', $user->department_id)->sum('purchase_price');
            $deptAssets = Asset::with(['creator'])
                ->where('current_department_id', $user->department_id)
                ->latest()
                ->take(10)
                ->get();

            $deptMutations = MutationForm::with(['fromDepartment', 'toDepartment', 'sender', 'receiver'])
                ->where(function ($q) use ($user) {
                    $q->where('from_department_id', $user->department_id)
                      ->orWhere('to_department_id', $user->department_id);
                })
                ->latest()
                ->take(5)
                ->get();
        }

        // 5 Executive & Operational Charts Data
        // 1. Chart: Distribusi Status Aset (Doughnut)
        $chartStatus = [
            'labels' => ['Aktif Operasional', 'Gudang Inventaris', 'Dalam Perbaikan', 'Dihapuskan'],
            'data' => [$activeAssets, $inStorageAssets, $underRepairAssets, $disposedAssets],
            'colors' => ['#2D6A4F', '#537E83', '#D97706', '#991B1B'],
        ];

        // 2. Chart: Valuasi Nilai Aset per Unit Kerja (Horizontal Bar)
        $topDeptValuations = Department::where('is_active', true)
            ->withSum('assets', 'purchase_price')
            ->orderBy('assets_sum_purchase_price', 'desc')
            ->take(6)
            ->get();
        $chartValuations = [
            'labels' => $topDeptValuations->pluck('name')->toArray(),
            'data' => $topDeptValuations->pluck('assets_sum_purchase_price')->map(fn ($v) => (float) ($v ?? 0))->toArray(),
        ];

        // 3. Chart: Tren Mutasi Aset 6 Bulan Terakhir (Line / Area)
        $trendLabels = [];
        $trendData = [];
        for ($i = 5; $i >= 0; $i--) {
            $d = now()->subMonths($i);
            $trendLabels[] = $d->format('M Y');
            $trendData[] = MutationForm::whereYear('created_at', $d->year)
                ->whereMonth('created_at', $d->month)
                ->count();
        }
        $chartMonthlyTrends = [
            'labels' => $trendLabels,
            'data' => $trendData,
        ];

        // 4. Chart: Komposisi Kondisi Fisik Barang (Doughnut)
        $condGood = \App\Models\MutationItem::where('item_condition', 'good')->count();
        $condLight = \App\Models\MutationItem::where('item_condition', 'damaged_light')->count();
        $condHeavy = \App\Models\MutationItem::where('item_condition', 'damaged_heavy')->count();
        if ($condGood === 0 && $condLight === 0 && $condHeavy === 0) {
            $condGood = $activeAssets + $inStorageAssets;
            $condLight = $underRepairAssets;
            $condHeavy = $disposedAssets;
        }
        $chartConditions = [
            'labels' => ['Kondisi Baik', 'Rusak Ringan', 'Rusak Berat'],
            'data' => [$condGood, $condLight, $condHeavy],
            'colors' => ['#2D6A4F', '#D97706', '#991B1B'],
        ];

        // 5. Chart: Top 5 Unit Distribusi Penerima Mutasi (Bar)
        $topReceivers = Department::where('is_active', true)
            ->withCount('incomingMutations')
            ->orderBy('incoming_mutations_count', 'desc')
            ->take(5)
            ->get();
        $chartTopReceivers = [
            'labels' => $topReceivers->pluck('name')->toArray(),
            'data' => $topReceivers->pluck('incoming_mutations_count')->toArray(),
        ];

        return view('dashboard', compact(
            'user',
            'stats',
            'departments',
            'recentAssets',
            'recentMutations',
            'recentAuditLogs',
            'userDepartment',
            'deptTotalAssets',
            'deptActiveAssets',
            'deptRepairAssets',
            'deptValuation',
            'deptAssets',
            'deptMutations',
            'chartStatus',
            'chartValuations',
            'chartMonthlyTrends',
            'chartConditions',
            'chartTopReceivers'
        ));
    }
}
