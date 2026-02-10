/**
 * Admin Sidebar Component Script
 * Handles collapsible menu sections, mobile sidebar overlay, and state persistence.
 * Admin-specific version.
 */

document.addEventListener("DOMContentLoaded", function () {
    console.log("[Admin Sidebar] Script initialized");

    // === Configuration ===
    const CONFIG = {
        // Selectors
        sidebarId: 'desktop-sidebar',
        mobileSidebarId: 'mobile-sidebar',
        mobileSidebarOverlayId: 'mobile-sidebar-overlay',
        expandableBtnClass: 'sidebar-expandable-btn',
        expandableContentClass: 'sidebar-expandable-content',
        // Storage
        storageKey: 'sidebar_expanded_items',
        // Animation
        collapseTransitionDuration: 200
    };

    // === State ===
    const state = {
        isMobileSidebarOpen: false,
        expandedItems: new Set()
    };

    // === Elements ===
    const desktopSidebar = document.getElementById(CONFIG.sidebarId);
    const mobileSidebar = document.getElementById(CONFIG.mobileSidebarId);
    const mobileSidebarOverlay = document.getElementById(CONFIG.mobileSidebarOverlayId);

    // Debug: Log element detection
    console.log("[Admin Sidebar] Elements found:", {
        desktopSidebar: !!desktopSidebar,
        mobileSidebar: !!mobileSidebar,
        mobileSidebarOverlay: !!mobileSidebarOverlay
    });

    // === Utility Functions ===

    /**
     * Load expanded items state from localStorage
     */
    function loadExpandedState() {
        try {
            const saved = localStorage.getItem(CONFIG.storageKey);
            if (saved) {
                const items = JSON.parse(saved);
                if (Array.isArray(items)) {
                    state.expandedItems = new Set(items);
                }
            }
        } catch (e) {
            console.warn("[Admin Sidebar] Failed to load sidebar state from localStorage:", e);
        }
    }

    /**
     * Save expanded items state to localStorage
     */
    function saveExpandedState() {
        try {
            localStorage.setItem(CONFIG.storageKey, JSON.stringify([...state.expandedItems]));
        } catch (e) {
            console.warn("[Admin Sidebar] Failed to save sidebar state to localStorage:", e);
        }
    }

    /**
     * Expand a collapsible section with animation
     * @param {HTMLElement} content - The content element to expand
     */
    function expandSection(content) {
        if (!content) return;

        // Set to auto height to measure
        content.style.height = 'auto';
        const targetHeight = content.scrollHeight + 'px';
        content.style.height = '0px';

        // Force reflow
        void content.offsetHeight;

        // Animate to target height
        content.style.height = targetHeight;
        content.classList.remove('hidden');
        content.classList.add('sidebar-expanded');

        // Reset height to auto after animation completes
        setTimeout(() => {
            if (content.classList.contains('sidebar-expanded')) {
                content.style.height = 'auto';
            }
        }, CONFIG.collapseTransitionDuration);
    }

    /**
     * Collapse a collapsible section with animation
     * @param {HTMLElement} content - The content element to collapse
     */
    function collapseSection(content) {
        if (!content) return;

        // Set explicit height for animation
        content.style.height = content.scrollHeight + 'px';

        // Force reflow
        content.offsetHeight;

        // Animate to 0
        content.style.height = '0px';
        content.classList.remove('sidebar-expanded');

        // Hide after animation completes
        setTimeout(() => {
            if (!content.classList.contains('sidebar-expanded')) {
                content.classList.add('hidden');
            }
        }, CONFIG.collapseTransitionDuration);
    }

    /**
     * Toggle chevron rotation
     * @param {HTMLElement} button - The button containing the chevron
     * @param {boolean} isExpanded - Whether the section is expanded
     */
    function toggleChevron(button, isExpanded) {
        const chevron = button.querySelector('.expand-chevron');
        if (chevron) {
            chevron.style.transform = isExpanded ? 'rotate(180deg)' : 'rotate(0deg)';
        }
    }

    /**
     * Initialize a single expandable section
     * @param {HTMLElement} button - The expand button
     * @param {string} itemId - Unique identifier for the section
     */
    function initExpandableSection(button, itemId) {
        const content = button.nextElementSibling;
        if (!content || !content.classList.contains(CONFIG.expandableContentClass)) {
            console.warn("[Admin Sidebar] Expandable content not found for button:", button);
            return;
        }

        // Check if should be initially expanded (from active route or saved state)
        const isInitiallyExpanded = button.dataset.initialExpanded === 'true' || state.expandedItems.has(itemId);

        if (isInitiallyExpanded) {
            content.classList.remove('hidden');
            content.classList.add('sidebar-expanded');
            content.style.height = 'auto';
            toggleChevron(button, true);
            state.expandedItems.add(itemId);
        } else {
            content.classList.add('hidden');
            content.style.height = '0px';
            toggleChevron(button, false);
        }

        // Add click handler
        button.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const isExpanded = content.classList.contains('sidebar-expanded');

            if (isExpanded) {
                collapseSection(content);
                toggleChevron(button, false);
                state.expandedItems.delete(itemId);
            } else {
                expandSection(content);
                toggleChevron(button, true);
                state.expandedItems.add(itemId);
            }

            saveExpandedState();
        });
    }

    /**
     * Initialize all expandable sections in a container
     * @param {HTMLElement} container - The container element
     */
    function initExpandableSections(container) {
        if (!container) return;

        const expandBtns = container.querySelectorAll('.' + CONFIG.expandableBtnClass);
        console.log("[Admin Sidebar] Found", expandBtns.length, "expandable buttons in container");
        expandBtns.forEach((btn, index) => {
            const itemId = btn.dataset.sidebarItem || `item-${index}`;
            initExpandableSection(btn, itemId);
        });
    }

    // === Mobile Sidebar Functions ===

    /**
     * Open mobile sidebar
     */
    function openMobileSidebar() {
        if (!mobileSidebar || !mobileSidebarOverlay) return;

        state.isMobileSidebarOpen = true;

        // Show overlay
        mobileSidebarOverlay.classList.remove('hidden');
        void mobileSidebarOverlay.offsetHeight; // Force reflow
        mobileSidebarOverlay.classList.add('sidebar-overlay-active');

        // Show sidebar
        mobileSidebar.classList.remove('-translate-x-full');
        mobileSidebar.classList.add('translate-x-0');

        // Prevent body scroll
        document.body.style.overflow = 'hidden';

        console.log("[Admin Sidebar] Mobile sidebar opened");
    }

    /**
     * Close mobile sidebar
     */
    function closeMobileSidebar() {
        if (!mobileSidebar || !mobileSidebarOverlay) return;

        state.isMobileSidebarOpen = false;

        // Hide sidebar
        mobileSidebar.classList.remove('translate-x-0');
        mobileSidebar.classList.add('-translate-x-full');

        // Hide overlay
        mobileSidebarOverlay.classList.remove('sidebar-overlay-active');

        setTimeout(() => {
            if (!state.isMobileSidebarOpen) {
                mobileSidebarOverlay.classList.add('hidden');
            }
        }, 150);

        // Restore body scroll
        document.body.style.overflow = '';

        console.log("[Admin Sidebar] Mobile sidebar closed");
    }

    /**
     * Toggle mobile sidebar
     */
    function toggleMobileSidebar() {
        if (state.isMobileSidebarOpen) {
            closeMobileSidebar();
        } else {
            openMobileSidebar();
        }
    }

    // === Initialization ===

    // Load saved state
    loadExpandedState();

    // Initialize desktop sidebar expandable sections
    if (desktopSidebar) {
        console.log("[Admin Sidebar] Initializing desktop sidebar");
        initExpandableSections(desktopSidebar);
    }

    // Initialize mobile sidebar expandable sections
    if (mobileSidebar) {
        console.log("[Admin Sidebar] Initializing mobile sidebar");
        initExpandableSections(mobileSidebar);
    }

    // === Event Listeners ===

    // Listen for toggle-mobile-sidebar event from header
    document.addEventListener('toggle-mobile-sidebar', function () {
        console.log("[Admin Sidebar] Received toggle-mobile-sidebar event");
        toggleMobileSidebar();
    });

    // Close mobile sidebar on overlay click
    if (mobileSidebarOverlay) {
        mobileSidebarOverlay.addEventListener('click', function () {
            closeMobileSidebar();
        });
    }

    // Close mobile sidebar on ESC key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && state.isMobileSidebarOpen) {
            closeMobileSidebar();
        }
    });

    // Close mobile sidebar when clicking a link (for better UX)
    if (mobileSidebar) {
        mobileSidebar.addEventListener('click', function (e) {
            const link = e.target.closest('a[href]:not([href="#"])');
            if (link && !link.closest('.' + CONFIG.expandableContentClass + '.hidden')) {
                // Small delay to allow navigation
                setTimeout(() => {
                    closeMobileSidebar();
                }, 100);
            }
        });
    }

    // === Expose public API ===
    window.SidebarComponent = {
        openMobile: openMobileSidebar,
        closeMobile: closeMobileSidebar,
        toggleMobile: toggleMobileSidebar,
        getState: () => ({ ...state, expandedItems: [...state.expandedItems] })
    };

    console.log("[Admin Sidebar] Initialization complete");
});
