<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        // 1. Ekstraksi kode HTTP status secara dinamis & akurat
        $code = isset($exception) && method_exists($exception, 'getStatusCode')
            ? (int) $exception->getStatusCode()
            : (int) ($code ?? trim($__env->yieldContent('code', '404')) ?: 404);

        // 2. Ekstraksi pesan kustom jika ada
        $customMessage = isset($exception) && $exception->getMessage()
            ? $exception->getMessage()
            : trim($__env->yieldContent('message', ''));

        // 3. Penanganan Auth & Sesi yang 100% Tahan Banting (Safe against DB disconnect / Server crash)
        try {
            $isAuth = function_exists('auth') && auth()->check();
            $user = $isAuth ? auth()->user() : null;
            $userRole = $user ? ($user->role_label ?? $user->role ?? 'Pengguna') : 'Tamu (Guest)';
            $userName = $user ? $user->name : 'Tidak Terautentikasi';
        } catch (\Throwable $e) {
            $isAuth = false;
            $user = null;
            $userRole = 'Database Offline / Unreachable';
            $userName = 'Tamu (Guest)';
        }

        try {
            $csrfToken = function_exists('csrf_token') ? csrf_token() : '';
        } catch (\Throwable $e) {
            $csrfToken = '';
        }

        try {
            $currentUrl = request()->fullUrl() ?? url()->current() ?? '/';
        } catch (\Throwable $e) {
            $currentUrl = '/';
        }

        try {
            $homeUrl = $isAuth && \Illuminate\Support\Facades\Route::has('dashboard') 
                ? route('dashboard') 
                : (\Illuminate\Support\Facades\Route::has('login') ? route('login') : '/');
            $loginUrl = \Illuminate\Support\Facades\Route::has('login') ? route('login') : '/login';
            $assetsUrl = \Illuminate\Support\Facades\Route::has('assets.index') ? route('assets.index') : '/assets';
            $mutationsUrl = \Illuminate\Support\Facades\Route::has('mutations.index') ? route('mutations.index') : '/mutations';
        } catch (\Throwable $e) {
            $homeUrl = '/';
            $loginUrl = '/login';
            $assetsUrl = '/assets';
            $mutationsUrl = '/mutations';
        }

        // 4. Konfigurasi ramah manusia (Super mudah dipahami siapa saja) sesuai DESIGN.md
        $errorConfigs = [
            400 => [
                'code'        => 400,
                'tag'         => 'PERMINTAAN KELIRU',
                'title'       => 'Ups! Ada Data yang Kurang Pas',
                'subtitle'    => 'Sistem belum bisa memproses permintaan ini karena ada format atau isian data yang belum sesuai.',
                'theme'       => 'amber',
                'accent_bar'  => '#D97706',
                'icon_bg'     => '#FEF3C7',
                'icon_color'  => '#B45309',
                'solution'    => 'Pastikan semua isian formulir sudah diisi dengan benar, lalu coba kirim kembali.',
                'can_reload'  => true,
            ],
            401 => [
                'code'        => 401,
                'tag'         => 'BELUM LOGIN',
                'title'       => 'Silakan Masuk ke Akun Dulu',
                'subtitle'    => 'Biar sistem tahu ini kamu, yuk masuk (login) menggunakan akun WBI kamu terlebih dahulu.',
                'theme'       => 'teal',
                'accent_bar'  => '#002a22',
                'icon_bg'     => '#bdecde',
                'icon_color'  => '#002a22',
                'solution'    => 'Klik tombol "Masuk ke Akun" di bawah, lalu masukkan email dan password kamu.',
                'is_auth'     => true,
            ],
            403 => [
                'code'        => 403,
                'tag'         => 'AKSES TERKUNCI',
                'title'       => 'Waduh! Kamu Belum Punya Izin Akses',
                'subtitle'    => 'Halaman ini dikunci khusus untuk bagian tertentu. Akun kamu saat ini belum memiliki izin untuk membukanya.',
                'theme'       => 'amber',
                'accent_bar'  => '#D97706',
                'icon_bg'     => '#FEF3C7',
                'icon_color'  => '#B45309',
                'solution'    => 'Jika kamu memang butuh membuka halaman ini untuk tugas kerja, silakan hubungi tim Admin / IT WBI ya.',
                'show_roles'  => true,
            ],
            404 => [
                'code'        => 404,
                'tag'         => 'HALAMAN TIDAK ADA',
                'title'       => 'Ups! Halaman Tidak Ditemukan',
                'subtitle'    => 'Halaman atau barang/aset yang kamu cari tidak ada di sini. Mungkin salah ketik alamatnya atau sudah dipindahkan.',
                'theme'       => 'slate',
                'accent_bar'  => '#537E83',
                'icon_bg'     => '#E6F0F0',
                'icon_color'  => '#2A5257',
                'solution'    => 'Coba periksa lagi alamat webnya, atau klik tombol "Ke Dashboard" di bawah untuk mencari menu yang kamu inginkan.',
                'can_search'  => true,
            ],
            419 => [
                'code'        => 419,
                'tag'         => 'SESI HABIS',
                'title'       => 'Sesi Formulir Sudah Habis',
                'subtitle'    => 'Karena halaman ini didiamkan terlalu lama tanpa aktivitas, sistem menguncinya demi keamanan data kamu.',
                'theme'       => 'gold',
                'accent_bar'  => '#805600',
                'icon_bg'     => '#ffddaf',
                'icon_color'  => '#775000',
                'solution'    => 'Gampang kok! Tinggal klik tombol "Muat Ulang" di bawah, lalu kamu bisa mengisi atau mengirim formulirnya lagi.',
                'can_reload'  => true,
            ],
            429 => [
                'code'        => 429,
                'tag'         => 'TERLALU CEPAT',
                'title'       => 'Santai Sejenak, Jangan Buru-buru',
                'subtitle'    => 'Kamu menekan tombol atau membuka halaman terlalu cepat berturut-turut. Sistem butuh istirahat sebentar.',
                'theme'       => 'amber',
                'accent_bar'  => '#D97706',
                'icon_bg'     => '#FEF3C7',
                'icon_color'  => '#B45309',
                'solution'    => 'Tunggu sekitar 5–10 detik, lalu klik tombol "Muat Ulang" secara perlahan ya.',
                'can_reload'  => true,
            ],
            500 => [
                'code'        => 500,
                'tag'         => 'SISTEM TERKENDALA',
                'title'       => 'Aduh! Terjadi Gangguan di Sistem',
                'subtitle'    => 'Ada sedikit kendala teknis di dalam server kami. Jangan khawatir, data kamu tetap aman dan sistem sedang kami tangani.',
                'theme'       => 'red',
                'accent_bar'  => '#991B1B',
                'icon_bg'     => '#FEE2E2',
                'icon_color'  => '#991B1B',
                'solution'    => 'Coba muat ulang halaman ini. Jika masih terkendala, klik "Salin Info Error" lalu kirimkan ke tim IT Support.',
                'is_server'   => true,
                'can_reload'  => true,
            ],
            503 => [
                'code'        => 503,
                'tag'         => 'SEDANG DIPERBAIKI',
                'title'       => 'Sistem Sedang Pemeliharaan Berkala',
                'subtitle'    => 'Kami sedang merawat dan meningkatkan sistem inventaris WBI agar semakin cepat dan nyaman saat kamu pakai.',
                'theme'       => 'slate',
                'accent_bar'  => '#537E83',
                'icon_bg'     => '#E6F0F0',
                'icon_color'  => '#2A5257',
                'solution'    => 'Pemeliharaan ini biasanya hanya sebentar. Silakan kembali lagi beberapa saat lagi ya!',
                'can_reload'  => true,
            ],
        ];

        // Konfigurasi aktif berdasarkan kode status (dengan fallback pintar)
        $cfg = $errorConfigs[$code] ?? [
            'code'        => $code,
            'tag'         => 'KENDALA SISTEM',
            'title'       => 'Ups! Terjadi Kendala (' . $code . ')',
            'subtitle'    => 'Permintaan kamu belum bisa diproses oleh server saat ini.',
            'theme'       => ($code >= 500 ? 'red' : 'amber'),
            'accent_bar'  => ($code >= 500 ? '#991B1B' : '#805600'),
            'icon_bg'     => '#F3F4F6',
            'icon_color'  => '#374151',
            'solution'    => 'Silakan klik tombol "Ke Dashboard" atau "Kembali" untuk melanjutkan aktivitas kamu.',
            'can_reload'  => true,
        ];

        // Judul selalu bersih dan ramah manusia (jangan tampilkan teks mentah exception kodingan seperti ModelNotFound)
        $displayTitle = $cfg['title'];
        $displaySubtitle = $cfg['subtitle'];

        // Metadata diagnostik untuk pelaporan
        $refId = 'WBI-' . strtoupper(dechex(time() + $code)) . '-' . rand(1000, 9999);
        $timestamp = date('d M Y, H:i:s') . ' WIB';
    @endphp

    @if($csrfToken)
    <meta name="csrf-token" content="{{ $csrfToken }}">
    @endif
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>{{ $code }} — {{ $displayTitle }} | WBI Inventaris</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@500;600;700;800&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Standalone Resilient Fallback CSS (Ensures flawless corporate styling under any condition) -->
    <style>
        :root {
            --wbi-primary: #002a22;
            --wbi-primary-light: #134137;
            --wbi-primary-surface: #bdecde;
            --wbi-secondary: #805600;
            --wbi-secondary-light: #ffc569;
            --wbi-slate: #537E83;
            --wbi-danger: #991B1B;
            --wbi-warning: #D97706;
            --wbi-bg: #F8F9FA;
            --wbi-surface: #FFFFFF;
            --wbi-border: #E5E7EB;
            --wbi-text: #1a1c1b;
            --wbi-text-muted: #525c59;
            --font-display: 'Hanken Grotesk', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            --font-body: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
        }

        body {
            font-family: var(--font-body);
            background-color: var(--wbi-bg);
            color: var(--wbi-text);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            -webkit-font-smoothing: antialiased;
        }

        .font-display { font-family: var(--font-display); }
        .font-mono { font-family: var(--font-mono); }

        /* Subtle Pattern Background */
        .bg-grid-pattern {
            background-image: radial-gradient(rgba(0, 42, 34, 0.08) 1px, transparent 1px);
            background-size: 24px 24px;
        }

        /* Ambient Glow */
        .ambient-glow {
            position: absolute;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.12;
            pointer-events: none;
            z-index: 0;
        }

        /* Card Shadow */
        .wbi-error-card {
            background-color: var(--wbi-surface);
            border: 1px solid var(--wbi-border);
            border-radius: 12px;
            box-shadow: 0 10px 30px -10px rgba(0, 42, 34, 0.08), 0 4px 12px rgba(0, 42, 34, 0.03);
            position: relative;
            overflow: hidden;
        }

        /* Top Accent Bar */
        .wbi-error-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background-color: {{ $cfg['accent_bar'] }};
        }

        /* Pulse Animation */
        @keyframes subtlePulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.04); opacity: 0.85; }
        }
        .animate-subtle-pulse {
            animation: subtlePulse 4s ease-in-out infinite;
        }

        /* Float Animation */
        @keyframes gentleFloat {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-6px); }
        }
        .animate-float {
            animation: gentleFloat 5s ease-in-out infinite;
        }

        /* Buttons */
        .btn-primary-wbi {
            background-color: var(--wbi-primary);
            color: #ffffff;
            font-weight: 600;
            font-size: 0.875rem;
            padding: 0.625rem 1.25rem;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
            transition: all 0.2s ease;
            border: 1px solid transparent;
            cursor: pointer;
        }
        .btn-primary-wbi:hover {
            background-color: var(--wbi-primary-light);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 42, 34, 0.15);
        }

        .btn-secondary-wbi {
            background-color: #ffffff;
            color: var(--wbi-text);
            font-weight: 600;
            font-size: 0.875rem;
            padding: 0.625rem 1.25rem;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
            transition: all 0.2s ease;
            border: 1px solid var(--wbi-border);
            cursor: pointer;
        }
        .btn-secondary-wbi:hover {
            background-color: #f3f4f2;
            color: #000000;
        }

        .btn-outline-action {
            background-color: transparent;
            color: var(--wbi-slate);
            font-weight: 500;
            font-size: 0.8125rem;
            padding: 0.5rem 0.875rem;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            text-decoration: none;
            border: 1px dashed #c0c8c4;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .btn-outline-action:hover {
            background-color: #f0f4f3;
            border-color: var(--wbi-slate);
            color: var(--wbi-primary);
        }

        /* Diagnostic details box */
        .diagnostic-box {
            background-color: #F8F9FA;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            font-family: var(--font-mono);
            font-size: 0.75rem;
        }
    </style>
