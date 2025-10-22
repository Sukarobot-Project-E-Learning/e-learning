

document.addEventListener('DOMContentLoaded', function() {
  // Data articles
  const articles = [
  // === PRODUCT (10 berita) ===
  // === PRODUCT (10 berita) ===
  { id: 1, title: 'Robot Kolaboratif Membantu Pabrik Pintar', category: 'product', date: '2025-09-29', excerpt: 'Implementasi cobot di lini produksi meningkatkan efisiensi 30%—studi kasus dari pabrik X.', image: "/admin/img/blog1.jpeg" },
  { id: 2, title: 'Lengan Robotik Baru untuk Industri Otomotif', category: 'product', date: '2025-09-20', excerpt: 'Produk terbaru menghadirkan presisi lebih tinggi dalam perakitan mobil modern.', image: "/admin/img/blog1.jpeg" },
  { id: 3, title: 'Robot Service di Rumah Sakit', category: 'product', date: '2025-09-15', excerpt: 'Robot membantu distribusi obat di rumah sakit besar Jakarta.', image: "/admin/img/blog1.jpeg" },
  { id: 4, title: 'Dapur Pintar dengan Robot Chef', category: 'product', date: '2025-09-10', excerpt: 'Robot memasak menu cepat saji dengan kualitas restoran.', image: "/admin/img/blog1.jpeg" },
  { id: 5, title: 'Robot Pertanian Menyemprot Tanaman', category: 'product', date: '2025-09-08', excerpt: 'Robot pertanian otomatis meningkatkan hasil panen padi.', image: "/admin/img/blog1.jpeg" },
  { id: 6, title: 'Robot Pembersih Jalan Kota', category: 'product', date: '2025-09-05', excerpt: 'Teknologi robot sweeping mulai diadopsi pemerintah kota besar.', image: "/admin/img/blog1.jpeg" },
  { id: 7, title: 'Robot Kasir di Minimarket', category: 'product', date: '2025-09-03', excerpt: 'Minimarket menguji coba kasir berbasis robot.', image: "/admin/img/blog1.jpeg" },
  { id: 8, title: 'Robot Pemadam Api', category: 'product', date: '2025-09-01', excerpt: 'Robot pemadam dengan sensor panas digunakan dalam latihan darurat.', image: "/admin/img/blog1.jpeg" },
  { id: 9, title: 'Drone Robotik untuk Pengiriman Paket', category: 'product', date: '2025-08-29', excerpt: 'Drone otonom mulai mengirim paket di area perkotaan.', image: "/admin/img/blog1.jpeg" },
  { id: 10, title: 'Robot Kurir Dalam Gedung', category: 'product', date: '2025-08-27', excerpt: 'Robot kurir digunakan di pusat perbelanjaan besar.', image: "/admin/img/blog1.jpeg" },

  // === RESEARCH ===
  { id: 11, title: 'Algoritma Navigasi Baru untuk Drone Indoor', category: 'research', date: '2025-09-20', excerpt: 'Metode SLAM ringan memungkinkan drone menavigasi ruang tertutup dengan latency rendah.', image: "/admin/img/blog1.jpeg" },
  { id: 12, title: 'AI untuk Kontrol Robot Eksoskeleton', category: 'research', date: '2025-09-18', excerpt: 'Peneliti mengembangkan algoritma kontrol berbasis AI untuk rehabilitasi medis.', image: "/admin/img/blog1.jpeg" },
  { id: 13, title: 'Material Baru untuk Robot Lembut', category: 'research', date: '2025-09-16', excerpt: 'Robot lembut kini lebih fleksibel dengan material silikon generasi baru.', image: "/admin/img/blog1.jpeg" },
  { id: 14, title: 'Visi Komputer untuk Robot Industri', category: 'research', date: '2025-09-14', excerpt: 'Sistem penglihatan komputer meningkatkan akurasi deteksi komponen.', image: "/admin/img/blog1.jpeg" },
  { id: 15, title: 'Robot Sosial dengan Emosi Tiruan', category: 'research', date: '2025-09-12', excerpt: 'Studi terbaru meneliti respon emosional robot dalam interaksi manusia.', image: "/admin/img/blog1.jpeg" },
  { id: 16, title: 'Perkembangan Quantum AI untuk Robotik', category: 'research', date: '2025-09-10', excerpt: 'Quantum computing membuka kemungkinan algoritma kontrol baru.', image: "/admin/img/blog1.jpeg" },
  { id: 17, title: 'Robot Penjelajah Mars Miniatur', category: 'research', date: '2025-09-08', excerpt: 'Versi mini rover Mars diuji di padang pasir.', image: "/admin/img/blog1.jpeg" },
  { id: 18, title: 'AI Deteksi Kebocoran Gas Industri', category: 'research', date: '2025-09-06', excerpt: 'Sensor cerdas mendeteksi kebocoran gas berbahaya.', image: "/admin/img/blog1.jpeg" },
  { id: 19, title: 'Swarm Robot untuk Pencarian dan Penyelamatan', category: 'research', date: '2025-09-04', excerpt: 'Tim robot kecil bekerja bersama mencari korban bencana.', image: "/admin/img/blog1.jpeg" },
  { id: 20, title: 'Neural Network Khusus untuk Gerakan Robot', category: 'research', date: '2025-09-02', excerpt: 'Jaringan syaraf tiruan dikembangkan untuk optimasi gerak.', image: "/admin/img/blog1.jpeg" },

  // === EVENT ===
  { id: 21, title: 'Konferensi Robotika Asia 2025', category: 'event', date: '2025-09-25', excerpt: 'Konferensi terbesar Asia membahas tren terbaru robotika.', image: "/admin/img/blog1.jpeg" },
  { id: 22, title: 'Workshop Robot Edukasi untuk Pelajar', category: 'event', date: '2025-09-22', excerpt: 'Pelajar dikenalkan dengan robot edukasi di Jakarta.', image: "/admin/img/blog1.jpeg" },
  { id: 23, title: 'Pameran Teknologi AI & Robot', category: 'event', date: '2025-09-19', excerpt: 'Pameran internasional menampilkan AI dan robot terbaru.', image: "/admin/img/blog1.jpeg" },
  { id: 24, title: 'Hackathon Robot 2025', category: 'event', date: '2025-09-17', excerpt: 'Kompetisi 48 jam membangun solusi robotik inovatif.', image: "/admin/img/blog1.jpeg" },
  { id: 25, title: 'Seminar Robot Medis Masa Depan', category: 'event', date: '2025-09-15', excerpt: 'Seminar membahas penggunaan robot dalam bidang kesehatan.', image: "/admin/img/blog1.jpeg" },
  { id: 26, title: 'Kompetisi Robot Line Follower Nasional', category: 'event', date: '2025-09-13', excerpt: 'Tim dari berbagai sekolah berlomba membuat robot tercepat.', image: "/admin/img/blog1.jpeg" },
  { id: 27, title: 'Expo Start-up Robotik Indonesia', category: 'event', date: '2025-09-11', excerpt: 'Startup lokal pamerkan inovasi robotika.', image: "/admin/img/blog1.jpeg" },
  { id: 28, title: 'Pelatihan IoT untuk Robotika', category: 'event', date: '2025-09-09', excerpt: 'Pelatihan IoT dasar untuk pengembangan robot cerdas.', image: "/admin/img/blog1.jpeg" },
  { id: 29, title: 'Festival Drone Nasional 2025', category: 'event', date: '2025-09-07', excerpt: 'Festival tahunan menampilkan drone racing dan pameran.', image: "/admin/img/blog1.jpeg" },
  { id: 30, title: 'Kongres AI & Robot Global', category: 'event', date: '2025-09-05', excerpt: 'Para ilmuwan dunia bertemu membahas etika AI dan robot.', image: "/admin/img/blog1.jpeg" },
  ];

  const perPage = 4; let currentPage = 1; let filtered = [...articles];
  const grid = document.getElementById('articlesGrid'); const tpl = document.getElementById('cardTpl');


  // Custom Dropdown Logic
  const trigger = document.getElementById('categoryTrigger');
  const menu = document.getElementById('categoryMenu');
  const selected = document.getElementById('selectedCategory');
  const icon = document.getElementById('dropdownIcon');

  // Toggle dropdown
  trigger.addEventListener('click', function() {
    const isOpen = !menu.classList.contains('invisible');
    if (isOpen) {
      closeDropdown();
    } else {
      openDropdown();
    }
  });

  // Close on option click + Trigger filter
  menu.querySelectorAll('button').forEach(option => {
    option.addEventListener('click', function() {
      const value = this.dataset.value;
      selected.textContent = this.textContent;
      // Trigger filter logic (mirip change event pada select)
      currentPage = 1;
      filtered = value === 'all' ? [...articles] : articles.filter(x => x.category === value);
      render();
      closeDropdown();
    });
  });

  // Close on outside click
  document.addEventListener('click', function(event) {
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

  function render(){
grid.innerHTML = '';
const list = filtered.slice(0, currentPage * perPage);
list.forEach(a => {
const node = tpl.content.cloneNode(true);
node.querySelector('img').src = a.image;
node.querySelector('[data-cat]').textContent = a.category.toUpperCase();
node.querySelector('[data-title]').textContent = a.title;
node.querySelector('[data-excerpt]').textContent = a.excerpt;
node.querySelector('[data-date]').textContent = new Date(a.date).toLocaleDateString('id-ID', {year:'numeric', month:'short', day:'numeric'});

// Tambahan ini: buat link ke halaman detail
const linkEl = node.querySelector('[data-link]');
linkEl.href = `/berita?id=${a.id}`;


grid.appendChild(node);
});
document.getElementById('loadMore').style.display = filtered.length > list.length ? 'inline-block' : 'none';
}

  render();

  // Event listeners
  document.getElementById('loadMore').addEventListener('click', () => { currentPage++; render(); });

  // Hapus event listener untuk 'category' change karena sekarang custom dropdown menangani filter

  // Search input (jika ada elemen #search)
  const searchEl = document.getElementById('search');
  if (searchEl) {
    searchEl.addEventListener('input', (e) => {
      const q = e.target.value.toLowerCase().trim();
      currentPage = 1;
      filtered = articles.filter(a => (a.title + ' ' + a.excerpt).toLowerCase().includes(q));
      render();
    });
  }

  // Mobile button (jika ada)
  const mobileBtn = document.getElementById('mobileBtn');
  if (mobileBtn) {
    mobileBtn.addEventListener('click', () => { document.getElementById('mobileNav').classList.toggle('hidden'); });
  }

  // Sort button
  document.getElementById('sortBtn').addEventListener('click', (e) => {
    const b = e.currentTarget;
    if (b.dataset.order === 'asc') {
      b.dataset.order = 'desc';
      b.textContent = 'Terbaru';
      articles.sort((a, b) => new Date(b.date) - new Date(a.date));
    } else {
      b.dataset.order = 'asc';
      b.textContent = 'Terlama';
      articles.sort((a, b) => new Date(a.date) - new Date(b.date));
    }
    currentPage = 1;
    filtered = [...articles];
    selected.textContent = 'Semua Kategori'; // Reset dropdown visual
    if (searchEl) searchEl.value = ''; // Reset search jika ada
    render();
  });

  // Keyboard escape untuk mobile nav (jika ada)
  window.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      const mobileNav = document.getElementById('mobileNav');
      if (mobileNav) mobileNav.classList.add('hidden');
    }
  });
});

