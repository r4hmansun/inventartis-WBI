<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetHistory;
use App\Models\Department;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    /**
     * Display a listing of assets.
     */
    public function index(Request $request)
    {
        $query = Asset::with('currentDepartment', 'creator');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by department
        if ($request->filled('department_id')) {
            $query->where('current_department_id', $request->department_id);
        }

        // Search by name or asset code
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('asset_code', 'like', "%{$search}%");
            });
        }

        $user = $request->user();
        $userDepartment = $user->department;

        // Support scope tab: my_department
        if ($request->get('scope') === 'my_dept' && $user->department_id) {
            $query->where('current_department_id', $user->department_id);
        }

        $assets = $query->latest()->paginate(10)->onEachSide(1)->withQueryString();
        $departments = Department::active()->orderBy('name')->get();

        // Department-specific count if user belongs to one
        $myDeptAssetCount = $user->department_id
            ? Asset::where('current_department_id', $user->department_id)->count()
            : 0;

        return view('assets.index', compact('assets', 'departments', 'userDepartment', 'myDeptAssetCount'));
    }

    /**
     * Show the form for creating a new asset.
     */
    public function create()
    {
        $departments = Department::active()->orderBy('name')->get();

        return view('assets.create', compact('departments'));
    }

    /**
     * Store a newly created asset (FR-REG-01, FR-REG-02, BR-01, BR-02).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'purchase_price' => ['required', 'numeric', 'min:500000'],
            'purchase_date' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ]);

        // BR-01: Validate capitalization threshold
        if ($validated['purchase_price'] < 500000) {
            return back()
                ->withErrors(['purchase_price' => 'Nilai barang di bawah Rp 500.000. Barang tidak dapat didaftarkan sebagai Aset Inventaris (Non-Asset).'])
                ->withInput();
        }

        // BR-02: Auto-assign to Gudang Inventaris
        $gudang = Department::where('code', 'GDG-INV')->firstOrFail();

        // FR-REG-02: Auto-generate asset code
        $assetCode = Asset::generateAssetCode($gudang->code);

        $asset = Asset::create([
            'asset_code' => $assetCode,
            'name' => $validated['name'],
            'purchase_price' => $validated['purchase_price'],
            'purchase_date' => $validated['purchase_date'],
            'current_department_id' => $gudang->id,
            'status' => 'in_storage',
            'created_by_user_id' => $request->user()->id,
        ]);

        // FR-LOG-01: Create audit trail entry
        AssetHistory::create([
            'asset_id' => $asset->id,
            'action_type' => 'registration',
            'from_department_id' => null,
            'to_department_id' => $gudang->id,
            'actor_user_id' => $request->user()->id,
            'notes' => 'Registrasi aset baru ke Gudang Inventaris.',
        ]);

        return redirect()->route('assets.index')
            ->with('success', "Aset berhasil didaftarkan dengan kode: {$assetCode}");
    }

    /**
     * Display the specified asset with its history.
     */
    public function show(Asset $asset)
    {
        $asset->load('currentDepartment', 'creator', 'histories.actor', 'histories.fromDepartment', 'histories.toDepartment');

        return view('assets.show', compact('asset'));
    }
}