</head>

<body class="bg-grid-pattern relative">

    <!-- Ambient Gradient Background Lights -->
    <div class="ambient-glow -top-24 -left-24 bg-teal-600"></div>
    <div class="ambient-glow top-1/2 -right-24 bg-amber-500"></div>

    <!-- Header / Brand Top Bar -->
    <header class="relative z-10 w-full max-w-5xl mx-auto px-4 sm:px-6 pt-6 pb-2 flex items-center justify-between">
        <a href="{{ $homeUrl }}" class="flex items-center gap-3 group text-decoration-none">
            <div class="h-10 w-10 rounded-lg bg-white border border-border-light p-1.5 flex items-center justify-center shadow-xs group-hover:border-primary/40 transition-colors">
                <img src="{{ asset('images/logo.png') }}" alt="WBI Logo" class="h-full w-auto object-contain">
            </div>
            <div>
                <span class="font-display font-bold text-base text-on-surface leading-none block">WBI Inventaris</span>
                <span class="text-[11px] text-on-surface-variant leading-none mt-0.5 block">Politeknik Wilmar Bisnis Indonesia</span>
            </div>
        </a>

        <!-- System Status Badge -->
        <div class="flex items-center gap-2 px-3 py-1 rounded-full bg-white/80 backdrop-blur-xs border border-border-light text-xs text-on-surface-variant shadow-2xs">
            <span class="w-2 h-2 rounded-full {{ $code >= 500 ? 'bg-danger animate-ping' : ($code === 503 ? 'bg-amber-500' : 'bg-success') }}"></span>
            <span class="font-medium hidden sm:inline">{{ $code >= 500 ? 'Sistem Terkendala' : ($code === 503 ? 'Pemeliharaan' : 'Sistem Terhubung') }}</span>
            <span class="font-mono text-[10px] text-slate-500 font-semibold uppercase">{{ $cfg['tag'] }}</span>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="relative z-10 flex-1 flex items-center justify-center px-4 sm:px-6 py-8">
        <div class="w-full max-w-3xl">

            <div class="wbi-error-card p-6 sm:p-10">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-center">
                    
                    <!-- Left Column: Dynamic SVG Illustration -->
                    <div class="md:col-span-5 flex flex-col items-center justify-center text-center">
                        <div class="relative w-48 h-48 sm:w-56 sm:h-56 flex items-center justify-center">
                            
                            <!-- Illustration Background Disc -->
                            <div class="absolute inset-0 rounded-full opacity-60 animate-subtle-pulse" style="background-color: {{ $cfg['icon_bg'] }};"></div>
                            
                            <!-- Dynamic SVG Illustrations Based on Code -->
                            @if($code === 404)
                                <!-- 404: Missing Asset / Warehouse Radar -->
                                <svg class="w-32 h-32 sm:w-36 sm:h-36 relative z-10 animate-float" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <!-- Warehouse Box Frame -->
                                    <rect x="25" y="45" width="70" height="50" rx="6" fill="#FFFFFF" stroke="#537E83" stroke-width="3" stroke-dasharray="6 4"/>
                                    <!-- Empty Box flap -->
                                    <path d="M25 45L40 28H80L95 45" fill="#E6F0F0" stroke="#537E83" stroke-width="3" stroke-linejoin="round"/>
                                    <!-- Center Question / Search Pin -->
                                    <circle cx="60" cy="70" r="16" fill="#537E83" fill-opacity="0.1" stroke="#002a22" stroke-width="2.5"/>
                                    <path d="M56 65C56 62.7909 57.7909 61 60 61C62.2091 61 64 62.7909 64 65C64 66.8 62.5 67.5 61 69V71" stroke="#002a22" stroke-width="2.5" stroke-linecap="round"/>
                                    <circle cx="61" cy="75" r="1.5" fill="#002a22"/>
                                    <!-- Radar Sweep -->
                                    <circle cx="95" cy="35" r="14" fill="#ffddaf" stroke="#805600" stroke-width="2"/>
                                    <path d="M95 27V35L101 39" stroke="#805600" stroke-width="2" stroke-linecap="round"/>
                                </svg>

                            @elseif($code === 403)
                                <!-- 403: Security Gate & Shield Locked -->
                                <svg class="w-32 h-32 sm:w-36 sm:h-36 relative z-10 animate-float" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M60 20L95 34V64C95 86 60 102 60 102C60 102 25 86 25 64V34L60 20Z" fill="#FFFFFF" stroke="#D97706" stroke-width="3" stroke-linejoin="round"/>
                                    <path d="M60 26L88 38V63C88 81 60 94 60 94C60 94 32 81 32 63V38L60 26Z" fill="#FEF3C7"/>
                                    <!-- Padlock Icon -->
                                    <rect x="46" y="56" width="28" height="22" rx="4" fill="#D97706"/>
                                    <path d="M52 56V48C52 43.5817 55.5817 40 60 40C64.4183 40 68 43.5817 68 48V56" stroke="#D97706" stroke-width="3.5" stroke-linecap="round"/>
                                    <circle cx="60" cy="65" r="2.5" fill="#FFFFFF"/>
                                    <path d="M60 67.5V72" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round"/>
                                </svg>

                            @elseif($code === 401)
                                <!-- 401: ID Badge & User Access -->
                                <svg class="w-32 h-32 sm:w-36 sm:h-36 relative z-10 animate-float" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <!-- ID Card Lanyard -->
                                    <path d="M60 15V32" stroke="#002a22" stroke-width="3" stroke-dasharray="4 3"/>
                                    <rect x="32" y="32" width="56" height="74" rx="6" fill="#FFFFFF" stroke="#002a22" stroke-width="3"/>
                                    <rect x="48" y="38" width="24" height="4" rx="2" fill="#a2d0c2"/>
                                    <!-- User Avatar Silhouette -->
                                    <circle cx="60" cy="58" r="12" fill="#bdecde" stroke="#002a22" stroke-width="2"/>
                                    <path d="M42 84C42 75 50 73 60 73C70 73 78 75 78 84" fill="#bdecde" stroke="#002a22" stroke-width="2"/>
                                    <!-- Key Badge -->
                                    <circle cx="85" cy="85" r="14" fill="#ffddaf" stroke="#805600" stroke-width="2"/>
                                    <path d="M82 85L88 85M88 82V88" stroke="#805600" stroke-width="2" stroke-linecap="round"/>
                                </svg>

                            @elseif($code === 419)
                                <!-- 419: Hourglass Session Expired -->
                                <svg class="w-32 h-32 sm:w-36 sm:h-36 relative z-10 animate-float" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M35 25H85M35 95H85" stroke="#805600" stroke-width="4" stroke-linecap="round"/>
                                    <path d="M42 27C42 50 56 58 60 60C56 62 42 70 42 93M78 27C78 50 64 58 60 60C64 62 78 70 78 93" stroke="#805600" stroke-width="3" stroke-linejoin="round"/>
                                    <!-- Sand -->
                                    <path d="M48 88C50 78 70 78 72 88H48Z" fill="#ffc569"/>
                                    <circle cx="60" cy="68" r="2" fill="#805600"/>
                                    <circle cx="60" cy="74" r="1.5" fill="#805600"/>
                                    <!-- Reload Ribbon -->
                                    <path d="M92 48A18 18 0 1 1 82 34L88 34V40" stroke="#002a22" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>

                            @elseif($code === 429)
                                <!-- 429: Speedometer & Rate Limit -->
                                <svg class="w-32 h-32 sm:w-36 sm:h-36 relative z-10 animate-float" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M25 80A40 40 0 1 1 95 80" stroke="#D97706" stroke-width="4" stroke-linecap="round" stroke-dasharray="12 4"/>
                                    <circle cx="60" cy="75" r="8" fill="#D97706"/>
                                    <!-- Gauge Needle pointing to Red zone -->
                                    <path d="M60 75L86 48" stroke="#991B1B" stroke-width="3.5" stroke-linecap="round"/>
                                    <circle cx="86" cy="48" r="3" fill="#991B1B"/>
                                    <rect x="42" y="88" width="36" height="14" rx="4" fill="#FEF3C7" stroke="#D97706" stroke-width="1.5"/>
                                    <text x="60" y="98" font-family="monospace" font-size="8" font-weight="bold" fill="#B45309" text-anchor="middle">LIMIT</text>
                                </svg>

                            @elseif($code === 500)
                                <!-- 500: Server Circuit / Broken Cog -->
                                <svg class="w-32 h-32 sm:w-36 sm:h-36 relative z-10 animate-float" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <!-- Server Stack -->
                                    <rect x="25" y="30" width="70" height="22" rx="4" fill="#FFFFFF" stroke="#991B1B" stroke-width="2.5"/>
                                    <circle cx="35" cy="41" r="2.5" fill="#991B1B"/>
                                    <circle cx="43" cy="41" r="2.5" fill="#537E83"/>
                                    <rect x="65" y="38" width="20" height="5" rx="2" fill="#FEE2E2"/>

                                    <rect x="25" y="60" width="70" height="22" rx="4" fill="#FFFFFF" stroke="#991B1B" stroke-width="2.5"/>
                                    <circle cx="35" cy="71" r="2.5" fill="#991B1B"/>
                                    <circle cx="43" cy="71" r="2.5" fill="#537E83"/>
                                    <rect x="65" y="68" width="20" height="5" rx="2" fill="#FEE2E2"/>

                                    <!-- Alert Triangle overlay -->
                                    <path d="M88 65L106 96H70L88 65Z" fill="#FEE2E2" stroke="#991B1B" stroke-width="2.5" stroke-linejoin="round"/>
                                    <path d="M88 76V85" stroke="#991B1B" stroke-width="2.5" stroke-linecap="round"/>
                                    <circle cx="88" cy="90" r="1.5" fill="#991B1B"/>
                                </svg>

                            @elseif($code === 503)
                                <!-- 503: Maintenance Gear & Wrench -->
                                <svg class="w-32 h-32 sm:w-36 sm:h-36 relative z-10 animate-float" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <!-- Gear -->
                                    <circle cx="60" cy="55" r="22" fill="#E6F0F0" stroke="#537E83" stroke-width="3"/>
                                    <circle cx="60" cy="55" r="8" fill="#FFFFFF" stroke="#537E83" stroke-width="2.5"/>
                                    <path d="M60 25V33M60 77V85M30 55H38M82 55H90M39 34L45 40M75 70L81 76M81 34L75 40M39 76L45 70" stroke="#537E83" stroke-width="3.5" stroke-linecap="round"/>
                                    <!-- Wrench crossing -->
                                    <path d="M38 88L72 54C75 51 77 46 75 43C73 40 68 40 65 44L32 77L38 88Z" fill="#ffc569" stroke="#805600" stroke-width="2"/>
                                    <!-- Barrier Tape -->
                                    <rect x="20" y="88" width="80" height="12" rx="3" fill="#ffddaf" stroke="#805600" stroke-width="1.5"/>
                                    <path d="M26 88L34 100M42 88L50 100M58 88L66 100M74 88L82 100M90 88L98 100" stroke="#805600" stroke-width="1.5"/>
                                </svg>

                            @else
                                <!-- Generic / Fallback SVG -->
                                <svg class="w-32 h-32 sm:w-36 sm:h-36 relative z-10 animate-float" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="60" cy="60" r="38" fill="#F3F4F6" stroke="#717975" stroke-width="3"/>
                                    <path d="M60 40V66" stroke="#717975" stroke-width="4" stroke-linecap="round"/>
                                    <circle cx="60" cy="78" r="2.5" fill="#717975"/>
                                </svg>
                            @endif
                        </div>

                        <!-- Status Badge Pill -->
                        <div class="mt-4 inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold" style="background-color: {{ $cfg['icon_bg'] }}; color: {{ $cfg['icon_color'] }};">
                            <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $cfg['accent_bar'] }};"></span>
                            <span>HTTP STATUS {{ $code }}</span>
                        </div>
                    </div>

                    <!-- Right Column: Content, Guidance & Actions -->
                    <div class="md:col-span-7 flex flex-col justify-center">
                        
                        <!-- Big Code + Title -->
                        <div class="mb-3">
                            <div class="font-display font-extrabold text-4xl sm:text-5xl tracking-tight text-on-surface flex items-baseline gap-3">
                                <span>{{ $code }}</span>
                                <span class="text-xs font-mono font-medium text-on-surface-variant/70 tracking-normal px-2 py-0.5 rounded bg-surface-container border border-border-light">
                                    {{ $cfg['tag'] }}
                                </span>
                            </div>
                            <h1 class="font-display text-xl sm:text-2xl font-bold text-on-surface mt-1">
                                {{ $displayTitle }}
                            </h1>
                        </div>

                        <!-- Subtitle Description -->
                        <p class="text-sm text-on-surface-variant leading-relaxed mb-4">
                            {{ $displaySubtitle }}
                        </p>

                        <!-- Practical Guidance Alert Box (Bahasa Ramah Pengguna) -->
                        <div class="p-3.5 rounded-lg bg-surface-container/80 border border-border-light text-xs text-on-surface-variant mb-6 flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-primary shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <span class="font-semibold text-on-surface">Saran Penanganan:</span>
                                <span class="ml-1 leading-relaxed">{{ $cfg['solution'] }}</span>
                            </div>
                        </div>

                        <!-- Action Buttons Row -->
                        <div class="flex flex-wrap items-center gap-2.5 mb-6">
                            
                            <!-- Primary Button: Smart Dashboard / Login Redirect -->
                            @if($code === 401)
                                <a href="{{ $loginUrl }}" class="btn-primary-wbi">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                    </svg>
                                    <span>Masuk ke Akun</span>
                                </a>
                            @else
                                <a href="{{ $homeUrl }}" class="btn-primary-wbi">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                    </svg>
                                    <span>{{ $isAuth ? 'Ke Dashboard' : 'Halaman Utama' }}</span>
                                </a>
                            @endif

                            <!-- Secondary Button: Back to previous page -->
                            <button type="button" onclick="window.history.length > 1 ? window.history.back() : window.location.href='{{ $homeUrl }}'" class="btn-secondary-wbi">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                <span>Kembali</span>
                            </button>

                            <!-- Reload Button (if applicable) -->
                            @if(!empty($cfg['can_reload']))
                                <button type="button" onclick="window.location.reload()" class="btn-secondary-wbi" title="Muat Ulang Halaman">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    <span class="hidden sm:inline">Refresh</span>
                                </button>
                            @endif
                        </div>

                        <!-- Technical Diagnostic Accordion / Panel -->
                        <div class="border-t border-border-light pt-4">
                            <div class="flex items-center justify-between text-xs text-on-surface-variant mb-2">
                                <button type="button" onclick="toggleDiagnostics()" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-gray hover:text-on-surface transition-colors focus:outline-none cursor-pointer">
                                    <svg id="diagChevron" class="w-3.5 h-3.5 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                    <span>Informasi Diagnostik Teknis</span>
                                </button>

                                <button type="button" onclick="copyDiagnosticInfo()" id="btnCopyDiag" class="btn-outline-action" title="Salin info pelaporan ke clipboard">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                    <span id="copyText">Salin Info Error</span>
                                </button>
                            </div>

                            <!-- Diagnostic Body (Collapsible) -->
                            <div id="diagnosticPanel" class="hidden diagnostic-box p-3 space-y-1.5 text-on-surface-variant">
                                <div class="flex justify-between items-center py-0.5 border-b border-border-light/60">
                                    <span class="text-slate-500">Ref ID:</span>
                                    <span class="font-semibold text-on-surface">{{ $refId }}</span>
                                </div>
                                <div class="flex justify-between items-center py-0.5 border-b border-border-light/60">
                                    <span class="text-slate-500">Waktu Kejadian:</span>
                                    <span>{{ $timestamp }}</span>
                                </div>
                                <div class="flex justify-between items-center py-0.5 border-b border-border-light/60">
                                    <span class="text-slate-500">Pengguna / Role:</span>
                                    <span>{{ $userName }} ({{ $userRole }})</span>
                                </div>
                                <div class="flex justify-between items-start py-0.5 border-b border-border-light/60">
                                    <span class="text-slate-500 shrink-0">URL Permintaan:</span>
                                    <span class="truncate ml-2 text-right text-[11px] text-primary" title="{{ $currentUrl }}">{{ $currentUrl }}</span>
                                </div>
                                @if($customMessage)
                                <div class="pt-1 text-[11px] text-danger bg-red-50 p-1.5 rounded">
                                    <span class="font-bold">Exception:</span> {{ $customMessage }}
                                </div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Quick Navigation Footnote (Emergency Module Links) -->
            <div class="mt-6 flex flex-wrap items-center justify-between gap-4 text-xs text-on-surface-variant px-2">
                <div class="flex items-center gap-3">
                    <span class="font-medium text-slate-500">Tautan Cepat:</span>
                    @if($isAuth)
                        <a href="{{ $assetsUrl }}" class="hover:text-primary underline transition-colors">Gudang Aset</a>
                        <span class="text-border-light">•</span>
                        <a href="{{ $mutationsUrl }}" class="hover:text-primary underline transition-colors">Mutasi Aset</a>
                    @else
                        <a href="{{ $loginUrl }}" class="hover:text-primary underline transition-colors">Login Pegawai</a>
                    @endif
                </div>

                <div class="flex items-center gap-2 text-slate-500">
                    <span>Butuh bantuan?</span>
                    <a href="mailto:it-support@wbi.ac.id?subject=Laporan%20Kendala%20Sistem%20Inventaris%20[{{ $refId }}]" class="font-semibold text-primary hover:underline">
                        Hubungi IT Support
                    </a>
                </div>
            </div>

        </div>
    </main>

    <!-- Global Footer -->
    <footer class="relative z-10 w-full text-center py-4 border-t border-border-light/60 text-xs text-on-surface-variant bg-white/50 backdrop-blur-xs">
        <p>&copy; {{ date('Y') }} Politeknik Wilmar Bisnis Indonesia — Sistem Manajemen Inventaris & Mutasi Aset</p>
    </footer>

    <!-- Interactive Script -->
    <script>
        // Toggle Diagnostic Details Box
        function toggleDiagnostics() {
            const panel = document.getElementById('diagnosticPanel');
            const chevron = document.getElementById('diagChevron');
            if (!panel) return;
            
            const isHidden = panel.classList.contains('hidden');
            if (isHidden) {
                panel.classList.remove('hidden');
                chevron.classList.add('rotate-90');
            } else {
                panel.classList.add('hidden');
                chevron.classList.remove('rotate-90');
            }
        }

        // Copy Diagnostic Data to Clipboard with Instant Feedback
        function copyDiagnosticInfo() {
            const infoText = `[LAPORAN KENDALA WBI INVENTARIS]
Kode Status : {{ $code }} ({{ $cfg['tag'] }})
Judul Error : {{ $displayTitle }}
ID Referensi: {{ $refId }}
Waktu       : {{ $timestamp }}
User/Role   : {{ $userName }} ({{ $userRole }})
URL         : {{ $currentUrl }}
Pesan Tambahan: {{ $customMessage ?: 'None' }}`;

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(infoText).then(() => {
                    showCopiedFeedback();
                }).catch(() => {
                    fallbackCopy(infoText);
                });
            } else {
                fallbackCopy(infoText);
            }
        }

        function fallbackCopy(text) {
            const textArea = document.createElement('textarea');
            textArea.value = text;
            textArea.style.position = 'fixed';
            textArea.style.left = '-999999px';
            textArea.style.top = '-999999px';
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            try {
                document.execCommand('copy');
                showCopiedFeedback();
            } catch (err) {
                console.error('Gagal menyalin:', err);
            }
            document.body.removeChild(textArea);
        }

        function showCopiedFeedback() {
            const copyText = document.getElementById('copyText');
            const btn = document.getElementById('btnCopyDiag');
            if (!copyText) return;

            const originalText = copyText.innerText;
            copyText.innerText = 'Tersalin!';
            if (btn) {
                btn.style.backgroundColor = '#bdecde';
                btn.style.color = '#002a22';
                btn.style.borderColor = '#002a22';
            }

            setTimeout(() => {
                copyText.innerText = originalText;
                if (btn) {
                    btn.style.backgroundColor = '';
                    btn.style.color = '';
                    btn.style.borderColor = '';
                }
            }, 2500);
        }
    </script>
</body>
</html>
