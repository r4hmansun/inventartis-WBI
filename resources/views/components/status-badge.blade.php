{{-- Status Badge Component --}}
@props(['status'])

@php
$statusConfig = [
    'in_storage' => ['label' => 'In Storage', 'class' => 'bg-slate-100 text-slate-gray'],
    'active' => ['label' => 'Active', 'class' => 'bg-emerald-50 text-success'],
    'under_repair' => ['label' => 'Under Repair', 'class' => 'bg-amber-50 text-warning'],
    'disposed' => ['label' => 'Disposed', 'class' => 'bg-red-50 text-danger'],
    // Mutation form statuses
    'draft' => ['label' => 'Draft', 'class' => 'bg-slate-100 text-slate-gray'],
    'waiting_receiver' => ['label' => 'Menunggu Penerima', 'class' => 'bg-amber-50 text-warning'],
    'ready_for_execution' => ['label' => 'Siap Eksekusi', 'class' => 'bg-blue-50 text-blue-700'],
    'archived' => ['label' => 'Diarsipkan', 'class' => 'bg-emerald-50 text-success'],
    'rejected' => ['label' => 'Ditolak', 'class' => 'bg-red-50 text-danger'],
    // Item conditions
    'good' => ['label' => 'Baik', 'class' => 'bg-emerald-50 text-success'],
    'damaged_light' => ['label' => 'Rusak Ringan', 'class' => 'bg-amber-50 text-warning'],
    'damaged_heavy' => ['label' => 'Rusak Berat', 'class' => 'bg-red-50 text-danger'],
];

$config = $statusConfig[$status] ?? ['label' => $status, 'class' => 'bg-slate-100 text-slate-600'];
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-xl text-[11px] font-mono font-medium {{ $config['class'] }}">
    {{ $config['label'] }}
</span>
