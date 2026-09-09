{{-- ============================================================
     WBI Asset Management — Modern Skeleton Loading System
     Design System Compliant (DESIGN.md)
     - Replaces generic modal/spinners with realistic Skeleton UI
     - Shimmering placeholders for header, stats, and data tables
     - Top slim progress bar + subtle floating status pill
     ============================================================ --}}

<!-- 1. Top Slim Navigation Progress Bar -->
<div id="wbi-topbar-loader" 
     class="fixed top-0 left-0 h-[2.5px] w-0 z-[100000] opacity-0 transition-all duration-200 pointer-events-none"
     style="background: linear-gradient(90deg, #002a22 0%, #134137 60%, #ffc569 100%); box-shadow: 0 0 8px rgba(0, 42, 34, 0.35), 0 0 3px #ffc569;">
</div>

<!-- 2. Modern Content Skeleton Screen (Positioned in main content canvas) -->
<div id="wbi-skeleton-loader" 
     class="fixed top-16 right-0 bottom-0 left-0 lg:left-[260px] z-30 bg-neutral-bg hidden opacity-0 pointer-events-none transition-opacity duration-200 overflow-y-auto p-4 sm:p-6 select-none"
     role="status"
     aria-live="polite"
     aria-label="Memuat konten...">

    <div class="max-w-7xl mx-auto space-y-6 w-full">
        
        <!-- Header & Breadcrumb Skeleton -->
        <div class="bg-surface-white rounded-2xl border border-border-light p-6 sm:p-8 shadow-xs flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5 relative overflow-hidden">
            <div class="space-y-3 w-full max-w-lg">
                <div class="flex items-center gap-2">
                    <div class="w-16 h-5 rounded-full wbi-skeleton"></div>
                    <div class="w-32 h-4 rounded-md wbi-skeleton"></div>
                </div>
                <div class="w-64 sm:w-80 h-8 rounded-lg wbi-skeleton"></div>
                <div class="w-full max-w-md h-4 rounded-md wbi-skeleton"></div>
            </div>

            <!-- Action Button & Floating Micro-Status Pill -->
            <div class="flex items-center gap-3 shrink-0">
                <div class="w-36 h-10 rounded-xl wbi-skeleton hidden sm:block"></div>
                <div class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-surface-container/80 border border-border-light text-xs font-medium text-on-surface-variant shadow-2xs">
                    <span class="w-2 h-2 rounded-full bg-primary animate-ping"></span>
                    <span id="wbi-skeleton-text" class="font-sans">Memuat data...</span>
                </div>
            </div>
        </div>

        <!-- 3 KPI Metric Cards Skeleton -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            <!-- Card 1 -->
            <div class="bg-surface-white rounded-2xl border border-border-light p-6 shadow-xs flex items-center justify-between relative overflow-hidden">
                <div class="space-y-2.5 flex-1 pr-4">
                    <div class="w-28 h-3.5 rounded wbi-skeleton"></div>
                    <div class="w-20 h-9 rounded-md wbi-skeleton"></div>
                    <div class="w-36 h-3 rounded wbi-skeleton"></div>
                </div>
                <div class="w-14 h-14 rounded-2xl wbi-skeleton shrink-0"></div>
            </div>

            <!-- Card 2 -->
            <div class="bg-surface-white rounded-2xl border border-border-light p-6 shadow-xs flex items-center justify-between relative overflow-hidden">
                <div class="space-y-2.5 flex-1 pr-4">
                    <div class="w-32 h-3.5 rounded wbi-skeleton"></div>
                    <div class="w-28 h-9 rounded-md wbi-skeleton"></div>
                    <div class="w-40 h-3 rounded wbi-skeleton"></div>
                </div>
                <div class="w-14 h-14 rounded-2xl wbi-skeleton shrink-0"></div>
            </div>

            <!-- Card 3 -->
            <div class="bg-surface-white rounded-2xl border border-border-light p-6 shadow-xs flex items-center justify-between relative overflow-hidden">
                <div class="space-y-2.5 flex-1 pr-4">
                    <div class="w-24 h-3.5 rounded wbi-skeleton"></div>
                    <div class="w-16 h-9 rounded-md wbi-skeleton"></div>
                    <div class="w-32 h-3 rounded wbi-skeleton"></div>
                </div>
                <div class="w-14 h-14 rounded-2xl wbi-skeleton shrink-0"></div>
            </div>
        </div>

        <!-- Main Data Table Container Skeleton -->
        <div class="bg-surface-white rounded-2xl border border-border-light overflow-hidden shadow-xs relative">
            
            <!-- Table Toolbar / Search & Filter Skeleton -->
            <div class="px-6 py-5 border-b border-border-light flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="space-y-1.5">
                    <div class="w-48 h-5 rounded-md wbi-skeleton"></div>
                    <div class="w-72 h-3.5 rounded wbi-skeleton"></div>
                </div>
                <div class="flex items-center gap-2.5">
                    <div class="w-44 sm:w-56 h-9 rounded-lg wbi-skeleton"></div>
                    <div class="w-24 h-9 rounded-lg wbi-skeleton"></div>
                </div>
            </div>

            <!-- Table Rows Skeleton -->
            <div class="divide-y divide-border-light">
                <!-- Column Headers -->
                <div class="px-6 py-3.5 bg-surface-container-low flex items-center gap-4">
                    <div class="w-32 h-3.5 rounded wbi-skeleton"></div>
                    <div class="w-52 h-3.5 rounded wbi-skeleton hidden sm:block"></div>
                    <div class="w-28 h-3.5 rounded wbi-skeleton hidden md:block"></div>
                    <div class="w-24 h-3.5 rounded wbi-skeleton hidden lg:block"></div>
                    <div class="w-20 h-3.5 rounded wbi-skeleton ml-auto"></div>
                </div>

                <!-- Row 1 -->
                <div class="px-6 py-4 flex items-center gap-4">
                    <div class="w-32 h-6 rounded-md wbi-skeleton"></div>
                    <div class="flex-1 space-y-1 sm:block hidden">
                        <div class="w-52 h-4 rounded wbi-skeleton"></div>
                        <div class="w-32 h-3 rounded wbi-skeleton"></div>
                    </div>
                    <div class="w-28 h-4 rounded wbi-skeleton hidden md:block"></div>
                    <div class="w-20 h-6 rounded-full wbi-skeleton hidden lg:block"></div>
                    <div class="w-16 h-7 rounded-lg wbi-skeleton ml-auto"></div>
                </div>

                <!-- Row 2 -->
                <div class="px-6 py-4 flex items-center gap-4">
                    <div class="w-28 h-6 rounded-md wbi-skeleton"></div>
                    <div class="flex-1 space-y-1 sm:block hidden">
                        <div class="w-44 h-4 rounded wbi-skeleton"></div>
                        <div class="w-28 h-3 rounded wbi-skeleton"></div>
                    </div>
                    <div class="w-32 h-4 rounded wbi-skeleton hidden md:block"></div>
                    <div class="w-20 h-6 rounded-full wbi-skeleton hidden lg:block"></div>
                    <div class="w-16 h-7 rounded-lg wbi-skeleton ml-auto"></div>
                </div>

                <!-- Row 3 -->
                <div class="px-6 py-4 flex items-center gap-4">
                    <div class="w-36 h-6 rounded-md wbi-skeleton"></div>
                    <div class="flex-1 space-y-1 sm:block hidden">
                        <div class="w-60 h-4 rounded wbi-skeleton"></div>
                        <div class="w-36 h-3 rounded wbi-skeleton"></div>
                    </div>
                    <div class="w-24 h-4 rounded wbi-skeleton hidden md:block"></div>
                    <div class="w-20 h-6 rounded-full wbi-skeleton hidden lg:block"></div>
                    <div class="w-16 h-7 rounded-lg wbi-skeleton ml-auto"></div>
                </div>

                <!-- Row 4 -->
                <div class="px-6 py-4 flex items-center gap-4">
                    <div class="w-30 h-6 rounded-md wbi-skeleton"></div>
                    <div class="flex-1 space-y-1 sm:block hidden">
                        <div class="w-48 h-4 rounded wbi-skeleton"></div>
                        <div class="w-24 h-3 rounded wbi-skeleton"></div>
                    </div>
                    <div class="w-28 h-4 rounded wbi-skeleton hidden md:block"></div>
                    <div class="w-20 h-6 rounded-full wbi-skeleton hidden lg:block"></div>
                    <div class="w-16 h-7 rounded-lg wbi-skeleton ml-auto"></div>
                </div>

                <!-- Row 5 -->
                <div class="px-6 py-4 flex items-center gap-4">
                    <div class="w-34 h-6 rounded-md wbi-skeleton"></div>
                    <div class="flex-1 space-y-1 sm:block hidden">
                        <div class="w-56 h-4 rounded wbi-skeleton"></div>
                        <div class="w-32 h-3 rounded wbi-skeleton"></div>
                    </div>
                    <div class="w-24 h-4 rounded wbi-skeleton hidden md:block"></div>
                    <div class="w-20 h-6 rounded-full wbi-skeleton hidden lg:block"></div>
                    <div class="w-16 h-7 rounded-lg wbi-skeleton ml-auto"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Embedded Skeleton Shimmer & Button Spinner Styles -->
