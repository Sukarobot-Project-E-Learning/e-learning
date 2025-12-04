@extends('client.main')

@section('css')
<link rel="stylesheet" href="{{ 'client/css/instruktur.css' }}">
@endsection

@section('body')
<!-- HERO SECTION -->
<section class="py-16 bg-gradient-to-br from-blue-50 via-white to-orange-50 relative overflow-visible text-center pt-34">
    <!-- Background Elements -->
    <div class="absolute top-0 right-0 w-[300px] h-[300px] bg-orange-200/20 rounded-full blur-[80px] animate-pulse pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-[300px] h-[300px] bg-blue-200/20 rounded-full blur-[80px] animate-pulse pointer-events-none"></div>

    <div class="relative z-10 max-w-4xl mx-auto px-6">
        <h1 class="text-3xl md:text-5xl font-extrabold text-gray-900 mb-6 leading-tight">
            Instruktur <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-orange-500">Profesional & Berpengalaman</span>
        </h1>
        <p class="text-gray-600 text-lg mb-8 max-w-2xl mx-auto">
            Belajar langsung dari para ahli yang siap membimbingmu mencapai potensi terbaik.
        </p>
        
        <!-- Custom dropdown -->
        <div class="relative w-full max-w-xs mx-auto z-20">
            <button id="dropdownBtn" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-gray-700 bg-white shadow-sm flex justify-between items-center hover:border-blue-400 transition font-medium">
                <span>Semua Keahlian</span>
                <svg id="dropdownIcon" class="w-5 h-5 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M6 9l6 6 6-6" />
                </svg>
            </button>
            <ul id="dropdownMenu" class="absolute left-0 right-0 mt-2 bg-white border border-gray-100 rounded-xl shadow-xl hidden overflow-auto z-[9999] py-2">
                <li class="px-4 py-2.5 hover:bg-blue-50 hover:text-blue-600 cursor-pointer transition-colors text-sm font-medium text-gray-600" data-value="all">Semua Keahlian</li>
            </ul>
        </div>
    </div>
</section>

<!-- GRID INSTRUKTUR + PAGINATION -->
<div class="container mx-auto px-6 py-16 max-w-7xl">
  <div id="instructorGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8"></div>
  <div id="pagination" class="flex justify-center mt-12 space-x-2 flex-wrap"></div>
</div>

<!-- MODAL SCROLLABLE -->
<div class="fixed inset-0 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm hidden z-[9999]" id="modalBg">
  <div class="bg-white rounded-2xl max-w-md w-full relative flex flex-col shadow-2xl overflow-hidden transform transition-all scale-100">
    <button class="absolute top-3 right-3 bg-white/80 p-1 rounded-full text-gray-500 hover:text-red-500 transition-colors z-10" id="modalClose">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
    <img id="modalImage" class="w-full h-56 object-cover" src="" alt="Instruktur">
    <div id="modalBody" class="p-6 overflow-y-auto max-h-[60vh]"></div>
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

  let instructorsPerPage = 8;
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
      card.className = 'group bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col';
      card.innerHTML = `
        <div class="relative overflow-hidden">
            <img class="w-full h-56 object-cover transition-transform duration-500 group-hover:scale-105" src="${inst.foto}" alt="${inst.nama}">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        </div>
        <div class="p-6 flex flex-col flex-1">
          <div class="mb-3">
            <span class="inline-block bg-blue-50 text-blue-600 text-xs font-bold px-2.5 py-1 rounded-full mb-2 border border-blue-100">${inst.keahlian}</span>
            <h2 class="text-lg font-bold text-gray-900 group-hover:text-blue-600 transition-colors line-clamp-1">${inst.nama}</h2>
            <p class="text-gray-500 text-sm font-medium">${inst.jabatan}</p>
          </div>
          
          <p class="text-gray-600 text-sm line-clamp-2 mb-4 leading-relaxed">${inst.deskripsi}</p>
          
          <div class="mt-auto pt-4 border-t border-gray-50 flex items-center justify-between">
             <span class="text-xs font-semibold text-gray-400 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                ${inst.pengalaman}
             </span>
             <button class="text-blue-600 font-bold text-sm hover:underline viewProfileBtn">Lihat Profil</button>
          </div>
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
      btn.className = `w-10 h-10 rounded-xl text-sm font-bold transition-all flex items-center justify-center ${i === currentPage
        ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30'
        : 'bg-white text-gray-600 border border-gray-200 hover:border-blue-400 hover:text-blue-600'
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
    li.className = 'px-4 py-2.5 hover:bg-blue-50 hover:text-blue-600 cursor-pointer transition-colors text-sm font-medium text-gray-600';
    li.innerText = k;
    li.dataset.value = k;
    li.addEventListener('click', e => {
      selectedKeahlian = e.target.dataset.value;
      currentPage = 1;
      renderPage(1);
      dropdownBtn.querySelector('span').textContent = e.target.innerText;
      dropdownMenu.classList.add('hidden');
      dropdownIcon.classList.remove('rotate-180');
    });
    dropdownMenu.appendChild(li);
  });

  document.querySelector('[data-value="all"]').addEventListener('click', e => {
    selectedKeahlian = 'all';
    currentPage = 1;
    renderPage(1);
    dropdownBtn.querySelector('span').textContent = e.target.innerText;
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
      <div class="mb-4">
          <span class="inline-block bg-blue-50 text-blue-600 text-xs font-bold px-2.5 py-1 rounded-full mb-2 border border-blue-100">${inst.keahlian}</span>
          <h2 class="text-2xl font-bold text-gray-900">${inst.nama}</h2>
          <p class="text-blue-600 font-medium">${inst.jabatan}</p>
      </div>
      
      <div class="flex items-center gap-2 text-sm text-gray-500 mb-4 bg-gray-50 p-3 rounded-xl">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
        <span class="font-semibold">Pengalaman: ${inst.pengalaman}</span>
      </div>

      <p class="text-gray-700 leading-relaxed">${inst.deskripsi}</p>
    `;
    modalBg.classList.remove('hidden');
    setTimeout(() => {
        modalBg.children[0].classList.remove('scale-95', 'opacity-0');
        modalBg.children[0].classList.add('scale-100', 'opacity-100');
    }, 10);
  }

  modalClose.addEventListener('click', closeModal);
  modalBg.addEventListener('click', e => { if (e.target === modalBg) closeModal(); });

  function closeModal() {
      modalBg.classList.add('hidden');
  }
</script>

@endsection
