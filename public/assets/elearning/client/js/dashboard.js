document.addEventListener("DOMContentLoaded", () => {
  const navItems = document.querySelectorAll(".nav-item");
  const sections = document.querySelectorAll(".section");

  navItems.forEach(item => {
    item.addEventListener("click", () => {
      // Hilangkan active dari semua nav dan section
      navItems.forEach(i => i.classList.remove("active"));
      sections.forEach(sec => sec.classList.remove("active"));
      sections.forEach(sec => sec.classList.add("hidden"));

      // Aktifkan menu yang diklik
      item.classList.add("active");
      const section = document.getElementById(item.dataset.section);
      section.classList.add("active");
      section.classList.remove("hidden");
    });
  });
});
