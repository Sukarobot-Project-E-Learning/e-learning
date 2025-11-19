document.addEventListener("DOMContentLoaded", () => {
    const navItems = document.querySelectorAll(".nav-item");
    const sections = document.querySelectorAll(".section");
    const sidebar = document.querySelector("aside");
    const sidebarToggle = document.getElementById("sidebar-toggle");
    const sidebarOverlay = document.getElementById("sidebar-overlay");
    const modal = document.getElementById("photoModal");
    const openBtn = document.getElementById("photo-change-btn");
    const closeBtn = document.getElementById("closePhotoModal");
    const cancelBtn = document.getElementById("cancelPhotoModal");
    const inputPhoto = document.getElementById("inputPhoto");
    const preview = document.getElementById("photoPreview");

    // Open modal
    openBtn.addEventListener("click", () => {
        modal.classList.remove("hidden");
    });

    // Close modal
    closeBtn.addEventListener("click", () => modal.classList.add("hidden"));
    cancelBtn.addEventListener("click", () => modal.classList.add("hidden"));

    // Click outside modal → close
    modal.addEventListener("click", (e) => {
        if (e.target === modal) modal.classList.add("hidden");
    });

    // Preview image when uploaded
    inputPhoto.addEventListener("change", (e) => {
        const file = e.target.files[0];
        if (!file) return;
        preview.src = URL.createObjectURL(file);
    });

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
