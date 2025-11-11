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
            const response = await fetch("/api/register", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-Requested-With": "XMLHttpRequest"
                },
                body: JSON.stringify(formData)
            });

            const result = await response.json();

            if (response.ok && result.success) {
                // Simpan token ke localStorage
                localStorage.setItem("auth_token", result.data.access_token);
                localStorage.setItem("user_data", JSON.stringify(result.data.user));

                // Tampilkan pesan sukses
                alert(result.message || "Akun berhasil dibuat! 🎉");

                // Redirect ke dashboard atau home
                window.location.href = "/dashboard";
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
});
