document.addEventListener('DOMContentLoaded', function () {
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
        openModalBtn.addEventListener('click', function (e) {
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
        photoModal.addEventListener('click', function (e) {
            if (e.target === photoModal) {
                closeModal();
            }
        });
    }

    // Image Preview & Validation
    if (inputPhoto && photoPreview) {
        inputPhoto.addEventListener('change', function (e) {
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
                reader.onload = function (e) {
                    photoPreview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    }


    // Password Validation
    const newPasswordInput = document.getElementById('newPassword');
    const confirmPasswordInput = document.getElementById('newPasswordConfirmation');
    const newPasswordError = document.getElementById('newPasswordError');
    const confirmPasswordError = document.getElementById('confirmPasswordError');

    function validatePassword() {
        if (!newPasswordInput) return;

        const password = newPasswordInput.value;

        // Validate Length
        if (password.length > 0 && password.length < 8) {
            newPasswordError.textContent = 'Password minimal 8 karakter.';
            newPasswordError.classList.remove('hidden');
            newPasswordInput.classList.add('border-red-500');
            newPasswordInput.classList.remove('border-green-500', 'border-[#dbdfe6]');
        } else if (password.length >= 8) {
            newPasswordError.classList.add('hidden');
            newPasswordInput.classList.remove('border-red-500');
            newPasswordInput.classList.add('border-green-500');
        } else {
            // Empty
            newPasswordError.classList.add('hidden');
            newPasswordInput.classList.remove('border-red-500', 'border-green-500');
            newPasswordInput.classList.add('border-[#dbdfe6]');
        }

        validateConfirmation();
    }

    function validateConfirmation() {
        if (!confirmPasswordInput || !newPasswordInput) return;

        const password = newPasswordInput.value;
        const confirm = confirmPasswordInput.value;

        if (confirm.length > 0) {
            if (confirm !== password) {
                confirmPasswordError.textContent = 'Konfirmasi password tidak cocok.';
                confirmPasswordError.classList.remove('hidden');
                confirmPasswordInput.classList.add('border-red-500');
                confirmPasswordInput.classList.remove('border-green-500', 'border-[#dbdfe6]');
            } else {
                confirmPasswordError.classList.add('hidden');
                confirmPasswordInput.classList.remove('border-red-500');
                confirmPasswordInput.classList.add('border-green-500');
            }
        } else {
            confirmPasswordError.classList.add('hidden');
            confirmPasswordInput.classList.remove('border-red-500', 'border-green-500');
            confirmPasswordInput.classList.add('border-[#dbdfe6]');
        }
    }

    if (newPasswordInput) {
        newPasswordInput.addEventListener('input', validatePassword);
    }

    if (confirmPasswordInput) {
        confirmPasswordInput.addEventListener('input', validateConfirmation);
    }
});