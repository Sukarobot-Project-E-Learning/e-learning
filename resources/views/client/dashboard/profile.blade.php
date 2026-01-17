@extends('client.layouts.dashboard')

@section('dashboard-content')
    <!-- Profil -->
    <section id="profile" class="section">
      <h2 class="text-2xl font-bold text-blue-700 mb-4">Profil Pengguna</h2>
      <!-- ProfileHeader -->
      <div class="py-6 border-b border-gray-200">
        <div class="flex w-full flex-col gap-4 @container sm:flex-row sm:justify-between sm:items-center">
          <div class="flex gap-4 items-center">
            <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full h-24 w-24 sm:h-32 sm:w-32 flex-shrink-0" data-alt="User profile picture" style="background-image: url({{ $user->avatar_url }})"></div>
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

      <!-- Instructor Status Indicator -->
      @if(isset($instructorApplication))
          @if($instructorApplication->status == 'pending')
              <div class="mt-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r">
                  <div class="flex">
                      <div class="flex-shrink-0">
                          <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                              <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                          </svg>
                      </div>
                      <div class="ml-3">
                          <h3 class="text-sm leading-5 font-medium text-yellow-800">
                              Pengajuan Instruktur Sedang Pending
                          </h3>
                          <div class="mt-2 text-sm leading-5 text-yellow-700">
                              <p>Mohon menunggu admin melakukan verifikasi terhadap data Anda.</p>
                          </div>
                      </div>
                  </div>
              </div>
          @elseif($instructorApplication->status == 'approved')
              <div class="mt-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-r">
                  <div class="flex justify-between items-start">
                      <div class="flex">
                          <div class="flex-shrink-0">
                              <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                              </svg>
                          </div>
                          <div class="ml-3">
                              <h3 class="text-sm leading-5 font-medium text-green-800">
                                  Selamat! Anda adalah Instruktur
                              </h3>
                              <div class="mt-2 text-sm leading-5 text-green-700">
                                  <p>Akun Anda telah disetujui sebagai instruktur. Anda dapat mengakses dashboard instruktur <a href="{{ route('instructor.dashboard') }}" class="font-medium text-green-600 hover:text-green-500 underline transition duration-150 ease-in-out">di sini</a>.</p>  
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          @elseif($instructorApplication->status == 'rejected')
              <div class="mt-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r">
                  <div class="flex justify-between items-start">
                      <div class="flex">
                          <div class="flex-shrink-0">
                              <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                              </svg>
                          </div>
                          <div class="ml-3">
                              <h3 class="text-sm leading-5 font-medium text-red-800">
                                  Pengajuan Instruktur Ditolak
                              </h3>
                              <div class="mt-2 text-sm leading-5 text-red-700">
                                  <p>Alasan: {{ $instructorApplication->admin_notes }}</p>
                              </div>
                          </div>
                      </div>
                      <div class="ml-4 flex-shrink-0">
                          <a href="{{ route('client.become-instructor.create') }}" class="font-medium text-red-600 hover:text-red-500 underline transition duration-150 ease-in-out">
                              Ajukan Ulang
                          </a>
                      </div>
                  </div>
              </div>
          @endif
      @endif

      <!-- Form Section -->
      <form action="{{ route('client.dashboard.update') }}" method="POST" enctype="multipart/form-data" class="py-6 space-y-8">
        @csrf
        @method('PUT')
        <!-- Personal Information Section -->
        <div>
          <h2 class="text-[#111318] text-[22px] font-bold leading-tight tracking-[-0.015em] pb-4">Informasi Pribadi</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- TextField: Nama Lengkap -->
            <div class="flex flex-col">
              <label class="text-[#111318] text-base font-medium leading-normal pb-2" for="fullName">Nama Lengkap</label>
              <input name="name" class="form-input flex w-full min-w-0 resize-none overflow-hidden rounded-lg text-[#111318] focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-[#dbdfe6] bg-white focus:border-primary h-14 placeholder:text-[#616f89] p-[15px] text-base font-normal leading-normal" id="fullName" value="{{ $user->name }}"/>
            </div>
            <!-- TextField: Email -->
            <div class="flex flex-col">
              <label class="text-[#111318] text-base font-medium leading-normal pb-2" for="email">Alamat Email</label>
                <input name="email" class="form-input flex w-full min-w-0 resize-none overflow-hidden rounded-lg text-[#111318] focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-[#dbdfe6] bg-white focus:border-primary h-14 placeholder:text-[#616f89] p-[15px] text-base font-normal leading-normal" id="email" type="email" value="{{ $user->email }}"/>
              </div>
              <!-- TextField: Phone -->
              <div class="flex flex-col">
                <label class="text-[#111318] text-base font-medium leading-normal pb-2" for="phone">Nomor Telepon</label>
                <input name="phone" class="form-input flex w-full min-w-0 resize-none overflow-hidden rounded-lg text-[#111318] focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-[#dbdfe6] bg-white focus:border-primary h-14 placeholder:text-[#616f89] p-[15px] text-base font-normal leading-normal" id="phone" type="number" placeholder="Tambah nomor telepon Anda..." value="{{ $user->phone }}"/>
              </div>
              <!-- Job -->
              <div class="flex flex-col">
                <label class="text-[#111318] text-base font-medium leading-normal pb-2" for="job">Pekerjaan</label>
                <input name="job" class="form-input flex w-full min-w-0 resize-none overflow-hidden rounded-lg text-[#111318] focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-[#dbdfe6] bg-white focus:border-primary h-14 placeholder:text-[#616f89] p-[15px] text-base font-normal leading-normal" id="job" type="text" placeholder="Tambah pekerjaan Anda..." value="{{ $user->job }}"/>
              </div>
              <!-- TextField: Address -->
              <div class="flex flex-col md:col-span-2">
                <label class="text-[#111318] text-base font-medium leading-normal pb-2" for="address">Alamat</label>
                <textarea name="address" class="form-textarea flex w-full min-w-0 flex-1 resize-y overflow-hidden rounded-lg text-[#111318] focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-[#dbdfe6] bg-white focus:border-primary placeholder:text-[#616f89] p-[15px] text-base font-normal leading-normal min-h-32" id="address" placeholder="Tambah alamat Anda...">{{ $user->address }}</textarea>
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
                <input name="current_password" class="form-input flex w-full min-w-0 resize-none overflow-hidden rounded-lg text-[#111318] focus:outline-0 focus:ring-2 focus:ring-primary/50 border {{ $errors->has('current_password') ? 'border-red-500' : 'border-[#dbdfe6]' }} bg-white focus:border-primary h-14 placeholder:text-[#616f89] p-[15px] text-base font-normal leading-normal" id="currentPassword" placeholder="••••••••" type="password"/>
                @error('current_password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
              </div>
              <!-- TextField: Kata Sandi Baru -->
              <div class="flex flex-col">
                <label class="text-[#111318] text-base font-medium leading-normal pb-2" for="newPassword">Kata Sandi Baru</label>
                <input name="new_password" class="form-input flex w-full min-w-0 resize-none overflow-hidden rounded-lg text-[#111318] focus:outline-0 focus:ring-2 focus:ring-primary/50 border {{ $errors->has('new_password') ? 'border-red-500' : 'border-[#dbdfe6]' }} bg-white focus:border-primary h-14 placeholder:text-[#616f89] p-[15px] text-base font-normal leading-normal" id="newPassword" placeholder="Minimal 8 karakter" type="password"/>
                @error('new_password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
              </div>
              <!-- ruang kosong -->
              <div class="flex flex-col"></div>
              <!-- TextField: Konfirmasi Kata Sandi Baru -->
              <div class="flex flex-col">
                <label class="text-[#111318] text-base font-medium leading-normal pb-2" for="newPasswordConfirmation">Konfirmasi Kata Sandi Baru</label>
                <input name="new_password_confirmation" class="form-input flex w-full min-w-0 resize-none overflow-hidden rounded-lg text-[#111318] focus:outline-0 focus:ring-2 focus:ring-primary/50 border {{ $errors->has('new_password_confirmation') ? 'border-red-500' : 'border-[#dbdfe6]' }} bg-white focus:border-primary h-14 placeholder:text-[#616f89] p-[15px] text-base font-normal leading-normal" id="newPasswordConfirmation" placeholder="Ulangi kata sandi baru" type="password"/>
                @error('new_password_confirmation')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
              </div>
            </div>
          </div>
        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t border-gray-200">
          <button id="cancelEditProfile" data-route="{{ route('client.dashboard') }}" class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-6 bg-[#f0f2f4] text-[#111318] text-base font-bold leading-normal tracking-[0.015em]" type="button">
          <span class="truncate">Batal</span>
          </button>
          <button class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-6 bg-orange-500 text-white text-base font-bold leading-normal tracking-[0.015em]" type="submit">
          <span class="truncate">Simpan Perubahan</span>
          </button>
        </div>
      </form>
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
                          src="{{ $user->avatar_url }}" 
                          class="w-full h-full object-cover">
                  </div>
              </div>

              <!-- Upload Box -->
              <label for="inputPhoto" 
                    class="border-2 border-dashed border-gray-300 rounded-xl p-6 block cursor-pointer hover:bg-gray-50 transition">
                  <p class="text-gray-600">Klik untuk memilih foto dari komputer</p>
                  <p class="text-xs text-gray-400">JPG, PNG, max 2MB</p>
                  <input name="avatar" type="file" id="inputPhoto" class="hidden" accept="image/*">
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

  @if (session('success'))
  <script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: '{{ session('success') }}',
        showConfirmButton: false,
        timer: 1500
    });
  </script>
  @endif

  @if (session('error'))
  <script>
    Swal.fire({
        icon: 'error',
        title: 'Batal',
        text: '{{ session('error') }}',
        showConfirmButton: false,
        timer: 1500
    });
  </script>
  @endif
  <script src="{{ asset('assets/elearning/client/js/dashboard/profile.js') }}"></script>
@endsection
