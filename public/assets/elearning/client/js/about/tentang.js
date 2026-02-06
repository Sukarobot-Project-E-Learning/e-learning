document.addEventListener('DOMContentLoaded', () => {
  // FAQ Toggle
  document.querySelectorAll('.faq-toggle').forEach(button => {
    button.addEventListener('click', () => {
      const content = button.nextElementSibling;
      content.classList.toggle('hidden');
      button.querySelector('svg').classList.toggle('rotate-180');
    });
  });

  const sections = document.querySelectorAll('.content-section');
  const sidebarLinks = document.querySelectorAll('.sidebar-link');
  const mobileLinks = document.querySelectorAll('.mobile-link');

  // Helper function: Activate Section based on ID
  function activateSection(targetId) {
    // Hide all sections, Show target
    sections.forEach(sec => sec.classList.add('hidden'));
    const targetSection = document.getElementById(targetId);
    if (targetSection) {
      targetSection.classList.remove('hidden');
    }

    // Update Sidebar Active State
    sidebarLinks.forEach(link => {
      link.classList.remove('active', 'bg-blue-50', 'text-blue-600');
      link.classList.add('text-gray-600');
      if (link.getAttribute('data-target') === targetId) {
        link.classList.add('active', 'bg-blue-50', 'text-blue-600');
        link.classList.remove('text-gray-600');
      }
    });

    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  // 1. Handle Initial Hash on Page Load
  if (window.location.hash) {
    const targetId = window.location.hash.substring(1); // Remove '#'
    activateSection(targetId);
  }

  // 2. Handle Hash Change (when clicking footer links while already on /tentang)
  window.addEventListener('hashchange', () => {
    const targetId = window.location.hash.substring(1);
    activateSection(targetId);
  });

  // 3. Sidebar Click Events
  sidebarLinks.forEach(link => {
    link.addEventListener('click', e => {
      e.preventDefault();
      const target = link.getAttribute('data-target');

      // Update Hash (this will trigger hashchange event if we want, OR we can just call activateSection)
      // If we just set hash, the hashchange listener handles it.
      // But for smoother experience without jumping, let's just update history and call function.
      history.pushState(null, null, `#${target}`);
      activateSection(target);
    });
  });

  // 4. Mobile Menu Click Events
  mobileLinks.forEach(link => {
    link.addEventListener('click', () => {
      const target = link.dataset.target;
      history.pushState(null, null, `#${target}`);
      activateSection(target);
    });
  });

});