document.addEventListener("DOMContentLoaded", () => {
    // Modal logic (only if elements exist)
    const modal = document.getElementById("photoModal");
    const openBtn = document.getElementById("photo-change-btn");
    const closeBtn = document.getElementById("closePhotoModal");
    const cancelBtn = document.getElementById("cancelPhotoModal");
    const inputPhoto = document.getElementById("inputPhoto");
    const preview = document.getElementById("photoPreview");

    if (openBtn && modal) {
        // Open modal
        openBtn.addEventListener("click", () => {
            modal.classList.remove("hidden");
        });

        // Close modal
        if (closeBtn) closeBtn.addEventListener("click", () => modal.classList.add("hidden"));
        if (cancelBtn) cancelBtn.addEventListener("click", () => modal.classList.add("hidden"));

        // Click outside modal → close
        modal.addEventListener("click", (e) => {
            if (e.target === modal) modal.classList.add("hidden");
        });

        // Preview image when uploaded
        if (inputPhoto && preview) {
            inputPhoto.addEventListener("change", (e) => {
                const file = e.target.files[0];
                if (!file) return;
                preview.src = URL.createObjectURL(file);
            });
        }
    }
});
