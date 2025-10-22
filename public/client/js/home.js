// === Inisialisasi AOS (animasi scroll) ===
AOS.init({ once: true, duration: 900, easing: "ease-in-out" });

// === SWIPER HERO / SLIDER UTAMA ===
new Swiper(".mySwiperHero", {
  slidesPerView: 1,
  spaceBetween: 20,
  loop: true,
  autoplay: { delay: 4000 },
  pagination: { el: ".swiper-pagination", clickable: true },
  navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" },
});

// === SWIPER ALUMNI ===
const alumniSwiper = new Swiper(".alumniSwiper", {
  slidesPerView: 1,
  loop: true,
  spaceBetween: 20,
  pagination: {
    el: ".swiper-pagination",
    clickable: true,
  },
  autoplay: {
    delay: 4500,
    disableOnInteraction: false,
  },
  breakpoints: {
    768: { slidesPerView: 1 },
    1024: { slidesPerView: 1 },
  },
});

// === SWIPER PARTNER LOGO (infinite & smooth) ===
const partnerSwiper = new Swiper(".partnerSwiper", {
  slidesPerView: 2,
  spaceBetween: 30,
  loop: true,
  speed: 2500, // durasi transisi (semakin kecil semakin cepat)
  allowTouchMove: false, // biar tidak bisa digeser manual
  autoplay: {
    delay: 0, // tanpa jeda antar-slide
    disableOnInteraction: false,
  },
  freeMode: true, // biar jalan terus seperti marquee
  freeModeMomentum: false,
  breakpoints: {
    480: { slidesPerView: 3 },
    768: { slidesPerView: 4 },
    1024: { slidesPerView: 5 },
    1280: { slidesPerView: 6 },
  },
});

// === AOS ulang untuk memastikan animasi berjalan setelah Swiper aktif ===
AOS.init({
  duration: 800,
  once: true,
});

// === Popup otomatis saat halaman dimuat ===
// (Kode popup dipindahkan ke bawah untuk menghindari duplikasi)

// === SWIPER EVENT (khusus tombol navigasi prev/next custom) ===
const eventSwiper = new Swiper(".mySwiper", {
  slidesPerView: 1,
  centeredSlides: true,
  loop: true,
  spaceBetween: 30,
  autoplay: {
    delay: 3500,
    disableOnInteraction: false,
  },
  pagination: {
    el: ".swiper-pagination",
    clickable: true,
  },
});

// === Tombol Navigasi Event ===
const nextBtn = document.getElementById("nextBtn");
const prevBtn = document.getElementById("prevBtn");

if (nextBtn && prevBtn && eventSwiper) {
  nextBtn.addEventListener("click", () => eventSwiper.slideNext());
  prevBtn.addEventListener("click", () => eventSwiper.slidePrev());
}

// === Infinite Scroll Partner (dua arah) ===
document.addEventListener("DOMContentLoaded", () => {
  const tracks = document.querySelectorAll(".partner-track");

  tracks.forEach((track, index) => {
    // Gandakan isi supaya loop tanpa ujung
    track.innerHTML += track.innerHTML;

    // Terapkan animasi scroll-infinite ke semua baris
    track.style.animationName = "scroll-infinite";
    track.style.animationDuration = "40s";
    track.style.animationTimingFunction = "linear";
    track.style.animationIterationCount = "infinite";

    // Delay sedikit baris kedua agar tampak natural
    if (index === 1) {
      track.style.animationDelay = "20s"; // setengah durasi
    }
  });
});

// === Infinite Scroll Partner (dua arah — versi kiri dan kanan) ===
document.addEventListener("DOMContentLoaded", () => {
  const leftTrack = document.querySelector(".track-left");
  const rightTrack = document.querySelector(".track-right");

  if (leftTrack && rightTrack) {
    // Gandakan isi agar loop tanpa ujung
    leftTrack.innerHTML += leftTrack.innerHTML;
    rightTrack.innerHTML += rightTrack.innerHTML;

    // Terapkan animasi untuk arah berbeda
    leftTrack.style.animation = "scroll-left 40s linear infinite";
    rightTrack.style.animation = "scroll-right 40s linear infinite";
  }
});

// === Popup otomatis dengan handler close ===
window.addEventListener("load", () => {
  const popup = document.getElementById("popup");
  const closePopup = document.getElementById("closePopup");
  const body = document.body;

  if (popup && closePopup) {
    // Tampilkan popup otomatis setelah halaman dimuat
    setTimeout(() => {
      popup.classList.remove("hidden");
      body.classList.add("overflow-hidden");
    }, 500); // delay 500ms agar animasi halaman selesai dulu

    // Fungsi untuk menutup popup
    const closePopupHandler = () => {
      popup.classList.add("hidden");
      body.classList.remove("overflow-hidden");
    };

    // Tombol X menutup popup
    closePopup.addEventListener("click", (e) => {
      e.stopPropagation();
      closePopupHandler();
    });

    // Klik di area gelap (overlay) juga menutup popup
    popup.addEventListener("click", (e) => {
      if (e.target === popup) {
        closePopupHandler();
      }
    });

    // ESC key untuk menutup popup
    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape" && !popup.classList.contains("hidden")) {
        closePopupHandler();
      }
    });
  }
});


  const filterBtns = document.querySelectorAll(".filter-btn");
    const programCards = document.querySelectorAll(".program-card");

    filterBtns.forEach(btn => {
      btn.addEventListener("click", () => {
        filterBtns.forEach(b => b.classList.remove("active"));
        btn.classList.add("active");

        const category = btn.dataset.filter;
        programCards.forEach(card => {
          if (category === "all" || card.dataset.category === category) {
            card.classList.remove("hidden");
          } else {
            card.classList.add("hidden");
          }
        });
      });
    });
