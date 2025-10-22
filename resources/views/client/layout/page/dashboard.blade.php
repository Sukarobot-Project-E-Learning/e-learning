@extends('client.layout.main')

@section('css')

@endsection

@section('body')
<body class="font-sans antialiased text-slate-800">
@php
  $user = $user ?? (object)['name'=>'Ruhiyat','email'=>'ruhiyat@example.com'];
  $notifications = $notifications ?? [
    ['text'=>'Permintaan gabung kelas "AI Dasar" disetujui','time'=>'2 jam lalu'],
    ['text'=>'Pembayaran menunggu persetujuan: Order #SPP123','time'=>'1 hari lalu'],
    ['text'=>'Event "Hackathon SMK" akan dimulai besok','time'=>'2 hari lalu'],
  ];
  $classes = $classes ?? [
    ['id'=>1,'title'=>'Pemrograman Dasar','status'=>'aktif','members'=>28,'remaining_days'=>10,'remaining_sessions'=>3],
    ['id'=>2,'title'=>'Web Lanjut','status'=>'sedang diikuti','members'=>15,'remaining_days'=>5,'remaining_sessions'=>2],
    ['id'=>3,'title'=>'Robotika','status'=>'selesai','members'=>12,'remaining_days'=>0,'remaining_sessions'=>0],
  ];
  $events = $events ?? [
    ['title'=>'Lomba Koding 2025','poster'=>'https://picsum.photos/400/220?random=11','desc'=>'Kompetisi koding bergengsi untuk siswa SMK seluruh Indonesia.','date'=>'2025-07-21','status'=>'diikuti'],
    ['title'=>'Seminar IoT Nasional','poster'=>'https://picsum.photos/400/220?random=12','desc'=>'Bahas perkembangan IoT dan implementasinya di dunia industri.','date'=>'2024-11-12','status'=>'diikuti'],
    ['title'=>'Workshop UI/UX Design','poster'=>'https://picsum.photos/400/220?random=13','desc'=>'Belajar desain antarmuka modern dari expert.','date'=>'2025-01-15','status'=>'diikuti'],
  ];
  $payments = $payments ?? [
    ['title'=>'Order #SPP123','amount'=>120000,'status'=>'pending','date'=>'2025-10-14'],
    ['title'=>'Topup Dana','amount'=>50000,'status'=>'paid','date'=>'2025-09-10'],
  ];
  $recommendClasses = [
    ['title'=>'Machine Learning Dasar','tag'=>'Kelas','img'=>'https://picsum.photos/400/200?random=21'],
    ['title'=>'Python untuk AI','tag'=>'Kelas','img'=>'https://picsum.photos/400/200?random=22'],
    ['title'=>'Web Development Lanjutan','tag'=>'Kelas','img'=>'https://picsum.photos/400/200?random=23'],
    ['title'=>'Arduino Robotics','tag'=>'Kelas','img'=>'https://picsum.photos/400/200?random=24'],
  ];
  $recommendEvents = [
    ['title'=>'AI Summit 2025','tag'=>'Event','img'=>'https://picsum.photos/400/220?random=25'],
    ['title'=>'Hackathon FutureTech','tag'=>'Event','img'=>'https://picsum.photos/400/220?random=26'],
    ['title'=>'Seminar Cloud Computing','tag'=>'Event','img'=>'https://picsum.photos/400/220?random=27'],
    ['title'=>'Workshop UI/UX Advance','tag'=>'Event','img'=>'https://picsum.photos/400/220?random=28'],
  ];
@endphp

