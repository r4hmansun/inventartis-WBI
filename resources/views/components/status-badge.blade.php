{{-- Status Badge Component (JetBrains Mono Technical Badge) --}}
@props(['status'])

@php
$statusConfig = [
    'in_storage' => [
        'label' => 'IN STORAGE',
        'bg' => 'bg-slate-100/90',
        'text' => 'text-slate-700',
        'border' => 'border-slate-300',
        'dot' => 'bg-slate-500',
    ],
    'active' => [
        'label' => 'ACTIVE',
        'bg' => 'bg-emerald-50',
        'text' => 'text-emerald-800',
        'border' => 'border-emerald-300',
        'dot' => 'bg-emerald-600',
    ],
    'under_repair' => [
        'label' => 'UNDER REPAIR',
        'bg' => 'bg-amber-50',
        'text' => 'text-amber-800',
        'border' => 'border-amber-300',
        'dot' => 'bg-amber-500',
    ],
    'disposed' => [
        'label' => 'DISPOSED',
        'bg' => 'bg-rose-50',
        'text' => 'text-rose-800',
        'border' => 'border-rose-300',
        'dot' => 'bg-rose-500',
    ],
    // Mutation form statuses
    'draft' => [
        'label' => 'DRAFT',
        'bg' => 'bg-stone-100',
        'text' => 'text-stone-700',
        'border' => 'border-stone-300',
        'dot' => 'bg-stone-400',
    ],
    'waiting_receiver' => [
        'label' => 'MENUNGGU APPROVAL',
        'bg' => 'bg-amber-50',
        'text' => 'text-amber-800',
        'border' => 'border-amber-300',
        'dot' => 'bg-amber-500 animate-pulse',
    ],
    'ready_for_execution' => [
        'label' => 'SIAP EKSEKUSI',
        'bg' => 'bg-teal-50',
        'text' => 'text-[#002a22]',
        'border' => 'border-teal-300',
        'dot' => 'bg-teal-600',
    ],
    'archived' => [
        'label' => 'DIARSIPKAN',
        'bg' => 'bg-emerald-50',
        'text' => 'text-emerald-800',
        'border' => 'border-emerald-300',
        'dot' => 'bg-emerald-600',
    ],
    'rejected' => [
        'label' => 'DITOLAK',
        'bg' => 'bg-rose-50',
        'text' => 'text-rose-800',
        'border' => 'border-rose-300',
        'dot' => 'bg-rose-500',
    ],
    // Item conditions
    'good' => [
        'label' => 'BAIK',
        'bg' => 'bg-emerald-50',
        'text' => 'text-emerald-800',
        'border' => 'border-emerald-300',
        'dot' => 'bg-emerald-500',
    ],
    'damaged_light' => [
        'label' => 'RUSAK RINGAN',
        'bg' => 'bg-amber-50',
        'text' => 'text-amber-800',
        'border' => 'border-amber-300',
        'dot' => 'bg-amber-500',
    ],
    'damaged_heavy' => [
        'label' => 'RUSAK BERAT',
        'bg' => 'bg-rose-50',
        'text' => 'text-rose-800',
        'border' => 'border-rose-300',
        'dot' => 'bg-rose-500',
    ],
];

$config = $statusConfig[$status] ?? [
    'label' => strtoupper(str_replace('_', ' ', $status)),
    'bg' => 'bg-stone-100',
    'text' => 'text-stone-700',
    'border' => 'border-stone-300',
    'dot' => 'bg-stone-400',
];
@endphp

<span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-[4px] border {{ $config['border'] }} {{ $config['bg'] }} {{ $config['text'] }} text-[11px] font-mono font-medium tracking-tight">
    <span class="w-1.5 h-1.5 rounded-full shrink-0 {{ $config['dot'] }}"></span>
    <span>{{ $config['label'] }}</span>
</span>

