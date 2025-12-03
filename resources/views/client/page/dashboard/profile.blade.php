@extends('client.layouts.dashboard')

@section('dashboard-content')
    <!-- Profil -->
    <section id="profile" class="section">
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

    <!-- Ubah foto Modal -->
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
@endsection