<!-- DASHBOARD WRAPPER -->
<div class="max-w-7xl mx-auto px-6 py-8" x-data="dashboardData()">

  <!-- HEADER -->
  <div class="flex items-center justify-between mb-8">
    <div>
      <h1 class="text-2xl font-semibold text-slate-900">Dashboard</h1>
      <p class="text-sm text-slate-500 mt-1">Selamat datang kembali 👋, {{ $user->name }}</p>
    </div>

    <div class="flex items-center gap-4">
      <!-- NOTIFIKASI -->
      <div class="relative" x-data="{ open:false }" @click.outside="open=false">
        <button @click="open=!open" class="relative p-2 rounded-lg hover:bg-slate-100 transition">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M15 17h5l-1.4-1.4A2 2 0 0 1 18 14.2V11a6 6 0 1 0-12 0v3.2a2 2 0 0 1-.6 1.4L4 17h5m6 0v1a3 3 0 1 1-6 0v-1" />
          </svg>
          <span class="absolute -top-1 -right-1 text-xs bg-orange-500 text-white px-1.5 py-0.5 rounded-full">
            {{ count($notifications) }}
          </span>
        </button>

        <!-- CARD NOTIFIKASI -->
        <div x-show="open" x-transition class="absolute right-0 mt-2 w-80 bg-white border rounded-xl p-4 card-shadow z-50">
          <div class="flex justify-between items-center mb-2">
            <h3 class="font-semibold text-sm">Notifikasi</h3>
            <button @click="markAllRead()" class="text-xs text-blue-500 hover:underline">Tandai semua</button>
          </div>
          <div class="max-h-56 overflow-auto space-y-2">
            @foreach($notifications as $note)
              <div class="p-3 rounded-lg hover:bg-slate-50 transition">
                <p class="text-sm">{{ $note['text'] }}</p>
                <p class="text-xs text-slate-400 mt-1">{{ $note['time'] }}</p>
              </div>
            @endforeach
          </div>
        </div>
      </div>

      <!-- PROFIL -->
      <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-50 cursor-pointer">
        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-400 to-blue-500 flex items-center justify-center text-white font-semibold">
          {{ strtoupper(substr($user->name,0,1)) }}
        </div>
        <div class="hidden sm:block text-right">
          <div class="text-sm font-medium">{{ $user->name }}</div>
          <div class="text-xs text-slate-400">{{ $user->email }}</div>
        </div>
      </div>
    </div>
  </div>

  <!-- WELCOME SECTION -->
  <div class="bg-white glass rounded-2xl p-6 card-shadow mb-12">
    <div class="grid md:grid-cols-3 gap-6 items-center">
      <div class="md:col-span-2">
        <h2 class="text-xl font-semibold text-slate-900">Selamat Datang di Dashboard TheFuture 🚀</h2>
        <p class="text-slate-600 mt-2">
          Pantau seluruh aktivitas belajar, event, dan status pembayaran Anda di satu tempat. Mari terus maju menuju masa depan teknologi!
        </p>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-6">
          <div class="p-3 bg-blue-50 rounded-lg text-center">
            <div class="text-xs text-blue-600">Kelas Aktif</div>
            <div class="text-lg font-semibold">{{ collect($classes)->where('status','aktif')->count() }}</div>
          </div>
          <div class="p-3 bg-orange-50 rounded-lg text-center">
            <div class="text-xs text-orange-600">Sedang Diikuti</div>
            <div class="text-lg font-semibold">{{ collect($classes)->where('status','sedang diikuti')->count() }}</div>
          </div>
          <div class="p-3 bg-green-50 rounded-lg text-center">
            <div class="text-xs text-green-600">Event</div>
            <div class="text-lg font-semibold">{{ count($events) }}</div>
          </div>
          <div class="p-3 bg-gray-50 rounded-lg text-center">
            <div class="text-xs text-gray-600">Pending</div>
            <div class="text-lg font-semibold text-orange-500">{{ collect($payments)->where('status','pending')->count() }}</div>
          </div>
        </div>
      </div>

      <div class="hidden md:flex items-center justify-center">
        <img src="https://cdn-icons-png.flaticon.com/512/906/906175.png" class="w-44 opacity-90" alt="Mural" />
      </div>
    </div>
  </div>

  <div class="border-t border-slate-200 my-8"></div>

  <!-- KELAS SAYA -->
