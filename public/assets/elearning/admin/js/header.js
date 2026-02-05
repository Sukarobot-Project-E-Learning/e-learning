/**
 * Admin Header Component Script
 * Handles profile dropdowns, mobile menu toggle, and keyboard navigation.
 * Admin-specific version (no navigation dropdowns).
 */

document.addEventListener("DOMContentLoaded", function () {
    console.log("[Admin Header] Script initialized");

    // === Configuration ===
    const CONFIG = {
        headerId: 'panel-header',
        // Desktop profile dropdown
        desktopProfileTriggerId: 'desktop-profile-trigger',
        desktopProfileMenuId: 'desktop-profile-menu',
        // Mobile profile dropdown
        mobileProfileTriggerId: 'mobile-profile-trigger',
        mobileProfileMenuId: 'mobile-profile-menu',
        // Mobile sidebar
        mobileSidebarBtnId: 'mobile-sidebar-btn',
        // Timing
        hoverDelay: 100,
        closeDelay: 150
    };

    // === State ===
    const state = {
        desktopProfileOpen: false,
        mobileProfileOpen: false,
        hoverTimers: {}
    };

    // === Elements ===
    const header = document.getElementById(CONFIG.headerId);
    const desktopProfileTrigger = document.getElementById(CONFIG.desktopProfileTriggerId);
    const desktopProfileMenu = document.getElementById(CONFIG.desktopProfileMenuId);
    const mobileProfileTrigger = document.getElementById(CONFIG.mobileProfileTriggerId);
    const mobileProfileMenu = document.getElementById(CONFIG.mobileProfileMenuId);
    const mobileSidebarBtn = document.getElementById(CONFIG.mobileSidebarBtnId);

    // Debug: Log element detection
    console.log("[Admin Header] Elements found:", {
        header: !!header,
        desktopProfileTrigger: !!desktopProfileTrigger,
        desktopProfileMenu: !!desktopProfileMenu,
        mobileProfileTrigger: !!mobileProfileTrigger,
        mobileProfileMenu: !!mobileProfileMenu,
        mobileSidebarBtn: !!mobileSidebarBtn
    });

    // === Utility Functions ===

    /**
     * Show a dropdown menu with animation
     * @param {HTMLElement} menu - The dropdown menu element
     */
    function showDropdown(menu) {
        if (!menu) return;
        menu.classList.remove('hidden');
        // Force reflow for animation
        void menu.offsetHeight;
        menu.classList.add('dropdown-active');
    }

    /**
     * Hide a dropdown menu with animation
     * @param {HTMLElement} menu - The dropdown menu element
     */
    function hideDropdown(menu) {
        if (!menu) return;
        menu.classList.remove('dropdown-active');
        // Wait for transition to complete before hiding
        setTimeout(() => {
            if (!menu.classList.contains('dropdown-active')) {
                menu.classList.add('hidden');
            }
        }, 150);
    }

    /**
     * Toggle chevron rotation
     * @param {HTMLElement} trigger - The trigger button containing the chevron
     * @param {boolean} isOpen - Whether the dropdown is open
     */
    function toggleChevron(trigger, isOpen) {
        if (!trigger) return;
        const chevron = trigger.querySelector('.chevron-icon');
        if (chevron) {
            chevron.style.transform = isOpen ? 'rotate(180deg)' : 'rotate(0deg)';
        }
    }

    /**
     * Clear hover timer for a specific dropdown
     * @param {string} key - Timer key identifier
     */
    function clearHoverTimer(key) {
        if (state.hoverTimers[key]) {
            clearTimeout(state.hoverTimers[key]);
            delete state.hoverTimers[key];
        }
    }

    /**
     * Close all dropdowns
     */
    function closeAllDropdowns() {
        // Desktop profile
        if (state.desktopProfileOpen) {
            state.desktopProfileOpen = false;
            hideDropdown(desktopProfileMenu);
            toggleChevron(desktopProfileTrigger, false);
        }
        // Mobile profile
        if (state.mobileProfileOpen) {
            state.mobileProfileOpen = false;
            hideDropdown(mobileProfileMenu);
            toggleChevron(mobileProfileTrigger, false);
        }
    }

    // === Desktop Profile Dropdown (Hover-based) ===
    if (desktopProfileTrigger && desktopProfileMenu) {
        const profileContainer = desktopProfileTrigger.closest('.relative');

        if (profileContainer) {
            console.log("[Admin Header] Desktop profile hover events attached");
            
            profileContainer.addEventListener('mouseenter', function () {
                clearHoverTimer('desktopProfile');
                state.desktopProfileOpen = true;
                showDropdown(desktopProfileMenu);
                toggleChevron(desktopProfileTrigger, true);
            });

            profileContainer.addEventListener('mouseleave', function () {
                state.hoverTimers['desktopProfile'] = setTimeout(() => {
                    state.desktopProfileOpen = false;
                    hideDropdown(desktopProfileMenu);
                    toggleChevron(desktopProfileTrigger, false);
                }, CONFIG.closeDelay);
            });
        } else {
            console.warn("[Admin Header] Desktop profile container (.relative) not found");
        }
    } else {
        console.warn("[Admin Header] Desktop profile dropdown elements not found");
    }

    // === Mobile Profile Dropdown (Click-based) ===
    if (mobileProfileTrigger && mobileProfileMenu) {
        console.log("[Admin Header] Mobile profile click events attached");
        
        mobileProfileTrigger.addEventListener('click', function (e) {
            e.stopPropagation();
            state.mobileProfileOpen = !state.mobileProfileOpen;

            if (state.mobileProfileOpen) {
                showDropdown(mobileProfileMenu);
                toggleChevron(mobileProfileTrigger, true);
            } else {
                hideDropdown(mobileProfileMenu);
                toggleChevron(mobileProfileTrigger, false);
            }
        });
    } else {
        console.warn("[Admin Header] Mobile profile dropdown elements not found");
    }

    // === Mobile Sidebar Toggle ===
    if (mobileSidebarBtn) {
        console.log("[Admin Header] Mobile sidebar button events attached");
        
        mobileSidebarBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            // Dispatch custom event for sidebar to handle
            document.dispatchEvent(new CustomEvent('toggle-mobile-sidebar'));
        });
    } else {
        console.warn("[Admin Header] Mobile sidebar button not found");
    }

    // === Click Outside Handler ===
    document.addEventListener('click', function (e) {
        // Close mobile profile if clicking outside
        if (state.mobileProfileOpen && mobileProfileMenu && mobileProfileTrigger) {
            if (!mobileProfileMenu.contains(e.target) && !mobileProfileTrigger.contains(e.target)) {
                state.mobileProfileOpen = false;
                hideDropdown(mobileProfileMenu);
                toggleChevron(mobileProfileTrigger, false);
            }
        }
    });

    // === Keyboard Navigation (ESC to close) ===
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeAllDropdowns();
        }
    });

    // === Expose public API for external use ===
    window.HeaderComponent = {
        closeAll: closeAllDropdowns,
        getState: () => ({ ...state })
    };

    console.log("[Admin Header] Initialization complete");
});
