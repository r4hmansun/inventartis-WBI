@props([
    'type' => 'card', // card, table, stat, text, button
    'rows' => 5,
    'class' => '',
])

@if($type === 'card')
    <div {{ $attributes->merge(['class' => 'bg-surface-white rounded-2xl border border-border-light p-6 shadow-xs relative overflow-hidden ' . $class]) }}>
        <div class="space-y-3">
            <div class="w-1/3 h-4 rounded wbi-skeleton"></div>
            <div class="w-1/2 h-7 rounded-md wbi-skeleton"></div>
            <div class="w-2/3 h-3 rounded wbi-skeleton"></div>
        </div>
    </div>

@elseif($type === 'stat')
    <div {{ $attributes->merge(['class' => 'bg-surface-white rounded-2xl border border-border-light p-6 shadow-xs flex items-center justify-between relative overflow-hidden ' . $class]) }}>
        <div class="space-y-2 flex-1 pr-4">
            <div class="w-28 h-3.5 rounded wbi-skeleton"></div>
            <div class="w-20 h-8 rounded-md wbi-skeleton"></div>
            <div class="w-36 h-3 rounded wbi-skeleton"></div>
        </div>
        <div class="w-12 h-12 rounded-xl wbi-skeleton shrink-0"></div>
    </div>

@elseif($type === 'table')
    <div {{ $attributes->merge(['class' => 'bg-surface-white rounded-2xl border border-border-light overflow-hidden shadow-xs relative ' . $class]) }}>
        <div class="px-6 py-4 border-b border-border-light flex items-center justify-between">
            <div class="w-36 h-5 rounded wbi-skeleton"></div>
            <div class="w-48 h-8 rounded-lg wbi-skeleton"></div>
        </div>
        <div class="divide-y divide-border-light">
            @for($i = 0; $i < $rows; $i++)
                <div class="px-6 py-4 flex items-center gap-4">
                    <div class="w-28 h-5 rounded wbi-skeleton"></div>
                    <div class="flex-1 space-y-1">
                        <div class="w-{{ [48, 56, 64, 40][$i % 4] }} h-4 rounded wbi-skeleton"></div>
                    </div>
                    <div class="w-24 h-4 rounded wbi-skeleton hidden sm:block"></div>
                    <div class="w-20 h-6 rounded-full wbi-skeleton"></div>
                    <div class="w-16 h-7 rounded-lg wbi-skeleton ml-auto"></div>
                </div>
            @endfor
        </div>
    </div>

@elseif($type === 'button')
    <div {{ $attributes->merge(['class' => 'w-28 h-9 rounded-lg wbi-skeleton ' . $class]) }}></div>

@elseif($type === 'text')
    <div {{ $attributes->merge(['class' => 'w-full h-4 rounded wbi-skeleton ' . $class]) }}></div>
@endif
