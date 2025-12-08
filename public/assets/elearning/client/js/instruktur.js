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