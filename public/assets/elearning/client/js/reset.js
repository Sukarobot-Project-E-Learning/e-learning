document.addEventListener("DOMContentLoaded", () => {
    const card = document.getElementById("auth-card");
    const step1 = document.getElementById("step-1");
    const step2 = document.getElementById("step-2");
    const step3 = document.getElementById("step-3");

    const form1 = document.getElementById("form-step-1");
    const form2 = document.getElementById("form-step-2");
    const form3 = document.getElementById("form-step-3");

    // Pastikan hanya Step 1 yang tampil saat halaman dibuka
    step1.classList.remove("hidden");
    step2.classList.add("hidden");
    step3.classList.add("hidden");

    // Animasi muncul kartu
    setTimeout(() => card.classList.add("show"), 200);

    // ========================
    // STEP 1 → STEP 2
    // ========================
    form1.addEventListener("submit", (e) => {
      e.preventDefault();

      step1.classList.add("hidden");
      step2.classList.remove("hidden");
      step2.classList.add("animate-fade", "show");

      // Fokus otomatis ke input OTP pertama
      setTimeout(() => {
        const firstOTP = document.querySelector(".otp-input");
        if (firstOTP) firstOTP.focus();
      }, 300);
    });

    // ========================
    // STEP 3 (Reset Password)
    // ========================
    const toggleNew = document.getElementById("toggle-new");
    const toggleConfirm = document.getElementById("toggle-confirm");
    const newPassword = document.getElementById("new-password");
    const confirmPassword = document.getElementById("confirm-password");
    const passwordError = document.getElementById("password-error");

    // SVG ikon mata (formal abu-abu)
    const eyeOpenSVG = `
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
        <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.8"/>
      </svg>`;

    const eyeClosedSVG = `
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 3l18 18M10.584 10.587A3 3 0 0113.41 13.41M9.88 9.88L5.46 5.46M21 21L2.5 2.5M6.96 6.96C4.93 8.227 3.42 10.003 2.458 12c1.274 4.057 5.065 7 9.542 7 1.278 0 2.514-.206 3.67-.587M17.04 17.04C19.07 15.773 20.58 13.997 21.542 12c-.63-2.007-2.027-3.754-3.981-4.902"/>
      </svg>`;

    // Tombol tampil/sembunyikan password
    if (toggleNew && newPassword) {
      toggleNew.innerHTML = eyeOpenSVG;
      toggleNew.addEventListener("click", () => {
        const isVisible = newPassword.type === "text";
        newPassword.type = isVisible ? "password" : "text";
        toggleNew.innerHTML = isVisible ? eyeOpenSVG : eyeClosedSVG;
      });
    }

    if (toggleConfirm && confirmPassword) {
      toggleConfirm.innerHTML = eyeOpenSVG;
      toggleConfirm.addEventListener("click", () => {
        const isVisible = confirmPassword.type === "text";
        confirmPassword.type = isVisible ? "password" : "text";
        toggleConfirm.innerHTML = isVisible ? eyeOpenSVG : eyeClosedSVG;
      });
    }

    form3.addEventListener("submit", (e) => {
      e.preventDefault();
      if (newPassword.value !== confirmPassword.value) {
        passwordError.classList.remove("hidden");

        // Efek getar
        form3.animate(
          [
            { transform: "translateX(0)" },
            { transform: "translateX(-8px)" },
            { transform: "translateX(8px)" },
            { transform: "translateX(0)" },
          ],
          { duration: 300, easing: "ease-in-out" }
        );
      } else {
        passwordError.classList.add("hidden");

        // 🔥 Popup Loading + Success
        const popup = document.getElementById("popup-success");
        const popupLoading = document.getElementById("popup-loading");
        const popupCheck = document.getElementById("popup-check");
        const popupText = document.getElementById("popup-text");

        popup.classList.remove("hidden");
        popupLoading.classList.remove("hidden");
        popupCheck.classList.add("hidden");
        popupText.textContent = "Memproses...";

        // Ganti ke success setelah delay
        setTimeout(() => {
          popupLoading.classList.add("hidden");
          popupCheck.classList.remove("hidden");
          popupText.textContent = "Password berhasil diganti!";
        }, 1500);

        // Tutup dan redirect
        setTimeout(() => {
          popup.classList.add("hidden");
          window.location.href = "/login";
        }, 3000);
      }
    });


    // ========================
    // OTP Logic di STEP 2
    // ========================
    const correctOTP = "1111"; // ubah sesuai OTP yang valid
    const inputs = document.querySelectorAll(".otp-input");

    inputs.forEach((input, index) => {
      // hanya angka & auto next
      input.addEventListener("input", (e) => {
        const value = e.target.value.replace(/[^0-9]/g, "");
        e.target.value = value;
        if (value && index < inputs.length - 1) inputs[index + 1].focus();
        checkOTP();
      });

      // backspace → pindah ke kiri
      input.addEventListener("keydown", (e) => {
        if (e.key === "Backspace" && !e.target.value && index > 0) {
          inputs[index - 1].focus();
        }
      });
    });

    // ========================
    // Fungsi pengecekan OTP
    // ========================
    function checkOTP() {
      const enteredOTP = Array.from(inputs).map((i) => i.value).join("");
      if (enteredOTP.length === inputs.length) {
        if (enteredOTP === correctOTP) {
          // ✅ OTP BENAR — efek pulse hijau
          inputs.forEach((i) => {
            i.classList.remove(
              "border-gray-300",
              "border-red-500",
              "ring-red-400",
              "focus:ring-blue-400"
            );
            i.classList.add("border-green-500", "ring-2", "ring-green-400");
          });

          // Efek pulse lembut
          inputs.forEach((i) => {
            i.animate(
              [
                { transform: "scale(1)" },
                { transform: "scale(1.1)" },
                { transform: "scale(1)" },
              ],
              { duration: 400, easing: "ease-in-out" }
            );
          });

          // Lanjut ke Step 3
          setTimeout(() => {
            step2.classList.add("hidden");
            step3.classList.remove("hidden");
            step3.classList.add("animate-fade", "show");
            const firstPass = step3.querySelector("input");
            if (firstPass) firstPass.focus();
          }, 600);
        } else {
          // ❌ OTP SALAH — efek getar merah + reset input
          inputs.forEach((i) => {
            i.classList.remove(
              "border-gray-300",
              "border-green-500",
              "ring-green-400",
              "focus:ring-blue-400"
            );
            i.classList.add("border-red-500", "ring-2", "ring-red-400");
          });

          const form = document.getElementById("form-step-2");
          form.animate(
            [
              { transform: "translateX(0)" },
              { transform: "translateX(-8px)" },
              { transform: "translateX(8px)" },
              { transform: "translateX(-6px)" },
              { transform: "translateX(6px)" },
              { transform: "translateX(0)" },
            ],
            { duration: 400, easing: "ease-in-out" }
          );

          // Reset input OTP
          setTimeout(() => {
            inputs.forEach((i) => {
              i.value = "";
              i.classList.remove("border-red-500", "ring-red-400");
              i.classList.add("border-gray-300");
            });
            inputs[0].focus();
          }, 500);
        }
      }
    }

    // ========================
    // Tombol konfirmasi manual (opsional)
    // ========================
    form2.addEventListener("submit", (e) => {
      e.preventDefault();
      const enteredOTP = Array.from(inputs).map((i) => i.value).join("");
      if (enteredOTP === correctOTP) {
        step2.classList.add("hidden");
        step3.classList.remove("hidden");
        step3.classList.add("animate-fade", "show");
      } else {
        const form = document.getElementById("form-step-2");
        form.animate(
          [
            { transform: "translateX(0)" },
            { transform: "translateX(-8px)" },
            { transform: "translateX(8px)" },
            { transform: "translateX(-6px)" },
            { transform: "translateX(6px)" },
            { transform: "translateX(0)" },
          ],
          { duration: 400, easing: "ease-in-out" }
        );
        inputs.forEach((i) => (i.value = ""));
        inputs[0].focus();
      }
    });
  });
