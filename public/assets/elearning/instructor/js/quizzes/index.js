/**
 * Instructor Quizzes Index - Vanilla JS Module
 * Handles search, sort, pagination, and delete modal
 */

(function() {
    'use strict';

    // ==================== QUIZ STORE ====================
    
    class QuizStore extends TableStore {
        constructor(data) {
            super(data);
            this.stats = { published: 0, draft: 0, total: 0 };
            this.calculateStats();
            this.applyFilters();
        }

        calculateStats() {
            this.stats = {
                published: this.allData.filter(item => item.status === 'published').length,
                draft: this.allData.filter(item => item.status === 'draft').length,
                total: this.allData.length,
            };
        }

        resetFilters() {
            this.searchQuery = '';
            this.currentPage = 1;
            this.applyFilters();
        }

        getState() {
            return {
                ...super.getState(),
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
                    (item.program && item.program.toLowerCase().includes(query))
                );
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

    const QuizView = {
        elements: {},

        init() {
            this.elements = {
                // Stats
                statPublished: document.getElementById('stat-published'),
                statTotal: document.getElementById('stat-total'),
                // Search
                searchInput: document.getElementById('search-input'),
                searchClear: document.getElementById('search-clear'),
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
            this.renderResultsInfo(state);
            this.renderTable(state);
            this.renderMobileList(state);
            this.renderPagination(state);
            this.renderSortIndicators(state);
        },

        renderStats(stats) {
            if (this.elements.statPublished) this.elements.statPublished.textContent = stats.published;
            if (this.elements.statTotal) this.elements.statTotal.textContent = stats.total;
        },

        renderSearchState(state) {
            if (this.elements.searchClear) {
                this.elements.searchClear.classList.toggle('hidden', !state.searchQuery);
            }
            if (this.elements.activeFiltersContainer) {
                this.elements.activeFiltersContainer.classList.toggle('hidden', !state.searchQuery);
            }
            if (this.elements.searchTermDisplay) {
                this.elements.searchTermDisplay.classList.toggle('hidden', !state.searchQuery);
                const termEl = this.elements.searchTermDisplay.querySelector('.search-term');
                if (termEl) termEl.textContent = state.searchQuery;
            }
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
                        if (desc) desc.textContent = 'Coba kata kunci lain';
                        if (createBtn) createBtn.classList.add('hidden');
                    } else {
                        if (title) title.textContent = 'Belum ada tugas';
                        if (desc) desc.textContent = 'Buat tugas/postest Anda';
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
            const title = TableUtils.highlightSearch(item.title || '', searchQuery);
            const program = TableUtils.highlightSearch(item.program || 'N/A', searchQuery);

            return `
            <tr class="hover:bg-blue-50/50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                            <span class="text-sm font-bold text-white">${TableUtils.escapeHtml((item.title || 'Q').charAt(0).toUpperCase())}</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="font-semibold text-gray-900 dark:text-white truncate">${title}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4"><span class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-gray-700 bg-gray-100 rounded-lg dark:bg-gray-700 dark:text-gray-300">${program}</span></td>
                <td class="px-6 py-4 text-center"><span class="text-sm text-gray-600 dark:text-gray-400">${item.total_questions || 0}</span></td>
                <td class="px-6 py-4 text-center"><span class="text-sm text-gray-600 dark:text-gray-400">${item.total_responses || 0}</span></td>
                <td class="px-6 py-4">
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        <p>${TableUtils.formatDate(item.created_at)}</p>
                        <p class="text-xs text-gray-400">${TableUtils.formatTimeAgo(item.created_at)}</p>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center justify-center gap-2">
                        <a href="/instructor/quizzes/${item.id}" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            Lihat
                        </a>
                        <a href="/instructor/quizzes/${item.id}/edit" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-emerald-600 bg-emerald-50 rounded-lg hover:bg-emerald-100 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Edit
                        </a>
                        <button data-delete='${JSON.stringify({id: item.id, title: item.title})}' class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Hapus
                        </button>
                    </div>
                </td>
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
            const title = TableUtils.highlightSearch(item.title || '', searchQuery);

            return `
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-100 dark:border-gray-700">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                        <span class="text-lg font-bold text-white">${TableUtils.escapeHtml((item.title || 'Q').charAt(0).toUpperCase())}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-gray-900 dark:text-white truncate">${title}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">${TableUtils.escapeHtml(item.program || 'N/A')}</p>
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        ${item.total_questions || 0} pertanyaan
                    </span>
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        ${item.total_responses || 0} respon
                    </span>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <span class="text-xs text-gray-400">${TableUtils.formatDate(item.created_at)}</span>
                    <div class="flex items-center gap-2">
                        <a href="/instructor/quizzes/${item.id}" class="px-3 py-1.5 text-sm font-medium text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">Lihat</a>
                        <a href="/instructor/quizzes/${item.id}/edit" class="px-3 py-1.5 text-sm font-medium text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors">Edit</a>
                        <button data-delete='${JSON.stringify({id: item.id, title: item.title})}' class="px-3 py-1.5 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors">Hapus</button>
                    </div>
                </div>
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

    class QuizController {
        constructor() {
            this.store = null;
            this.deleteModal = null;
        }

        init() {
            // Load data from JSON script tag
            const dataEl = document.getElementById('quizzesData');
            if (!dataEl) {
                console.error('Quizzes data not found');
                return;
            }

            let data = [];
            try {
                data = JSON.parse(dataEl.textContent);
            } catch (e) {
                console.error('Failed to parse quiz data:', e);
            }

            // Initialize store
            this.store = new QuizStore(data);

            // Initialize view
            QuizView.init();

            // Initialize delete modal
            this.deleteModal = new DeleteModalController('delete-modal', 'delete-form', 'delete-target-title');
            this.deleteModal.setBaseUrl('/instructor/quizzes');

            // Subscribe view to store updates
            this.store.subscribe(state => QuizView.render(state));

            // Bind events
            this.bindEvents();

            // Initial render
            QuizView.render(this.store.getState());
        }

        bindEvents() {
            const { searchInput, searchClear, perPageSelect, resetFilterBtn, sortButtons } = QuizView.elements;

            // Search with debounce
            searchInput?.addEventListener('input', TableUtils.debounce(e => {
                this.store.setSearch(e.target.value);
            }, 300));

            // Clear search
            searchClear?.addEventListener('click', () => {
                if (searchInput) searchInput.value = '';
                this.store.setSearch('');
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
            QuizView.elements.paginationButtons?.addEventListener('click', e => {
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
        const controller = new QuizController();
        controller.init();
    });

})();
