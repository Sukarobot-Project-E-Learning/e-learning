document.addEventListener("DOMContentLoaded", () => {
    const card = document.getElementById("register-card");
    const form = document.getElementById("register-form");
    const submitBtn = document.getElementById("submit-btn");
    const errorMessages = document.getElementById("error-messages");
    const errorList = document.getElementById("error-list");

    // Animasi muncul
    setTimeout(() => card.classList.add("show"), 200);

    // Handle submit
    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        // Reset error messages
        errorMessages.classList.add("hidden");
        errorList.innerHTML = "";

        // Disable button dan ubah text
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="animate-pulse">Mendaftar...</span>';

        // Get form data
        const formData = {
            name: document.getElementById("name").value,
            username: document.getElementById("username").value,
            phone: document.getElementById("phone").value,
            email: document.getElementById("email").value,
            password: document.getElementById("password").value,
            password_confirmation: document.getElementById("password_confirmation").value
        };

        try {
            // Call API register
            const response = await fetch("/auth/register", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(formData)
            });

            const result = await response.json();

            if (response.ok && result.success) {
                // Tampilkan pesan sukses dengan SweetAlert2
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil! 🎉',
                    text: result.message || 'Registrasi berhasil! Selamat datang di Sukarobot',
                    confirmButtonText: 'Lanjut ke Dashboard',
                    confirmButtonColor: '#10b981',
                    allowOutsideClick: false
                }).then(() => {
                    // Redirect ke dashboard (session sudah dibuat di server)
                    window.location.href = result.data.redirect_url || "/dashboard";
                });
            } else {
                // Tampilkan error
                showErrors(result.errors || { general: [result.message] });
            }
        } catch (error) {
            console.error("Register error:", error);
            showErrors({
                general: ["Terjadi kesalahan koneksi. Pastikan server berjalan."]
            });
        } finally {
            // Enable button kembali
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    });

    // Function untuk menampilkan error
    function showErrors(errors) {
        errorList.innerHTML = "";

        Object.keys(errors).forEach(key => {
            const errorArray = Array.isArray(errors[key]) ? errors[key] : [errors[key]];
            errorArray.forEach(error => {
                const li = document.createElement("li");
                li.textContent = error;
                errorList.appendChild(li);
            });
        });

        errorMessages.classList.remove("hidden");
        errorMessages.scrollIntoView({ behavior: "smooth", block: "center" });
    }

    // seek password
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password_confirmation');
    const iconPassword = document.getElementById('icon_password');
    const iconConfirmPassword = document.getElementById('icon_password_confirmation');


    if (togglePassword) {
        togglePassword.addEventListener('click', function (e) {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                iconPassword.classList.remove('fa-eye');
                iconPassword.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                iconPassword.classList.remove('fa-eye-slash');
                iconPassword.classList.add('fa-eye');
            }
        });
    }

    if (toggleConfirmPassword) {
        toggleConfirmPassword.addEventListener('click', function (e) {
            if (confirmPasswordInput.type === 'password') {
                confirmPasswordInput.type = 'text';
                iconConfirmPassword.classList.remove('fa-eye');
                iconConfirmPassword.classList.add('fa-eye-slash');
            } else {
                confirmPasswordInput.type = 'password';
                iconConfirmPassword.classList.remove('fa-eye-slash');
                iconConfirmPassword.classList.add('fa-eye');
            }
        });
    }
});
