{{-- Enterprise Metric Card Component (No AI Slop) --}}
@props([
    'title',
    'value',
    'unit' => 'Unit',
    'subvalue' => null,
    'badge' => null,
    'badgeType' => 'neutral',
    'icon' => null,
])

@php
$badgeStyles = [
    'neutral' => 'bg-stone-100 text-stone-700 border-stone-300',
    'teal' => 'bg-[#002a22]/5 text-[#002a22] border-[#002a22]/20 font-semibold',
    'success' => 'bg-emerald-50 text-emerald-800 border-emerald-300',
    'warning' => 'bg-amber-50 text-amber-800 border-amber-300',
    'slate' => 'bg-slate-100 text-slate-700 border-slate-300',
    'danger' => 'bg-rose-50 text-rose-800 border-rose-300',
];
$badgeClass = $badgeStyles[$badgeType] ?? $badgeStyles['neutral'];
@endphp

<div class="bg-surface-white rounded-2xl border border-border-light p-5 sm:p-6 shadow-xs hover:border-slate-300 hover:shadow-sm transition-all duration-200 flex flex-col justify-between group">
    <div>
        {{-- Card Header --}}
        <div class="flex items-center justify-between gap-2 pb-2 mb-3 border-b border-border-light">
            <div class="flex items-center gap-2.5">
                @if($icon)
                <div class="w-8 h-8 rounded-lg bg-surface-container text-primary-light flex items-center justify-center shrink-0">
                    {!! $icon !!}
                </div>
                @endif
                <span class="text-xs sm:text-sm font-bold text-slate-700 font-sans tracking-wide uppercase">{{ $title }}</span>
            </div>
            @if($badge)
            <span class="inline-flex items-center px-2.5 py-1 text-xs font-mono font-semibold rounded-md border {{ $badgeClass }}">
                {{ $badge }}
            </span>
            @endif
        </div>

        {{-- Metric Display --}}
        <div class="flex items-baseline gap-2">
            <span class="font-mono text-3xl sm:text-4xl font-bold text-slate-900 tracking-tight">{{ $value }}</span>
            @if($unit)
            <span class="text-sm font-sans font-semibold text-slate-500">{{ $unit }}</span>
            @endif
        </div>
    </div>

    {{-- Context / Subvalue Footer --}}
    @if($subvalue || isset($footer))
    <div class="mt-4 pt-3 border-t border-border-light flex items-center justify-between text-xs sm:text-sm text-slate-600">
        @if($subvalue)
        <span class="truncate">{!! $subvalue !!}</span>
        @endif
        @if(isset($footer))
        <div>{{ $footer }}</div>
        @endif
    </div>
    @endif
</div>


