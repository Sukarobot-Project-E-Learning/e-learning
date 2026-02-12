/**
 * Instructor Programs Index - Vanilla JS Module
 * Handles search, filter, sort, pagination, and delete modal
 */

(function() {
    'use strict';

    // ==================== PROGRAM STORE ====================
    
    class ProgramStore extends TableStore {
        constructor(data) {
            super(data);
            this.statusFilter = 'all';
            this.stats = { pending: 0, approved: 0, rejected: 0 };
            this.calculateStats();
            this.applyFilters();
        }

        calculateStats() {
            this.stats = {
                pending: this.allData.filter(item => item.status === 'pending').length,
                approved: this.allData.filter(item => item.status === 'approved').length,
                rejected: this.allData.filter(item => item.status === 'rejected').length,
            };
        }

        setStatusFilter(status) {
            this.statusFilter = status;
            this.currentPage = 1;
            this.applyFilters();
        }

        resetFilters() {
            this.searchQuery = '';
            this.statusFilter = 'all';
            this.currentPage = 1;
            this.applyFilters();
        }

        getState() {
            return {
                ...super.getState(),
                statusFilter: this.statusFilter,
                stats: this.stats,
            };
        }

        applyFilters() {
            let result = [...this.allData];

            // Search filter
            if (this.searchQuery.trim()) {
                const query = this.searchQuery.toLowerCase().trim();
                result = result.filter(item => 
                    (item.title && item.title.toLowerCase().includes(query)) ||
                    (item.category && item.category.toLowerCase().includes(query)) ||
                    (item.type && item.type.toLowerCase().includes(query))
                );
            }

            // Status filter
            if (this.statusFilter !== 'all') {
                result = result.filter(item => item.status === this.statusFilter);
            }

            // Sort
            this.filteredData = this.sortData(result);
            this.totalPages = Math.ceil(this.filteredData.length / this.perPage) || 1;

            if (this.currentPage > this.totalPages) {
                this.currentPage = 1;
            }

            this.updatePagination();
            this.notify();
        }
    }

    // ==================== VIEW RENDERER ====================

    const ProgramView = {
        elements: {},

        init() {
            this.elements = {
                // Stats
                statPending: document.getElementById('stat-pending'),
                statApproved: document.getElementById('stat-approved'),
                statRejected: document.getElementById('stat-rejected'),
                // Search & Filter
                searchInput: document.getElementById('search-input'),
                searchClear: document.getElementById('search-clear'),
                filterButtons: document.querySelectorAll('[data-filter]'),
                perPageSelect: document.getElementById('per-page-select'),
                resetFilterBtn: document.getElementById('reset-filter-btn'),
                activeFiltersContainer: document.getElementById('active-filters'),
                // Results info
                resultsShowing: document.getElementById('results-showing'),
                resultsTotal: document.getElementById('results-total'),
                searchTermDisplay: document.getElementById('search-term-display'),
                // Table
                desktopTbody: document.getElementById('desktop-tbody'),
                mobileList: document.getElementById('mobile-list'),
                emptyStateDesktop: document.getElementById('empty-state-desktop'),
                emptyStateMobile: document.getElementById('empty-state-mobile'),
                // Pagination
                paginationContainer: document.getElementById('pagination-container'),
                paginationInfo: document.getElementById('pagination-info'),
                paginationButtons: document.getElementById('pagination-buttons'),
                // Sort buttons
                sortButtons: document.querySelectorAll('[data-sort]'),
            };
        },

        render(state) {
            this.renderStats(state.stats);
            this.renderSearchState(state);
            this.renderFilterButtons(state.statusFilter);
            this.renderResultsInfo(state);
            this.renderTable(state);
            this.renderMobileList(state);
            this.renderPagination(state);
            this.renderSortIndicators(state);
        },

        renderStats(stats) {
            if (this.elements.statPending) this.elements.statPending.textContent = stats.pending;
            if (this.elements.statApproved) this.elements.statApproved.textContent = stats.approved;
            if (this.elements.statRejected) this.elements.statRejected.textContent = stats.rejected;
        },

        renderSearchState(state) {
            if (this.elements.searchClear) {
                this.elements.searchClear.classList.toggle('hidden', !state.searchQuery);
            }
            if (this.elements.activeFiltersContainer) {
                const hasFilters = state.searchQuery || state.statusFilter !== 'all';
                this.elements.activeFiltersContainer.classList.toggle('hidden', !hasFilters);
            }
            if (this.elements.searchTermDisplay) {
                this.elements.searchTermDisplay.classList.toggle('hidden', !state.searchQuery);
                const termEl = this.elements.searchTermDisplay.querySelector('.search-term');
                if (termEl) termEl.textContent = state.searchQuery;
            }
        },

        renderFilterButtons(activeFilter) {
            this.elements.filterButtons.forEach(btn => {
                const filter = btn.dataset.filter;
                const isActive = filter === activeFilter;
                
                // Remove all state classes
                btn.classList.remove('bg-blue-600', 'text-white', 'border-blue-600',
                    'bg-yellow-500', 'border-yellow-500', 'bg-green-500', 'border-green-500',
                    'bg-red-500', 'border-red-500', 'bg-white', 'text-gray-600', 'border-gray-200');
                
                if (isActive) {
                    const colors = {
                        all: ['bg-blue-600', 'text-white', 'border-blue-600'],
                        pending: ['bg-yellow-500', 'text-white', 'border-yellow-500'],
                        approved: ['bg-green-500', 'text-white', 'border-green-500'],
                        rejected: ['bg-red-500', 'text-white', 'border-red-500'],
                    };
                    btn.classList.add(...(colors[filter] || colors.all));
                } else {
                    btn.classList.add('bg-white', 'text-gray-600', 'border-gray-200');
                }
            });
        },

        renderResultsInfo(state) {
            if (this.elements.resultsShowing) {
                this.elements.resultsShowing.textContent = state.paginatedData.length;
            }
            if (this.elements.resultsTotal) {
                this.elements.resultsTotal.textContent = state.filteredData.length;
            }
        },

        renderTable(state) {
            const tbody = this.elements.desktopTbody;
            if (!tbody) return;

            if (state.paginatedData.length === 0) {
                tbody.innerHTML = '';
                if (this.elements.emptyStateDesktop) {
                    this.elements.emptyStateDesktop.classList.remove('hidden');
                    const title = this.elements.emptyStateDesktop.querySelector('.empty-title');
                    const desc = this.elements.emptyStateDesktop.querySelector('.empty-desc');
                    const createBtn = this.elements.emptyStateDesktop.querySelector('.empty-create-btn');
                    
                    if (state.searchQuery) {
                        if (title) title.textContent = 'Tidak ada hasil ditemukan';
                        if (desc) desc.textContent = 'Coba kata kunci lain atau hapus filter';
                        if (createBtn) createBtn.classList.add('hidden');
                    } else {
                        if (title) title.textContent = 'Belum ada pengajuan program';
                        if (desc) desc.textContent = 'Mulai dengan mengajukan program pertama Anda';
                        if (createBtn) createBtn.classList.remove('hidden');
                    }
                }
                return;
            }

            if (this.elements.emptyStateDesktop) {
                this.elements.emptyStateDesktop.classList.add('hidden');
            }

            tbody.innerHTML = state.paginatedData.map(item => this.renderTableRow(item, state.searchQuery)).join('');
        },

        renderTableRow(item, searchQuery) {
            const statusClasses = {
                pending: 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                approved: 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                rejected: 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
            };
            const statusDotClasses = { pending: 'bg-yellow-500', approved: 'bg-green-500', rejected: 'bg-red-500' };
            const statusLabels = { pending: 'Menunggu', approved: 'Disetujui', rejected: 'Ditolak' };
            const typeClasses = {
                online: 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                offline: 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
                video: 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
            };

            const title = TableUtils.highlightSearch(item.title || '', searchQuery);
            const category = TableUtils.highlightSearch(item.category || '-', searchQuery);
            const typeLabel = item.type ? item.type.charAt(0).toUpperCase() + item.type.slice(1) : '-';

            const actionsHtml = item.status === 'approved' 
                ? `<a href="/instructor/programs/${item.id}" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    Lihat
                   </a>`
                : `<div class="flex items-center gap-2">
                    <a href="/instructor/programs/${item.id}/edit" data-turbo="false" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-emerald-600 bg-emerald-50 rounded-lg hover:bg-emerald-100 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit
                    </a>
                    <button data-delete='${JSON.stringify({id: item.id, title: item.title})}' class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Hapus
                    </button>
                   </div>`;

            return `
            <tr class="hover:bg-blue-50/50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 w-10 h-10 rounded-lg overflow-hidden flex items-center justify-center bg-gradient-to-br from-blue-500 to-blue-600">
                            ${item.image ? `<img src="/storage/${item.image}" alt="${TableUtils.escapeHtml(item.title)}" class="w-full h-full object-cover">` : `<span class="text-sm font-bold text-white">${TableUtils.escapeHtml((item.title || 'P').charAt(0).toUpperCase())}</span>`}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="font-semibold text-gray-900 dark:text-white truncate">${title}</p>
                            ${item.rejection_reason ? `<p class="text-xs text-red-500 mt-1 truncate">Alasan: ${TableUtils.escapeHtml(item.rejection_reason)}</p>` : ''}
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4"><span class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-gray-700 bg-gray-100 rounded-lg dark:bg-gray-700 dark:text-gray-300">${category}</span></td>
                <td class="px-6 py-4"><span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg ${typeClasses[item.type] || typeClasses.online}">${TableUtils.escapeHtml(typeLabel)}</span></td>
                <td class="px-6 py-4">
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        <p>${TableUtils.formatDate(item.created_at)}</p>
                        <p class="text-xs text-gray-400">${TableUtils.formatTimeAgo(item.created_at)}</p>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <span class="status-badge inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-full ${statusClasses[item.status] || statusClasses.pending}">
                        <span class="w-1.5 h-1.5 rounded-full ${statusDotClasses[item.status] || statusDotClasses.pending}"></span>
                        ${statusLabels[item.status] || 'Unknown'}
                    </span>
                </td>
                <td class="px-6 py-4"><div class="flex items-center justify-center gap-2">${actionsHtml}</div></td>
            </tr>`;
        },

        renderMobileList(state) {
            const container = this.elements.mobileList;
            if (!container) return;

            if (state.paginatedData.length === 0) {
                container.innerHTML = '';
                if (this.elements.emptyStateMobile) {
                    this.elements.emptyStateMobile.classList.remove('hidden');
                }
                return;
            }

            if (this.elements.emptyStateMobile) {
                this.elements.emptyStateMobile.classList.add('hidden');
            }

            container.innerHTML = state.paginatedData.map(item => this.renderMobileCard(item, state.searchQuery)).join('');
        },

        renderMobileCard(item, searchQuery) {
            const statusClasses = { pending: 'bg-yellow-100 text-yellow-700', approved: 'bg-green-100 text-green-700', rejected: 'bg-red-100 text-red-700' };
            const statusDotClasses = { pending: 'bg-yellow-500', approved: 'bg-green-500', rejected: 'bg-red-500' };
            const statusLabels = { pending: 'Menunggu', approved: 'Disetujui', rejected: 'Ditolak' };
            const title = TableUtils.highlightSearch(item.title || '', searchQuery);
            const category = TableUtils.highlightSearch(item.category || '-', searchQuery);

            const actionsHtml = item.status === 'approved'
                ? `<a href="/instructor/programs/${item.id}" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-blue-600 bg-blue-50 rounded-xl hover:bg-blue-100 transition-colors">Lihat Detail</a>`
                : `<a href="/instructor/programs/${item.id}/edit" data-turbo="false" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-emerald-600 bg-emerald-50 rounded-xl hover:bg-emerald-100 transition-colors">Edit</a>
                   <button data-delete='${JSON.stringify({id: item.id, title: item.title})}' class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-red-600 bg-red-50 rounded-xl hover:bg-red-100 transition-colors">Hapus</button>`;

            return `
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden p-4">
                <div class="flex items-start justify-between gap-3 mb-3">
                    <div class="flex items-center gap-3 min-w-0 flex-1">
                        <div class="flex-shrink-0 w-12 h-12 rounded-xl overflow-hidden flex items-center justify-center bg-gradient-to-br from-blue-500 to-blue-600 shadow-lg">
                            ${item.image ? `<img src="/storage/${item.image}" alt="${TableUtils.escapeHtml(item.title)}" class="w-full h-full object-cover">` : `<span class="text-lg font-bold text-white">${TableUtils.escapeHtml((item.title || 'P').charAt(0).toUpperCase())}</span>`}
                        </div>
                        <div class="min-w-0 flex-1">
                            <h3 class="font-semibold text-gray-900 dark:text-white truncate">${title}</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">${TableUtils.formatDate(item.created_at)} • ${TableUtils.formatTimeAgo(item.created_at)}</p>
                        </div>
                    </div>
                    <span class="status-badge flex-shrink-0 inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full ${statusClasses[item.status] || statusClasses.pending}">
                        <span class="w-1.5 h-1.5 rounded-full ${statusDotClasses[item.status] || statusDotClasses.pending}"></span>
                        ${statusLabels[item.status] || 'Unknown'}
                    </span>
                </div>
                ${item.rejection_reason ? `<div class="mb-3 p-2.5 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-100"><p class="text-xs text-red-600">Alasan ditolak: ${TableUtils.escapeHtml(item.rejection_reason)}</p></div>` : ''}
                <div class="flex flex-wrap items-center gap-2 mb-4">
                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-gray-600 bg-gray-100 rounded-lg">${category}</span>
                </div>
                <div class="flex items-center gap-2 pt-3 border-t border-gray-100 dark:border-gray-700">${actionsHtml}</div>
            </div>`;
        },

        renderPagination(state) {
            const container = this.elements.paginationContainer;
            if (!container) return;

            container.classList.toggle('hidden', state.totalPages <= 1);

            if (this.elements.paginationInfo) {
                this.elements.paginationInfo.innerHTML = `Halaman <span class="font-semibold text-blue-600">${state.currentPage}</span> dari <span class="font-semibold">${state.totalPages}</span>`;
            }

            if (this.elements.paginationButtons) {
                const pages = TableUtils.getVisiblePages(state.currentPage, state.totalPages);
                this.elements.paginationButtons.innerHTML = `
                    <button data-page="1" ${state.currentPage === 1 ? 'disabled' : ''} class="pagination-btn p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg disabled:opacity-40 disabled:cursor-not-allowed transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/></svg>
                    </button>
                    <button data-page="${state.currentPage - 1}" ${state.currentPage === 1 ? 'disabled' : ''} class="pagination-btn p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg disabled:opacity-40 disabled:cursor-not-allowed transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    ${pages.map(page => page === '...' 
                        ? `<span class="w-10 h-10 flex items-center justify-center text-gray-400">...</span>`
                        : `<button data-page="${page}" class="pagination-btn min-w-[40px] h-10 text-sm font-medium rounded-lg transition-all duration-200 ${page === state.currentPage ? 'bg-blue-600 text-white shadow-lg' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-600'}">${page}</button>`
                    ).join('')}
                    <button data-page="${state.currentPage + 1}" ${state.currentPage === state.totalPages ? 'disabled' : ''} class="pagination-btn p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg disabled:opacity-40 disabled:cursor-not-allowed transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                    <button data-page="${state.totalPages}" ${state.currentPage === state.totalPages ? 'disabled' : ''} class="pagination-btn p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg disabled:opacity-40 disabled:cursor-not-allowed transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/></svg>
                    </button>
                `;
            }
        },

        renderSortIndicators(state) {
            this.elements.sortButtons.forEach(btn => {
                const column = btn.dataset.sort;
                const icon = btn.querySelector('.sort-icon');
                if (icon) {
                    icon.classList.toggle('rotate-180', state.sortColumn === column && state.sortDirection === 'asc');
                }
            });
        }
    };

    // ==================== CONTROLLER ====================

    class ProgramController {
        constructor() {
            this.store = null;
            this.deleteModal = null;
        }

        init() {
            // Load data from JSON script tag
            const dataEl = document.getElementById('programSubmissionsData');
            if (!dataEl) {
                console.error('Program submissions data not found');
                return;
            }

            let data = [];
            try {
                data = JSON.parse(dataEl.textContent);
            } catch (e) {
                console.error('Failed to parse program data:', e);
            }

            // Initialize store
            this.store = new ProgramStore(data);

            // Initialize view
            ProgramView.init();

            // Initialize delete modal
            this.deleteModal = new DeleteModalController('delete-modal', 'delete-form', 'delete-target-title');
            this.deleteModal.setBaseUrl('/instructor/programs');

            // Subscribe view to store updates
            this.store.subscribe(state => ProgramView.render(state));

            // Bind events
            this.bindEvents();

            // Initial render
            ProgramView.render(this.store.getState());
        }

        bindEvents() {
            const { searchInput, searchClear, filterButtons, perPageSelect, resetFilterBtn, sortButtons } = ProgramView.elements;

            // Search with debounce
            searchInput?.addEventListener('input', TableUtils.debounce(e => {
                this.store.setSearch(e.target.value);
            }, 300));

            // Clear search
            searchClear?.addEventListener('click', () => {
                if (searchInput) searchInput.value = '';
                this.store.setSearch('');
            });

            // Filter buttons
            filterButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    this.store.setStatusFilter(btn.dataset.filter);
                });
            });

            // Per page select
            perPageSelect?.addEventListener('change', e => {
                this.store.setPerPage(e.target.value);
            });

            // Reset filters
            resetFilterBtn?.addEventListener('click', () => {
                if (searchInput) searchInput.value = '';
                this.store.resetFilters();
            });

            // Sort buttons
            sortButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    this.store.setSort(btn.dataset.sort);
                });
            });

            // Pagination (delegated)
            ProgramView.elements.paginationButtons?.addEventListener('click', e => {
                const btn = e.target.closest('[data-page]');
                if (btn && !btn.disabled) {
                    this.store.goToPage(parseInt(btn.dataset.page, 10));
                }
            });

            // Delete buttons (delegated)
            document.addEventListener('click', e => {
                const btn = e.target.closest('[data-delete]');
                if (btn) {
                    try {
                        const item = JSON.parse(btn.dataset.delete);
                        this.deleteModal.open(item);
                    } catch (err) {
                        console.error('Failed to parse delete data:', err);
                    }
                }
            });
        }
    }

    // ==================== INITIALIZE ====================

    document.addEventListener('DOMContentLoaded', () => {
        const controller = new ProgramController();
        controller.init();
    });

})();
