@extends('client.main')

@section('css')
<link rel="stylesheet" href="{{ asset('assets/elearning/client/css/dashboard.css') }}">
<style>
    @media (min-width: 1024px) {
        footer {
            margin-left: 16rem; /* w-64 is 16rem */
            width: auto;
        }
    }
</style>
@endsection

@section('body')
<script defer src="{{ asset('assets/elearning/client/js/dashboard.js') }}"></script>

<div class="flex min-h-screen bg-gray-50 pt-24 bg-white">
  <!-- Sidebar -->
  <aside id="sidebar" class="w-64 pt-2 pr-1 bg-white border-r border-gray-200 fixed h-full lg:flex flex-1 flex-col justify-between shadow-md rounded-xl">
    <div>
      <nav>
        <ul class="space-y-1 text-gray-700">
          <li><button class="nav-item active rounded-lg" data-section="profile"><i class="fa-regular fa-user mr-2"></i>Profil</button></li>
          <li><button class="nav-item rounded-lg" data-section="program"><i class="fa-solid fa-book mr-2"></i>Program</button></li>
          <li><button class="nav-item rounded-lg" data-section="certificate"><i class="fa-solid fa-award mr-2"></i>Sertifikat</button></li>
          <li><button class="nav-item rounded-lg" data-section="transactions"><i class="fa-solid fa-clock-rotate-left mr-2"></i>Riwayat Transaksi</button></li>
          <li><button class="nav-item rounded-lg" data-section="voucher"><i class="fa-solid fa-ticket mr-2"></i>Voucher</button></li>
        </ul>
      </nav>
    </div>
  </aside>

  <!-- Main Content -->
  <main class="ml-64 flex-2 pt-2 pr-8 pb-8 pl-8 transition-all duration-300">
    <!-- sidebar toggle -->
    <button id="sidebar-toggle" class="lg:hidden p-2 rounded-lg hover:bg-gray-100">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
      </svg>
    </button>

    <!-- overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/40 hidden z-30 lg:max-w-screen"></div>
    
    <!-- Profil -->
    <section id="profile" class="section active">
      <h2 class="text-2xl font-bold text-blue-700 mb-4">Profil Pengguna</h2>
      <!-- Profile Content Card -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <!-- ProfileHeader -->
        <div class="p-6 border-b border-gray-200">
          <div class="flex w-full flex-col gap-4 @container sm:flex-row sm:justify-between sm:items-center">
            <div class="flex gap-4 items-center">
              <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full h-24 w-24 sm:h-32 sm:w-32 flex-shrink-0" data-alt="User profile picture" style="background-image: url({{ $user->avatar ?? asset('assets/elearning/client/img/default-avatar.jpeg') }})"></div>
              <div class="flex flex-col justify-center">
                <p class="text-[#111318] text-[22px] font-bold leading-tight tracking-[-0.015em]">{{ $user->name }}</p>
                <p class="text-[#616f89] text-base font-normal leading-normal">{{ $user->email }}</p>
              </div>
            </div>
            <button class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-[#f0f2f4] text-[#111318] text-sm font-bold leading-normal tracking-[0.015em] w-full max-w-[480px] sm:w-auto" id="photo-change-btn">
            <span class="truncate">Ganti Foto</span>
            </button>
          </div>
        </div>
        <!-- Form Section -->
        <form class="p-6 space-y-8">
          <!-- Personal Information Section -->
          <div>
            <h2 class="text-[#111318] text-[22px] font-bold leading-tight tracking-[-0.015em] pb-4">Informasi Pribadi</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- TextField: Nama Lengkap -->
              <div class="flex flex-col">
                <label class="text-[#111318] text-base font-medium leading-normal pb-2" for="fullName">Nama Lengkap</label>
                <input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-[#111318] focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-[#dbdfe6] bg-white focus:border-primary h-14 placeholder:text-[#616f89] p-[15px] text-base font-normal leading-normal" id="fullName" value="{{ $user->name }}"/>
              </div>
              <!-- TextField: Email -->
              <div class="flex flex-col">
                <label class="text-[#111318] text-base font-medium leading-normal pb-2" for="email">Alamat Email</label>
                <input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-[#111318] focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-[#dbdfe6] bg-white focus:border-primary h-14 placeholder:text-[#616f89] p-[15px] text-base font-normal leading-normal" id="email" type="email" value="{{ $user->email }}"/>
              </div>
              <!-- TextField: Phone -->
              <div class="flex flex-col">
                <label class="text-[#111318] text-base font-medium leading-normal pb-2" for="phone">Nomor Telepon</label>
                <input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-[#111318] focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-[#dbdfe6] bg-white focus:border-primary h-14 placeholder:text-[#616f89] p-[15px] text-base font-normal leading-normal" id="email" type="email" placeholder="Tambah nomor telepon Anda..." value="{{ $user->phone }}"/>
              </div>
              <!-- Job -->
              <div class="flex flex-col">
                <label class="text-[#111318] text-base font-medium leading-normal pb-2" for="job">Pekerjaan</label>
                <input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-[#111318] focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-[#dbdfe6] bg-white focus:border-primary h-14 placeholder:text-[#616f89] p-[15px] text-base font-normal leading-normal" id="email" type="email" placeholder="Tambah pekerjaan Anda..." value="{{ $user->job }}"/>
              </div>
              <!-- TextField: Address -->
              <div class="flex flex-col md:col-span-2">
                <label class="text-[#111318] text-base font-medium leading-normal pb-2" for="bio">Alamat</label>
                <textarea class="form-textarea flex w-full min-w-0 flex-1 resize-y overflow-hidden rounded-lg text-[#111318] focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-[#dbdfe6] bg-white focus:border-primary placeholder:text-[#616f89] p-[15px] text-base font-normal leading-normal min-h-32" id="bio" placeholder="Tambah alamat Anda...">{{ $user->address }}</textarea>
              </div>
            </div>
          </div>
            <!-- Security Section -->
            <div>
              <h2 class="text-[#111318] text-[22px] font-bold leading-tight tracking-[-0.015em] pb-4">Ubah Kata Sandi</h2>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- TextField: Kata Sandi Lama -->
                <div class="flex flex-col">
                  <label class="text-[#111318] text-base font-medium leading-normal pb-2" for="currentPassword">Kata Sandi Saat Ini</label>
                  <input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-[#111318] focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-[#dbdfe6] bg-white focus:border-primary h-14 placeholder:text-[#616f89] p-[15px] text-base font-normal leading-normal" id="currentPassword" placeholder="••••••••" type="password"/>
                </div>
                <!-- TextField: Kata Sandi Baru -->
                <div class="flex flex-col">
                  <label class="text-[#111318] text-base font-medium leading-normal pb-2" for="newPassword">Kata Sandi Baru</label>
                  <input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-[#111318] focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-[#dbdfe6] bg-white focus:border-primary h-14 placeholder:text-[#616f89] p-[15px] text-base font-normal leading-normal" id="newPassword" placeholder="Minimal 8 karakter" type="password"/>
                </div>
              </div>
          </div>
          <!-- Action Buttons -->
          <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t border-gray-200">
            <button class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-6 bg-[#f0f2f4] text-[#111318] text-base font-bold leading-normal tracking-[0.015em]" type="button">
            <span class="truncate">Batal</span>
            </button>
            <button class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-6 bg-orange-500 text-white text-base font-bold leading-normal tracking-[0.015em]" type="submit">
            <span class="truncate">Simpan Perubahan</span>
            </button>
          </div>
        </form>
      </div>
    </section>

    <!-- Program -->
    <section id="program" class="section hidden">
      <h2 class="text-2xl font-bold text-blue-700 mb-4">Program Saya</h2>
      <div class="flex flex-wrap gap-2 mb-6">
          <button class="px-3 py-1.5 text-sm font-medium rounded-full bg-blue-500 text-white">Semua Program</button>
          <button class="px-3 py-1.5 text-sm font-medium rounded-full text-gray-700 bg-gray-100 hover:bg-gray-200">Kursus</button>
          <button class="px-3 py-1.5 text-sm font-medium rounded-full text-gray-700 bg-gray-100 hover:bg-gray-200">Pelatihan</button>
          <button class="px-3 py-1.5 text-sm font-medium rounded-full text-gray-700 bg-gray-100 hover:bg-gray-200">Sertifikasi</button>
          <button class="px-3 py-1.5 text-sm font-medium rounded-full text-gray-700 bg-gray-100 hover:bg-gray-200">Outing Class</button>
          <button class="px-3 py-1.5 text-sm font-medium rounded-full text-gray-700 bg-gray-100 hover:bg-gray-200">Outboard</button>
      </div>
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <div class="flex flex-col rounded-xl overflow-hidden shadow-sm bg-white border border-gray-200 hover:shadow-lg transition-shadow duration-300">
            <div class="relative">
            <div class="w-full bg-center bg-no-repeat aspect-[16/9] bg-cover" data-alt="Abstract graphic design elements" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCEJSQL2WBpLKYVxdc66LfZFXWcBZ4tFSK2YLrw4b9sdXkFl-9gYbt97SdL_uoiwb4FOYkbMml8NbErMXrFFw3nJGik1qzXyaRXkDU_ccXAIhr71b6tE-azDFOVrZJuzFXVpTqUTKDOaHa-REhNqYNnmTrNAp0qtswvW7aH5BRjtGh6SbqPrpl1Q2Lj8vTE-HSlAVI4AEfkX3H7kqq3p73g958N7sXnwCw0Jr2dZsMskU0S6dKdG94V0CA3FpAu5QngCQxvIRSTU7s");'></div>
            <span class="absolute top-3 left-3 bg-yellow-400/20 text-yellow-800 text-xs font-semibold px-2.5 py-1 rounded-full">Kursus</span>
            </div>
            <div class="flex flex-col p-5 gap-4 flex-1">
              <h2 class="text-gray-900 text-lg font-bold leading-tight tracking-[-0.015em]">Dasar-dasar Desain Grafis dengan Figma</h2>
              <p class="text-gray-500 text-sm font-normal leading-normal">Dibeli pada: 15 Okt 2023</p>
              <div class="w-full">
              <div class="flex justify-between mb-1">
                <span class="text-sm font-medium text-gray-600">Progres</span>
                <span class="text-sm font-medium text-primary">75%</span>
              </div>
              <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-orange-500 h-2 rounded-full" style="width: 75%"></div>
              </div>
              </div>
              <button class="mt-auto flex w-full min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-orange-500 text-white text-sm font-medium leading-normal hover:bg-orange-600 focus:ring-4 focus:ring-primary/30">
              <span class="truncate">Lanjutkan Belajar</span>
              </button>
            </div>
          </div>
          <div class="flex flex-col rounded-xl overflow-hidden shadow-sm bg-white border border-gray-200 hover:shadow-lg transition-shadow duration-300">
          <div class="relative">
          <div class="w-full bg-center bg-no-repeat aspect-[16/9] bg-cover" data-alt="People collaborating around a table" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuC8Gtm3Pa4CMSXP81AuBjbYpl0Ih3j7KPFCufGVliiH02zWLsM6gCOjDpsxv7Jg3kirPg9zLdKJTLPdjq0Y0J1vJ7asGBgHiq8uF02v4D55KrMQuDaXBnWOOghuLX-51KcpCBePEp8_PoGugahjG9MoLyXLx_2o-ufqmpsJ1gGrptAmyA1ytywyKuIBqj9jr7Nl8NaVuVt0B5w2_BGkNhquCexZbreBFSJ2opwc63XDB85hAQbqcJV_MgjGyq7bn8nlC8RuKXs6cB4");'></div>
          <span class="absolute top-3 left-3 bg-purple-400/20 text-purple-800 text-xs font-semibold px-2.5 py-1 rounded-full">Pelatihan</span>
          </div>
            <div class="flex flex-col p-5 gap-4 flex-1">
            <h2 class="text-gray-900 text-lg font-bold leading-tight tracking-[-0.015em]">Manajemen Proyek Agile untuk Pemula</h2>
            <p class="text-gray-500 text-sm font-normal leading-normal">Dibeli pada: 12 Sep 2023</p>
            <div class="w-full">
              <div class="flex justify-between mb-1">
                <span class="text-sm font-medium text-gray-600">Progres</span>
                <span class="text-sm font-medium text-primary">40%</span>
              </div>
              <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-orange-500 h-2 rounded-full" style="width: 40%"></div>
              </div>
            </div>
            <button class="mt-auto flex w-full min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-orange-500 text-white text-sm font-medium leading-normal hover:bg-orange-600 focus:ring-4 focus:ring-primary/30">
            <span class="truncate">Lanjutkan Belajar</span>
            </button>
            </div>
          </div>
            <div class="flex flex-col rounded-xl overflow-hidden shadow-sm bg-white border border-gray-200 hover:shadow-lg transition-shadow duration-300">
            <div class="relative">  
              <div class="w-full bg-center bg-no-repeat aspect-[16/9] bg-cover" data-alt="Illustration of a certificate with a seal" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCxlEy-QNmZuN4IkQii4igq5qMoK-qPbVtADXDas6HAuJ2KvICP9bj9oOvt6WGgDdt89TVzP8TJfIAplMnjfm_dE9WDrglNNH4DNjghuBwah2qCcGtDP5Zq34edOfyFzlqfb4Q3gWsV_0tuhOlmMPugb9ApANNOet9Ct0bAn7AJLkpbcYUZ23RmMRiP2wyCPlsdtIkQuHATR6TEHF0Lg41ouOeQnT-vI9UqGKU4WeDYpt_uhCE0Iy6IyHYFRaioYcss1-nR-LUOnlA");'></div>
              <span class="absolute top-3 left-3 bg-green-400/20 text-green-800 text-xs font-semibold px-2.5 py-1 rounded-full">Sertifikasi</span>
            </div>
            <div class="flex flex-col p-5 gap-4 flex-1">
              <h2 class="text-gray-900 text-lg font-bold leading-tight tracking-[-0.015em]">Sertifikasi Digital Marketing Profesional</h2>
              <p class="text-gray-500 text-sm font-normal leading-normal">Dibeli pada: 05 Agu 2023</p>
              <div class="w-full">
                <div class="flex justify-between mb-1">
                  <span class="text-sm font-medium text-gray-600">Progres</span>
                  <span class="text-sm font-medium text-green-600">100% Selesai</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                  <div class="bg-green-600 h-2 rounded-full" style="width: 100%"></div>
                </div>
              </div>
              <button class="mt-auto flex w-full min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-gray-500 text-white text-sm font-medium leading-normal hover:bg-gray-600 focus:ring-4 focus:ring-gray-300">
              <span class="truncate">Lihat Detail</span>
              </button>
            </div>
          </div>
      </div>
      <nav class="flex items-center justify-between border-t border-gray-200 px-4 sm:px-0 mt-10 pt-6">
          <div class="-mt-px flex w-0 flex-1">
            <a class="inline-flex items-center border-t-2 border-transparent pr-1 pt-4 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700 " href="#">
            <span class="material-symbols-outlined mr-3 h-5 w-5"><</span>Sebelumnya</a>
          </div>
          <div class="hidden md:-mt-px md:flex">
            <a aria-current="page" class="inline-flex items-center border-t-2 border-primary px-4 pt-4 text-sm font-medium text-primary" href="#">1</a>
            <a class="inline-flex items-center border-t-2 border-transparent px-4 pt-4 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700 " href="#">2</a>
            <a class="inline-flex items-center border-t-2 border-transparent px-4 pt-4 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700 " href="#">3</a>
          </div>
          <div class="-mt-px flex w-0 flex-1 justify-end">
            <a class="inline-flex items-center border-t-2 border-transparent pl-1 pt-4 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700 " href="#">Selanjutnya<span class="material-symbols-outlined ml-3 h-5 w-5">></span></a>
          </div>
      </nav>
    </section>

    <!-- Sertifikat -->
    <section id="certificate" class="section hidden">
      <h2 class="text-2xl font-bold text-blue-700 mb-4">Sertifikat</h2>
      <div class="bg-white p-6 rounded-xl shadow-md">
        <table class="w-full border-collapse">
          <thead>
            <tr class="bg-blue-50 text-blue-700">
              <th class="p-3 text-left">Nama Program</th>
              <th class="p-3 text-left">Tanggal Diterima</th>
              <th class="p-3 text-left">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr class="border-t">
              <td class="p-3">Kursus Web Development</td>
              <td class="p-3">12 Mei 2025</td>
              <td class="p-3"><button class="btn-orange">Download</button></td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>

    <!-- Riwayat Transaksi -->
    <section id="transactions" class="section hidden">
      <h2 class="text-2xl font-bold text-blue-700 mb-4">Riwayat Transaksi</h2>
      <div class="bg-white p-6 rounded-xl shadow-md">
        <ul class="space-y-4">
          <li class="flex justify-between items-center border-b pb-3">
            <div>
              <p class="font-semibold text-gray-800">Kursus Frontend Development</p>
              <p class="text-sm text-gray-500">Transaksi ID: #123456</p>
            </div>
            <span class="text-orange-600 font-bold">Rp 250.000</span>
          </li>
        </ul>
      </div>
    </section>

    <!-- Voucher -->
    <section id="voucher" class="section hidden">
      <h2 class="text-2xl font-bold text-blue-700 mb-4">Voucher Saya</h2>
      <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-gradient-to-r from-orange-500 to-blue-500 text-white p-6 rounded-xl shadow-lg">
          <h3 class="text-lg font-semibold">Voucher Diskon 20%</h3>
          <p class="text-sm mt-1">Berlaku hingga 31 Desember 2025</p>
          <button class="bg-white text-orange-600 mt-3 px-4 py-2 rounded-lg font-medium hover:bg-gray-100 transition">Gunakan</button>
        </div>
      </div>
    </section>
  </main>

  <!-- Ubah foto -->
  <!-- Overlay -->
  <div id="photoModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50 hidden">
    
      <!-- Modal content -->
      <div class="bg-white rounded-2xl w-full max-w-lg shadow-xl animate-fadeIn">
          
          <!-- Header -->
          <div class="flex justify-between items-center px-6 py-4 border-b">
              <h2 class="text-xl font-bold text-gray-900">Ubah Foto Profil</h2>
              <button id="closePhotoModal" class="text-gray-500 hover:text-gray-700 text-xl cursor-pointer">
                  &times;
              </button>
          </div>

          <!-- Tabs -->
          <div class="flex border-b px-6 justify-center">
              <button class="tab-btn font-medium py-3 px-4">
                  Upload Foto
              </button>
          </div>

          <!-- Body -->
          <div class="px-6 py-6 text-center">

              <!-- Foto preview -->
              <div class="flex justify-center mb-6">
                  <div class="w-28 h-28 rounded-full border-4 border-white shadow-md overflow-hidden">
                      <img id="photoPreview" 
                          src="{{ Auth::user()->avatar ?? asset('assets/elearning/client/img/default-avatar.jpeg') }}" 
                          class="w-full h-full object-cover">
                  </div>
              </div>

              <!-- Upload Box -->
              <label for="inputPhoto" 
                    class="border-2 border-dashed border-gray-300 rounded-xl p-6 block cursor-pointer hover:bg-gray-50 transition">
                  <p class="text-gray-600">Klik untuk memilih foto dari komputer</p>
                  <p class="text-xs text-gray-400">JPG, PNG, max 2MB</p>
                  <input type="file" id="inputPhoto" class="hidden" accept="image/*">
              </label>

          </div>

          <!-- Footer -->
          <div class="flex justify-end gap-3 px-6 py-4 border-t bg-gray-50 rounded-b-2xl">
              <button id="cancelPhotoModal" 
                      class="px-4 py-2 rounded-lg bg-white border text-gray-600 hover:bg-gray-100 cursor-pointer">
                  Batal
              </button>

              <button class="px-5 py-2 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700 cursor-pointer">
                  Simpan Perubahan
              </button>
          </div>

      </div>

  </div>
</div>
@endsection

