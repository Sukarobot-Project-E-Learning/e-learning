@extends('client.main')

@section('css')
<link rel="stylesheet" href="{{ 'client/css/instruktur.css' }}">
@endsection

@section('body')
<!-- HERO SECTION -->
<section class="relative rounded-b-xl py-12 bg-gradient-to-br from-indigo-100 via-sky-100 to-blue-50 overflow-visible pt-24">
  <div class="absolute inset-0 pointer-events-none overflow-hidden">
    <svg class="absolute top-0 left-0 w-full h-full opacity-60" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" preserveAspectRatio="none">
      <defs>
        <linearGradient id="softWave" x1="0" y1="0" x2="1" y2="1">
          <stop offset="0%" stop-color="#93c5fd" stop-opacity="0.3" />
          <stop offset="100%" stop-color="#3b82f6" stop-opacity="0.1" />
        </linearGradient>
        <pattern id="tinyDots" x="0" y="0" width="30" height="30" patternUnits="userSpaceOnUse">
          <circle cx="2" cy="2" r="1.2" fill="rgba(37,99,235,0.1)" />
        </pattern>
      </defs>
      <rect width="100%" height="100%" fill="url(#softWave)" />
      <rect width="100%" height="100%" fill="url(#tinyDots)" />
      <path fill="rgba(59,130,246,0.15)" d="M0,160 C480,280 960,40 1440,200 L1440,320 L0,320 Z"></path>
    </svg>
  </div>

  <div class="container mx-auto px-6 max-w-5xl relative z-10 flex flex-col items-center md:items-start text-center md:text-left">
    <h1 class="text-3xl md:text-4xl font-bold text-blue-900 mb-3 relative inline-block">
      <span>Instruktur Kami</span>
    </h1>
    <p class="text-gray-700 mb-6 text-sm md:text-base max-w-md">
      Kenali para instruktur yang siap membimbingmu di Sukarobot!
    </p>

    <!-- Custom dropdown -->
    <div class="relative w-full max-w-full sm:w-2/3 md:w-1/3 z-20">
      <button id="dropdownBtn" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-700 bg-white/90 text-sm flex justify-between items-center">
        Semua Keahlian
        <svg id="dropdownIcon" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path d="M6 9l6 6 6-6" />
        </svg>
      </button>
      <ul id="dropdownMenu" class="absolute left-0 right-0 mt-1 bg-white border border-gray-300 rounded-lg shadow-lg hidden max-h-60 overflow-auto z-50">
        <li class="px-3 py-2 hover:bg-blue-100 cursor-pointer" data-value="all">Semua Keahlian</li>
      </ul>
    </div>
  </div>
</section>

<!-- GRID INSTRUKTUR + PAGINATION -->
<div class="container mx-auto px-6 py-10 max-w-5xl">
  <div id="instructorGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6"></div>
  <div id="pagination" class="flex justify-center mt-6 space-x-2 flex-wrap"></div>
</div>

<!-- MODAL SCROLLABLE -->
<div class="fixed inset-0 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm hidden z-[9999]" id="modalBg">
  <div class="bg-white rounded-lg max-w-md w-full relative flex flex-col shadow-lg">
    <button class="absolute top-2 right-3 text-gray-500 hover:text-gray-700 font-bold text-xl" id="modalClose">&times;</button>
    <img id="modalImage" class="w-full h-48 object-cover rounded-t-lg" src="" alt="Instruktur">
    <div id="modalBody" class="p-4 overflow-y-auto max-h-64"></div>
  </div>
</div>

