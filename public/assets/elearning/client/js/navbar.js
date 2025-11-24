document.addEventListener("DOMContentLoaded", function () {
    // === Scroll Effect ===
    const navbar = document.getElementById('main-navbar');

    function handleScroll() {
        const isMobileMenuOpen = !document.getElementById('mobile-menu').classList.contains('hidden');

        if (window.scrollY > 20 || isMobileMenuOpen) {
            navbar.classList.add('bg-white', 'shadow-md', 'py-3');
            navbar.classList.remove('bg-transparent', 'py-5');
        } else {
            navbar.classList.add('bg-transparent', 'py-5');
            navbar.classList.remove('bg-white', 'shadow-md', 'py-3');
        }
    }

    window.addEventListener('scroll', handleScroll);

    // === Mobile Menu Toggle ===
    const mobileBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    const iconMenu = document.getElementById('icon-menu');
    const iconClose = document.getElementById('icon-close');

    if (mobileBtn) {
        mobileBtn.addEventListener('click', () => {
            const isHidden = mobileMenu.classList.contains('hidden');

            if (isHidden) {
                // Open menu
                mobileMenu.classList.remove('hidden');
                iconMenu.classList.add('hidden');
                iconClose.classList.remove('hidden');
            } else {
                // Close menu
                mobileMenu.classList.add('hidden');
                iconMenu.classList.remove('hidden');
                iconClose.classList.add('hidden');
            }
            // Update navbar style immediately
            handleScroll();
        });
    }

    // === Mobile Dropdowns ===
    const mobileDropdownBtns = document.querySelectorAll('.mobile-dropdown-btn');
    mobileDropdownBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const content = btn.nextElementSibling;
            content.classList.toggle('hidden');
            // Optional: Rotate arrow
            const arrow = btn.querySelector('span');
            if (arrow) {
                arrow.style.transform = content.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
                arrow.style.transition = 'transform 0.2s';
            }
        });
    });

    // === Hash Scroll Fix (Preserved) ===
    adjustScrollForHash();
});

function adjustScrollForHash() {
    const { hash } = window.location;
    if (!hash) return;
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
