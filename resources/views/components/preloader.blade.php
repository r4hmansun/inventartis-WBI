{{-- ============================================================
     WBI Asset Management — Professional Page Preloader & Loader
     Design System Compliant (DESIGN.md)
     ============================================================ --}}

<!-- 1. Top Slim Navigation Progress Bar (Pre-load for transitions & AJAX) -->
<div id="wbi-topbar-loader" 
     class="fixed top-0 left-0 h-[2.5px] w-0 z-[100000] opacity-0 transition-all duration-200 pointer-events-none"
     style="background: linear-gradient(90deg, #002a22 0%, #134137 60%, #ffc569 100%); box-shadow: 0 0 8px rgba(0, 42, 34, 0.35), 0 0 3px #ffc569;">
</div>

<!-- 2. Fullscreen Initial Page Splash Preloader -->
<div id="wbi-preloader" 
     class="fixed inset-0 z-[99999] flex flex-col items-center justify-center bg-[#ffffff] select-none transition-all duration-350 ease-out"
     aria-label="Memuat Halaman"
     role="status">

    <!-- Ambient Subtle Glow Disc -->
    <div class="absolute w-72 h-72 rounded-full bg-[#bdecde]/25 blur-3xl pointer-events-none -z-10 animate-pulse"></div>

    <div class="flex flex-col items-center text-center px-4 max-w-sm">
        
        <!-- Logo Card Disc with Subtle Breathing Animation -->
        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-white border border-[#E5E7EB] p-3 sm:p-4 flex items-center justify-center shadow-sm wbi-logo-pulse">
            <img src="{{ asset('images/logo.png') }}" alt="WBI Logo" class="h-full w-auto object-contain">
        </div>

        <!-- Brand Titles -->
        <div class="mt-4 mb-5">
            <h2 class="font-display text-base sm:text-lg font-bold text-[#1a1c1b] tracking-tight leading-tight">
                WBI Inventaris
            </h2>
            <p class="text-xs text-[#525c59] font-body mt-0.5">
                Sistem Manajemen &amp; Mutasi Aset
            </p>
        </div>

        <!-- Corporate Linear Progress Bar Track -->
        <div class="w-44 sm:w-52 h-[3.5px] bg-[#E5E7EB] rounded-full overflow-hidden relative mb-3">
            <div class="wbi-linear-shimmer"></div>
        </div>

        <!-- Micro Status Text in JetBrains Mono / Inter -->
        <div class="flex items-center gap-1.5 text-[11px] font-mono text-[#525c59]/80">
            <span class="w-1.5 h-1.5 rounded-full bg-[#002a22] animate-ping"></span>
            <span id="wbi-preloader-text">Memuat sistem...</span>
        </div>
    </div>
</div>

<!-- Embedded Preloader Styles -->
<style>
    /* Breathing animation for logo disc */
    @keyframes wbiLogoBreath {
        0%, 100% { transform: scale(1); box-shadow: 0 4px 12px rgba(0, 42, 34, 0.05); }
        50% { transform: scale(1.03); box-shadow: 0 8px 24px rgba(0, 42, 34, 0.12); }
    }
    .wbi-logo-pulse {
        animation: wbiLogoBreath 2.2s ease-in-out infinite;
    }

    /* Continuous Linear Progress Shimmer */
    @keyframes wbiShimmerMove {
        0% { left: -40%; width: 30%; }
        50% { left: 30%; width: 50%; }
        100% { left: 100%; width: 40%; }
    }
    .wbi-linear-shimmer {
        position: absolute;
        top: 0;
        bottom: 0;
        background: linear-gradient(90deg, #002a22 0%, #134137 50%, #805600 100%);
        border-radius: 9999px;
        animation: wbiShimmerMove 1.4s cubic-bezier(0.4, 0, 0.2, 1) infinite;
    }

    /* Hidden State with Smooth Fade & Slight Scale */
    .wbi-preloader-hidden {
        opacity: 0 !important;
        visibility: hidden !important;
        transform: scale(0.985) !important;
        pointer-events: none !important;
    }

    /* Spinner for Submit Buttons */
    .wbi-btn-spinner {
        display: inline-block;
        width: 1rem;
        height: 1rem;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        border-top-color: #ffffff;
        animation: wbiSpin 0.6s linear infinite;
    }
    @keyframes wbiSpin {
        to { transform: rotate(360deg); }
    }
</style>

<!-- Preloader & Page Navigation Script -->
<script>
    (function () {
        const preloader = document.getElementById('wbi-preloader');
        const topBar = document.getElementById('wbi-topbar-loader');

        // 1. Hide Initial Preloader Smoothly
        function dismissInitialPreloader() {
            if (!preloader || preloader.classList.contains('wbi-preloader-hidden')) return;
            
            // Add fade class
            preloader.classList.add('wbi-preloader-hidden');
            
            // Cleanup from DOM after transition
            setTimeout(function () {
                if (preloader.parentNode) {
                    preloader.style.display = 'none';
                }
            }, 400);
        }

        // Trigger on load
        if (document.readyState === 'complete') {
            setTimeout(dismissInitialPreloader, 100);
        } else {
            window.addEventListener('load', function () {
                setTimeout(dismissInitialPreloader, 150);
            });
        }

        // Safety timeout fallback (max 1.5s so user is never blocked)
        setTimeout(dismissInitialPreloader, 1500);

        // 2. Global Topbar Loading Controller
        let progressInterval = null;
        
        window.showPageLoader = function () {
            if (!topBar) return;
            clearInterval(progressInterval);
            topBar.style.opacity = '1';
            topBar.style.width = '15%';

            let currentWidth = 15;
            progressInterval = setInterval(function () {
                if (currentWidth < 85) {
                    currentWidth += Math.random() * 12;
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
            }, 200);
        };

        // 3. Auto Trigger Top Bar Preload on Navigation Links
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
                window.showPageLoader();
            }
        });

        // 4. Auto Trigger Loader & Disable Button on Form Submits
        document.addEventListener('submit', function (e) {
            const form = e.target;
            if (form.hasAttribute('data-no-loader')) return;

            window.showPageLoader();

            const submitBtn = form.querySelector('button[type="submit"]:not([data-no-loading])');
            if (submitBtn && !submitBtn.disabled) {
                // Store original content
                if (!submitBtn.dataset.originalHtml) {
                    submitBtn.dataset.originalHtml = submitBtn.innerHTML;
                }
                
                // Show inline spinner
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.85';
                submitBtn.style.cursor = 'wait';
                submitBtn.innerHTML = '<span class="wbi-btn-spinner inline-block mr-2"></span> Memproses...';
            }
        });

        // Hide loader when navigating back via bfcache
        window.addEventListener('pageshow', function (event) {
            window.hidePageLoader();
            dismissInitialPreloader();
        });
    })();
</script>