<style>
    /* Continuous Lightwave Shimmer Effect for Skeleton Elements */
    .wbi-skeleton {
        position: relative;
        overflow: hidden;
        background-color: #E2E8F0; /* slate-200 / border-light */
        border-radius: 0.375rem;
    }

    .wbi-skeleton::after {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        transform: translateX(-100%);
        background-image: linear-gradient(
            90deg,
            rgba(255, 255, 255, 0) 0%,
            rgba(255, 255, 255, 0.45) 30%,
            rgba(255, 255, 255, 0.75) 50%,
            rgba(255, 255, 255, 0.45) 70%,
            rgba(255, 255, 255, 0) 100%
        );
        animation: wbiSkeletonShimmer 1.6s infinite ease-in-out;
        content: '';
    }

    @keyframes wbiSkeletonShimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }

    /* Inline Spinner for Submit Buttons */
    .wbi-btn-spinner {
        display: inline-block;
        width: 0.875rem;
        height: 0.875rem;
        border: 2px solid rgba(255, 255, 255, 0.35);
        border-radius: 50%;
        border-top-color: #ffffff;
        animation: wbiSpin 0.6s linear infinite;
        vertical-align: -0.125em;
    }
    @keyframes wbiSpin {
        to { transform: rotate(360deg); }
    }
