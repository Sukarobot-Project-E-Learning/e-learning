document.addEventListener('DOMContentLoaded', function() {
    // Existing Cancel Edit Profile
    const cancelEditBtn = document.getElementById('cancelEditProfile');
    if (cancelEditBtn) {
        cancelEditBtn.addEventListener('click', function () {
            const route = this.getAttribute('data-route');
            window.location.href = route + "?cancel=true";
        });
    }

    // Modal Logic
    const photoModal = document.getElementById('photoModal');
    const openModalBtn = document.getElementById('photo-change-btn');
    const closeModalBtn = document.getElementById('closePhotoModal');
    const cancelModalBtn = document.getElementById('cancelPhotoModal');
    const inputPhoto = document.getElementById('inputPhoto');
    const photoPreview = document.getElementById('photoPreview');
    const photoPreviewDefaultSrc = photoPreview ? photoPreview.src : '';

    // Open Modal
    if (openModalBtn && photoModal) {
        openModalBtn.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent default button behavior
            photoModal.classList.remove('hidden');
        });
    }

    // Close Modal
    function closeModal() {
        if (photoModal) {
            photoModal.classList.add('hidden');
            // Reset input and preview
            if (inputPhoto) inputPhoto.value = '';
            if (photoPreview) photoPreview.src = photoPreviewDefaultSrc;
        }
    }

    if (closeModalBtn) closeModalBtn.addEventListener('click', closeModal);
    if (cancelModalBtn) cancelModalBtn.addEventListener('click', closeModal);
    
    // Close when clicking outside
    if (photoModal) {
        photoModal.addEventListener('click', function(e) {
            if (e.target === photoModal) {
                closeModal();
            }
        });
    }

    // Image Preview & Validation
    if (inputPhoto && photoPreview) {
        inputPhoto.addEventListener('change', function(e) {
            const file = this.files[0];
            
            if (file) {
                // Validation 2MB
                if (file.size > 2 * 1024 * 1024) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Ukuran Gambar Terlalu Besar',
                        text: 'Maksimal ukuran gambar adalah 2MB.',
                        confirmButtonColor: '#f97316'
                    });
                    this.value = ''; // Reset input
                    photoPreview.src = photoPreviewDefaultSrc; // Reset preview
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    photoPreview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    }
});