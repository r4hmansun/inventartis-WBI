@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
        <div class="flex justify-between flex-1 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-on-surface-variant/50 bg-surface-container rounded-lg cursor-not-allowed">
                    &laquo; Sebelumnya
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-on-surface bg-surface-white border border-border-light rounded-lg hover:bg-surface-container transition-colors shadow-2xs">
                    &laquo; Sebelumnya
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-on-surface bg-surface-white border border-border-light rounded-lg hover:bg-surface-container transition-colors shadow-2xs">
                    Selanjutnya &raquo;
                </a>
            @else
                <span class="relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-on-surface-variant/50 bg-surface-container rounded-lg cursor-not-allowed">
                    Selanjutnya &raquo;
                </span>
            @endif
        </div>

        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-xs text-on-surface-variant">
                    Menampilkan
                    <span class="font-mono font-semibold text-on-surface">{{ $paginator->firstItem() ?? 0 }}</span>
                    sampai
                    <span class="font-mono font-semibold text-on-surface">{{ $paginator->lastItem() ?? 0 }}</span>
                    dari total
                    <span class="font-mono font-semibold text-on-surface">{{ $paginator->total() }}</span>
                    data
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex items-center gap-1">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span class="relative inline-flex items-center justify-center w-8 h-8 rounded-lg border border-border-light/60 bg-surface-container/40 text-on-surface-variant/40 cursor-not-allowed text-xs" aria-hidden="true">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center justify-center w-8 h-8 rounded-lg border border-border-light bg-surface-white text-on-surface-variant hover:text-on-surface hover:bg-surface-container transition-colors shadow-2xs text-xs" aria-label="{{ __('pagination.previous') }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="relative inline-flex items-center justify-center w-8 h-8 text-xs font-mono text-on-surface-variant">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="relative inline-flex items-center justify-center w-8 h-8 rounded-lg bg-primary text-white text-xs font-mono font-bold shadow-2xs">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="relative inline-flex items-center justify-center w-8 h-8 rounded-lg border border-border-light bg-surface-white text-on-surface text-xs font-mono font-medium hover:bg-surface-container transition-colors shadow-2xs" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center justify-center w-8 h-8 rounded-lg border border-border-light bg-surface-white text-on-surface-variant hover:text-on-surface hover:bg-surface-container transition-colors shadow-2xs text-xs" aria-label="{{ __('pagination.next') }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                            <span class="relative inline-flex items-center justify-center w-8 h-8 rounded-lg border border-border-light/60 bg-surface-container/40 text-on-surface-variant/40 cursor-not-allowed text-xs" aria-hidden="true">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
