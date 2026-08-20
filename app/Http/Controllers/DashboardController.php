<?php

namespace App\Http\Controllers;

use App\Models\Asset;
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

        $stats = [
            'total_assets' => Asset::count(),
            'active_assets' => Asset::active()->count(),
            'in_storage' => Asset::inStorage()->count(),
            'pending_mutations' => MutationForm::whereIn('status', ['draft', 'waiting_receiver', 'ready_for_execution'])->count(),
            'total_departments' => Department::active()->count(),
        ];

        // Recent assets
        $recentAssets = Asset::with('currentDepartment', 'creator')
            ->latest()
            ->take(5)
            ->get();

        // Recent mutations
        $recentMutations = MutationForm::with('fromDepartment', 'toDepartment', 'sender')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact('user', 'stats', 'recentAssets', 'recentMutations'));
    }
}
