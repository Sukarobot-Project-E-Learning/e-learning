@extends('admin.layouts.app')

@section('title', 'Edit Admin')

@section('content')

    <div class="container px-6 mx-auto">

        <!-- Page Header -->
        <div class="my-6">
            <div class="flex items-start justify-between">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Edit Admin</h2>

            </div>
        </div>

        <!-- Form Card -->
        <div class="w-full mb-8 p-6 overflow-hidden rounded-lg shadow-md bg-white dark:bg-gray-800">
            <form id="adminForm" action="{{ route('admin.admins.update', $admin->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="space-y-6">

                    <!-- Status -->
                    <div class="mb-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="status">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select name="status" id="status" required
                                    class="block w-full px-4 py-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-orange-300">
                                <option value="aktif" {{ (isset($admin) && $admin->is_active) ? 'selected' : '' }}>Aktif</option>
                                <option value="non-aktif" {{ (isset($admin) && !$admin->is_active) ? 'selected' : '' }}>Non-Aktif</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Name -->
                    <div class="mb-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="name">
                            Nama <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" required
                               value="{{ $admin->name ?? '' }}"
                               placeholder="Masukkan nama"
                               class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-orange-300 dark:placeholder-gray-500">
                    </div>

                    <!-- Email -->
                    <div class="mb-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="email">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" id="email" required
                               value="{{ $admin->email ?? '' }}"
                               placeholder="Masukkan email"
                               class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-orange-300 dark:placeholder-gray-500">
                    </div>

                    <!-- Phone -->
                    <div class="mb-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="phone">
                            Nomor Telepon <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" name="phone" id="phone" required
                               value="{{ $admin->phone ?? '' }}"
                               placeholder="Masukkan nomor telepon"
                               class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-orange-300 dark:placeholder-gray-500">
                    </div>

                    <!-- Password -->
                    <div class="mb-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="password">
                            Password <span class="text-gray-500 text-xs">(Kosongkan jika tidak ingin mengubah)</span>
                        </label>
                        <input type="password" name="password" id="password"
                               placeholder="Masukkan password baru"
                               class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-orange-300 dark:placeholder-gray-500">
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="password_confirmation">
                            Konfirmasi Password
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               placeholder="Konfirmasi password baru"
                               class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-orange-300 dark:placeholder-gray-500">
                    </div>

                    <!-- Photo Upload -->
                    <div class="mb-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Upload Foto
                        </label>
                        <div class="flex items-center justify-center w-full">
                            <label for="photo" 
                                   class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500"
                                   x-data="{ 
                                       fileName: null,
                                       validateFile(file) {
                                           const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                                           const maxSize = 2 * 1024 * 1024; // 2MB

                                           if (!allowedTypes.includes(file.type)) {
                                               Swal.fire({
                                                   icon: 'error',
                                                   title: 'Upload Gagal',
                                                   text: 'Format file tidak sesuai. Harap unggah JPG, JPEG, atau PNG.'
                                               });
                                               this.fileName = null;
                                               $refs.photoInput.value = '';
                                               return false;
                                           }

                                           if (file.size > maxSize) {
                                               Swal.fire({
                                                   icon: 'error',
                                                   title: 'Upload Gagal',
                                                   text: 'Ukuran file melebihi 2MB.'
                                               });
                                               this.fileName = null;
                                               $refs.photoInput.value = '';
                                               return false;
                                           }

                                           this.fileName = file.name;
                                           return true;
                                       }
                                   }"
                                   @dragover.prevent
                                   @drop.prevent="
                                       let file = $event.dataTransfer.files[0];
                                       if (file && validateFile(file)) {
                                           $refs.photoInput.files = $event.dataTransfer.files;
                                       }
                                   ">
                                <div class="flex flex-col items-center justify-center pt-2 pb-3" x-show="!fileName">
                                    <svg class="w-8 h-8 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    <p class="mb-1 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="font-semibold">Seret dan lepas berkas, atau</span> 
                                        <span class="text-orange-600 hover:text-orange-700 dark:text-orange-400">Telusuri</span>
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        Unggah berkas dalam bentuk: JPG, JPEG, PNG
                                    </p>
                                </div>
                                <div x-show="fileName" class="flex flex-col items-center justify-center w-full h-full p-4">
                                    <svg class="w-8 h-8 mb-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-200" x-text="'File terpilih: ' + fileName"></p>
                                    <button type="button" 
                                            @click.stop.prevent="fileName = null; $refs.photoInput.value = ''"
                                            class="mt-2 text-xs text-red-500 hover:text-red-700 font-medium">
                                        Hapus File
                                    </button>
                                </div>
                                <input id="photo" 
                                       name="photo" 
                                       type="file" 
                                       class="hidden" 
                                       accept="image/*"
                                       x-ref="photoInput"
                                       @change="
                                           let file = $event.target.files[0];
                                           if (file) {
                                               validateFile(file);
                                           }
                                       ">
                            </label>
                        </div>
                    </div>

                </div>

            </form>

            <div class="flex flex-row justify-end items-end mt-6" style="gap: 16px;">
                <a href="{{ route('admin.admins.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                    Kembali
                </a>
                <button type="submit"
                        form="adminForm"
                        class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-orange-600 border border-transparent rounded-lg active:bg-orange-600 hover:bg-orange-700 focus:outline-none focus:shadow-outline-orange">
                    Simpan Perubahan
                </button>
            </div>
        </div>

    </div>

@push('scripts')
<script>
    // Form Validation
    document.getElementById('adminForm').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;
        const photoInput = document.getElementById('photo');
        
        // Password Validation (only if password is being changed)
        if (password && password.length > 0) {
            if (password.length < 8) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    text: 'Password minimal harus 8 karakter!'
                });
                return;
            }

            if (password !== confirmPassword) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    text: 'Konfirmasi password tidak cocok!'
                });
                return;
            }
        }

        // Photo Validation Removed (Handled by Alpine.js instant validation)
    });

    // Handle success/error messages from session
    @if(session('success'))
        Swal.fire({
            title: "{{ session('success') }}",
            icon: "success",
            draggable: true
        });
    @endif

    @if(session('error'))
        Swal.fire({
            title: "Error!",
            text: "{{ session('error') }}",
            icon: "error"
        });
    @endif
</script>
@endpush

@endsection

