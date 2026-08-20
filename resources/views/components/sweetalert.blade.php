{{-- Global SweetAlert2 Flash Message Handler --}}
@if (session('success') || session('error') || session('warning') || session('info') || session('status'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if (session('success'))
            if (typeof window.showToast === 'function') {
                window.showToast(@json(session('success')), 'success');
            }
        @endif

        @if (session('error'))
            if (typeof window.showError === 'function') {
                window.showError('Gagal', @json(session('error')));
            }
        @endif

        @if (session('warning'))
            if (typeof window.showWarning === 'function') {
                window.showWarning('Perhatian', @json(session('warning')));
            }
        @endif

        @if (session('info'))
            if (typeof window.showInfo === 'function') {
                window.showInfo('Informasi', @json(session('info')));
            }
        @endif

        @if (session('status'))
            if (typeof window.showToast === 'function') {
                window.showToast(@json(session('status')), 'info');
            }
        @endif
    });
</script>
@endif
