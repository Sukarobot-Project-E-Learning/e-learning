
document.addEventListener("DOMContentLoaded", () => {
    const card = document.getElementById("register-card");
    const form = document.getElementById("register-form");

    // Animasi muncul
    setTimeout(() => card.classList.add("show"), 200);

    // Handle submit
    form.addEventListener("submit", (e) => {
      e.preventDefault();
      alert("Akun berhasil dibuat 🎉 Silakan login.");
      window.location.href = "/login";
    });
  });
