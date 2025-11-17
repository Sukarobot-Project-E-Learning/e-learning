document.addEventListener("DOMContentLoaded", () => {
    const navItems = document.querySelectorAll(".nav-item");
    const sections = document.querySelectorAll(".section");
    const sidebar = document.querySelector("aside");
    const sidebarToggle = document.getElementById("sidebar-toggle");
    const sidebarOverlay = document.getElementById("sidebar-overlay");

    sidebarToggle.addEventListener("click", () => {
        // Toggles sidebar visibility
        sidebar.classList.toggle("translate-x-full");
        sidebarOverlay.classList.remove("hidden");
    });

    sidebarOverlay.addEventListener("click", () => {
        sidebar.classList.toggle("translate-x-full");
        sidebarOverlay.classList.add("hidden");
    });

    navItems.forEach((item) => {
        item.addEventListener("click", () => {
            // Hilangkan active dari semua nav dan section
            navItems.forEach((i) => i.classList.remove("active"));
            sections.forEach((sec) => sec.classList.remove("active"));
            sections.forEach((sec) => sec.classList.add("hidden"));

            // Aktifkan menu yang diklik
            item.classList.add("active");
            const section = document.getElementById(item.dataset.section);
            section.classList.add("active");
            section.classList.remove("hidden");
        });
    });
});