<!-- SECTION: KELAS SAYA -->
<div x-data="kelasSaya()" class="relative mb-20">
    <div class="flex items-center justify-between mb-8">
      <h3 class="text-2xl font-bold text-slate-900 flex items-center gap-2">
        📘 Kelas Saya
      </h3>
      <select x-model="classFilter"
        class="border border-slate-300 rounded-xl px-4 py-2 text-sm bg-white shadow-sm focus:ring-2 focus:ring-orange-400 outline-none transition">
        <option value="all">Semua</option>
        <option value="aktif">Aktif</option>
        <option value="sedang diikuti">Sedang Diikuti</option>
        <option value="selesai">Selesai</option>
      </select>
    </div>

    <div class="relative flex flex-col lg:flex-row gap-10 items-center justify-between">

      <!-- CARD UTAMA -->
      <div class="relative flex-1 max-w-3xl w-full">
        <!-- Tombol Kiri -->
        <button @click="prev"
          class="absolute -left-5 top-1/2 -translate-y-1/2 bg-white shadow-lg hover:bg-orange-100 p-3 rounded-full z-10 transition">
          ⬅️
        </button>

        <!-- CARD KELAS -->
        <template x-if="filteredClasses.length > 0">
          <div class="overflow-hidden rounded-2xl bg-white/80 backdrop-blur-md border border-slate-200 shadow-lg transition-all duration-500 relative">
            <template x-for="(c, i) in filteredClasses" :key="c.id">
              <div x-show="i === currentIndex"
                x-transition:enter="transition transform duration-700 ease-out"
                x-transition:enter-start="opacity-0 translate-x-10"
                x-transition:enter-end="opacity-100 translate-x-0"
                x-transition:leave="transition transform duration-700 ease-in"
                x-transition:leave-start="opacity-100 translate-x-0"
                x-transition:leave-end="opacity-0 -translate-x-10"
                class="flex flex-col md:flex-row items-stretch">

                <!-- Gambar -->
                <div class="relative md:w-1/2 overflow-hidden rounded-t-2xl md:rounded-l-2xl md:rounded-tr-none">
                  <img :src="'https://picsum.photos/600/320?random='+c.id"
                    class="w-full h-56 md:h-full object-cover transform hover:scale-105 transition-transform duration-700" />
                  <div
                    class="absolute top-3 left-3 px-3 py-1 text-xs rounded-full font-medium shadow-md"
                    :class="c.status==='selesai' ? 'bg-gray-400 text-white' : 'bg-gradient-to-r from-orange-500 to-blue-800 text-white'">
                    <span x-text="c.status"></span>
                  </div>
                </div>

                <!-- Isi Card -->
                <div class="p-6 flex flex-col justify-between md:w-1/2 bg-gradient-to-br from-white/90 to-slate-50">
                  <div>
                    <h4 class="text-xl font-semibold text-slate-900 mb-2 leading-tight" x-text="c.title"></h4>
                    <p class="text-sm text-slate-600 mb-1">👨‍🏫 Mentor: <span class="font-medium text-slate-800" x-text="c.mentor"></span></p>
                    <p class="text-sm text-slate-600 mb-1">📚 Materi: <span class="font-medium text-slate-800" x-text="c.materi"></span></p>
                    <p class="text-sm text-slate-600 mb-1">🕓 Jadwal: <span x-text="c.schedule"></span></p>
                    <p class="text-sm text-slate-600 mb-1">⏳ Durasi / Pertemuan: <span x-text="c.duration"></span></p>
                    <p class="text-sm text-slate-600 mb-1">📅 Mulai: <span x-text="c.start_date"></span></p>
                    <p class="text-sm text-slate-600 mb-1">🎯 Level: <span x-text="c.level"></span></p>
                    <p class="text-sm text-slate-600 mb-1">📖 Sisa Pertemuan: <span x-text="c.remaining_sessions + ' sesi'"></span></p>
                  </div>

                  <!-- Progress bar -->
                  <div class="mt-4">
                    <div class="flex justify-between text-xs text-slate-500 mb-1">
                      <span>Progress</span>
                      <span x-text="c.progress + '%'"></span>
                    </div>
                    <div class="h-2 w-full bg-slate-200 rounded-full overflow-hidden">
                      <div class="h-full bg-gradient-to-r from-orange-500 to-blue-700 rounded-full transition-all duration-700"
                        :style="`width:${c.progress}%`"></div>
                    </div>
                  </div>
                </div>
              </div>
            </template>
          </div>
        </template>

        <!-- Tombol Kanan -->
        <button @click="next"
          class="absolute -right-5 top-1/2 -translate-y-1/2 bg-white shadow-lg hover:bg-orange-100 p-3 rounded-full z-10 transition">
          ➡️
        </button>
      </div>

      <!-- ILUSTRATOR DI SAMPING KANAN -->
      <div class="hidden lg:flex flex-col items-center justify-center flex-1">
        <img src="https://illustrations.popsy.co/blue/student-with-laptop.svg" alt="Learning Illustration"
          class="w-80 hover:scale-105 transition-transform duration-700 drop-shadow-md" />
        <p class="text-center text-slate-600 mt-4 text-sm max-w-xs leading-relaxed">
          Tingkatkan potensi dirimu dengan kelas pilihan terbaik bersama mentor profesional. Belajar jadi lebih seru dan interaktif! 🚀
        </p>
      </div>
    </div>

    <!-- Jika kosong -->
    <template x-if="filteredClasses.length === 0">
      <p class="text-center text-slate-500 mt-10 italic">Belum ada kelas untuk kategori ini 😅</p>
    </template>
  </div>

  <script>
    function kelasSaya() {
      return {
        classFilter: 'all',
        currentIndex: 0,
        autoSlide: null,
        classes: [
          {
            id: 1,
            title: 'Fullstack Web Development',
            mentor: 'Ruhiyat Fauziah',
            materi: 'Laravel, Vue.js, TailwindCSS',
            status: 'aktif',
            remaining_sessions: 4,
            schedule: 'Senin & Kamis, 19.00 - 21.00',
            duration: '2 jam',
            start_date: '10 Oktober 2025',
            level: 'Intermediate',
            progress: 75
          },
          {
            id: 2,
            title: 'UI/UX Design Mastery',
            mentor: 'Nur Aini',
            materi: 'Figma, UX Research, Prototype',
            status: 'sedang diikuti',
            remaining_sessions: 2,
            schedule: 'Selasa & Jumat, 18.30 - 20.30',
            duration: '2 jam',
            start_date: '12 Oktober 2025',
            level: 'Beginner',
            progress: 60
          },
          {
            id: 3,
            title: 'Machine Learning Basics',
            mentor: 'Dimas Saputra',
            materi: 'Python, TensorFlow, Data Prep',
            status: 'selesai',
            remaining_sessions: 0,
            schedule: 'Rabu & Sabtu, 20.00 - 22.00',
            duration: '2 jam',
            start_date: '1 Oktober 2025',
            level: 'Advanced',
            progress: 100
          }
        ],
        get filteredClasses() {
          if (this.classFilter === 'all') return this.classes;
          return this.classes.filter(c => c.status === this.classFilter);
        },
        next() {
          if (this.filteredClasses.length > 0) {
            this.currentIndex = (this.currentIndex + 1) % this.filteredClasses.length;
          }
        },
        prev() {
          if (this.filteredClasses.length > 0) {
            this.currentIndex = (this.currentIndex - 1 + this.filteredClasses.length) % this.filteredClasses.length;
          }
        },
        startAutoSlide() {
          this.autoSlide = setInterval(() => this.next(), 5000);
        },
        stopAutoSlide() {
          clearInterval(this.autoSlide);
        },
        init() {
          this.startAutoSlide();
        }
      };
    }
  </script>




