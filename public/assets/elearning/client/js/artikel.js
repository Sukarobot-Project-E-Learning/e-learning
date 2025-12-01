

document.addEventListener('DOMContentLoaded', function () {
  // Variables
  let articles = [];
  let filtered = [];
  let currentPage = 1;
  let currentCategory = 'all';
  let currentSort = 'newest';
  const perPage = 12;
  const grid = document.getElementById('articlesGrid');
  const tpl = document.getElementById('cardTpl');


  // Fetch articles from API
  async function fetchArticles() {
    try {
      showLoading();
      const params = new URLSearchParams({
        category: currentCategory,
        sort: currentSort,
        per_page: 100 // Fetch more for client-side pagination
      });

      const response = await fetch(`/artikel/api?${params}`);
      const data = await response.json();
      articles = data.data;
      filtered = [...articles];
      render();
    } catch (error) {
      console.error('Error fetching articles:', error);
      grid.innerHTML = '<div class="col-span-full text-center text-red-500">Gagal memuat artikel</div>';
    } finally {
      hideLoading();
    }
  }

  // Show loading state
  function showLoading() {
    grid.innerHTML = `
      <div class="col-span-full flex justify-center items-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-orange-600"></div>
      </div>
    `;
  }

  // Hide loading state
  function hideLoading() {
    // Loading will be replaced by render()
  }

  // Custom Dropdown Logic
  const trigger = document.getElementById('categoryTrigger');
  const menu = document.getElementById('categoryMenu');
  const selected = document.getElementById('selectedCategory');
  const icon = document.getElementById('dropdownIcon');

  // Toggle dropdown
  trigger.addEventListener('click', function () {
    const isOpen = !menu.classList.contains('invisible');
    if (isOpen) {
      closeDropdown();
    } else {
      openDropdown();
    }
  });

  // Close on option click + Trigger filter
  menu.querySelectorAll('button').forEach(option => {
    option.addEventListener('click', function () {
      const value = this.dataset.value;
      selected.textContent = this.textContent;
      currentCategory = value;
      currentPage = 1;
      fetchArticles();
      closeDropdown();
    });
  });

  // Close on outside click
  document.addEventListener('click', function (event) {
    if (!trigger.contains(event.target)) {
      closeDropdown();
    }
  });

  function openDropdown() {
    menu.classList.remove('opacity-0', 'invisible', 'scale-95', 'translate-y-1');
    menu.classList.add('opacity-100', 'visible', 'scale-100', 'translate-y-0');
    icon.style.transform = 'rotate(180deg)';
  }

  function closeDropdown() {
    menu.classList.remove('opacity-100', 'visible', 'scale-100', 'translate-y-0');
    menu.classList.add('opacity-0', 'invisible', 'scale-95', 'translate-y-1');
    icon.style.transform = 'rotate(0deg)';
  }

  function render() {
    grid.innerHTML = '';
    const list = filtered.slice(0, currentPage * perPage);

    if (list.length === 0) {
      grid.innerHTML = '<div class="col-span-full text-center text-gray-500 py-12">Tidak ada artikel ditemukan</div>';
      document.getElementById('loadMore').style.display = 'none';
      return;
    }

    list.forEach(a => {
      const node = tpl.content.cloneNode(true);
      const img = node.querySelector('img');
      if (img) {
        img.src = a.image || '/assets/elearning/client/img/blogilustrator.jpeg';
        img.alt = a.title || 'Article image';
        img.onerror = function () {
          this.src = '/assets/elearning/client/img/blogilustrator.jpeg';
        };
      }

      const catEl = node.querySelector('[data-cat]');
      if (catEl) catEl.textContent = a.category ? a.category.toUpperCase() : 'UMUM';

      const titleEl = node.querySelector('[data-title]');
      if (titleEl) titleEl.textContent = a.title || 'Untitled';

      const dateEl = node.querySelector('[data-date]');
      if (dateEl) dateEl.textContent = a.published_at || '-';

      // Link ke detail artikel menggunakan slug
      const linkEl = node.querySelector('[data-link]');
      if (linkEl && a.slug) {
        linkEl.href = `/artikel/${a.slug}`;
      }

      grid.appendChild(node);
    });

    const loadMoreBtn = document.getElementById('loadMore');
    if (filtered.length > list.length) {
      loadMoreBtn.style.display = 'inline-block';
    } else {
      loadMoreBtn.style.display = 'none';
    }
  }

  // Initial load
  fetchArticles();

  // Event listeners
  document.getElementById('loadMore').addEventListener('click', () => {
    currentPage++;
    render();
  });

  // Search input (jika ada elemen #search)
  const searchEl = document.getElementById('search');
  if (searchEl) {
    let searchTimeout;
    searchEl.addEventListener('input', (e) => {
      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(() => {
        const q = e.target.value.toLowerCase().trim();
        currentPage = 1;
        filtered = articles.filter(a => {
          const searchText = (a.title + ' ' + a.excerpt + ' ' + a.category).toLowerCase();
          return searchText.includes(q);
        });
        render();
      }, 300); // Debounce 300ms
    });
  }

  // Sort button
  const sortBtn = document.getElementById('sortBtn');
  sortBtn.addEventListener('click', (e) => {
    const b = e.currentTarget;
    if (currentSort === 'newest') {
      currentSort = 'oldest';
      b.textContent = 'Terlama';
    } else {
      currentSort = 'newest';
      b.textContent = 'Terbaru';
    }
    currentPage = 1;
    fetchArticles();
  });

  // Mobile button (jika ada)
  const mobileBtn = document.getElementById('mobileBtn');
  if (mobileBtn) {
    mobileBtn.addEventListener('click', () => {
      document.getElementById('mobileNav').classList.toggle('hidden');
    });
  }

  // Keyboard escape untuk mobile nav (jika ada)
  window.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      const mobileNav = document.getElementById('mobileNav');
      if (mobileNav) mobileNav.classList.add('hidden');
      closeDropdown();
    }
  });
});

