

document.addEventListener('DOMContentLoaded', function () {
  // Variables
  let articles = [];
  let filtered = [];
  let currentPage = 1;
  let currentCategory = 'all';
  let currentSort = 'newest';
  const perPage = 12;
  
  // Get DOM elements with validation
  const grid = document.getElementById('articlesGrid');
  const tpl = document.getElementById('cardTpl');
  
  // Validate required elements exist
  if (!grid) {
    console.error('Element #articlesGrid not found');
    return;
  }
  
  if (!tpl) {
    console.error('Element #cardTpl not found');
    grid.innerHTML = '<div class="col-span-full text-center text-red-500 py-12">Template tidak ditemukan. Silakan refresh halaman.</div>';
    return;
  }


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
      
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      
      const data = await response.json();
      
      // Validate response data
      if (!data || !data.data) {
        throw new Error('Invalid response format from API');
      }
      
      // Ensure data.data is an array
      articles = Array.isArray(data.data) ? data.data : [];
      filtered = [...articles];
      
      console.log(`Loaded ${articles.length} articles successfully`);
      render();
    } catch (error) {
      console.error('Error fetching articles:', error);
      if (grid) {
        grid.innerHTML = '<div class="col-span-full text-center text-red-500 py-12">Gagal memuat artikel. Silakan refresh halaman.</div>';
      }
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

  // Toggle dropdown (with validation)
  if (trigger && menu) {
    trigger.addEventListener('click', function () {
      const isOpen = !menu.classList.contains('invisible');
      if (isOpen) {
        closeDropdown();
      } else {
        openDropdown();
      }
    });

    // Close on option click + Trigger filter
    if (menu) {
      menu.querySelectorAll('button').forEach(option => {
        option.addEventListener('click', function () {
          const value = this.dataset.value;
          if (selected) selected.textContent = this.textContent;
          currentCategory = value;
          currentPage = 1;
          fetchArticles();
          closeDropdown();
        });
      });
    }

    // Close on outside click
    document.addEventListener('click', function (event) {
      if (trigger && !trigger.contains(event.target)) {
        closeDropdown();
      }
    });
  }

  function openDropdown() {
    if (menu && icon) {
      menu.classList.remove('opacity-0', 'invisible', 'scale-95', 'translate-y-1');
      menu.classList.add('opacity-100', 'visible', 'scale-100', 'translate-y-0');
      icon.style.transform = 'rotate(180deg)';
    }
  }

  function closeDropdown() {
    if (menu && icon) {
      menu.classList.remove('opacity-100', 'visible', 'scale-100', 'translate-y-0');
      menu.classList.add('opacity-0', 'invisible', 'scale-95', 'translate-y-1');
      icon.style.transform = 'rotate(0deg)';
    }
  }

  function render() {
    try {
      grid.innerHTML = '';
      const list = filtered.slice(0, currentPage * perPage);

      if (list.length === 0) {
        grid.innerHTML = '<div class="col-span-full text-center text-gray-500 py-12">Tidak ada artikel ditemukan</div>';
        const loadMoreBtn = document.getElementById('loadMore');
        if (loadMoreBtn) loadMoreBtn.style.display = 'none';
        return;
      }

      list.forEach((a, index) => {
        try {
          // Validate template exists
          if (!tpl || !tpl.content) {
            console.error('Template element not found');
            return;
          }

          const node = tpl.content.cloneNode(true);
          
          // Set image
          const img = node.querySelector('img');
          if (img) {
            img.src = a.image || '/assets/elearning/client/img/blogilustrator.jpeg';
            img.alt = a.title || 'Article image';
            img.onerror = function () {
              this.src = '/assets/elearning/client/img/blogilustrator.jpeg';
            };
          }

          // Set category
          const catEl = node.querySelector('[data-cat]');
          if (catEl) {
            catEl.textContent = a.category ? a.category.toUpperCase() : 'UMUM';
          }

          // Set title
          const titleEl = node.querySelector('[data-title]');
          if (titleEl) {
            titleEl.textContent = a.title || 'Untitled';
          }

          // Set excerpt
          const excerptEl = node.querySelector('[data-excerpt]');
          if (excerptEl) {
            // Strip HTML tags if any (basic approach)
            const div = document.createElement('div');
            div.innerHTML = a.excerpt || '';
            excerptEl.textContent = div.textContent || div.innerText || '';
          }

          // Set date
          const dateEl = node.querySelector('[data-date]');
          if (dateEl) {
            dateEl.textContent = a.published_at || '-';
          }

          // Set link
          const linkEl = node.querySelector('[data-link]');
          if (linkEl && a.slug) {
            linkEl.href = `/artikel/${a.slug}`;
          }

          grid.appendChild(node);
        } catch (err) {
          console.error(`Error rendering article at index ${index}:`, err, a);
        }
      });

      // Handle load more button
      const loadMoreBtn = document.getElementById('loadMore');
      if (loadMoreBtn) {
        if (filtered.length > list.length) {
          loadMoreBtn.style.display = 'inline-block';
        } else {
          loadMoreBtn.style.display = 'none';
        }
      }
    } catch (error) {
      console.error('Error in render function:', error);
      grid.innerHTML = '<div class="col-span-full text-center text-red-500 py-12">Terjadi kesalahan saat menampilkan artikel</div>';
    }
  }

  // Initial load
  fetchArticles();

  // Event listeners
  const loadMoreBtn = document.getElementById('loadMore');
  if (loadMoreBtn) {
    loadMoreBtn.addEventListener('click', () => {
      currentPage++;
      render();
    });
  }

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
  if (sortBtn) {
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
  }

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

