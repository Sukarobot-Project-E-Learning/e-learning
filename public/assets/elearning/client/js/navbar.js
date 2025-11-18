document.addEventListener("DOMContentLoaded", function () {
    initNavbar(); // langsung aktifkan burger dan dropdown
    adjustScrollForHash(); // fix posisi scroll saat klik hash link
    const btn = document.getElementById("userMenuBtn");
    const menu = document.getElementById("userMenuDropdown");

    // dropdown user menu
    if (btn) {
        btn.addEventListener("click", () => {
            menu.classList.toggle("hidden");
        });
    }

    // klik di luar untuk tutup dropdown
    document.addEventListener("click", (e) => {
        if (!btn.contains(e.target) && !menu.contains(e.target)) {
            menu.classList.add("hidden");
        }
    });
});

// === Navbar burger & dropdown logic ===
function initNavbar() {
    const burgerBtn = document.querySelector(".custom-burger");
    const navMenu = document.querySelector(".custom-nav");

    if (burgerBtn && navMenu) {
        burgerBtn.addEventListener("click", function () {
            navMenu.classList.toggle("active");
            burgerBtn.classList.toggle("open");
        });
    }

    const dropdowns = document.querySelectorAll(".custom-dropdown > a");
    dropdowns.forEach((drop) => {
        drop.addEventListener("click", function (e) {
            if (window.innerWidth < 768) {
                e.preventDefault();
                const menu = this.nextElementSibling;
                document
                    .querySelectorAll(".custom-dropdown-menu")
                    .forEach((m) => {
                        if (m !== menu) m.style.display = "none";
                    });
                menu.style.display =
                    menu.style.display === "block" ? "none" : "block";
            }
        });
    });

    document.addEventListener("click", function (e) {
        if (!e.target.closest(".custom-dropdown")) {
            document.querySelectorAll(".custom-dropdown-menu").forEach((m) => {
                if (window.innerWidth < 768) m.style.display = "none";
            });
        }
    });
}

// === 🧭 Fix hash scrolling offset for sticky navbar ===
function adjustScrollForHash() {
    const { hash } = window.location;
    if (!hash) return;
    setTimeout(() => {
        const target = document.querySelector(hash);
        if (!target) return;
        const navbar = document.querySelector("nav, .navbar, header");
        const offset = navbar ? navbar.offsetHeight + 60 : 0;
        window.scrollTo({
            top: target.offsetTop - offset,
            behavior: "smooth",
        });
    }, 100);
}

document.addEventListener("DOMContentLoaded", () => {
    const dropdownToggles = document.querySelectorAll(".dropdown-toggle");
    const submenuToggles = document.querySelectorAll(".submenu-toggle");

    // === MOBILE DROPDOWN UTAMA ===
    dropdownToggles.forEach((btn) => {
        btn.addEventListener("click", (e) => {
            if (window.innerWidth < 768) {
                e.stopPropagation();
                const menu = btn.nextElementSibling;
                menu.classList.toggle("hidden");
            }
        });
    });

    // === MOBILE SUBMENU (Kompetisi) ===
    submenuToggles.forEach((btn) => {
        btn.addEventListener("click", (e) => {
            if (window.innerWidth < 768) {
                e.stopPropagation();
                const submenu = btn.nextElementSibling;
                submenu.classList.toggle("hidden");
            }
        });
    });

    // === Tutup semua dropdown saat klik di luar ===
    document.addEventListener("click", (e) => {
        if (!e.target.closest(".custom-dropdown")) {
            document
                .querySelectorAll(".dropdown-menu, .submenu")
                .forEach((menu) => {
                    menu.classList.add("hidden");
                });
        }
    });
});
