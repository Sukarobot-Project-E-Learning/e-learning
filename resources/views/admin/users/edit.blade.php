@extends('admin.layouts.app')

@section('title', 'Edit User')

@section('content')

    <div class="container px-6 mx-auto">

        <!-- Page Header -->
        <div class="my-6">
            <div class="flex items-start justify-between">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Edit User</h2>
                <div class="flex flex-col items-end">
                    <a href="{{ route('elearning.admin.users.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 mb-4">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                    <button type="submit" 
                            form="userForm"
                            class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Simpan Edit</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="w-full mb-8 overflow-hidden rounded-lg shadow-md bg-white dark:bg-gray-800">
            <form id="userForm" action="{{ route('elearning.admin.users.update', $user['id']) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="px-6 py-4 bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Form Edit User</h3>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select name="status" 
                                    id="status"
                                    class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:focus:shadow-outline-gray"
                                    required>
                                <option value="Aktif" {{ old('status', $user['status']) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="Non-Aktif" {{ old('status', $user['status']) == 'Non-Aktif' ? 'selected' : '' }}>Non-Aktif</option>
                                <option value="Pending" {{ old('status', $user['status']) == 'Pending' ? 'selected' : '' }}>Pending</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nama <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name"
                               value="{{ old('name', $user['name']) }}"
                               class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:focus:shadow-outline-gray"
                               placeholder="Masukkan nama lengkap"
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               name="email" 
                               id="email"
                               value="{{ old('email', $user['email']) }}"
                               class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:focus:shadow-outline-gray"
                               placeholder="Masukkan email"
                               required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nomor Telepon -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nomor Telepon <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" 
                               name="phone" 
                               id="phone"
                               value="{{ old('phone', $user['phone']) }}"
                               class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:focus:shadow-outline-gray"
                               placeholder="Masukkan nomor telepon"
                               required>
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pekerjaan -->
                    <div>
                        <label for="job" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Pekerjaan
                        </label>
                        <input type="text" 
                               name="job" 
                               id="job"
                               value="{{ old('job', $user['job'] ?? '') }}"
                               class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:focus:shadow-outline-gray"
                               placeholder="Masukkan pekerjaan">
                        @error('job')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Alamat -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Alamat
                        </label>
                        <textarea name="address" 
                                  id="address"
                                  rows="3"
                                  class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:focus:shadow-outline-gray"
                                  placeholder="Masukkan alamat lengkap">{{ old('address', $user['address'] ?? '') }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Negara -->
                    <div>
                        <label for="country" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Negara
                        </label>
                        <input type="text" 
                               name="country" 
                               id="country"
                               value="{{ old('country', $user['country'] ?? 'Indonesia') }}"
                               class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:focus:shadow-outline-gray"
                               placeholder="Masukkan negara">
                        @error('country')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Password <span class="text-gray-500">(Kosongkan jika tidak ingin mengubah)</span>
                        </label>
                        <input type="password" 
                               name="password" 
                               id="password"
                               class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:focus:shadow-outline-gray"
                               placeholder="Masukkan password baru">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Konfirmasi Password
                        </label>
                        <input type="password" 
                               name="password_confirmation" 
                               id="password_confirmation"
                               class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:focus:shadow-outline-gray"
                               placeholder="Konfirmasi password baru">
                    </div>

                    <!-- Unggah Foto -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Unggah Foto
                        </label>
                        <div x-data="{ 
                            file: null, 
                            preview: '{{ $user['photo'] ?? null }}',
                            dragOver: false,
                            handleFile(event) {
                                const file = event.target.files[0] || event.dataTransfer.files[0];
                                if (file) {
                                    this.file = file;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        this.preview = e.target.result;
                                    };
                                    reader.readAsDataURL(file);
                                }
                            }
                        }" 
                        @dragover.prevent="dragOver = true"
                        @dragleave.prevent="dragOver = false"
                        @drop.prevent="handleFile($event); dragOver = false">
                            
                            <!-- File Input Hidden -->
                            <input type="file" 
                                   name="photo" 
                                   id="photo"
                                   accept="image/jpeg,image/jpg,image/png"
                                   @change="handleFile($event)"
                                   class="hidden">
                            
                            <!-- Drag and Drop Area -->
                            <label for="photo" 
                                   class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 dark:border-gray-600"
                                   :class="dragOver ? 'bg-gray-100 border-purple-400' : ''">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-2 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="font-semibold">Klik untuk upload</span> atau seret dan lepas
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        Unggah berkas dalam bentuk JPG, JPEG, PNG
                                    </p>
                                </div>
                            </label>

                            <!-- Preview Image -->
                            <div x-show="preview" class="mt-4">
                                <img :src="preview" alt="Preview" class="w-32 h-32 object-cover rounded-lg border border-gray-300">
                                <button type="button" 
                                        @click="file = null; preview = null; document.getElementById('photo').value = ''"
                                        class="mt-2 text-sm text-red-600 hover:text-red-800">
                                    Hapus foto
                                </button>
                            </div>
                        </div>
                        @error('photo')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

            </form>
        </div>

    </div>

@endsection

