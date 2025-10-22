const instructors = [
    { foto:'https://images.unsplash.com/photo-1531427186611-ecfd6d936e63?auto=format&fit=crop&w=500&q=60', nama:'Muhammad Dwi Permadi', jabatan:'Social Media Manager', pengalaman:'7 Tahun', keahlian:'Social Media', deskripsi:'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor. Cras elementum ultrices diam. Maecenas ligula massa, varius a, semper congue, euismod non, mi. Proin porttitor, orci nec nonummy molestie, enim est eleifend mi, non fermentum diam nisl sit amet erat. Duis semper.' },
    { foto:'https://images.unsplash.com/photo-1595152772835-219674b2a8a6?auto=format&fit=crop&w=500&q=60', nama:'Yosua Pangayoman', jabatan:'Training Chief', pengalaman:'8 Tahun', keahlian:'Cleaning Service', deskripsi:'Yosua merupakan Training Chief di PT. Dana Purna Investama dan ahli training kebersihan.' },
    { foto:'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=500&q=60', nama:'Anisa Rahma', jabatan:'Graphic Designer', pengalaman:'5 Tahun', keahlian:'Design', deskripsi:'Anisa Rahma adalah seorang Graphic Designer berpengalaman dengan portofolio internasional.' },
    { foto:'https://images.unsplash.com/photo-1502764613149-7f1d229e230f?auto=format&fit=crop&w=500&q=60', nama:'Budi Santoso', jabatan:'Trainer IT', pengalaman:'6 Tahun', keahlian:'IT Training', deskripsi:'Budi Santoso adalah trainer IT yang ahli dalam berbagai bahasa pemrograman.' },
    { foto:'https://images.unsplash.com/photo-1552374196-c4e7ffc6e126?auto=format&fit=crop&w=500&q=60', nama:'Dewi Lestari', jabatan:'Content Creator', pengalaman:'4 Tahun', keahlian:'Content Creation', deskripsi:'Dewi Lestari ahli dalam membuat konten kreatif digital untuk brand besar.' },
    { foto:'https://images.unsplash.com/photo-1552374196-abc123?auto=format&fit=crop&w=500&q=60', nama:'Ahmad Fauzi', jabatan:'Trainer AI', pengalaman:'3 Tahun', keahlian:'AI', deskripsi:'Ahmad Fauzi fokus pada pengembangan AI dan Machine Learning.' },
    { foto:'https://images.unsplash.com/photo-1552374196-def456?auto=format&w=500&q=60', nama:'Siti Nurhaliza', jabatan:'Marketing Manager', pengalaman:'6 Tahun', keahlian:'Marketing', deskripsi:'Siti Nurhaliza mengelola strategi marketing untuk berbagai perusahaan startup.' },
    { foto:'https://images.unsplash.com/photo-1552374196-ghi789?auto=format&w=500&q=60', nama:'Rudi Hartono', jabatan:'Trainer Robotics', pengalaman:'5 Tahun', keahlian:'Robotics', deskripsi:'Rudi Hartono ahli robotics dan automation.' },
    { foto:'https://images.unsplash.com/photo-1552374196-jkl012?auto=format&w=500&q=60', nama:'Linda Amalia', jabatan:'Designer', pengalaman:'4 Tahun', keahlian:'Design', deskripsi:'Linda Amalia berpengalaman membuat desain UI/UX.' },
    { foto:'https://images.unsplash.com/photo-1552374196-mno345?auto=format&w=500&q=60', nama:'Fajar Pratama', jabatan:'Trainer IT', pengalaman:'7 Tahun', keahlian:'IT Training', deskripsi:'Fajar Pratama spesialis IT Training dan Cloud Computing.' }
  ];

  let instructorsPerPage = 4;
  let currentPage = 1;
  let selectedKeahlian = 'all';

  const filteredInstructors = () => selectedKeahlian==='all'?instructors:instructors.filter(i=>i.keahlian===selectedKeahlian);
  const totalPages = () => Math.ceil(filteredInstructors().length/instructorsPerPage);

  function renderPage(page){
    const grid = document.getElementById('instructorGrid');
    grid.innerHTML='';
    const start = (page-1)*instructorsPerPage;
    const pageInstructors = filteredInstructors().slice(start,start+instructorsPerPage);

    pageInstructors.forEach(inst=>{
      const card = document.createElement('div');
      card.className='bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transform hover:-translate-y-1 transition duration-300';
      card.innerHTML=`
        <img class="w-full h-44 object-cover" src="${inst.foto}" alt="${inst.nama}">
        <div class="p-5">
          <h2 class="text-base font-semibold text-blue-900">${inst.nama}</h2>
          <p class="text-gray-600 text-sm">${inst.jabatan}</p>
          <div class="flex justify-between mt-2 text-xs text-gray-500">
            <span>${inst.pengalaman}</span>
            <span class="bg-blue-50 text-blue-700 border border-blue-200 px-2 py-0.5 rounded-full">${inst.keahlian}</span>
          </div>
          <button class="mt-3 px-3 py-1 text-sm text-white bg-blue-500 rounded hover:bg-blue-600 transition viewProfileBtn">Lihat Profil Lengkap</button>
        </div>`;
      grid.appendChild(card);

      card.querySelector('.viewProfileBtn').addEventListener('click',()=>{showModal(inst)});
    });
    renderPagination();
  }

  function renderPagination(){
    const pagination=document.getElementById('pagination');
    pagination.innerHTML='';
    for(let i=1;i<=totalPages();i++){
      const btn=document.createElement('button');
      btn.className=`px-3 py-1 rounded text-sm ${i===currentPage?'bg-blue-500 text-white shadow':'bg-gray-200 text-gray-700 hover:bg-gray-300 transition'}`;
      btn.innerText=i;
      btn.onclick=()=>{currentPage=i;renderPage(i)};
      pagination.appendChild(btn);
    }
  }

  const dropdownBtn = document.getElementById('dropdownBtn');
  const dropdownMenu = document.getElementById('dropdownMenu');
  const dropdownIcon = document.getElementById('dropdownIcon');

  new Set(instructors.map(i=>i.keahlian)).forEach(k=>{
    if(k==='all') return;
    const li=document.createElement('li');
    li.className='px-3 py-2 hover:bg-blue-100 cursor-pointer';
    li.innerText=k;
    li.dataset.value=k;
    li.addEventListener('click', e=>{
      selectedKeahlian = e.target.dataset.value;
      currentPage = 1;
      renderPage(1);
      dropdownBtn.firstChild.textContent = e.target.innerText;
      dropdownMenu.classList.add('hidden');
      dropdownIcon.classList.remove('rotate-180'); // putar kembali saat menutup
    });
    dropdownMenu.appendChild(li);
  });

  // Event dropdown toggle
  dropdownBtn.addEventListener('click', ()=>{
    dropdownMenu.classList.toggle('hidden');
    dropdownIcon.classList.toggle('rotate-180'); // putar panah saat toggle
  });

  // Close dropdown jika klik di luar
  document.addEventListener('click', e=>{
    if(!dropdownBtn.contains(e.target) && !dropdownMenu.contains(e.target)){
      dropdownMenu.classList.add('hidden');
      dropdownIcon.classList.remove('rotate-180');
    }
  });

  renderPage(1);

  // Modal logic
  const modalBg=document.getElementById('modalBg');
  const modalBody=document.getElementById('modalBody');
  const modalImage=document.getElementById('modalImage');
  const modalClose=document.getElementById('modalClose');

  function showModal(inst){
    modalImage.src=inst.foto;
    modalBody.innerHTML=`
      <h2 class="text-lg font-bold mb-1">${inst.nama}</h2>
      <p class="text-gray-600 mb-1">${inst.jabatan}</p>
      <p class="text-gray-500 mb-1">Pengalaman: ${inst.pengalaman}</p>
      <p class="text-gray-500 mb-1">Keahlian: ${inst.keahlian}</p>
      <p class="text-gray-700 mt-2">${inst.deskripsi}</p>
    `;
    modalBg.classList.remove('hidden');
  }

  modalClose.addEventListener('click',()=>{modalBg.classList.add('hidden')});
  modalBg.addEventListener('click',e=>{if(e.target===modalBg) modalBg.classList.add('hidden')});

