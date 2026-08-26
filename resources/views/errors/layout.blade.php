<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        // 1. Ekstraksi kode HTTP status
        $code = isset($exception) && method_exists($exception, 'getStatusCode')
            ? (int) $exception->getStatusCode()
            : (int) ($code ?? trim($__env->yieldContent('code', '404')) ?: 404);

        // 2. Ekstraksi pesan kustom jika ada
        $customMessage = isset($exception) && $exception->getMessage()
            ? $exception->getMessage()
            : trim($__env->yieldContent('message', ''));

        // 3. Status Auth
        try {
            $isAuth = function_exists('auth') && auth()->check();
        } catch (\Throwable $e) {
            $isAuth = false;
        }

        try {
            $csrfToken = function_exists('csrf_token') ? csrf_token() : '';
        } catch (\Throwable $e) {
            $csrfToken = '';
        }

        try {
            $homeUrl = $isAuth && \Illuminate\Support\Facades\Route::has('dashboard') 
                ? route('dashboard') 
                : (\Illuminate\Support\Facades\Route::has('login') ? route('login') : '/');
            $loginUrl = \Illuminate\Support\Facades\Route::has('login') ? route('login') : '/login';
        } catch (\Throwable $e) {
            $homeUrl = '/';
            $loginUrl = '/login';
        }

        // 4. Konfigurasi error bersih & profesional
        $errorConfigs = [
            400 => [
                'code'        => 400,
                'tag'         => 'Permintaan Tidak Valid',
                'title'       => 'Permintaan Tidak Dapat Diproses',
                'subtitle'    => 'Format atau parameter data yang dikirim tidak sesuai dengan ketentuan sistem.',
                'solution'    => 'Periksa kembali data yang Anda masukkan dan coba lagi.',
                'can_reload'  => true,
            ],
            401 => [
                'code'        => 401,
                'tag'         => 'Autentikasi Diperlukan',
                'title'       => 'Sesi Anda Belum Terautentikasi',
                'subtitle'    => 'Silakan masuk menggunakan akun WBI Anda untuk mengakses halaman ini.',
                'solution'    => 'Klik tombol Masuk ke Akun untuk melanjutkan.',
                'is_auth'     => true,
            ],
            403 => [
                'code'        => 403,
                'tag'         => 'Akses Dibatasi',
                'title'       => 'Akses Tidak Diizinkan',
                'subtitle'    => 'Akun Anda tidak memiliki hak akses yang cukup untuk membuka halaman ini.',
                'solution'    => 'Silakan kembali ke dashboard atau gunakan menu yang tersedia.',
            ],
            404 => [
                'code'        => 404,
                'tag'         => 'Halaman Tidak Ditemukan',
                'title'       => 'Halaman Tidak Ditemukan',
                'subtitle'    => 'Halaman atau data yang Anda cari tidak tersedia atau alamat URL telah dipindahkan.',
                'solution'    => 'Pastikan alamat URL sudah benar atau kembali ke halaman utama.',
            ],
            419 => [
                'code'        => 419,
                'tag'         => 'Sesi Kedaluwarsa',
                'title'       => 'Sesi Halaman Berakhir',
                'subtitle'    => 'Halaman telah melewati batas waktu keaktifan demi keamanan data.',
                'solution'    => 'Silakan muat ulang halaman untuk memperbarui formulir.',
                'can_reload'  => true,
            ],
            429 => [
                'code'        => 429,
                'tag'         => 'Batas Permintaan',
                'title'       => 'Terlalu Banyak Permintaan',
                'subtitle'    => 'Sistem menerima terlalu banyak aksi dalam waktu singkat.',
                'solution'    => 'Mohon tunggu beberapa detik sebelum mencoba kembali.',
                'can_reload'  => true,
            ],
            500 => [
                'code'        => 500,
                'tag'         => 'Kendala Server',
                'title'       => 'Terjadi Kendala pada Sistem',
                'subtitle'    => 'Terjadi gangguan internal saat memproses permintaan Anda.',
                'solution'    => 'Silakan coba muat ulang halaman dalam beberapa saat.',
                'can_reload'  => true,
            ],
            503 => [
                'code'        => 503,
                'tag'         => 'Pemeliharaan',
                'title'       => 'Sistem Sedang Pemeliharaan',
                'subtitle'    => 'Layanan sedang dalam proses peningkatan dan perawatan berkala.',
                'solution'    => 'Layanan akan segera kembali normal dalam beberapa saat.',
            ],
        ];

        $cfg = $errorConfigs[$code] ?? [
            'code'        => $code,
            'tag'         => 'Pemberitahuan',
            'title'       => 'Terjadi Kendala',
            'subtitle'    => 'Sistem tidak dapat memproses permintaan ini saat ini.',
            'solution'    => 'Silakan kembali ke halaman utama.',
        ];

        $displayTitle = $cfg['title'];
        $displaySubtitle = $cfg['subtitle'];
    @endphp

    @if($csrfToken)
    <meta name="csrf-token" content="{{ $csrfToken }}">
    @endif
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>{{ $code }} | {{ config('app.name', 'Inventaris WBI') }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@500;600;700;800&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --font-display: 'Hanken Grotesk', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            --font-body: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
        }

        body {
            font-family: var(--font-body);
            background-color: #FFFFFF;
            color: #1a1c1b;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            -webkit-font-smoothing: antialiased;
        }

        .font-display { font-family: var(--font-display); }
        .font-mono { font-family: var(--font-mono); }
    </style>
