document.addEventListener("DOMContentLoaded", () => {
    const card = document.getElementById("login-card");

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
  });

  document.getElementById("googleLoginBtn").addEventListener("click", () => {
    google.accounts.oauth2.initCodeClient({
      client_id: "GANTI_DENGAN_CLIENT_ID_KAMU.apps.googleusercontent.com",
      scope: "email profile openid",
      ux_mode: "popup", // Bisa juga 'redirect' kalau mau diarahkan ke halaman lain
      callback: (response) => {
        console.log("Kode login:", response);
        // Di sini kamu bisa kirim response.code ke server untuk verifikasi token
      },
    }).requestCode();
  });
