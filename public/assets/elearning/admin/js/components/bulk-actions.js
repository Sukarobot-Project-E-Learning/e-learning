/**
 * Frontend Bulk Actions Handler
 * Handles bulk approve/reject for Program Proofs and Instructor Applications
 * Uses existing individual endpoints instead of dedicated bulk endpoints
 */

(function() {
    'use strict';

    class BulkActionsHandler {
        constructor(config) {
            this.config = {
                containerSelector: config.containerSelector || '.admin-data-table',
                entityType: config.entityType, // 'program-proofs' or 'instructor-applications'
                approveEndpoint: config.approveEndpoint, // e.g., '/admin/program-proofs/{id}/accept'
                rejectEndpoint: config.rejectEndpoint, // e.g., '/admin/program-proofs/{id}/reject'
                approveLabel: config.approveLabel || 'Setujui',
                rejectLabel: config.rejectLabel || 'Tolak',
                entityName: config.entityName || 'item',
                ...config
            };

            this.selectedIds = [];
            this.container = document.querySelector(this.config.containerSelector);
            
            if (!this.container) {
                console.warn('Bulk actions container not found:', this.config.containerSelector);
                return;
            }

            this.init();
        }

        init() {
            this.injectBulkUI();
            this.bindEvents();
        }

        injectBulkUI() {
            // Find the header area where bulk actions should be placed
            // Using a more reliable selector that matches the actual structure
            const headerArea = this.container.querySelector('.my-6');
            if (!headerArea) {
                console.warn('Header area not found');
                return;
            }

            // Find the flex container with buttons (the div with create/action buttons)
            let buttonContainer = headerArea.querySelector('.flex.flex-wrap.items-center.gap-3');
            
            // Fallback: try other possible selectors
            if (!buttonContainer) {
                buttonContainer = headerArea.querySelector('.flex.items-center.gap-3');
            }
            if (!buttonContainer) {
                buttonContainer = headerArea.querySelector('[class*="flex"][class*="items-center"][class*="gap-3"]');
            }
            
            if (buttonContainer) {
                // Create bulk actions container that matches the existing UI style
                const bulkActionsDiv = document.createElement('div');
                bulkActionsDiv.className = 'bulk-actions-container hidden items-center gap-2';
                bulkActionsDiv.setAttribute('data-element', 'bulk-actions');
                bulkActionsDiv.innerHTML = `
                    <button type="button" data-bulk-action="approve" class="bulk-approve-btn inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition-all duration-200 transform hover:scale-105">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        ${this.config.approveLabel}
                    </button>
                    <button type="button" data-bulk-action="reject" class="bulk-reject-btn inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all duration-200 transform hover:scale-105">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        ${this.config.rejectLabel}
                    </button>
                    <span class="bulk-selected-count text-sm text-gray-500 dark:text-gray-400"></span>
                `;
                buttonContainer.insertBefore(bulkActionsDiv, buttonContainer.firstChild);
            } else {
                // Fallback: create a floating toolbar at the top of the container
                const bulkToolbar = document.createElement('div');
                bulkToolbar.className = 'bulk-actions-container hidden items-center gap-2 mb-4 p-3 bg-orange-50 dark:bg-gray-800 rounded-lg border border-orange-200 dark:border-gray-700';
                bulkToolbar.setAttribute('data-element', 'bulk-actions');
                bulkToolbar.innerHTML = `
                    <button type="button" data-bulk-action="approve" class="bulk-approve-btn inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        ${this.config.approveLabel}
                    </button>
                    <button type="button" data-bulk-action="reject" class="bulk-reject-btn inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        ${this.config.rejectLabel}
                    </button>
                    <span class="bulk-selected-count text-sm text-gray-600 dark:text-gray-400 ml-2"></span>
                `;
                this.container.insertBefore(bulkToolbar, this.container.firstChild);
            }

            // Add checkboxes to table rows
            this.addCheckboxesToTable();
        }

        addCheckboxesToTable() {
            // Desktop table - add header checkbox
            const thead = this.container.querySelector('.table-desktop-view thead tr');
            if (thead && !thead.querySelector('.bulk-checkbox-header')) {
                const th = document.createElement('th');
                th.className = 'bulk-checkbox-header px-4 py-4 text-left';
                th.innerHTML = `
                    <input type="checkbox" class="bulk-select-all w-4 h-4 text-orange-600 bg-white rounded focus:ring-orange-500 cursor-pointer">
                `;
                thead.insertBefore(th, thead.firstChild);
            }

            // Desktop table - add row checkboxes
            const desktopTbody = this.container.querySelector('.table-desktop-view tbody');
            if (desktopTbody) {
                desktopTbody.querySelectorAll('tr[data-id]').forEach(row => {
                    if (!row.querySelector('.bulk-checkbox-cell')) {
                        const id = row.dataset.id;
                        const td = document.createElement('td');
                        td.className = 'bulk-checkbox-cell px-4 py-4';
                        td.innerHTML = `
                            <input type="checkbox" class="bulk-select-item w-4 h-4 text-orange-600 rounded focus:ring-orange-500 cursor-pointer" data-id="${id}">
                        `;
                        row.insertBefore(td, row.firstChild);
                    }
                });
            }

            // Mobile cards - add checkboxes
            const mobileView = this.container.querySelector('.table-mobile-view');
            if (mobileView) {
                mobileView.querySelectorAll('[data-element="mobile-card"]').forEach(card => {
                    if (!card.querySelector('.bulk-checkbox-mobile')) {
                        const id = card.dataset.id;
                        const checkboxDiv = document.createElement('div');
                        checkboxDiv.className = 'bulk-checkbox-mobile absolute top-2 left-2';
                        checkboxDiv.innerHTML = `
                            <input type="checkbox" class="bulk-select-item w-5 h-5 text-orange-600 rounded focus:ring-orange-500 cursor-pointer" data-id="${id}">
                        `;
                        card.style.position = 'relative';
                        card.insertBefore(checkboxDiv, card.firstChild);
                    }
                });
            }
        }

        extractRowId(row) {
            // First try data-id attribute
            if (row.dataset.id) return row.dataset.id;
            
            // Try to find ID from action buttons
            const viewBtn = row.querySelector('a[href*="/admin/"]');
            if (viewBtn) {
                const match = viewBtn.href.match(/\/(\d+)(?:\/|$)/);
                if (match) return match[1];
            }
            return null;
        }

        bindEvents() {
            // Header checkbox - select all visible (use class selector that matches our injected checkbox)
            const headerCheckbox = this.container.querySelector('.bulk-select-all');
            if (headerCheckbox) {
                headerCheckbox.addEventListener('change', (e) => {
                    this.toggleAllCheckboxes(e.target.checked);
                });
            }

            // Individual checkboxes - use event delegation on container
            this.container.addEventListener('change', (e) => {
                if (e.target.matches('.bulk-select-item')) {
                    // Sync checkbox state between desktop and mobile views
                    this.syncCheckboxState(e.target);
                    this.updateSelectedIds();
                }
            });

            // Bulk action buttons - listen on the bulk-actions-container
            const bulkActionsContainer = this.container.querySelector('.bulk-actions-container');
            if (bulkActionsContainer) {
                bulkActionsContainer.addEventListener('click', (e) => {
                    const btn = e.target.closest('[data-bulk-action]');
                    if (btn) {
                        const action = btn.dataset.bulkAction;
                        this.handleBulkAction(action);
                    }
                });
            }

            // Re-inject checkboxes on table content change (for AJAX pagination/filtering)
            const desktopTbody = this.container.querySelector('.table-desktop-view tbody');
            if (desktopTbody) {
                const observer = new MutationObserver(() => {
                    this.addCheckboxesToTable();
                    this.updateSelectedIds();
                });
                observer.observe(desktopTbody, { childList: true });
            }

            // Also observe mobile view
            const mobileView = this.container.querySelector('.table-mobile-view');
            if (mobileView) {
                const observer = new MutationObserver(() => {
                    this.addCheckboxesToTable();
                    this.updateSelectedIds();
                });
                observer.observe(mobileView, { childList: true });
            }
        }

        toggleAllCheckboxes(checked) {
            // Toggle all visible checkboxes (both desktop and mobile will be synced)
            const checkboxes = this.container.querySelectorAll('.bulk-select-item');
            checkboxes.forEach(cb => {
                const row = cb.closest('tr[data-id], [data-element="mobile-card"]');
                if (row && !row.classList.contains('hidden')) {
                    cb.checked = checked;
                }
            });
            this.updateSelectedIds();
        }

        // Sync checkbox state between desktop and mobile views for the same item
        syncCheckboxState(changedCheckbox) {
            const id = changedCheckbox.dataset.id;
            if (!id) return;
            
            // Find all checkboxes with the same ID and sync their state
            const allCheckboxesForId = this.container.querySelectorAll(`.bulk-select-item[data-id="${id}"]`);
            allCheckboxesForId.forEach(cb => {
                cb.checked = changedCheckbox.checked;
            });
        }

        updateSelectedIds() {
            // Get checked checkboxes - use Set to deduplicate IDs (same item appears in both desktop and mobile views)
            const checkboxes = this.container.querySelectorAll('.bulk-select-item:checked');
            const checkedIds = new Set();
            
            checkboxes.forEach(cb => {
                const row = cb.closest('tr[data-id], [data-element="mobile-card"]');
                if (row && !row.classList.contains('hidden') && cb.dataset.id) {
                    checkedIds.add(cb.dataset.id);
                }
            });
            
            this.selectedIds = Array.from(checkedIds);

            // Update count display
            const countEl = this.container.querySelector('.bulk-selected-count');
            if (countEl) {
                countEl.textContent = this.selectedIds.length > 0 ? `(${this.selectedIds.length} dipilih)` : '';
            }

            // Show/hide bulk actions container
            const bulkContainer = this.container.querySelector('.bulk-actions-container');
            if (bulkContainer) {
                if (this.selectedIds.length > 0) {
                    bulkContainer.classList.remove('hidden');
                    bulkContainer.classList.add('flex');
                } else {
                    bulkContainer.classList.add('hidden');
                    bulkContainer.classList.remove('flex');
                }
            }

            // Update header checkbox state - only count unique IDs for proper state
            const headerCheckbox = this.container.querySelector('.bulk-select-all');
            
            // Get all unique item IDs (deduplicated)
            const allItemIds = new Set();
            this.container.querySelectorAll('.bulk-select-item').forEach(cb => {
                const row = cb.closest('tr[data-id], [data-element="mobile-card"]');
                if (row && !row.classList.contains('hidden') && cb.dataset.id) {
                    allItemIds.add(cb.dataset.id);
                }
            });
            
            const totalUniqueItems = allItemIds.size;
            
            if (headerCheckbox && totalUniqueItems > 0) {
                const allChecked = this.selectedIds.length === totalUniqueItems;
                const someChecked = this.selectedIds.length > 0;
                headerCheckbox.checked = allChecked;
                headerCheckbox.indeterminate = someChecked && !allChecked;
            } else if (headerCheckbox) {
                headerCheckbox.checked = false;
                headerCheckbox.indeterminate = false;
            }
        }

        async handleBulkAction(action) {
            if (this.selectedIds.length === 0) {
                this.showNotification('warning', 'Pilih minimal satu item terlebih dahulu');
                return;
            }

            const actionLabel = action === 'approve' ? this.config.approveLabel : this.config.rejectLabel;
            const confirmed = await this.showConfirmModal(
                `${actionLabel} ${this.selectedIds.length} ${this.config.entityName}?`,
                `Apakah Anda yakin ingin ${actionLabel.toLowerCase()} ${this.selectedIds.length} ${this.config.entityName}?`
            );

            if (!confirmed) return;

            // Show progress modal
            const progressModal = this.showProgressModal(actionLabel);

            let successCount = 0;
            let failCount = 0;
            const errors = [];

            for (let i = 0; i < this.selectedIds.length; i++) {
                const id = this.selectedIds[i];
                this.updateProgress(progressModal, i + 1, this.selectedIds.length);

                try {
                    const endpoint = action === 'approve' 
                        ? this.config.approveEndpoint.replace('{id}', id)
                        : this.config.rejectEndpoint.replace('{id}', id);

                    const response = await fetch(endpoint, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                            'Accept': 'application/json'
                        }
                    });

                    const result = await response.json();
                    if (result.success || response.ok) {
                        successCount++;
                    } else {
                        failCount++;
                        errors.push(`ID ${id}: ${result.message || 'Unknown error'}`);
                    }
                } catch (error) {
                    failCount++;
                    errors.push(`ID ${id}: Network error`);
                    console.error(`Error processing ID ${id}:`, error);
                }
            }

            // Hide progress modal
            progressModal.remove();

            // Show result
            this.showResultModal(actionLabel, successCount, failCount, errors);

            // Reload if any success
            if (successCount > 0) {
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            }
        }

        showProgressModal(action) {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
            modal.id = 'bulk-progress-modal';
            modal.innerHTML = `
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg max-w-md w-full mx-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Memproses ${action}</h3>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 mb-4">
                        <div id="bulk-progress-bar" class="bg-blue-600 h-3 rounded-full transition-all duration-300" style="width: 0%"></div>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        <span id="bulk-progress-text">Memproses 0 dari 0</span>
                    </p>
                </div>
            `;
            document.body.appendChild(modal);
            return modal;
        }

        updateProgress(modal, current, total) {
            const progressBar = modal.querySelector('#bulk-progress-bar');
            const progressText = modal.querySelector('#bulk-progress-text');
            
            const percentage = (current / total) * 100;
            if (progressBar) progressBar.style.width = `${percentage}%`;
            if (progressText) progressText.textContent = `Memproses ${current} dari ${total}`;
        }

        showResultModal(action, successCount, failCount, errors) {
            const totalCount = successCount + failCount;
            const isSuccess = failCount === 0;
            
            const message = isSuccess
                ? `Berhasil ${action.toLowerCase()} ${successCount} ${this.config.entityName}`
                : `${successCount} berhasil, ${failCount} gagal dari ${totalCount} ${this.config.entityName}`;

            const errorDetails = errors.length > 0 
                ? `<div class="mt-3 p-3 bg-red-50 dark:bg-red-900/20 rounded text-sm text-red-700 dark:text-red-400 max-h-40 overflow-y-auto">${errors.join('<br>')}</div>`
                : '';

            this.showNotification(isSuccess ? 'success' : 'warning', message, errorDetails);
        }

        showConfirmModal(title, message) {
            return new Promise((resolve) => {
                // Use SweetAlert2 if available
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: title,
                        text: message,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Lanjutkan',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33'
                    }).then((result) => resolve(result.isConfirmed));
                } else {
                    // Fallback to confirm
                    resolve(confirm(`${title}\n\n${message}`));
                }
            });
        }

        showNotification(type, message, details = '') {
            // Use SweetAlert2 if available
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: type === 'error' || type === 'warning' ? type : 'success',
                    title: message,
                    html: details,
                    confirmButtonText: 'OK'
                });
            } else {
                alert(message + (details ? '\n\n' + details.replace(/<[^>]*>/g, '') : ''));
            }
        }
    }

    // Auto-initialize based on page
    document.addEventListener('DOMContentLoaded', () => {
        const path = window.location.pathname;

        // Program Proofs
        if (path.includes('/admin/program-proofs')) {
            new BulkActionsHandler({
                entityType: 'program-proofs',
                approveEndpoint: '/admin/program-proofs/{id}/accept',
                rejectEndpoint: '/admin/program-proofs/{id}/reject',
                approveLabel: 'Setujui',
                rejectLabel: 'Tolak',
                entityName: 'bukti program'
            });
        }

        // Instructor Applications
        if (path.includes('/admin/instructor-applications')) {
            new BulkActionsHandler({
                entityType: 'instructor-applications',
                approveEndpoint: '/admin/instructor-applications/{id}/approve',
                rejectEndpoint: '/admin/instructor-applications/{id}/reject',
                approveLabel: 'Setujui',
                rejectLabel: 'Tolak',
                entityName: 'pengajuan instruktur'
            });
        }
    });

    // Export for manual initialization
    window.BulkActionsHandler = BulkActionsHandler;

})();
