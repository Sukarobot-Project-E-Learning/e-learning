@extends('client.main')
@section('css')
<link rel="stylesheet" href="{{ 'client/css/about.css' }}">
@section('body')
      <!-- MOBILE MENU -->
  <div class="md:hidden bg-blue-700 text-white shadow-md sticky top-0 z-50">
    <div class="flex items-center justify-between px-4 py-3">
      <h1 class="text-lg font-semibold">Menu</h1>
      <button id="menu-toggle" class="focus:outline-none">
        <svg id="menu-icon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>
    </div>

    <div id="mobile-menu" class="hidden flex-col bg-blue-800 border-t border-blue-600">
      <button class="mobile-link text-left px-5 py-3 hover:bg-blue-600" data-target="tentang-kami">Tentang Kami</button>
      <button class="mobile-link text-left px-5 py-3 hover:bg-blue-600" data-target="kebijakan">Kebijakan & Privasi</button>
      <button class="mobile-link text-left px-5 py-3 hover:bg-blue-600" data-target="ketentuan">Ketentuan Pengguna</button>
      <button class="mobile-link text-left px-5 py-3 hover:bg-blue-600" data-target="FAQ">FAQ</button>
    </div>
  </div>

  <!-- KONTEN UTAMA -->
  <main class="flex-1 max-w-7xl mx-auto w-full px-4 sm:px-6 py-8 flex flex-col md:flex-row gap-8 pt-24">

    <!-- SIDEBAR (desktop) -->
    <aside class="hidden md:block w-64 h-fit top-24 bg-blue-700 text-white shadow-lg rounded-lg p-4 self-start">
      <nav class="space-y-2 text-sm">
        <a href="#" class="sidebar-link block px-3 py-2 rounded hover:bg-blue-800 active" data-target="tentang-kami">Tentang Kami</a>
        <a href="#" class="sidebar-link block px-3 py-2 rounded hover:bg-blue-800" data-target="kebijakan">Kebijakan & Privasi</a>
        <a href="#" class="sidebar-link block px-3 py-2 rounded hover:bg-blue-800" data-target="ketentuan">Ketentuan Pengguna</a>
        <a href="#" class="sidebar-link block px-3 py-2 rounded hover:bg-blue-800" data-target="FAQ">FAQ</a>
      </nav>
    </aside>

    <!-- KONTEN -->
    <section class="flex-1 bg-white shadow rounded-lg p-6 overflow-hidden">
      <!-- Tentang Kami -->
      <div id="tentang-kami" class="content-section">
        <h2 class="text-2xl font-bold mb-4">Tentang Kami</h2>
        <p>Sukarobot adalah platform pembelajaran teknologi dan robotika yang didedikasikan untuk semua kalangan.</p>
        <div class="my-6 flex justify-center">
          <img src="{{ 'client/img/Sukarobot-logo.png' }}" alt="Foto Tentang Kami" class="rounded-lg shadow-md object-cover w-full max-w-md">
        </div>
        <p class="mt-4 leading-relaxed">Kami percaya bahwa pembelajaran teknologi harus mudah diakses oleh siapa pun, di mana pun, dengan metode yang praktis dan interaktif.</p>
      </div>

      <!-- Kebijakan -->
      <div id="kebijakan" class="content-section hidden">
        <h2 class="text-2xl font-bold mb-4">Kebijakan & Privasi</h2>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec dignissim erat dictum enim posuere, nec mollis eros sodales. Ut quis lorem at diam semper interdum. Maecenas dictum accumsan dolor euismod volutpat. Nulla facilisi. Morbi in lorem gravida, convallis dui eget, pulvinar erat. Integer tincidunt interdum tristique. Quisque a pellentesque libero. Vestibulum iaculis lorem ac justo maximus consectetur. Praesent malesuada dui sed mauris convallis commodo. Duis a est ac ex iaculis bibendum. Proin ac tincidunt magna. Nam vel metus eget mauris sodales aliquet in quis dui. Suspendisse potenti. Vivamus sit amet pretium nunc, vel vulputate enim. In in nunc posuere, vulputate nulla ut, pellentesque arcu.
            Vestibulum fringilla purus sapien, a rhoncus sem accumsan in. Vestibulum vestibulum nulla sit amet nibh blandit hendrerit. Aliquam id gravida urna. Etiam diam risus, pretium sed mauris at, auctor tincidunt ante. Sed diam erat, ultrices facilisis enim id, volutpat consequat libero. Pellentesque ultricies, ligula sit amet cursus facilisis, dolor sapien feugiat augue, at dapibus quam risus id tortor. Fusce ac justo eu orci pharetra pulvinar nec vel orci. Nulla tristique condimentum elit, id iaculis odio euismod quis. Ut pretium, dui sit amet eleifend fringilla, sapien libero convallis leo, vitae porttitor mauris lacus ac felis. Nulla finibus suscipit massa vel viverra. Nunc euismod faucibus elit non ullamcorper.
            Duis non nulla felis. Praesent eget ipsum sem. Pellentesque id dui eu lacus rhoncus vestibulum. Phasellus tincidunt nulla vitae turpis porttitor porta. Donec eget magna at nibh condimentum hendrerit vehicula ut ante. Pellentesque mollis, felis eu congue mollis, mauris purus iaculis felis, eget dictum eros diam a lectus. Ut nec pulvinar orci. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nulla in quam felis. Etiam dapibus semper purus at iaculis. Vestibulum elementum, mi vitae lacinia luctus, massa diam interdum purus, tristique lacinia lacus ligula in nisl. Nulla iaculis nisi velit, vel suscipit ex porta ut.</p>
      </div>

    <!-- Ketentuan -->
    <div id="ketentuan" class="content-section hidden">
        <h2 class="text-2xl font-bold mb-4">Ketentuan Pengguna</h2>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec dignissim erat dictum enim posuere, nec mollis eros sodales. Ut quis lorem at diam semper interdum. Maecenas dictum accumsan dolor euismod volutpat. Nulla facilisi. Morbi in lorem gravida, convallis dui eget, pulvinar erat. Integer tincidunt interdum tristique. Quisque a pellentesque libero. Vestibulum iaculis lorem ac justo maximus consectetur. Praesent malesuada dui sed mauris convallis commodo. Duis a est ac ex iaculis bibendum. Proin ac tincidunt magna. Nam vel metus eget mauris sodales aliquet in quis dui. Suspendisse potenti. Vivamus sit amet pretium nunc, vel vulputate enim. In in nunc posuere, vulputate nulla ut, pellentesque arcu.
            Vestibulum fringilla purus sapien, a rhoncus sem accumsan in. Vestibulum vestibulum nulla sit amet nibh blandit hendrerit. Aliquam id gravida urna. Etiam diam risus, pretium sed mauris at, auctor tincidunt ante. Sed diam erat, ultrices facilisis enim id, volutpat consequat libero. Pellentesque ultricies, ligula sit amet cursus facilisis, dolor sapien feugiat augue, at dapibus quam risus id tortor. Fusce ac justo eu orci pharetra pulvinar nec vel orci. Nulla tristique condimentum elit, id iaculis odio euismod quis. Ut pretium, dui sit amet eleifend fringilla, sapien libero convallis leo, vitae porttitor mauris lacus ac felis. Nulla finibus suscipit massa vel viverra. Nunc euismod faucibus elit non ullamcorper.
            Duis non nulla felis. Praesent eget ipsum sem. Pellentesque id dui eu lacus rhoncus vestibulum. Phasellus tincidunt nulla vitae turpis porttitor porta. Donec eget magna at nibh condimentum hendrerit vehicula ut ante. Pellentesque mollis, felis eu congue mollis, mauris purus iaculis felis, eget dictum eros diam a lectus. Ut nec pulvinar orci. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nulla in quam felis. Etiam dapibus semper purus at iaculis. Vestibulum elementum, mi vitae lacinia luctus, massa diam interdum purus, tristique lacinia lacus ligula in nisl. Nulla iaculis nisi velit, vel suscipit ex porta ut.</p>
    </div>

      <!-- Bagian FAQ di dalam konten -->
    <div id="FAQ" class="content-section hidden">
        <h2 class="text-2xl font-bold mb-6">Bantuan (FAQ)</h2>
        <p class="text-gray-600 mb-6">Berikut beberapa pertanyaan yang sering diajukan oleh pengguna Sukarobot:</p>
      <div class="space-y-4">
      <!-- FAQ 1 -->
      <div class="border border-gray-200 rounded-lg overflow-hidden">
      <button class="faq-toggle w-full text-left px-4 py-3 font-semibold text-gray-800 flex justify-between items-center">
        <span>Siapa saja yang dapat belajar di Sukarobot?</span>
        <svg class="w-5 h-5 transition-transform transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
      </button>
      <div class="faq-content hidden px-4 pb-4 text-gray-600">
        Semua orang dapat belajar di Sukarobot selama dapat memahami bahasa Indonesia dan memiliki kemampuan dasar menggunakan komputer.
      </div>
    </div>

    <!-- FAQ 2 -->
    <div class="border border-gray-200 rounded-lg overflow-hidden">
      <button class="faq-toggle w-full text-left px-4 py-3 font-semibold text-gray-800 flex justify-between items-center">
        <span>Apa syarat agar dapat belajar secara optimal di Sukarobot?</span>
        <svg class="w-5 h-5 transition-transform transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
      </button>
      <div class="faq-content hidden px-4 pb-4 text-gray-600">
        Pastikan kamu memiliki koneksi internet yang stabil, perangkat yang memadai, dan lingkungan belajar yang nyaman.
      </div>
    </div>

    <!-- FAQ 3 -->
    <div class="border border-gray-200 rounded-lg overflow-hidden">
      <button class="faq-toggle w-full text-left px-4 py-3 font-semibold text-gray-800 flex justify-between items-center">
        <span>Bagaimana cara mendaftar di Sukarobot?</span>
        <svg class="w-5 h-5 transition-transform transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
      </button>
      <div class="faq-content hidden px-4 pb-4 text-gray-600">
        Kamu cukup mengunjungi situs resmi Sukarobot, klik “Daftar”, isi data diri, dan pilih program belajar.
      </div>
    </div>

    <!-- FAQ 4 -->
    <div class="border border-gray-200 rounded-lg overflow-hidden">
      <button class="faq-toggle w-full text-left px-4 py-3 font-semibold text-gray-800 flex justify-between items-center">
        <span>Apakah sertifikat disediakan setelah menyelesaikan kursus?</span>
        <svg class="w-5 h-5 transition-transform transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
      </button>
      <div class="faq-content hidden px-4 pb-4 text-gray-600">
        Ya! Peserta yang telah menyelesaikan kursus akan mendapatkan sertifikat resmi Sukarobot dalam format digital.
      </div>
    </div>

    <!-- FAQ 5 -->
    <div class="border border-gray-200 rounded-lg overflow-hidden">
      <button class="faq-toggle w-full text-left px-4 py-3 font-semibold text-gray-800 flex justify-between items-center">
        <span>Apakah ada biaya untuk mengikuti kursus di Sukarobot?</span>
        <svg class="w-5 h-5 transition-transform transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
      </button>
      <div class="faq-content hidden px-4 pb-4 text-gray-600">
        Beberapa program gratis dan beberapa program berbayar. Informasi lengkap tersedia pada halaman pendaftaran.
      </div>
    </div>

    <!-- FAQ 6 -->
    <div class="border border-gray-200 rounded-lg overflow-hidden">
      <button class="faq-toggle w-full text-left px-4 py-3 font-semibold text-gray-800 flex justify-between items-center">
        <span>Apakah materi bisa diakses kapan saja?</span>
        <svg class="w-5 h-5 transition-transform transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
      </button>
      <div class="faq-content hidden px-4 pb-4 text-gray-600">
        Ya, semua materi kursus dapat diakses secara online 24/7 selama akun aktif.
      </div>
    </div>

    <!-- FAQ 7 -->
    <div class="border border-gray-200 rounded-lg overflow-hidden">
      <button class="faq-toggle w-full text-left px-4 py-3 font-semibold text-gray-800 flex justify-between items-center">
        <span>Apakah ada forum diskusi untuk peserta?</span>
        <svg class="w-5 h-5 transition-transform transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
      </button>
      <div class="faq-content hidden px-4 pb-4 text-gray-600">
        Ya, peserta dapat berinteraksi di forum diskusi online yang tersedia untuk setiap kursus.
      </div>
    </div>

  </div>
