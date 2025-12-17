document.addEventListener("DOMContentLoaded", () => {
  const card = document.getElementById("auth-card");
  const step1 = document.getElementById("step-1");
  const step2 = document.getElementById("step-2");
  const step3 = document.getElementById("step-3");

  const form1 = document.getElementById("form-step-1");
  const form2 = document.getElementById("form-step-2");
  const form3 = document.getElementById("form-step-3");

  // Get CSRF token
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

  // Pastikan hanya Step 1 yang tampil saat halaman dibuka
  step1.classList.remove("hidden");
  step2.classList.add("hidden");
  step3.classList.add("hidden");

  // Animasi muncul kartu
  setTimeout(() => card.classList.add("show"), 200);

  // ========================
  // STEP 1 → STEP 2 (Send OTP to Email)
  // ========================
  form1.addEventListener("submit", async (e) => {
    e.preventDefault();

    const email = document.getElementById("identity").value.trim();
    const errorEl = document.getElementById("error-step-1");
    const btnStep1 = document.getElementById("btn-step-1");
    const btnText1 = document.getElementById("btn-text-1");
    const btnLoading1 = document.getElementById("btn-loading-1");

    // Hide error
    errorEl.classList.add("hidden");

    // Show loading state
    btnStep1.disabled = true;
    btnText1.classList.add("hidden");
    btnLoading1.classList.remove("hidden");

    try {
      const response = await fetch('/password/send-otp', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json'
        },
        body: JSON.stringify({ email })
      });

      const data = await response.json();

      if (data.success) {
        // Store email for later steps
        document.getElementById("reset-email").value = email;

        // Transition to Step 2
        step1.classList.add("hidden");
        step2.classList.remove("hidden");
        step2.classList.add("animate-fade", "show");

        // Focus on first OTP input
        setTimeout(() => {
          const firstOTP = document.querySelector(".otp-input");
          if (firstOTP) firstOTP.focus();
        }, 300);
      } else {
        // Show error message
        errorEl.textContent = data.message || 'Terjadi kesalahan. Silakan coba lagi.';
        errorEl.classList.remove("hidden");

        // Shake animation
        form1.animate(
          [
            { transform: "translateX(0)" },
            { transform: "translateX(-8px)" },
            { transform: "translateX(8px)" },
            { transform: "translateX(0)" },
          ],
          { duration: 300, easing: "ease-in-out" }
        );
      }
    } catch (error) {
      console.error('Error:', error);
      errorEl.textContent = 'Gagal menghubungi server. Silakan coba lagi.';
      errorEl.classList.remove("hidden");
    } finally {
      // Reset button state
      btnStep1.disabled = false;
      btnText1.classList.remove("hidden");
      btnLoading1.classList.add("hidden");
    }
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

  form3.addEventListener("submit", async (e) => {
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
      return;
    }

    passwordError.classList.add("hidden");

    // Get stored values
    const email = document.getElementById("reset-email").value;
    const token = Array.from(document.querySelectorAll(".otp-input")).map(i => i.value).join("");

    // Show popup
    const popup = document.getElementById("popup-success");
    const popupLoading = document.getElementById("popup-loading");
    const popupCheck = document.getElementById("popup-check");
    const popupText = document.getElementById("popup-text");

    popup.classList.remove("hidden");
    popupLoading.classList.remove("hidden");
    popupCheck.classList.add("hidden");
    popupText.textContent = "Memproses...";

    try {
      const response = await fetch('/password/reset', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json'
        },
        body: JSON.stringify({
          email,
          token,
          password: newPassword.value,
          password_confirmation: confirmPassword.value
        })
      });

      const data = await response.json();

      if (data.success) {
        // Success
        popupLoading.classList.add("hidden");
        popupCheck.classList.remove("hidden");
        popupText.textContent = "Password berhasil diganti!";

        // Redirect after delay
        setTimeout(() => {
          popup.classList.add("hidden");
          window.location.href = "/login";
        }, 2000);
      } else {
        // Error
        popup.classList.add("hidden");
        passwordError.textContent = data.message || 'Gagal mengubah password';
        passwordError.classList.remove("hidden");
      }
    } catch (error) {
      console.error('Error:', error);
      popup.classList.add("hidden");
      passwordError.textContent = 'Gagal menghubungi server';
      passwordError.classList.remove("hidden");
    }
  });


  // ========================
  // OTP Logic di STEP 2
  // ========================
  const inputs = document.querySelectorAll(".otp-input");

  inputs.forEach((input, index) => {
    // hanya angka & auto next
    input.addEventListener("input", (e) => {
      const value = e.target.value.replace(/[^0-9]/g, "");
      e.target.value = value;
      if (value && index < inputs.length - 1) inputs[index + 1].focus();
      // Check OTP when all inputs are filled
      if (Array.from(inputs).every(i => i.value)) {
        verifyOTP();
      }
    });

    // backspace → pindah ke kiri
    input.addEventListener("keydown", (e) => {
      if (e.key === "Backspace" && !e.target.value && index > 0) {
        inputs[index - 1].focus();
      }
    });
  });

  // ========================
  // Fungsi verifikasi OTP
  // ========================
  async function verifyOTP() {
    const enteredOTP = Array.from(inputs).map((i) => i.value).join("");

    if (enteredOTP.length !== inputs.length) return;

    const email = document.getElementById("reset-email").value;

    try {
      const response = await fetch('/password/verify-otp', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json'
        },
        body: JSON.stringify({ email, token: enteredOTP })
      });

      const data = await response.json();

      if (data.success) {
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
    } catch (error) {
      console.error('Error:', error);
      // Reset on error
      inputs.forEach((i) => {
        i.value = "";
        i.classList.remove("border-red-500", "ring-red-400");
        i.classList.add("border-gray-300");
      });
      inputs[0].focus();
    }
  }

  // ========================
  // Tombol konfirmasi manual (opsional)
  // ========================
  form2.addEventListener("submit", (e) => {
    e.preventDefault();
    verifyOTP();
  });
});
