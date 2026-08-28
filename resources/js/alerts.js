import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

// Custom WBI Mixin / Theme Defaults matching DESIGN.md
const WbiSwal = Swal.mixin({
    showDenyButton: false,
    customClass: {
        popup: 'wbi-swal-popup',
        title: 'wbi-swal-title',
        htmlContainer: 'wbi-swal-text',
        confirmButton: 'wbi-swal-btn wbi-swal-btn-primary',
        cancelButton: 'wbi-swal-btn wbi-swal-btn-cancel',
        denyButton: 'wbi-swal-btn wbi-swal-btn-danger',
    },
    buttonsStyling: false,
});

// Toast Mixin for notifications
const WbiToast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3500,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
    },
    customClass: {
        popup: 'wbi-toast-popup',
        title: 'wbi-toast-title',
    }
});

// Global objects
window.Swal = Swal;
window.WbiSwal = WbiSwal;
window.WbiToast = WbiToast;

/**
 * Show a quick toast notification
 * @param {string} title
 * @param {'success'|'error'|'warning'|'info'|'question'} icon
 */
window.showToast = function(title, icon = 'success') {
    return WbiToast.fire({
        icon: icon,
        title: title,
    });
};

/**
 * Generic Alert popup
 * @param {object} options
 */
window.showAlert = function(options) {
    return WbiSwal.fire(options);
};

/**
 * Success Alert popup
 * @param {string} title
 * @param {string} text
 */
window.showSuccess = function(title, text = '') {
    return WbiSwal.fire({
        icon: 'success',
        title: title,
        text: text,
        confirmButtonText: 'Tutup',
    });
};

/**
 * Error Alert popup
 * @param {string} title
 * @param {string} text
 */
window.showError = function(title, text = '') {
    return WbiSwal.fire({
        icon: 'error',
        title: title,
        text: text,
        confirmButtonText: 'Mengerti',
    });
};

/**
 * Warning Alert popup
 * @param {string} title
 * @param {string} text
 */
window.showWarning = function(title, text = '') {
    return WbiSwal.fire({
        icon: 'warning',
        title: title,
        text: text,
        confirmButtonText: 'Mengerti',
    });
};

/**
 * Info Alert popup
 * @param {string} title
 * @param {string} text
 */
window.showInfo = function(title, text = '') {
    return WbiSwal.fire({
        icon: 'info',
        title: title,
        text: text,
        confirmButtonText: 'Tutup',
    });
};

/**
 * Confirmation dialog popup
 * @param {object} param0
 * @returns {Promise<boolean>}
 */
window.confirmAction = function({
    title = 'Apakah Anda yakin?',
    text = 'Tindakan ini tidak dapat dibatalkan.',
    icon = 'warning',
    confirmButtonText = 'Ya, Lanjutkan',
    cancelButtonText = 'Batal',
    isDanger = false,
} = {}) {
    return WbiSwal.fire({
        title,
        text,
        icon,
        showCancelButton: true,
        showDenyButton: false,
        confirmButtonText,
        cancelButtonText,
        reverseButtons: true,
        customClass: {
            popup: 'wbi-swal-popup',
            title: 'wbi-swal-title',
            htmlContainer: 'wbi-swal-text',
            confirmButton: `wbi-swal-btn ${isDanger ? 'wbi-swal-btn-danger' : 'wbi-swal-btn-primary'}`,
            cancelButton: 'wbi-swal-btn wbi-swal-btn-cancel',
            denyButton: 'wbi-swal-btn wbi-swal-btn-danger',
        }
    }).then((result) => result.isConfirmed);
};

// Automatic listener for data-confirm and data-confirm-delete
document.addEventListener('DOMContentLoaded', () => {
    document.addEventListener('click', (e) => {
        const target = e.target.closest('[data-confirm], [data-confirm-delete]');
        if (!target) return;

        e.preventDefault();
        const isDelete = target.hasAttribute('data-confirm-delete');
        const message = target.getAttribute('data-confirm') || (isDelete ? 'Data yang dihapus tidak dapat dikembalikan!' : 'Apakah Anda yakin ingin melanjutkan tindakan ini?');
        const title = target.getAttribute('data-confirm-title') || (isDelete ? 'Konfirmasi Hapus' : 'Konfirmasi Tindakan');
        const confirmBtn = target.getAttribute('data-confirm-btn') || (isDelete ? 'Ya, Hapus' : 'Ya, Lanjutkan');

        window.confirmAction({
            title,
            text: message,
            icon: isDelete ? 'error' : 'warning',
            confirmButtonText: confirmBtn,
            cancelButtonText: 'Batal',
            isDanger: isDelete,
        }).then((confirmed) => {
            if (confirmed) {
                if (target.tagName === 'FORM') {
                    target.submit();
                } else if (target.form) {
                    target.form.submit();
                } else if (target.tagName === 'A' && target.href) {
                    window.location.href = target.href;
                }
            }
        });
    });
});