</head>

<body class="bg-white min-h-screen flex flex-col justify-between">

    <!-- Header / Brand Top Bar -->
    <header class="w-full max-w-5xl mx-auto px-4 sm:px-6 py-6 flex items-center justify-between border-b border-border-light">
        <a href="{{ $homeUrl }}" class="flex items-center gap-3 text-decoration-none">
            <div class="h-9 w-9 rounded-lg bg-white border border-border-light p-1.5 flex items-center justify-center shadow-2xs">
                <img src="{{ asset('images/logo.png') }}" alt="WBI Logo" class="h-full w-auto object-contain">
            </div>
            <div>
                <span class="font-display font-bold text-sm sm:text-base text-on-surface leading-none block">WBI Inventaris</span>
                <span class="text-[10px] sm:text-[11px] text-on-surface-variant leading-none mt-0.5 block">Politeknik Wilmar Bisnis Indonesia</span>
            </div>
        </a>

        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-surface-container border border-border-light text-xs font-mono text-on-surface-variant font-medium">
            <span>HTTP {{ $code }}</span>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="flex-1 flex items-center justify-center px-4 sm:px-6 py-12">
        <div class="w-full max-w-2xl text-center space-y-6">

            <!-- Error Code & Icon Badge -->
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-surface-container border border-border-light text-primary mx-auto shadow-2xs">
                @if($code === 404)
                    <svg class="w-10 h-10 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 4h.01"/>
                    </svg>
                @elseif($code === 403 || $code === 401)
                    <svg class="w-10 h-10 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                @elseif($code >= 500)
                    <svg class="w-10 h-10 text-rose-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                @else
                    <svg class="w-10 h-10 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                @endif
            </div>

            <!-- Big Code & Heading -->
            <div class="space-y-2">
                <p class="font-mono text-xs uppercase tracking-widest font-semibold text-primary">
                    {{ $cfg['tag'] }}
                </p>
                <h1 class="font-display text-2xl sm:text-3xl font-bold text-on-surface">
                    {{ $displayTitle }}
                </h1>
                <p class="text-sm sm:text-base text-on-surface-variant max-w-lg mx-auto leading-relaxed">
                    {{ $displaySubtitle }}
                </p>
            </div>

            <!-- Solution Note (Minimal) -->
            <p class="text-xs text-on-surface-variant/80 max-w-md mx-auto">
                {{ $cfg['solution'] }}
            </p>

            <!-- Action Buttons -->
            <div class="flex flex-wrap items-center justify-center gap-3 pt-2">
                @if($code === 401)
                    <a href="{{ $loginUrl }}" 
                       class="px-5 py-2.5 rounded-lg bg-primary text-white text-xs sm:text-sm font-semibold hover:bg-primary-light transition-all shadow-xs inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        <span>Masuk ke Akun</span>
                    </a>
                @else
                    <a href="{{ $homeUrl }}" 
                       class="px-5 py-2.5 rounded-lg bg-primary text-white text-xs sm:text-sm font-semibold hover:bg-primary-light transition-all shadow-xs inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>{{ $isAuth ? 'Ke Dashboard' : 'Halaman Utama' }}</span>
                    </a>
                @endif

                <button type="button" 
                        onclick="window.history.length > 1 ? window.history.back() : window.location.href='{{ $homeUrl }}'" 
                        class="px-5 py-2.5 rounded-lg bg-surface-white border border-border-light text-on-surface text-xs sm:text-sm font-medium hover:bg-surface-container transition-colors shadow-2xs inline-flex items-center gap-2">
                    <svg class="w-4 h-4 text-on-surface-variant" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Kembali</span>
                </button>

                @if(!empty($cfg['can_reload']))
                    <button type="button" 
                            onclick="window.location.reload()" 
                            class="px-4 py-2.5 rounded-lg bg-surface-white border border-border-light text-on-surface-variant hover:text-on-surface hover:bg-surface-container transition-colors shadow-2xs inline-flex items-center gap-1.5 text-xs font-medium" 
                            title="Muat Ulang Halaman">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <span>Muat Ulang</span>
                    </button>
                @endif
            </div>

        </div>
    </main>

    <!-- Clean Minimal Footer -->
    <footer class="w-full text-center py-4 border-t border-border-light text-xs text-on-surface-variant">
        &copy; {{ date('Y') }} Inventaris WBI
    </footer>

</body>
</html>
