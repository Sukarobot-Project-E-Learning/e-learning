@extends('client.main')
@section('css')
<link rel="stylesheet" href="{{ asset('assets/elearning/client/css/about/about.css') }}">
@endsection

@section('body')
  <!-- KONTEN UTAMA -->
  <main class="flex-1 max-w-7xl mx-auto w-full px-6 py-12 flex flex-col lg:flex-row gap-10 pt-34">

    <!-- SIDEBAR (desktop) -->
    <aside class="w-full lg:w-72 h-fit lg:sticky lg:top-32 bg-white border border-gray-100 shadow-lg rounded-2xl p-6 self-start">
      <h3 class="text-lg font-bold text-gray-900 mb-4 px-3">Menu Informasi</h3>
      <nav class="space-y-1">
        <a href="#" class="sidebar-link block px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 hover:bg-blue-50 hover:text-blue-600 active text-blue-600 bg-blue-50" data-target="tentang-kami">
            Tentang Kami
        </a>
        <a href="#" class="sidebar-link block px-4 py-3 rounded-xl text-sm font-medium text-gray-600 transition-all duration-200 hover:bg-blue-50 hover:text-blue-600" data-target="kebijakan">
            Kebijakan & Privasi
        </a>
        <a href="#" class="sidebar-link block px-4 py-3 rounded-xl text-sm font-medium text-gray-600 transition-all duration-200 hover:bg-blue-50 hover:text-blue-600" data-target="ketentuan">
            Ketentuan Pengguna
        </a>
        <a href="#" class="sidebar-link block px-4 py-3 rounded-xl text-sm font-medium text-gray-600 transition-all duration-200 hover:bg-blue-50 hover:text-blue-600" data-target="FAQ">
            Bantuan (FAQ)
        </a>
      </nav>
    </aside>

    <!-- KONTEN -->
    <section class="flex-1 bg-white shadow-sm border border-gray-100 rounded-2xl p-8 md:p-10 overflow-hidden min-h-[600px]">
      <!-- Tentang Kami -->
      <div id="tentang-kami" class="content-section">
        <h2 class="text-3xl font-bold text-gray-900 mb-6">Tentang Kami</h2>
        <div class="prose prose-lg max-w-none text-gray-600 leading-relaxed">
            <p class="text-lg">Sukarobot adalah platform pembelajaran teknologi dan robotika yang didedikasikan untuk semua kalangan.</p>
            
            <div class="my-8 relative rounded-2xl overflow-hidden shadow-lg group">
              <img src="{{ asset('assets/elearning/client/img/logo.png') }}" alt="Foto Tentang Kami" class="w-full h-auto transform group-hover:scale-105 transition duration-700"
              onerror="this.src='https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=800&q=80'">
              <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
            </div>

            <p>Kami percaya bahwa pembelajaran teknologi harus mudah diakses oleh siapa pun, di mana pun, dengan metode yang praktis dan interaktif. Misi kami adalah mencetak talenta digital yang siap bersaing di era industri 4.0 melalui kurikulum yang relevan dan mentor yang berpengalaman.</p>
            
            <h3 class="text-xl font-bold text-gray-900 mt-8 mb-4">Visi Kami</h3>
            <p>Menjadi platform edukasi teknologi terdepan di Indonesia yang mencetak jutaan talenta digital berkualitas.</p>
        </div>
      </div>

      <!-- Kebijakan -->
      <div id="kebijakan" class="content-section hidden">
        <h2 class="text-3xl font-bold text-gray-900 mb-6">Kebijakan & Privasi</h2>
        <div class="prose prose-blue max-w-none text-gray-600 leading-relaxed">
            <p>Kami menghargai privasi Anda. Kebijakan ini menjelaskan bagaimana kami mengumpulkan, menggunakan, dan melindungi informasi pribadi Anda saat menggunakan layanan Sukarobot.</p>
            
            <h3 class="text-lg font-bold text-gray-900 mt-6 mb-3">1. Pengumpulan Data</h3>
            <p>Kami mengumpulkan informasi yang Anda berikan saat mendaftar, seperti nama, alamat email, dan nomor telepon. Kami juga mengumpulkan data penggunaan untuk meningkatkan layanan kami.</p>

            <h3 class="text-lg font-bold text-gray-900 mt-6 mb-3">2. Penggunaan Data</h3>
            <p>Data Anda digunakan untuk memproses pendaftaran kursus, mengirimkan sertifikat, dan memberikan informasi terbaru seputar layanan kami. Kami tidak akan menjual data Anda kepada pihak ketiga.</p>
            
            <h3 class="text-lg font-bold text-gray-900 mt-6 mb-3">3. Keamanan</h3>
            <p>Kami menerapkan langkah-langkah keamanan teknis dan organisasi untuk melindungi data Anda dari akses yang tidak sah.</p>
        </div>
      </div>

    <!-- Ketentuan -->
    <div id="ketentuan" class="content-section hidden">
        <h2 class="text-3xl font-bold text-gray-900 mb-6">Ketentuan Pengguna</h2>
        <div class="prose prose-blue max-w-none text-gray-600 leading-relaxed">
            <p>Selamat datang di Sukarobot. Dengan mengakses layanan kami, Anda menyetujui ketentuan berikut:</p>
            
            <ul class="list-disc pl-5 space-y-2 mt-4">
                <li>Anda bertanggung jawab atas keamanan akun Anda.</li>
                <li>Materi kursus dilindungi hak cipta dan tidak boleh disebarluaskan tanpa izin.</li>
                <li>Kami berhak menonaktifkan akun yang melanggar aturan komunitas.</li>
                <li>Pembayaran yang sudah dilakukan tidak dapat dikembalikan kecuali dalam kondisi tertentu yang diatur dalam kebijakan pengembalian dana.</li>
            </ul>
        </div>
    </div>

      <!-- Bagian FAQ di dalam konten -->
    <div id="FAQ" class="content-section hidden">
        <h2 class="text-3xl font-bold text-gray-900 mb-2">Bantuan (FAQ)</h2>
        <p class="text-gray-500 mb-8">Pertanyaan yang sering diajukan oleh pengguna kami.</p>
        
      <div class="space-y-4">
      <!-- FAQ 1 -->
      <div class="border border-gray-200 rounded-xl overflow-hidden transition-all duration-200 hover:border-blue-300">
      <button class="faq-toggle w-full text-left px-6 py-4 font-semibold text-gray-800 flex justify-between items-center bg-gray-50 hover:bg-blue-50 transition-colors">
        <span>Siapa saja yang dapat belajar di Sukarobot?</span>
        <svg class="w-5 h-5 transition-transform transform text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
      </button>
      <div class="faq-content hidden px-6 py-4 text-gray-600 bg-white leading-relaxed">
        Semua orang dapat belajar di Sukarobot selama dapat memahami bahasa Indonesia dan memiliki kemampuan dasar menggunakan komputer. Kami menyediakan materi dari tingkat pemula hingga mahir.
      </div>
    </div>

    <!-- FAQ 2 -->
    <div class="border border-gray-200 rounded-xl overflow-hidden transition-all duration-200 hover:border-blue-300">
      <button class="faq-toggle w-full text-left px-6 py-4 font-semibold text-gray-800 flex justify-between items-center bg-gray-50 hover:bg-blue-50 transition-colors">
        <span>Apa syarat agar dapat belajar secara optimal?</span>
        <svg class="w-5 h-5 transition-transform transform text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
      </button>
      <div class="faq-content hidden px-6 py-4 text-gray-600 bg-white leading-relaxed">
        Pastikan kamu memiliki koneksi internet yang stabil, perangkat (laptop/PC) yang memadai sesuai kebutuhan software, dan lingkungan belajar yang nyaman.
      </div>
    </div>

    <!-- FAQ 3 -->
    <div class="border border-gray-200 rounded-xl overflow-hidden transition-all duration-200 hover:border-blue-300">
      <button class="faq-toggle w-full text-left px-6 py-4 font-semibold text-gray-800 flex justify-between items-center bg-gray-50 hover:bg-blue-50 transition-colors">
        <span>Bagaimana cara mendaftar?</span>
        <svg class="w-5 h-5 transition-transform transform text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
      </button>
      <div class="faq-content hidden px-6 py-4 text-gray-600 bg-white leading-relaxed">
        Kamu cukup mengunjungi situs resmi Sukarobot, klik tombol “Daftar” di pojok kanan atas, isi data diri dengan lengkap, dan pilih program belajar yang kamu minati.
      </div>
    </div>

    <!-- FAQ 4 -->
    <div class="border border-gray-200 rounded-xl overflow-hidden transition-all duration-200 hover:border-blue-300">
      <button class="faq-toggle w-full text-left px-6 py-4 font-semibold text-gray-800 flex justify-between items-center bg-gray-50 hover:bg-blue-50 transition-colors">
        <span>Apakah dapat sertifikat?</span>
        <svg class="w-5 h-5 transition-transform transform text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
      </button>
      <div class="faq-content hidden px-6 py-4 text-gray-600 bg-white leading-relaxed">
        Ya! Peserta yang telah menyelesaikan seluruh materi kursus dan lulus ujian akhir akan mendapatkan sertifikat resmi Sukarobot dalam format digital yang dapat diunduh di dashboard.
      </div>
    </div>
  </div>
</div>

    </section>
  </main>

  <script src="{{ asset('assets/elearning/client/js/about/tentang.js') }}"></script>
@endsection
