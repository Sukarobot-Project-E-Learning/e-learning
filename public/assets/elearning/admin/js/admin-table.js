/**
 * Admin Data Table - Vanilla JavaScript
 * 
 * Handles search, filtering, sorting, pagination, bulk actions, and delete actions
 * for the reusable admin data table component.
 * 
 * Features real-time client-side search filtering without page reload.
 * 
 * NO Alpine.js - Pure vanilla JavaScript
 */

(function() {
    'use strict';

    // State
    let currentContainer = null;
    let currentSortKey = 'created_at';
    let currentSortDir = 'desc';
    let currentPage = 1;
    let lastPage = 1;
    let selectedIds = [];
    
    // Store original rows for real-time filtering
    let allDesktopRows = [];
    let allMobileCards = [];
    let currentSearchTerm = '';
    let currentFilterValue = '';

    // Debounce utility
    function debounce(func, wait) {
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

    // Normalize string for search comparison
    function normalize(s = "") {
        return String(s).trim().replace(/\s+/g, " ").toLowerCase();
    }

    // Check if row matches search term
    function matchesSearch(element, term) {
        if (!term) return true;
        const text = normalize(element.textContent || element.innerText || "");
        return text.includes(term);
    }

    // Check if row matches filter value (for status filter)
    function matchesFilter(element, filterValue, filterKey) {
        if (!filterValue) return true;
        // Try to find status badge or status data attribute
        const statusBadge = element.querySelector('[class*="bg-"][class*="text-"]');
        if (statusBadge) {
            const statusText = normalize(statusBadge.textContent);
            const normalizedFilter = normalize(filterValue);
            return statusText.includes(normalizedFilter) || normalizedFilter.includes(statusText);
        }
        // Check data attribute
        const dataStatus = element.dataset.status;
        if (dataStatus) {
            return normalize(dataStatus) === normalize(filterValue);
        }
        return true;
    }

    // Highlight search term in text
    function highlightText(element, term) {
        if (!term) return;
        // This is a simple approach - for production you might want a more robust solution
        const walker = document.createTreeWalker(element, NodeFilter.SHOW_TEXT, null, false);
        const textNodes = [];
        while (walker.nextNode()) {
            textNodes.push(walker.currentNode);
        }
        
        textNodes.forEach(node => {
            const text = node.textContent;
            const regex = new RegExp(`(${term.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
            if (regex.test(text)) {
                const span = document.createElement('span');
                span.innerHTML = text.replace(regex, '<mark class="search-highlight bg-yellow-200 dark:bg-yellow-700 px-0.5 rounded">$1</mark>');
                node.parentNode.replaceChild(span, node);
            }
        });
    }

    // Remove all highlights
    function removeHighlights(container) {
        const highlights = container.querySelectorAll('mark.search-highlight');
        highlights.forEach(mark => {
            const parent = mark.parentNode;
            parent.replaceChild(document.createTextNode(mark.textContent), mark);
            parent.normalize();
        });
        // Also remove wrapper spans
        const spans = container.querySelectorAll('span');
        spans.forEach(span => {
            if (span.children.length === 0 && span.querySelector('mark') === null) {
                // Skip
            }
        });
    }

    // Update display count
    function updateDisplayCount(container, visibleCount, totalCount, searchTerm) {
        const countContainer = container.querySelector('.flex.items-center.gap-1');
        if (countContainer) {
            const fromSpan = countContainer.querySelectorAll('.font-semibold.text-orange-600')[0];
            const toSpan = countContainer.querySelectorAll('.font-semibold.text-orange-600')[1];
            const totalSpan = countContainer.querySelectorAll('.font-semibold.text-orange-600')[2];
            
            if (fromSpan && toSpan && totalSpan) {
                if (searchTerm || currentFilterValue) {
                    fromSpan.textContent = visibleCount > 0 ? '1' : '0';
                    toSpan.textContent = visibleCount;
                    totalSpan.textContent = visibleCount;
                }
            }
        }
        
        // Update search result text
        const searchResultDiv = container.querySelector('.text-orange-600.text-xs, .text-orange-600.text-sm');
        if (searchTerm) {
            if (searchResultDiv) {
                searchResultDiv.innerHTML = `Hasil pencarian untuk "<span class="font-semibold">${escapeHtml(searchTerm)}</span>" (${visibleCount} hasil)`;
                searchResultDiv.classList.remove('hidden');
            } else {
                // Create search result div if not exists
                const filterContainer = container.querySelector('.mt-3.flex.flex-wrap');
                if (filterContainer) {
                    const newDiv = document.createElement('div');
                    newDiv.className = 'text-orange-600 text-xs sm:text-sm';
                    newDiv.innerHTML = `Hasil pencarian untuk "<span class="font-semibold">${escapeHtml(searchTerm)}</span>" (${visibleCount} hasil)`;
                    filterContainer.appendChild(newDiv);
                }
            }
        } else if (searchResultDiv) {
            searchResultDiv.classList.add('hidden');
        }
    }

    // Apply real-time search and filter
    function applyRealTimeFilter(container) {
        const searchTerm = normalize(currentSearchTerm);
        const filterValue = currentFilterValue;
        
        let visibleDesktopCount = 0;
        let visibleMobileCount = 0;
        
        // Filter desktop rows
        allDesktopRows.forEach(row => {
            const matchSearch = matchesSearch(row, searchTerm);
            const matchFilter = matchesFilter(row, filterValue, 'status');
            
            if (matchSearch && matchFilter) {
                row.classList.remove('hidden');
                visibleDesktopCount++;
            } else {
                row.classList.add('hidden');
            }
        });
        
        // Filter mobile cards
        allMobileCards.forEach(card => {
            const matchSearch = matchesSearch(card, searchTerm);
            const matchFilter = matchesFilter(card, filterValue, 'status');
            
            if (matchSearch && matchFilter) {
                card.classList.remove('hidden');
                visibleMobileCount++;
            } else {
                card.classList.add('hidden');
            }
        });
        
        // Show empty state if no results
        const desktopTable = container.querySelector('.table-desktop-view tbody');
        const mobileView = container.querySelector('.table-mobile-view');
        const totalVisible = Math.max(visibleDesktopCount, visibleMobileCount);
        
        // Handle empty state for desktop
        let emptyRow = desktopTable?.querySelector('.empty-search-row');
        if (totalVisible === 0 && desktopTable) {
            if (!emptyRow) {
                const colCount = container.querySelectorAll('thead th').length || 6;
                emptyRow = document.createElement('tr');
                emptyRow.className = 'empty-search-row';
                emptyRow.innerHTML = `
                    <td colspan="${colCount}" class="px-6 py-12 text-center text-gray-500">
                        <svg class="w-16 h-16 mx-auto mb-4 text-orange-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <p class="text-lg font-medium">Tidak ada hasil ditemukan</p>
                        <p class="text-sm text-gray-400 mt-1">Coba kata kunci lain</p>
                    </td>
                `;
                desktopTable.appendChild(emptyRow);
            }
            emptyRow.classList.remove('hidden');
        } else if (emptyRow) {
            emptyRow.classList.add('hidden');
        }
        
        // Handle empty state for mobile
        let emptyCard = mobileView?.querySelector('.empty-search-card');
        if (totalVisible === 0 && mobileView) {
            if (!emptyCard) {
                emptyCard = document.createElement('div');
                emptyCard.className = 'empty-search-card bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-8 text-center';
                emptyCard.innerHTML = `
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400">Tidak ada hasil ditemukan</p>
                `;
                mobileView.appendChild(emptyCard);
            }
            emptyCard.classList.remove('hidden');
        } else if (emptyCard) {
            emptyCard.classList.add('hidden');
        }
        
        // Update display count
        updateDisplayCount(container, totalVisible, allDesktopRows.length, currentSearchTerm);
        
        // Update bulk selection state
        updateBulkUI(container);
    }

    // URL parameter utility (for pagination and sorting that still need reload)
    function updateUrlParams(params) {
        const url = new URL(window.location.href);
        
        Object.entries(params).forEach(([key, value]) => {
            if (value === null || value === undefined || value === '') {
                url.searchParams.delete(key);
            } else {
                url.searchParams.set(key, value);
            }
        });
        
        // Reset to page 1 when changing filters (except for page changes)
        if (!params.hasOwnProperty('page')) {
            url.searchParams.delete('page');
        }
        
        return url.toString();
    }

    // Navigate with updated params
    function navigateWithParams(params) {
        showLoading();
        window.location.href = updateUrlParams(params);
    }

    // Show loading indicator
    function showLoading() {
        if (!currentContainer) return;
        
        const loadingEl = currentContainer.querySelector('.table-loading');
        if (loadingEl) {
            loadingEl.classList.remove('hidden');
        }
        
        const mobileView = currentContainer.querySelector('.table-mobile-view');
        const desktopView = currentContainer.querySelector('.table-desktop-view');
        if (mobileView) mobileView.classList.add('opacity-50', 'pointer-events-none');
        if (desktopView) desktopView.classList.add('opacity-50', 'pointer-events-none');
    }

    // Hide loading indicator
    function hideLoading() {
        if (!currentContainer) return;
        
        const loadingEl = currentContainer.querySelector('.table-loading');
        if (loadingEl) {
            loadingEl.classList.add('hidden');
        }
        
        const mobileView = currentContainer.querySelector('.table-mobile-view');
        const desktopView = currentContainer.querySelector('.table-desktop-view');
        if (mobileView) mobileView.classList.remove('opacity-50', 'pointer-events-none');
        if (desktopView) desktopView.classList.remove('opacity-50', 'pointer-events-none');
    }

    // Sort by column
    function sort(key) {
        let newDir = 'asc';
        if (key === currentSortKey) {
            newDir = currentSortDir === 'asc' ? 'desc' : 'asc';
        }
        navigateWithParams({ sort: key, dir: newDir, page: 1 });
    }

    // Go to specific page
    function goToPage(page) {
        if (page < 1 || page > lastPage || page === currentPage) return;
        navigateWithParams({ page: page });
    }

    // Previous page
    function prevPage() {
        if (currentPage > 1) {
            goToPage(currentPage - 1);
        }
    }

    // Next page
    function nextPage() {
        if (currentPage < lastPage) {
            goToPage(currentPage + 1);
        }
    }

    // Escape HTML for safe display
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Perform delete action via AJAX
    function performDelete(id, name, deleteRouteBase, csrfToken) {
        let deleteUrl = deleteRouteBase;
        if (deleteUrl.includes(':id')) {
            deleteUrl = deleteUrl.replace(':id', id);
        } else {
            deleteUrl = deleteUrl + '/' + id;
        }

        fetch(deleteUrl, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Dihapus!',
                        text: result.message || 'Data berhasil dihapus.',
                        icon: 'success'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    alert(result.message || 'Data berhasil dihapus.');
                    window.location.reload();
                }
            } else {
                if (typeof Swal !== 'undefined') {
                    Swal.fire('Error!', result.message || 'Gagal menghapus data.', 'error');
                } else {
                    alert(result.message || 'Gagal menghapus data.');
                }
            }
        })
        .catch(error => {
            console.error('Delete error:', error);
            if (typeof Swal !== 'undefined') {
                Swal.fire('Error!', 'Terjadi kesalahan saat menghapus data.', 'error');
            } else {
                alert('Terjadi kesalahan saat menghapus data.');
            }
        });
    }

    // Update bulk selection UI
    function updateBulkUI(container) {
        const bulkContainer = container.querySelector('.bulk-actions-container');
        const countEl = container.querySelector('.bulk-selected-count');
        const selectAllCheckbox = container.querySelector('.bulk-select-all');
        const itemCheckboxes = container.querySelectorAll('.bulk-select-item');
        
        // Filter to only visible checkboxes
        const visibleCheckboxes = Array.from(itemCheckboxes).filter(cb => {
            const row = cb.closest('tr[data-id]') || cb.closest('.bg-white, .dark\\:bg-gray-800');
            return row && !row.classList.contains('hidden');
        });
        
        // Update selectedIds to only include visible items
        const visibleIds = visibleCheckboxes.filter(cb => cb.checked).map(cb => cb.dataset.id);
        selectedIds = visibleIds;
        
        if (bulkContainer) {
            if (selectedIds.length > 0) {
                bulkContainer.classList.remove('hidden');
                bulkContainer.classList.add('flex');
            } else {
                bulkContainer.classList.add('hidden');
                bulkContainer.classList.remove('flex');
            }
        }
        
        if (countEl) {
            countEl.textContent = selectedIds.length > 0 ? '(' + selectedIds.length + ' dipilih)' : '';
        }
        
        // Update select all checkbox state based on visible items only
        if (selectAllCheckbox && visibleCheckboxes.length > 0) {
            const allChecked = visibleCheckboxes.every(cb => cb.checked);
            const someChecked = visibleCheckboxes.some(cb => cb.checked);
            selectAllCheckbox.checked = allChecked;
            selectAllCheckbox.indeterminate = someChecked && !allChecked;
        } else if (selectAllCheckbox) {
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = false;
        }
    }

    // Perform bulk status update
    function performBulkAction(bulkRoute, status, csrfToken, rejectionReason) {
        if (selectedIds.length === 0) {
            if (typeof Swal !== 'undefined') {
                Swal.fire('Perhatian', 'Pilih minimal satu item terlebih dahulu.', 'warning');
            } else {
                alert('Pilih minimal satu item terlebih dahulu.');
            }
            return;
        }
        
        if (!status) {
            if (typeof Swal !== 'undefined') {
                Swal.fire('Perhatian', 'Status tidak valid.', 'warning');
            } else {
                alert('Status tidak valid.');
            }
            return;
        }

        showLoading();
        
        const bodyData = {
            ids: selectedIds,
            status: status
        };
        
        // Add rejection_reason if provided
        if (rejectionReason) {
            bodyData.rejection_reason = rejectionReason;
        }
        
        fetch(bulkRoute, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(bodyData)
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: result.message || 'Status berhasil diubah.',
                        icon: 'success'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    alert(result.message || 'Status berhasil diubah.');
                    window.location.reload();
                }
            } else {
                hideLoading();
                if (typeof Swal !== 'undefined') {
                    Swal.fire('Error!', result.message || 'Gagal mengubah status.', 'error');
                } else {
                    alert(result.message || 'Gagal mengubah status.');
                }
            }
        })
        .catch(error => {
            console.error('Bulk action error:', error);
            hideLoading();
            if (typeof Swal !== 'undefined') {
                Swal.fire('Error!', 'Terjadi kesalahan saat mengubah status.', 'error');
            } else {
                alert('Terjadi kesalahan saat mengubah status.');
            }
        });
    }

    // Initialize a single data table
    function initDataTable(container) {
        if (!container) return;

        currentContainer = container;
        selectedIds = [];
        
        const entity = container.dataset.entity || 'item';
        const deleteRoute = container.dataset.deleteRoute || '';
        const bulkRoute = container.dataset.bulkRoute || '';
        const csrfToken = container.dataset.csrf || document.querySelector('meta[name="csrf-token"]')?.content || '';
        currentSortKey = container.dataset.sortKey || 'created_at';
        currentSortDir = container.dataset.sortDir || 'desc';
        currentPage = parseInt(container.dataset.currentPage) || 1;
        lastPage = parseInt(container.dataset.lastPage) || 1;

        // Elements
        const searchInput = container.querySelector('.table-search-input');
        const searchClear = container.querySelector('.table-search-clear');
        const statusFilter = container.querySelector('.table-status-filter');
        const perPageFilter = container.querySelector('.table-per-page-filter');
        const deleteButtons = container.querySelectorAll('.table-delete-btn');
        
        // Bulk selection elements
        const selectAllCheckbox = container.querySelector('.bulk-select-all');
        const itemCheckboxes = container.querySelectorAll('.bulk-select-item');
        const bulkApproveBtn = container.querySelector('.bulk-approve-btn');
        const bulkRejectBtn = container.querySelector('.bulk-reject-btn');
        const requiresRejectionReason = container.dataset.requiresRejectionReason === 'true';
        
        // Bulk rejection modal elements
        const bulkRejectModal = document.getElementById('bulkRejectModal');
        const bulkRejectionReasonInput = document.getElementById('bulkRejectionReason');
        const bulkRejectModalCancel = container.querySelector('.bulk-reject-modal-cancel') || document.querySelector('.bulk-reject-modal-cancel');
        const bulkRejectModalSubmit = container.querySelector('.bulk-reject-modal-submit') || document.querySelector('.bulk-reject-modal-submit');
        const bulkRejectModalBackdrop = document.querySelector('.bulk-reject-modal-backdrop');

        // Store original rows for real-time filtering
        const desktopTableBody = container.querySelector('.table-desktop-view tbody');
        const mobileViewContainer = container.querySelector('.table-mobile-view');
        
        if (desktopTableBody) {
            allDesktopRows = Array.from(desktopTableBody.querySelectorAll('tr[data-id]'));
        }
        if (mobileViewContainer) {
            allMobileCards = Array.from(mobileViewContainer.querySelectorAll('.bg-white, .dark\\:bg-gray-800')).filter(el => !el.classList.contains('empty-search-card'));
        }

        // Real-time search handler
        if (searchInput) {
            const debouncedFilter = debounce(function() {
                applyRealTimeFilter(container);
            }, 150);

            searchInput.addEventListener('input', function(e) {
                const value = e.target.value;
                currentSearchTerm = value;
                
                // Show/hide clear button
                if (searchClear) {
                    searchClear.classList.toggle('hidden', !value);
                }
                
                // Apply real-time filter
                debouncedFilter();
            });

            // Handle Enter key - still do server search for comprehensive results
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    // If user presses Enter, do a full server-side search
                    navigateWithParams({ search: e.target.value });
                }
            });
            
            // Initialize search term from input value
            currentSearchTerm = searchInput.value || '';
        }

        // Search clear button - real-time clear
        if (searchClear) {
            searchClear.addEventListener('click', function() {
                if (searchInput) {
                    searchInput.value = '';
                    currentSearchTerm = '';
                    searchClear.classList.add('hidden');
                    applyRealTimeFilter(container);
                }
            });
        }

        // Status filter handler - server-side filtering for accuracy
        if (statusFilter) {
            statusFilter.addEventListener('change', function(e) {
                const filterKey = this.dataset.filterKey || 'status';
                const filterValue = e.target.value;
                
                // Use server-side filtering for accurate results
                // This triggers a page reload with the new filter parameter
                const params = {};
                params[filterKey] = filterValue;
                navigateWithParams(params);
            });
            
            // Initialize filter value
            currentFilterValue = statusFilter.value || '';
        }

        // Per page filter handler - needs server reload
        if (perPageFilter) {
            perPageFilter.addEventListener('change', function(e) {
                navigateWithParams({ per_page: e.target.value });
            });
        }

        // Bulk select all handler - only select visible rows
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function(e) {
                const isChecked = e.target.checked;
                itemCheckboxes.forEach(function(cb) {
                    // Only toggle checkboxes in visible rows
                    const row = cb.closest('tr[data-id]') || cb.closest('.bg-white, .dark\\:bg-gray-800');
                    if (row && !row.classList.contains('hidden')) {
                        cb.checked = isChecked;
                        const id = cb.dataset.id;
                        if (isChecked) {
                            if (!selectedIds.includes(id)) {
                                selectedIds.push(id);
                            }
                        } else {
                            selectedIds = selectedIds.filter(function(sid) { return sid !== id; });
                        }
                    }
                });
                updateBulkUI(container);
            });
        }

        // Individual checkbox handlers
        itemCheckboxes.forEach(function(cb) {
            cb.addEventListener('change', function(e) {
                const id = this.dataset.id;
                if (e.target.checked) {
                    if (!selectedIds.includes(id)) {
                        selectedIds.push(id);
                    }
                } else {
                    selectedIds = selectedIds.filter(function(sid) { return sid !== id; });
                }
                updateBulkUI(container);
            });
        });

        // Bulk approve button handler (Terima)
        if (bulkApproveBtn) {
            bulkApproveBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (selectedIds.length === 0) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire('Perhatian', 'Pilih minimal satu item terlebih dahulu.', 'warning');
                    } else {
                        alert('Pilih minimal satu item terlebih dahulu.');
                    }
                    return;
                }
                
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Terima ' + selectedIds.length + ' item?',
                        text: 'Apakah Anda yakin ingin menerima item yang dipilih?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#16a34a',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Ya, Terima',
                        cancelButtonText: 'Batal'
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            performBulkAction(bulkRoute, 'approved', csrfToken, null);
                        }
                    });
                } else {
                    if (confirm('Apakah Anda yakin ingin menerima ' + selectedIds.length + ' item yang dipilih?')) {
                        performBulkAction(bulkRoute, 'approved', csrfToken, null);
                    }
                }
            });
        }

        // Bulk reject button handler (Tolak)
        if (bulkRejectBtn) {
            bulkRejectBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (selectedIds.length === 0) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire('Perhatian', 'Pilih minimal satu item terlebih dahulu.', 'warning');
                    } else {
                        alert('Pilih minimal satu item terlebih dahulu.');
                    }
                    return;
                }
                
                // If rejection reason is required, show modal
                if (requiresRejectionReason && bulkRejectModal) {
                    bulkRejectModal.classList.remove('hidden');
                    if (bulkRejectionReasonInput) {
                        bulkRejectionReasonInput.value = '';
                        bulkRejectionReasonInput.focus();
                    }
                } else {
                    // Otherwise, confirm and reject directly
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Tolak ' + selectedIds.length + ' item?',
                            text: 'Apakah Anda yakin ingin menolak item yang dipilih?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#dc2626',
                            cancelButtonColor: '#6b7280',
                            confirmButtonText: 'Ya, Tolak',
                            cancelButtonText: 'Batal'
                        }).then(function(result) {
                            if (result.isConfirmed) {
                                performBulkAction(bulkRoute, 'rejected', csrfToken, null);
                            }
                        });
                    } else {
                        if (confirm('Apakah Anda yakin ingin menolak ' + selectedIds.length + ' item yang dipilih?')) {
                            performBulkAction(bulkRoute, 'rejected', csrfToken, null);
                        }
                    }
                }
            });
        }

        // Bulk rejection modal handlers
        if (bulkRejectModal) {
            // Cancel button
            if (bulkRejectModalCancel) {
                bulkRejectModalCancel.addEventListener('click', function() {
                    bulkRejectModal.classList.add('hidden');
                });
            }
            
            // Backdrop click to close
            if (bulkRejectModalBackdrop) {
                bulkRejectModalBackdrop.addEventListener('click', function() {
                    bulkRejectModal.classList.add('hidden');
                });
            }
            
            // Submit button
            if (bulkRejectModalSubmit) {
                bulkRejectModalSubmit.addEventListener('click', function() {
                    const reason = bulkRejectionReasonInput ? bulkRejectionReasonInput.value.trim() : '';
                    
                    if (reason.length < 15) {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire('Perhatian', 'Alasan penolakan minimal 15 karakter.', 'warning');
                        } else {
                            alert('Alasan penolakan minimal 15 karakter.');
                        }
                        return;
                    }
                    
                    bulkRejectModal.classList.add('hidden');
                    performBulkAction(bulkRoute, 'rejected', csrfToken, reason);
                });
            }
        }

        // Delete button handlers
        deleteButtons.forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.dataset.id;
                const name = this.dataset.name || 'item ini';

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Hapus ' + entity + '?',
                        html: 'Apakah Anda yakin ingin menghapus <strong>' + escapeHtml(name) + '</strong>?<br><br><span class="text-red-500 text-sm">Data tidak dapat dikembalikan!</span>',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#f97316',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            performDelete(id, name, deleteRoute, csrfToken);
                        }
                    });
                } else {
                    if (confirm('Apakah Anda yakin ingin menghapus ' + name + '? Data tidak dapat dikembalikan!')) {
                        performDelete(id, name, deleteRoute, csrfToken);
                    }
                }
            });
        });

        // Initialize bulk UI state
        updateBulkUI(container);
    }

    // Initialize all data tables on page
    function initAllDataTables() {
        const tables = document.querySelectorAll('.admin-data-table');
        tables.forEach(initDataTable);
    }

    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAllDataTables);
    } else {
        initAllDataTables();
    }

    // Expose public API
    window.AdminDataTable = {
        init: initDataTable,
        initAll: initAllDataTables,
        sort: sort,
        goToPage: goToPage,
        prevPage: prevPage,
        nextPage: nextPage
    };

})();