</style>

<!-- Skeleton Controller Script -->
<script>
    (function () {
        const topBar = document.getElementById('wbi-topbar-loader');
        const skeletonLoader = document.getElementById('wbi-skeleton-loader');
        const skeletonText = document.getElementById('wbi-skeleton-text');

        let progressInterval = null;
        let skeletonTimeout = null;
        let navDebounceTimeout = null;

        // 1. Top Slim Progress Bar Controller
        window.showPageLoader = function () {
            if (!topBar) return;
            clearInterval(progressInterval);
            topBar.style.opacity = '1';
            topBar.style.width = '25%';

            let currentWidth = 25;
            progressInterval = setInterval(function () {
                if (currentWidth < 85) {
                    currentWidth += Math.random() * 10;
                    topBar.style.width = currentWidth + '%';
                }
            }, 200);
        };

        window.hidePageLoader = function () {
            if (!topBar) return;
            clearInterval(progressInterval);
            topBar.style.width = '100%';
            setTimeout(function () {
                topBar.style.opacity = '0';
                setTimeout(function () {
                    topBar.style.width = '0%';
                }, 250);
            }, 150);
        };

        // 2. Main Skeleton Screen Controller
        window.showSkeletonLoader = function (message) {
            if (!skeletonLoader) return;

            // Adjust positioning if no sidebar (e.g. login/guest layout)
            if (!document.getElementById('sidebar')) {
                skeletonLoader.classList.remove('lg:left-[260px]', 'top-16');
                skeletonLoader.classList.add('left-0', 'top-0');
            } else {
                skeletonLoader.classList.add('lg:left-[260px]', 'top-16');
                skeletonLoader.classList.remove('left-0', 'top-0');
            }

            if (skeletonText && message) {
                skeletonText.textContent = message;
            }

            clearTimeout(skeletonTimeout);
            window.showPageLoader();

            skeletonLoader.classList.remove('hidden');
            skeletonLoader.setAttribute('aria-hidden', 'false');

            requestAnimationFrame(function () {
                skeletonLoader.classList.remove('opacity-0', 'pointer-events-none');
                skeletonLoader.classList.add('opacity-100', 'pointer-events-auto');
            });

            // Failsafe auto-dismiss after 20s if request hangs
            skeletonTimeout = setTimeout(function () {
                window.hideSkeletonLoader();
            }, 20000);
        };

        window.hideSkeletonLoader = function () {
            if (!skeletonLoader) return;
            clearTimeout(skeletonTimeout);
            clearTimeout(navDebounceTimeout);

            window.hidePageLoader();

            skeletonLoader.classList.remove('opacity-100', 'pointer-events-auto');
            skeletonLoader.classList.add('opacity-0', 'pointer-events-none');

            setTimeout(function () {
                skeletonLoader.classList.add('hidden');
                skeletonLoader.setAttribute('aria-hidden', 'true');
                if (skeletonText) {
                    skeletonText.textContent = 'Memuat data...';
                }
            }, 200);
        };

        // Aliases for backwards compatibility
        window.showHeavyLoader = window.showSkeletonLoader;
        window.hideHeavyLoader = window.hideSkeletonLoader;

        // 3. Navigation Links Handler (Smooth debounce so instantaneous clicks don't flicker)
        document.addEventListener('click', function (e) {
            const link = e.target.closest('a');
            if (!link) return;

            const href = link.getAttribute('href');
            const target = link.getAttribute('target');
            const download = link.hasAttribute('download');

            // Only trigger for real internal page transitions
            if (href && 
                !href.startsWith('#') && 
                !href.startsWith('javascript:') && 
                !href.startsWith('mailto:') && 
                !href.startsWith('tel:') && 
                target !== '_blank' && 
                !download && 
                !e.ctrlKey && 
                !e.metaKey && 
                !e.shiftKey) {
                
                // Debounce by 120ms: if page unloads quickly, no flicker. If taking a moment, show skeleton!
                clearTimeout(navDebounceTimeout);
                navDebounceTimeout = setTimeout(function () {
                    const navMsg = link.getAttribute('data-loading-text') || 'Memuat halaman...';
                    window.showSkeletonLoader(navMsg);
                }, 120);
            }
        });

        // 4. Form Submit Handler: Only for state-changing methods (POST, PUT, PATCH, DELETE)
        document.addEventListener('submit', function (e) {
            const form = e.target;
            if (!form || !(form instanceof HTMLFormElement)) return;

            // Skip GET forms (search, filter, pagination, query params)
            const method = (form.getAttribute('method') || 'GET').toUpperCase();
            if (method === 'GET') return;

            // Honor explicit opt-out
            if (form.hasAttribute('data-no-loader')) return;

            // Check if HTML5 validation is supported and passes
            if (typeof form.checkValidity === 'function' && !form.checkValidity()) {
                return;
            }

            // Submit button loading state & double-click prevention
            const submitBtn = form.querySelector('button[type="submit"]:not([data-no-loading])');
            if (submitBtn && !submitBtn.disabled) {
                if (!submitBtn.dataset.originalHtml) {
                    submitBtn.dataset.originalHtml = submitBtn.innerHTML;
                }
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.85';
                submitBtn.style.cursor = 'wait';
                const btnText = submitBtn.getAttribute('data-loading-text') || 'Memproses...';
                submitBtn.innerHTML = '<span class="wbi-btn-spinner inline-block mr-2"></span> ' + btnText;
            }

            // Show skeleton loading with custom or contextual message
            const heavyMsg = form.getAttribute('data-loading-text') || 'Sedang memproses data ke sistem...';
            window.showSkeletonLoader(heavyMsg);
        });

        // 5. Click Handler for Explicitly Skeleton/Heavy Elements
        document.addEventListener('click', function (e) {
            const heavyEl = e.target.closest('[data-skeleton], [data-heavy]');
            if (!heavyEl) return;

            // Let form submit event listener handle form submit buttons
            if (heavyEl.matches('button[type="submit"]') && heavyEl.closest('form')) return;

            const msg = heavyEl.getAttribute('data-loading-text') || 'Sedang memproses...';
            window.showSkeletonLoader(msg);
        });

        // 6. Restore State on Back-Forward Navigation (bfcache)
        function restoreAllStates() {
            clearTimeout(navDebounceTimeout);
            window.hideSkeletonLoader();

            // Restore any disabled submit buttons
            document.querySelectorAll('button[data-original-html]').forEach(function (btn) {
                btn.disabled = false;
                btn.innerHTML = btn.dataset.originalHtml;
                delete btn.dataset.originalHtml;
                btn.style.opacity = '';
                btn.style.cursor = '';
            });
        }

        window.addEventListener('pageshow', restoreAllStates);

        // 7. Escape key safety dismiss
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && skeletonLoader && !skeletonLoader.classList.contains('hidden')) {
                restoreAllStates();
            }
        });
    })();
</script>
