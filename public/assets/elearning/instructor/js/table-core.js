/**
 * Instructor Table Core - Shared utilities for table management
 * Clean architecture with Store, View, and utility functions
 */

// ==================== UTILITIES ====================

const TableUtils = {
    /**
     * Debounce function execution
     */
    debounce(fn, delay = 300) {
        let timeoutId;
        return (...args) => {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => fn.apply(this, args), delay);
        };
    },

    /**
     * Escape HTML to prevent XSS
     */
    escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    },

    /**
     * Format date to Indonesian locale
     */
    formatDate(dateString) {
        if (!dateString) return '-';
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
    },

    /**
     * Format relative time
     */
    formatTimeAgo(dateString) {
        if (!dateString) return '';
        const date = new Date(dateString);
        const now = new Date();
        const diffMs = now - date;
        const diffDays = Math.floor(diffMs / (1000 * 60 * 60 * 24));

        if (diffDays === 0) return 'Hari ini';
        if (diffDays === 1) return 'Kemarin';
        if (diffDays < 7) return `${diffDays} hari lalu`;
        if (diffDays < 30) return `${Math.floor(diffDays / 7)} minggu lalu`;
        if (diffDays < 365) return `${Math.floor(diffDays / 30)} bulan lalu`;
        return `${Math.floor(diffDays / 365)} tahun lalu`;
    },

    /**
     * Highlight search term in text
     */
    highlightSearch(text, query) {
        if (!query || !query.trim() || !text) return this.escapeHtml(text);
        const escaped = this.escapeHtml(text);
        const regex = new RegExp(`(${query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
        return escaped.replace(regex, '<mark class="search-highlight">$1</mark>');
    },

    /**
     * Calculate visible page numbers for pagination
     */
    getVisiblePages(currentPage, totalPages, delta = 2) {
        const range = [];
        const rangeWithDots = [];

        for (let i = Math.max(2, currentPage - delta); i <= Math.min(totalPages - 1, currentPage + delta); i++) {
            range.push(i);
        }

        if (currentPage - delta > 2) {
            rangeWithDots.push(1, '...');
        } else {
            rangeWithDots.push(1);
        }

        rangeWithDots.push(...range);

        if (currentPage + delta < totalPages - 1) {
            rangeWithDots.push('...', totalPages);
        } else if (totalPages > 1) {
            rangeWithDots.push(totalPages);
        }

        return [...new Set(rangeWithDots)];
    }
};

// ==================== BASE STORE ====================

class TableStore {
    constructor(initialData = []) {
        this.allData = initialData;
        this.filteredData = [];
        this.paginatedData = [];
        this.searchQuery = '';
        this.sortColumn = 'created_at';
        this.sortDirection = 'desc';
        this.perPage = 10;
        this.currentPage = 1;
        this.totalPages = 1;
        this.listeners = [];
    }

    subscribe(listener) {
        this.listeners.push(listener);
        return () => {
            this.listeners = this.listeners.filter(l => l !== listener);
        };
    }

    notify() {
        this.listeners.forEach(listener => listener(this.getState()));
    }

    getState() {
        return {
            allData: this.allData,
            filteredData: this.filteredData,
            paginatedData: this.paginatedData,
            searchQuery: this.searchQuery,
            sortColumn: this.sortColumn,
            sortDirection: this.sortDirection,
            perPage: this.perPage,
            currentPage: this.currentPage,
            totalPages: this.totalPages,
        };
    }

    setSearch(query) {
        this.searchQuery = query;
        this.currentPage = 1;
        this.applyFilters();
    }

    setSort(column) {
        if (this.sortColumn === column) {
            this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            this.sortColumn = column;
            this.sortDirection = 'asc';
        }
        this.applyFilters();
    }

    setPerPage(value) {
        this.perPage = parseInt(value, 10);
        this.currentPage = 1;
        this.applyFilters();
    }

    goToPage(page) {
        if (page >= 1 && page <= this.totalPages) {
            this.currentPage = page;
            this.updatePagination();
            this.notify();
        }
    }

    // Override in subclass
    applyFilters() {
        this.notify();
    }

    updatePagination() {
        const start = (this.currentPage - 1) * this.perPage;
        const end = start + this.perPage;
        this.paginatedData = this.filteredData.slice(start, end);
    }

    sortData(data) {
        return [...data].sort((a, b) => {
            let aVal = a[this.sortColumn] || '';
            let bVal = b[this.sortColumn] || '';

            if (this.sortColumn === 'created_at') {
                aVal = new Date(aVal);
                bVal = new Date(bVal);
            } else {
                aVal = aVal.toString().toLowerCase();
                bVal = bVal.toString().toLowerCase();
            }

            if (this.sortDirection === 'asc') {
                return aVal > bVal ? 1 : -1;
            }
            return aVal < bVal ? 1 : -1;
        });
    }
}

// ==================== MODAL CONTROLLER ====================

class DeleteModalController {
    constructor(modalId, formId, titleId) {
        this.modal = document.getElementById(modalId);
        this.form = document.getElementById(formId);
        this.titleEl = document.getElementById(titleId);
        this.target = null;
        this.baseUrl = '';

        this.bindEvents();
    }

    setBaseUrl(url) {
        this.baseUrl = url;
    }

    bindEvents() {
        // Close on backdrop click
        this.modal?.addEventListener('click', (e) => {
            if (e.target === this.modal) {
                this.close();
            }
        });

        // Close button
        this.modal?.querySelector('[data-action="cancel"]')?.addEventListener('click', () => {
            this.close();
        });

        // Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !this.modal?.classList.contains('hidden')) {
                this.close();
            }
        });
    }

    open(item) {
        this.target = item;
        if (this.titleEl) {
            this.titleEl.textContent = item.title || '';
        }
        if (this.form) {
            this.form.action = `${this.baseUrl}/${item.id}`;
        }
        this.modal?.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    close() {
        this.target = null;
        this.modal?.classList.add('hidden');
        document.body.style.overflow = '';
    }
}

// Export for use in other modules
window.TableUtils = TableUtils;
window.TableStore = TableStore;
window.DeleteModalController = DeleteModalController;