</div>

<!-- Script FAQ Toggle -->
<script>
  document.querySelectorAll('.faq-toggle').forEach(button => {
    button.addEventListener('click', () => {
      const content = button.nextElementSibling;
      content.classList.toggle('hidden');
      button.querySelector('svg').classList.toggle('rotate-180');
    });
  });
</script>

    </section>
  </main>

    <script>
    // === MOBILE MENU TOGGLE ===
    const menuToggle = document.getElementById('menu-toggle');
    const menu = document.getElementById('mobile-menu');
    const menuIcon = document.getElementById('menu-icon');

    menuToggle.addEventListener('click', () => {
      menu.classList.toggle('hidden');
      menuIcon.classList.toggle('rotate-90');
    });

    // === PILIH MENU HP ===
    const mobileLinks = document.querySelectorAll('.mobile-link');
    const sections = document.querySelectorAll('.content-section');
    mobileLinks.forEach(link => {
      link.addEventListener('click', () => {
        sections.forEach(sec => sec.classList.add('hidden'));
        document.getElementById(link.dataset.target).classList.remove('hidden');
        menu.classList.add('hidden');
        window.scrollTo({ top: 0, behavior: 'smooth' });
      });
    });

    // === SIDEBAR (DESKTOP) ===
    const sidebarLinks = document.querySelectorAll('.sidebar-link');
    sidebarLinks.forEach(link => {
      link.addEventListener('click', e => {
        e.preventDefault();
        const target = link.getAttribute('data-target');
        sections.forEach(sec => sec.classList.add('hidden'));
        document.getElementById(target).classList.remove('hidden');
        sidebarLinks.forEach(a => a.classList.remove('active', 'bg-blue-800', 'font-semibold'));
        link.classList.add('active', 'bg-blue-800', 'font-semibold');
      });
    });
  </script>

@endsection
