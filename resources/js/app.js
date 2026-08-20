// WBI Inventaris — Main Application JS
import './alerts.js';

// Format currency to Indonesian Rupiah format
window.formatRupiah = function(angka) {
    const number = parseInt(angka.toString().replace(/[^,\d]/g, ''), 10);
    if (isNaN(number)) return '0';
    return number.toLocaleString('id-ID');
};
