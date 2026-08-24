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
            'deptMutations'
        ));
    }
}
