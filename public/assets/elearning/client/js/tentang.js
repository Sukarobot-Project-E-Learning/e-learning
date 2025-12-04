document.addEventListener('DOMContentLoaded', () => {
  // FAQ Toggle
  document.querySelectorAll('.faq-toggle').forEach(button => {
    button.addEventListener('click', () => {
      const content = button.nextElementSibling;
      content.classList.toggle('hidden');
      button.querySelector('svg').classList.toggle('rotate-180');
    });
  });

  // === PILIH MENU HP ===
  const mobileLinks = document.querySelectorAll('.mobile-link');
  const sections = document.querySelectorAll('.content-section');
  mobileLinks.forEach(link => {
    link.addEventListener('click', () => {
      sections.forEach(sec => sec.classList.add('hidden'));
      document.getElementById(link.dataset.target).classList.remove('hidden');
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  });

  // Sidebar Links
  const sidebarLinks = document.querySelectorAll('.sidebar-link');
  sidebarLinks.forEach(link => {
    link.addEventListener('click', e => {
      e.preventDefault();
      const target = link.getAttribute('data-target');
      sections.forEach(sec => sec.classList.add('hidden'));
      document.getElementById(target).classList.remove('hidden');

      // Reset all links
      sidebarLinks.forEach(a => {
        a.classList.remove('active', 'bg-blue-50', 'text-blue-600');
        a.classList.add('text-gray-600');
      });

      // Set active link
      link.classList.add('active', 'bg-blue-50', 'text-blue-600');
      link.classList.remove('text-gray-600');
    });
  });
});