/**
 * Navbar Interaction Script
 * Handles scroll effects, mobile menu toggling, and dropdowns.
 */

document.addEventListener("DOMContentLoaded", function () {
    console.log("Navbar script loaded");

    // === Configuration ===
    const CONFIG = {
        scrollThreshold: 20,
        navbarId: 'main-navbar',
        mobileMenuId: 'mobile-menu',
        mobileBtnId: 'mobile-menu-btn',
        iconMenuId: 'icon-menu',
        iconCloseId: 'icon-close',
        dropdownBtnClass: '.mobile-dropdown-btn',
        dropdownContentClass: '.mobile-dropdown-content'
    };

    // === Elements ===
    const navbar = document.getElementById(CONFIG.navbarId);
    const mobileBtn = document.getElementById(CONFIG.mobileBtnId);
    const mobileMenu = document.getElementById(CONFIG.mobileMenuId);
    const iconMenu = document.getElementById(CONFIG.iconMenuId);
    const iconClose = document.getElementById(CONFIG.iconCloseId);

    // === Scroll Effect ===
    function handleScroll() {
        if (!navbar) return;

        const isMobileMenuOpen = mobileMenu && !mobileMenu.classList.contains('hidden');
        const shouldBeWhite = window.scrollY > CONFIG.scrollThreshold || isMobileMenuOpen;

        if (shouldBeWhite) {
            navbar.classList.add('bg-white', 'shadow-md', 'py-3');
            navbar.classList.remove('bg-transparent', 'py-5');
        } else {
            navbar.classList.add('bg-transparent', 'py-5');
            navbar.classList.remove('bg-white', 'shadow-md', 'py-3');
        }
    }

    // Initialize scroll listener
    window.addEventListener('scroll', handleScroll);
    // Initial check
    handleScroll();

    // === Mobile Menu Toggle ===
    if (mobileBtn && mobileMenu) {
        mobileBtn.addEventListener('click', function (e) {
            e.stopPropagation(); // Prevent bubbling
            console.log("Mobile menu button clicked");

            const isHidden = mobileMenu.classList.contains('hidden');

            if (isHidden) {
                // Open Menu
                mobileMenu.classList.remove('hidden');
                if (iconMenu) iconMenu.classList.add('hidden');
                if (iconClose) iconClose.classList.remove('hidden');
            } else {
                // Close Menu
                mobileMenu.classList.add('hidden');
                if (iconMenu) iconMenu.classList.remove('hidden');
                if (iconClose) iconClose.classList.add('hidden');
            }

            // Update navbar style to match state
            handleScroll();
        });
    } else {
        console.warn("Mobile menu elements not found");
    }

    // === Mobile Dropdowns ===
    const dropdownBtns = document.querySelectorAll(CONFIG.dropdownBtnClass);

    dropdownBtns.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            console.log("Dropdown clicked");

            // Find the content div (next sibling)
            // We use nextElementSibling and check if it matches the content class or just assume structure
            const content = this.nextElementSibling; // Assuming structure: button + div

            if (content) {
                content.classList.toggle('hidden');

                // Rotate arrow if it exists
                const arrow = this.querySelector('span');
                if (arrow) {
                    const isHidden = content.classList.contains('hidden');
                    arrow.style.transform = isHidden ? 'rotation(0deg)' : 'rotate(180deg)';
                    // Note: 'rotation' is not standard CSS, using 'rotate'
                    arrow.style.transform = isHidden ? 'rotate(0deg)' : 'rotate(180deg)';
                }
            }
        });
    });

    // === Close Menu on Click Outside ===
    document.addEventListener('click', function (e) {
        if (!mobileMenu || mobileMenu.classList.contains('hidden')) return;
        if (!mobileBtn) return;

        // If click is NOT inside menu AND NOT on the button
        if (!mobileMenu.contains(e.target) && !mobileBtn.contains(e.target)) {
            console.log("Clicked outside menu, closing");
            mobileMenu.classList.add('hidden');
            if (iconMenu) iconMenu.classList.remove('hidden');
            if (iconClose) iconClose.classList.add('hidden');
            handleScroll();
        }
    });

    // === Hash Scroll Fix ===
    adjustScrollForHash();
});

function adjustScrollForHash() {
    const { hash } = window.location;
    if (!hash) return;

    // Wait a bit for layout to settle
    setTimeout(() => {
        const target = document.querySelector(hash);
        if (!target) return;

        const navbar = document.querySelector("nav");
        const offset = navbar ? navbar.offsetHeight + 20 : 0;

        window.scrollTo({
            top: target.offsetTop - offset,
            behavior: "smooth",
        });
    }, 100);
}
