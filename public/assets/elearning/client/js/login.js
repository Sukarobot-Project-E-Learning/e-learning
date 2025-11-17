document.addEventListener("DOMContentLoaded", () => {
    const card = document.getElementById("login-card");
    const form = document.getElementById("login-form");
    const submitBtn = document.getElementById("submit-btn");
    const errorMessages = document.getElementById("error-messages");
    const errorList = document.getElementById("error-list");

    // animasi card muncul
    setTimeout(() => {
      card.classList.remove("opacity-0", "scale-90");
      card.classList.add("opacity-100", "scale-100");
    }, 300);

    // animasi teks muncul bertahap
    const fadeElements = document.querySelectorAll(".animate-fade");
    fadeElements.forEach((el, i) => {
      setTimeout(() => {
        el.style.animation = "fadeUp 0.6s ease forwards";
      }, 600 + i * 300);
    });

    // Handle login form submit
    form.addEventListener("submit", (e) => {
        // Disable button dan ubah text
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="animate-pulse">Logging in...</span>';

        // Form akan submit secara normal (Laravel akan handle)
        // Button akan di-enable kembali jika ada error (via page reload)
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

    // Google Login Handler (untuk nanti)
    const googleLoginBtn = document.getElementById("googleLoginBtn");
    if (googleLoginBtn) {
        googleLoginBtn.addEventListener("click", () => {
            alert("Google OAuth belum diimplementasikan. Akan segera ditambahkan!");
            // TODO: Implementasi Google OAuth
            /*
            google.accounts.oauth2.initCodeClient({
              client_id: "GANTI_DENGAN_CLIENT_ID_KAMU.apps.googleusercontent.com",
              scope: "email profile openid",
              ux_mode: "popup",
              callback: (response) => {
                console.log("Kode login:", response);
                // Kirim response.code ke server untuk verifikasi token
              },
            }).requestCode();
            */
        });
    }
});