<script>
  const instructors = [
    { foto: 'https://images.unsplash.com/photo-1531427186611-ecfd6d936e63?auto=format&fit=crop&w=500&q=60', nama: 'Muhammad Dwi Permadi', jabatan: 'Social Media Manager', pengalaman: '7 Tahun', keahlian: 'Social Media', deskripsi: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet.' },
    { foto: 'https://images.unsplash.com/photo-1595152772835-219674b2a8a6?auto=format&fit=crop&w=500&q=60', nama: 'Yosua Pangayoman', jabatan: 'Training Chief', pengalaman: '8 Tahun', keahlian: 'Cleaning Service', deskripsi: 'Yosua merupakan Training Chief di PT. Dana Purna Investama dan ahli training kebersihan.' },
    { foto: 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=500&q=60', nama: 'Anisa Rahma', jabatan: 'Graphic Designer', pengalaman: '5 Tahun', keahlian: 'Design', deskripsi: 'Anisa Rahma adalah seorang Graphic Designer berpengalaman dengan portofolio internasional.' },
    { foto: 'https://images.unsplash.com/photo-1502764613149-7f1d229e230f?auto=format&fit=crop&w=500&q=60', nama: 'Budi Santoso', jabatan: 'Trainer IT', pengalaman: '6 Tahun', keahlian: 'IT Training', deskripsi: 'Budi Santoso adalah trainer IT yang ahli dalam berbagai bahasa pemrograman.' },
    { foto: 'https://images.unsplash.com/photo-1552374196-abc123?auto=format&fit=crop&w=500&q=60', nama: 'Ahmad Fauzi', jabatan: 'Trainer AI', pengalaman: '3 Tahun', keahlian: 'AI', deskripsi: 'Ahmad Fauzi fokus pada pengembangan AI dan Machine Learning.' },
    { foto: 'https://images.unsplash.com/photo-1552374196-def456?auto=format&w=500&q=60', nama: 'Siti Nurhaliza', jabatan: 'Marketing Manager', pengalaman: '6 Tahun', keahlian: 'Marketing', deskripsi: 'Siti Nurhaliza mengelola strategi marketing untuk berbagai perusahaan startup.' },
    { foto: 'https://images.unsplash.com/photo-1552374196-ghi789?auto=format&fit=crop&w=500&q=60', nama: 'Rudi Hartono', jabatan: 'Trainer Robotics', pengalaman: '5 Tahun', keahlian: 'Robotics', deskripsi: 'Rudi Hartono ahli robotics dan automation.' },
    { foto: 'https://images.unsplash.com/photo-1552374196-jkl012?auto=format&fit=crop&w=500&q=60', nama: 'Linda Amalia', jabatan: 'Designer', pengalaman: '4 Tahun', keahlian: 'Design', deskripsi: 'Linda Amalia berpengalaman membuat desain UI/UX.' },
    { foto: 'https://images.unsplash.com/photo-1552374196-mno345?auto=format&fit=crop&w=500&q=60', nama: 'Fajar Pratama', jabatan: 'Trainer IT', pengalaman: '7 Tahun', keahlian: 'IT Training', deskripsi: 'Fajar Pratama spesialis IT Training dan Cloud Computing.' }
  ];

  let instructorsPerPage = 4;
  let currentPage = 1;
  let selectedKeahlian = 'all';

  const filteredInstructors = () => selectedKeahlian === 'all' ? instructors : instructors.filter(i => i.keahlian === selectedKeahlian);
  const totalPages = () => Math.ceil(filteredInstructors().length / instructorsPerPage);

  function renderPage(page) {
    const grid = document.getElementById('instructorGrid');
    grid.innerHTML = '';
    const start = (page - 1) * instructorsPerPage;
    const pageInstructors = filteredInstructors().slice(start, start + instructorsPerPage);

    pageInstructors.forEach(inst => {
      const card = document.createElement('div');
      card.className = 'bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transform transition-all duration-300 hover:-translate-y-1 flex flex-col';
      card.innerHTML = `
        <img class="w-full h-44 object-cover transition-transform duration-500 hover:scale-105" src="${inst.foto}" alt="${inst.nama}">
        <div class="p-5 flex flex-col flex-1 space-y-1 sm:space-y-2">
          <h2 class="text-base font-semibold text-blue-900">${inst.nama}</h2>
          <p class="text-gray-600 text-sm">${inst.jabatan}</p>
          <div class="flex justify-between mt-1 sm:mt-2 text-xs text-gray-500">
            <span>${inst.pengalaman}</span>
            <span class="bg-blue-50 text-blue-700 border border-blue-200 px-2 py-0.5 rounded-full">${inst.keahlian}</span>
          </div>
          <p class="text-gray-700 line-clamp-2">${inst.deskripsi}</p>
          <button class="mt-auto px-3 py-1 text-sm text-white bg-blue-500 rounded hover:bg-blue-600 transition viewProfileBtn">Lihat Profil Lengkap</button>
        </div>`;
      grid.appendChild(card);

      card.querySelector('.viewProfileBtn').addEventListener('click', () => { showModal(inst); });
    });
    renderPagination();
  }

  function renderPagination() {
    const pagination = document.getElementById('pagination');
    pagination.innerHTML = '';
    for (let i = 1; i <= totalPages(); i++) {
      const btn = document.createElement('button');
      btn.className = `px-3 py-1 rounded text-sm ${i === currentPage
        ? 'bg-blue-500 text-white shadow'
        : 'bg-gray-200 text-gray-700 hover:bg-gray-300 transition'
        }`;
      btn.innerText = i;
      btn.onclick = () => { currentPage = i; renderPage(i); };
      pagination.appendChild(btn);
    }
  }

  const dropdownBtn = document.getElementById('dropdownBtn');
  const dropdownMenu = document.getElementById('dropdownMenu');
  const dropdownIcon = document.getElementById('dropdownIcon');

  // Tambahkan daftar keahlian unik ke dropdown
  new Set(instructors.map(i => i.keahlian)).forEach(k => {
    const li = document.createElement('li');
    li.className = 'px-3 py-2 hover:bg-blue-100 cursor-pointer';
    li.innerText = k;
    li.dataset.value = k;
    li.addEventListener('click', e => {
      selectedKeahlian = e.target.dataset.value;
      currentPage = 1;
      renderPage(1);
      dropdownBtn.childNodes[0].textContent = e.target.innerText;
      dropdownMenu.classList.add('hidden');
      dropdownIcon.classList.remove('rotate-180');
    });
    dropdownMenu.appendChild(li);
  });

  document.querySelector('[data-value="all"]').addEventListener('click', e => {
    selectedKeahlian = 'all';
    currentPage = 1;
    renderPage(1);
    dropdownBtn.childNodes[0].textContent = e.target.innerText;
    dropdownMenu.classList.add('hidden');
    dropdownIcon.classList.remove('rotate-180');
  });

  dropdownBtn.addEventListener('click', () => {
    dropdownMenu.classList.toggle('hidden');
    dropdownIcon.classList.toggle('rotate-180');
  });

  document.addEventListener('click', e => {
    if (!dropdownBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
      dropdownMenu.classList.add('hidden');
      dropdownIcon.classList.remove('rotate-180');
    }
  });

  // Render pertama kali
  renderPage(1);

  // MODAL
  const modalBg = document.getElementById('modalBg');
  const modalBody = document.getElementById('modalBody');
  const modalImage = document.getElementById('modalImage');
  const modalClose = document.getElementById('modalClose');

  function showModal(inst) {
    modalImage.src = inst.foto;
    modalBody.innerHTML = `
      <h2 class="text-lg font-bold mb-1">${inst.nama}</h2>
      <p class="text-gray-600 mb-1">${inst.jabatan}</p>
      <p class="text-gray-500 mb-1">Pengalaman: ${inst.pengalaman}</p>
      <p class="text-gray-500 mb-1">Keahlian: ${inst.keahlian}</p>
      <p class="text-gray-700 mt-2">${inst.deskripsi}</p>
    `;
    modalBg.classList.remove('hidden');
  }

  modalClose.addEventListener('click', () => { modalBg.classList.add('hidden'); });
  modalBg.addEventListener('click', e => { if (e.target === modalBg) modalBg.classList.add('hidden'); });
</script>

@endsection
