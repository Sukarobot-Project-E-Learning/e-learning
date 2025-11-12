document.addEventListener("DOMContentLoaded", () => {
  const filterButtons = Array.from(document.querySelectorAll('.filter-btn'));
  const sortButtons = Array.from(document.querySelectorAll('.sort-btn'));
  const searchInput = document.getElementById('kelas-search');
  const hideSoldInput = document.getElementById('hide-soldout-kelas');
  const container = document.querySelector('.kelas-container');
  const jumlahKelas = document.querySelector('.jumlah-kelas');
  const headerTitle = document.querySelector('.header'); // ← ambil elemen header judul

  if (!container) return;

  let cards = Array.from(container.querySelectorAll('.kelas-card'));
  const updateJumlahKelas = (count) => { if (jumlahKelas) jumlahKelas.textContent = `${count} Kelas`; };

  cards.forEach((c, i) => c.dataset.origIndex = i);

  let activeCategory = (document.querySelector('.filter-btn.bg-orange-500') || document.querySelector('.filter-btn.active'))?.dataset.filter || 'all';
  let sortOrder = (document.querySelector('.sort-btn.active')?.dataset.sort) || 'newest';
  let searchQuery = '';
  let hideSold = !!(hideSoldInput && hideSoldInput.checked);

  function normalize(s = '') { return String(s).trim().replace(/\s+/g, ' ').toLowerCase(); }

  function getSlots(card) {
    if (card.dataset.slots) return parseInt(card.dataset.slots, 10) || 0;
    const text = card.innerText || '';
    if (/kuota\s*habis/i.test(text)) return 0;
    const m = text.match(/sisa\s+(\d+)/i);
    return m ? parseInt(m[1], 10) : Infinity;
  }

  function matchesCategory(card) {
    if (!activeCategory || activeCategory === 'all') return true;
    const raw = card.dataset.category || '';
    const cats = raw.split(',').map(c => normalize(c));
    return cats.includes(normalize(activeCategory));
  }

  function matchesSearch(card) {
    if (!searchQuery) return true;
    return card.innerText.toLowerCase().includes(searchQuery.toLowerCase());
  }

  function updateHeaderTitle() {
    if (!headerTitle) return;
    if (activeCategory === 'all') {
      headerTitle.textContent = 'Semua Kelas';
    } else {
      headerTitle.textContent = activeCategory.charAt(0).toUpperCase() + activeCategory.slice(1);
    }
  }

  function applyAll() {
    cards = Array.from(container.querySelectorAll('.kelas-card'));

    let filtered = cards.filter(c => {
      if (!matchesCategory(c)) return false;
      if (!matchesSearch(c)) return false;
      if (hideSold && getSlots(c) <= 0) return false;
      return true;
    });

    filtered.sort((a, b) => {
      const da = a.dataset.date ? new Date(a.dataset.date).getTime() : parseInt(a.dataset.origIndex || 0, 10);
      const db = b.dataset.date ? new Date(b.dataset.date).getTime() : parseInt(b.dataset.origIndex || 0, 10);
      return sortOrder === 'newest' ? db - da : da - db;
    });

    cards.forEach(c => c.style.display = 'none');
    filtered.forEach(c => { c.style.display = 'block'; container.appendChild(c); });

    updateJumlahKelas(filtered.length);
    updateHeaderTitle(); // ← update judul setiap kali filter berubah
  }

  function debounce(fn, wait = 200) { let t; return (...args) => { clearTimeout(t); t = setTimeout(() => fn(...args), wait); }; }

  function handleHashFilter() {
    const hash = window.location.hash || '';
    if (hash.startsWith('#filter=')) {
      const filterValue = decodeURIComponent(hash.replace('#filter=', ''));
      const targetBtn = document.querySelector(`.filter-btn[data-filter="${filterValue}"]`);
      if (targetBtn) {
        filterButtons.forEach(b => { b.classList.remove('bg-orange-500', 'text-white'); b.classList.add('text-blue-600'); });
        targetBtn.classList.add('bg-orange-500', 'text-white');
        targetBtn.classList.remove('text-blue-600');
        activeCategory = targetBtn.dataset.filter || 'all';
        applyAll();
        return true;
      }
    }
    return false;
  }

  if (!handleHashFilter()) applyAll();

  window.addEventListener('hashchange', () => { handleHashFilter(); });

  filterButtons.forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      filterButtons.forEach(b => { b.classList.remove('bg-orange-500', 'text-white'); b.classList.add('text-blue-600'); });
      btn.classList.add('bg-orange-500', 'text-white');
      btn.classList.remove('text-blue-600');
      activeCategory = btn.dataset.filter || 'all';
      applyAll();
      try { window.location.hash = `filter=${encodeURIComponent(activeCategory)}`; } catch (err) { /* ignore */ }
    });
  });

  sortButtons.forEach(sb => {
    sb.addEventListener('click', (e) => {
      e.preventDefault();
      sortButtons.forEach(s => s.classList.remove('active'));
      sb.classList.add('active');
      sortOrder = sb.dataset.sort === 'oldest' ? 'oldest' : 'newest';
      applyAll();
    });
  });

  const activeSort = document.querySelector('.sort-btn.active') || document.querySelector('.sort-btn[data-sort="newest"]');
  if (activeSort) { activeSort.classList.add('active'); sortOrder = activeSort.dataset.sort || 'newest'; }

  if (searchInput) {
    searchInput.addEventListener('input', debounce((e) => {
      searchQuery = e.target.value || '';
      applyAll();
    }, 200));
  }

  if (hideSoldInput) {
    hideSoldInput.addEventListener('change', (e) => {
      hideSold = !!e.target.checked;
      applyAll();
    });
  }
});
