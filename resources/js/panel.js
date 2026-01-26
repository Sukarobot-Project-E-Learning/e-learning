/**
 * Panel JS - Minimal (CDNs loaded in layout)
 * 
 * Alpine.js, Turbo, ApexCharts, and SweetAlert2 are loaded via CDN in app.blade.php
 * This file only contains custom panel-specific JavaScript if needed.
 */

// Service Worker Registration (Production only)
if ('serviceWorker' in navigator && window.location.hostname !== 'localhost') {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js').catch(err => {
            console.log('ServiceWorker registration failed: ', err);
        });
    });
}

// Global helper functions
window.PanelHelpers = {
    // Format currency in Indonesian Rupiah
    formatCurrency: function (amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(amount);
    },

    // Format date to Indonesian locale
    formatDate: function (dateString) {
        if (!dateString) return '-';
        return new Date(dateString).toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'short',
            year: 'numeric'
        });
    },

    // Debounce function
    debounce: function (func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
};