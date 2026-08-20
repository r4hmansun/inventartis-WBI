{{-- Stat Card Component --}}
@props(['title', 'value', 'icon' => null, 'color' => 'primary'])

@php
$colorMap = [
    'primary' => 'border-t-primary-light bg-surface-white',
    'success' => 'border-t-success bg-surface-white',
    'warning' => 'border-t-warning bg-surface-white',
    'slate' => 'border-t-slate-gray bg-surface-white',
    'danger' => 'border-t-danger bg-surface-white',
];
$iconBgMap = [
    'primary' => 'bg-primary-surface text-primary-light',
    'success' => 'bg-emerald-50 text-success',
    'warning' => 'bg-amber-50 text-warning',
    'slate' => 'bg-slate-100 text-slate-gray',
    'danger' => 'bg-red-50 text-danger',
];
$cardClass = $colorMap[$color] ?? $colorMap['primary'];
$iconClass = $iconBgMap[$color] ?? $iconBgMap['primary'];
@endphp

<div class="border-t-[3px] {{ $cardClass }} rounded-lg border border-border-light p-5 transition-all duration-200 hover:shadow-md">
    <div class="flex items-start justify-between">
        <div>
            <p class="text-sm text-on-surface-variant font-medium mb-1">{{ $title }}</p>
            <p class="font-display text-3xl font-bold text-on-surface">{{ $value }}</p>
        </div>
        @if($icon)
        <div class="w-11 h-11 rounded-xl {{ $iconClass }} flex items-center justify-center">
            {!! $icon !!}
        </div>
        @endif
    </div>
    @if(isset($footer))
    <div class="mt-3 pt-3 border-t border-border-light text-xs text-on-surface-variant">
        {{ $footer }}
    </div>
    @endif
</div>
