<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetHistory;
use App\Models\Department;
use App\Models\MutationForm;
use App\Models\MutationItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MutationController extends Controller
{
    /**
     * Display a listing of mutation forms.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $query = MutationForm::with(['fromDepartment', 'toDepartment', 'sender', 'receiver', 'executor', 'items.asset']);

        // Scope filter for regular department users
        if ($user->hasRole('user') && $user->department_id) {
            $query->where(function ($q) use ($user) {
                $q->where('from_department_id', $user->department_id)
                  ->orWhere('to_department_id', $user->department_id);
            });
        }

        // Filter tab scope
        if ($request->filled('scope')) {
            if ($request->scope === 'waiting_my_approval' && $user->department_id) {
                $query->where('to_department_id', $user->department_id)
                      ->where('status', 'waiting_receiver');
            } elseif ($request->scope === 'outgoing' && $user->department_id) {
                $query->where('from_department_id', $user->department_id);
            } elseif ($request->scope === 'incoming' && $user->department_id) {
                $query->where('to_department_id', $user->department_id);
            } elseif ($request->scope === 'ready_execution') {
                $query->where('status', 'ready_for_execution');
            } elseif ($request->scope === 'archived') {
                $query->where('status', 'archived');
            }
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Department filter
        if ($request->filled('department_id')) {
            $deptId = $request->department_id;
            $query->where(function ($q) use ($deptId) {
                $q->where('from_department_id', $deptId)
                  ->orWhere('to_department_id', $deptId);
            });
        }

        // Search by form number or reason
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('form_number', 'like', "%{$search}%")
                  ->orWhere('reason', 'like', "%{$search}%");
            });
        }

        $mutations = $query->latest()->paginate(10)->withQueryString();

        // Calculate quick badge counts
        $countQuery = MutationForm::query();
        if ($user->hasRole('user') && $user->department_id) {
            $countQuery->where(function ($q) use ($user) {
                $q->where('from_department_id', $user->department_id)
                  ->orWhere('to_department_id', $user->department_id);
            });
        }

        $waitingMyApprovalCount = 0;
        if ($user->department_id) {
            $waitingMyApprovalCount = MutationForm::where('to_department_id', $user->department_id)
                ->where('status', 'waiting_receiver')
                ->count();
        }

        $readyExecutionCount = MutationForm::where('status', 'ready_for_execution')->count();
        $archivedCount = MutationForm::where('status', 'archived')->count();
        $departments = Department::active()->orderBy('name')->get();

        return view('mutations.index', compact(
            'mutations',
            'waitingMyApprovalCount',
            'readyExecutionCount',
            'archivedCount',
            'departments',
            'user'
        ));
    }

    /**
     * Show the form for creating a new mutation form.
     */
    public function create(Request $request)
    {
        $user = $request->user();

        // Determine source department
        if ($user->hasRole('user')) {
            if (!$user->department_id) {
                return redirect()->route('dashboard')
                    ->with('error', 'Akun Anda belum terhubung dengan unit/departemen.');
            }
            $fromDepartment = $user->department;
        } else {
            // Admin, Finance, or Inventory staff can pick origin or default to Gudang INV
            $selectedFromDeptId = $request->get('from_department_id', $user->department_id);
            $fromDepartment = Department::find($selectedFromDeptId)
                ?? Department::where('code', 'GDG-INV')->first()
                ?? Department::first();
        }

        // Available assets belonging to source department (status in_storage or active)
        $availableAssets = Asset::where('current_department_id', $fromDepartment->id)
            ->whereIn('status', ['active', 'in_storage'])
            ->orderBy('name')
            ->get();

        // Target departments (all active departments except source)
        $targetDepartments = Department::active()
            ->where('id', '!=', $fromDepartment->id)
            ->orderBy('name')
            ->get();

        $allDepartments = Department::active()->orderBy('name')->get();

        return view('mutations.create', compact(
            'fromDepartment',
            'availableAssets',
            'targetDepartments',
            'allDepartments',
            'user'
        ));
    }

    /**
     * Store a newly created mutation form.
     */
    public function store(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'from_department_id' => ['required', 'exists:departments,id'],
            'to_department_id' => ['required', 'exists:departments,id', 'different:from_department_id'],
            'reason' => ['required', 'string', 'max:500'],
            'asset_ids' => ['required', 'array', 'min:1'],
            'asset_ids.*' => ['required', 'exists:assets,id'],
            'item_conditions' => ['nullable', 'array'],
            'item_conditions.*' => ['in:good,damaged_light,damaged_heavy'],
            'sender_approval_confirm' => ['required', 'accepted'],
        ], [
            'sender_approval_confirm.accepted' => 'Anda wajib mengonfirmasi persetujuan pengajuan mutasi aset.',
            'asset_ids.required' => 'Pilih minimal satu barang/aset untuk dimutasi.',
            'to_department_id.different' => 'Departemen tujuan mutasi tidak boleh sama dengan departemen asal.',
        ]);

        // Security check: regular user must match from_department_id
        if ($user->hasRole('user') && $user->department_id != $validated['from_department_id']) {
            return back()->withErrors(['from_department_id' => 'Anda hanya berhak mengajukan mutasi dari unit kerja Anda.'])->withInput();
        }

        // Validate assets belong to from_department_id
        $validAssets = Asset::where('current_department_id', $validated['from_department_id'])
            ->whereIn('id', $validated['asset_ids'])
            ->whereIn('status', ['active', 'in_storage'])
            ->get();

        if ($validAssets->count() !== count($validated['asset_ids'])) {
            return back()->withErrors(['asset_ids' => 'Salah satu aset yang dipilih tidak valid atau tidak berada di unit asal.'])->withInput();
        }

        $formNumber = MutationForm::generateFormNumber();

        $mutation = DB::transaction(function () use ($validated, $user, $formNumber, $validAssets) {
            $mutationForm = MutationForm::create([
                'form_number' => $formNumber,
                'from_department_id' => $validated['from_department_id'],
                'to_department_id' => $validated['to_department_id'],
                'reason' => $validated['reason'],
                'status' => 'waiting_receiver',
                'sender_user_id' => $user->id,
                'sender_signature' => 'APPROVED_DIGITAL_BY_' . strtoupper($user->name),
                'sender_signed_at' => now(),
            ]);

            foreach ($validAssets as $asset) {
                $condition = $validated['item_conditions'][$asset->id] ?? 'good';
                MutationItem::create([
                    'mutation_form_id' => $mutationForm->id,
                    'asset_id' => $asset->id,
                    'item_condition' => $condition,
                ]);
            }

            return $mutationForm;
        });

        return redirect()->route('mutations.show', $mutation)
            ->with('success', "Formulir Mutasi {$mutation->form_number} berhasil diterbitkan dan telah disetujui pihak pengirim. Menunggu persetujuan unit penerima.");
    }

    /**
     * Display the specified mutation form.
     */
    public function show(MutationForm $mutation, Request $request)
    {
        $user = $request->user();

        // Authorization check for regular user
        if ($user->hasRole('user') && $user->department_id) {
            if ($user->department_id != $mutation->from_department_id && $user->department_id != $mutation->to_department_id) {
                abort(403, 'Anda tidak memiliki hak akses untuk melihat formulir mutasi ini.');
            }
        }

        $mutation->load(['fromDepartment', 'toDepartment', 'sender', 'receiver', 'executor', 'items.asset']);

        // Check user actions
        $canApproveAsReceiver = false;
        if ($mutation->status === 'waiting_receiver') {
            if ($user->hasRole('admin') || ($user->department_id && $user->department_id == $mutation->to_department_id)) {
                $canApproveAsReceiver = true;
            }
        }

        $canExecute = false;
        if ($mutation->status === 'ready_for_execution') {
            if ($user->hasRole('inventory', 'admin')) {
                $canExecute = true;
            }
        }

        return view('mutations.show', compact('mutation', 'user', 'canApproveAsReceiver', 'canExecute'));
    }

    /**
     * Approve the mutation form as the receiving department.
     */
    public function approveReceiver(MutationForm $mutation, Request $request)
    {
        $user = $request->user();

        if ($mutation->status !== 'waiting_receiver') {
            return back()->with('error', 'Status mutasi saat ini tidak dalam antrean persetujuan penerima.');
        }

        // Authorization
        if (!$user->hasRole('admin') && $user->department_id != $mutation->to_department_id) {
            abort(403, 'Hanya perwakilan dari departemen penerima yang berhak menyetujui mutasi ini.');
        }

        $mutation->update([
            'receiver_user_id' => $user->id,
            'receiver_signature' => 'APPROVED_DIGITAL_BY_' . strtoupper($user->name),
            'receiver_signed_at' => now(),
            'status' => 'ready_for_execution',
        ]);

        return redirect()->route('mutations.show', $mutation)
            ->with('success', "Persetujuan Penerima berhasil dikonfirmasi oleh {$user->name}. Status formulir kini Siap Eksekusi oleh Bagian Inventaris.");
    }

    /**
     * Reject the mutation form.
     */
    public function reject(MutationForm $mutation, Request $request)
    {
        $user = $request->user();

        if ($mutation->status !== 'waiting_receiver') {
            return back()->with('error', 'Formulir ini tidak dapat ditolak pada status saat ini.');
        }

        if (!$user->hasRole('admin') && $user->department_id != $mutation->to_department_id) {
            abort(403, 'Hanya perwakilan dari departemen penerima yang berhak menolak mutasi ini.');
        }

        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:500'],
        ]);

        $mutation->update([
            'receiver_user_id' => $user->id,
            'rejection_reason' => $validated['rejection_reason'],
            'status' => 'rejected',
        ]);

        return redirect()->route('mutations.show', $mutation)
            ->with('info', 'Permohonan mutasi aset telah ditolak.');
    }

    /**
     * Execute the mutation (Inventory Staff / Admin).
     */
    public function execute(MutationForm $mutation, Request $request)
    {
        $user = $request->user();

        if (!$user->hasRole('inventory', 'admin')) {
            abort(403, 'Hanya Bagian Inventaris atau Admin yang berhak mengeksekusi mutasi aset.');
        }

        if ($mutation->status !== 'ready_for_execution') {
            return back()->with('error', 'Mutasi belum siap dieksekusi. Memerlukan persetujuan ganda dari penyerah dan penerima.');
        }

        $mutation->load(['items.asset', 'fromDepartment', 'toDepartment']);

        DB::transaction(function () use ($mutation, $user) {
            foreach ($mutation->items as $item) {
                $asset = $item->asset;
                $previousDeptId = $asset->current_department_id;

                // Update asset ownership & ensure status active
                $asset->update([
                    'current_department_id' => $mutation->to_department_id,
                    'status' => 'active',
                ]);

                $actionType = ($mutation->fromDepartment && $mutation->fromDepartment->code === 'GDG-INV')
                    ? 'initial_dispatch'
                    : 'department_mutation';

                // Record immutable audit trail
                AssetHistory::create([
                    'asset_id' => $asset->id,
                    'action_type' => $actionType,
                    'from_department_id' => $previousDeptId,
                    'to_department_id' => $mutation->to_department_id,
                    'actor_user_id' => $user->id,
                    'notes' => "Eksekusi mutasi resmi: {$mutation->form_number}. Dari {$mutation->fromDepartment->name} ke {$mutation->toDepartment->name}. Alasan: {$mutation->reason}",
                ]);
            }

            // Lock and archive the mutation form
            $mutation->update([
                'executed_by_user_id' => $user->id,
                'status' => 'archived',
                'archived_pdf_path' => "archives/mutations/{$mutation->form_number}.pdf",
            ]);
        });

        return redirect()->route('mutations.show', $mutation)
            ->with('success', "Mutasi {$mutation->form_number} berhasil dieksekusi! Kepemilikan aset telah resmi berpindah ke {$mutation->toDepartment->name} dan dokumen Berita Acara telah diarsipkan.");
    }

    /**
     * Print Official Berita Acara Serah Terima Aset.
     */
    public function print(MutationForm $mutation)
    {
        $mutation->load(['fromDepartment', 'toDepartment', 'sender', 'receiver', 'executor', 'items.asset']);

        return view('mutations.print', compact('mutation'));
    }
}