<!-- REKOMENDASI KELAS -->
<div class="mb-14">
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-xl font-semibold text-slate-900 flex items-center gap-2">
        🔥 Rekomendasi Kelas
      </h3>
      <a href="#" class="text-sm text-orange-600 hover:underline hover:text-orange-700 transition">
        Lihat Semua
      </a>
    </div>

    <div class="flex gap-6 overflow-x-auto scrollbar-hide pb-4">
      @foreach($recommendClasses as $r)
      <div
        class="group min-w-[260px] bg-white/90 backdrop-blur-lg rounded-2xl overflow-hidden border border-slate-200 hover:border-orange-400 transition-all duration-500 hover:shadow-2xl hover:-translate-y-2 relative"
      >
        <!-- Gambar -->
        <div class="relative overflow-hidden">
          <img
            src="{{ $r['img'] }}"
            alt="{{ $r['title'] }}"
            class="h-40 w-full object-cover transform group-hover:scale-110 transition-transform duration-700"
          />
          <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition duration-500"></div>
          <div class="absolute top-3 left-3 bg-orange-500 text-white text-[11px] font-medium px-2 py-1 rounded-full shadow-md">
            {{ $r['tag'] }}
          </div>
        </div>

        <!-- Isi -->
        <div class="p-4 flex flex-col justify-between h-[160px]">
          <h4 class="font-semibold text-slate-800 text-sm mb-2 line-clamp-2 group-hover:text-orange-600 transition">
            {{ $r['title'] }}
          </h4>
          <p class="text-xs text-slate-500 mb-3 leading-snug">
            Asah kemampuanmu dalam {{ strtolower($r['title']) }} dengan pembelajaran interaktif yang seru dan aplikatif.
          </p>

          <!-- Info kecil -->
          <div class="flex items-center justify-between text-[11px] text-slate-500 mb-3">
            <div class="flex items-center gap-1"><i class="fa-solid fa-signal text-orange-500"></i> Intermediate</div>
            <div class="flex items-center gap-1"><i class="fa-solid fa-user-group text-blue-700"></i> 120+</div>
            <div class="flex items-center gap-1"><i class="fa-solid fa-clock text-blue-700"></i> 12 Jam</div>
          </div>

          <div class="flex items-center justify-between">
            <div class="flex items-center gap-1 text-[11px] text-orange-500">
              ⭐ 4.8/5
            </div>
            <button
              class="px-3 py-1.5 text-sm bg-gradient-to-r from-orange-500 to-blue-800 text-white rounded-lg shadow-md hover:shadow-lg hover:scale-[1.05] transition-all duration-300"
            >
              Lihat Detail
            </button>
          </div>
        </div>

        <!-- Garis bawah saat hover -->
        <div class="absolute bottom-0 left-0 w-0 h-[3px] bg-gradient-to-r from-orange-500 to-blue-800 group-hover:w-full transition-all duration-300"></div>
      </div>
      @endforeach
    </div>
  </div>


  <!-- REKOMENDASI EVENT -->
  <div class="mb-12">
    <h3 class="text-lg font-semibold mb-3">Rekomendasi Event</h3>
    <div class="flex gap-4 overflow-x-auto scrollbar-hide pb-2">
      @foreach($recommendEvents as $e)
      <div class="min-w-[240px] bg-white rounded-xl overflow-hidden border hover:shadow-md transition">
        <img src="{{ $e['img'] }}" class="h-36 w-full object-cover" alt="{{ $e['title'] }}">
        <div class="p-3">
          <div class="text-xs text-orange-500 mb-1">{{ $e['tag'] }}</div>
          <h4 class="font-semibold mb-1 text-slate-800">{{ $e['title'] }}</h4>
          <p class="text-xs text-slate-500 mb-2">Event teknologi terkini dan networking kesempatan besar.</p>
          <button class="px-3 py-1 text-sm bg-blue-500 text-white rounded-lg hover:bg-blue-600">Ikuti</button>
        </div>
      </div>
      @endforeach
    </div>
  </div>

  <!-- HISTORY -->
  <div class="grid md:grid-cols-2 gap-6">
    <!-- EVENT -->
    <div class="bg-white rounded-2xl p-5 card-shadow">
      <h4 class="font-semibold mb-3">History Event</h4>
      <div class="space-y-3">
        @foreach($events as $e)
        <div class="flex gap-3 border rounded-xl p-3 hover:bg-slate-50 transition">
          <img src="{{ $e['poster'] }}" class="w-20 h-20 object-cover rounded-lg">
          <div class="flex-1">
            <h5 class="text-sm font-semibold">{{ $e['title'] }}</h5>
            <p class="text-xs text-slate-500 mt-1">{{ $e['desc'] }}</p>
            <div class="text-xs text-slate-400 mt-1">{{ $e['date'] }}</div>
          </div>
        </div>
        @endforeach
      </div>
    </div>

    <!-- PEMBAYARAN -->
    <div class="bg-white rounded-2xl p-5 card-shadow">
      <h4 class="font-semibold mb-3">History Pembayaran</h4>
      <div class="space-y-3">
        @foreach($payments as $p)
        <div class="border rounded-xl p-3 hover:bg-slate-50 transition">
          <div class="flex justify-between items-center">
            <div>
              <p class="font-medium text-sm">{{ $p['title'] }}</p>
              <p class="text-xs text-slate-500">Rp {{ number_format($p['amount'],0,',','.') }}</p>
              <p class="text-xs text-slate-400 mt-1">{{ $p['date'] }}</p>
            </div>
            <span class="text-xs font-semibold px-2 py-1 rounded-full
              {{ $p['status'] === 'pending' ? 'bg-orange-100 text-orange-600' : 'bg-green-100 text-green-600' }}">
              {{ ucfirst($p['status']) }}
            </span>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</div>

<!-- ALPINE.JS -->
<script src="//unpkg.com/alpinejs" defer></script>

<script>
function dashboardData() {
  return {
    classFilter: 'all',
    classes: @json($classes),
    markAllRead() {
      alert('Semua notifikasi ditandai dibaca (demo).');
    },
    get filteredClasses() {
      if (this.classFilter === 'all') return this.classes;
      return this.classes.filter(c => c.status === this.classFilter);
    }
  }
}
</script>
@endsection
