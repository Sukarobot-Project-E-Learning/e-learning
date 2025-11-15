@extends('instructor.layouts.app')

@section('title', 'Edit Profile')

@section('content')
    <div class="container px-6 mx-auto">
        <!-- Page Header -->
        <div class="my-6">
            <div class="flex items-start justify-between">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Edit Profile</h2>
                <div class="flex flex-col items-end">
                    <a href="{{ route('instructor.dashboard') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 mb-4">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                    <button type="submit" 
                            form="profileForm"
                            class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Simpan Profile</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="w-full mb-8 overflow-hidden rounded-lg shadow-md bg-white dark:bg-gray-800">
            <form id="profileForm" action="{{ route('instructor.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="px-6 py-4 bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Informasi Profile</h3>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Nama -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name"
                               value="{{ old('name', 'John Doe') }}"
                               class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600"
                               placeholder="Masukkan nama lengkap"
                               required>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               name="email" 
                               id="email"
                               value="{{ old('email', 'john.doe@example.com') }}"
                               class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600"
                               placeholder="Masukkan email"
                               required>
                    </div>

                    <!-- Nomor Telepon -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nomor Telepon
                        </label>
                        <input type="tel" 
                               name="phone" 
                               id="phone"
                               value="{{ old('phone', '081234567890') }}"
                               class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600"
                               placeholder="Masukkan nomor telepon">
                    </div>

                    <!-- Pekerjaan -->
                    <div>
                        <label for="job" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Pekerjaan
                        </label>
                        <input type="text" 
                               name="job" 
                               id="job"
                               value="{{ old('job', 'Social Media Manager') }}"
                               class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600"
                               placeholder="Masukkan pekerjaan">
                    </div>

                    <!-- Pengalaman -->
                    <div>
                        <label for="experience" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Pengalaman
                        </label>
                        <input type="text" 
                               name="experience" 
                               id="experience"
                               value="{{ old('experience', '7 Tahun') }}"
                               class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600"
                               placeholder="Contoh: 7 Tahun">
                    </div>

                    <!-- Keahlian -->
                    <div>
                        <label for="expertise" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Keahlian
                        </label>
                        <input type="text" 
                               name="expertise" 
                               id="expertise"
                               value="{{ old('expertise', 'Social Media, Digital Marketing') }}"
                               class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600"
                               placeholder="Masukkan keahlian (pisahkan dengan koma)">
                    </div>

                    <!-- Bio -->
                    <div>
                        <label for="bio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Bio / Deskripsi
                        </label>
                        <textarea name="bio" 
                                  id="bio"
                                  rows="4"
                                  class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600"
                                  placeholder="Tuliskan bio atau deskripsi tentang Anda">{{ old('bio', '') }}</textarea>
                    </div>

                    <!-- Unggah Foto -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Foto Profile
                        </label>
                        <div x-data="{ 
                            file: null, 
                            preview: null,
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
                        @dragover.prevent
                        @drop.prevent="handleFile($event)">
                            
                            <input type="file" 
                                   name="photo" 
                                   id="photo"
                                   accept="image/jpeg,image/jpg,image/png"
                                   @change="handleFile($event)"
                                   class="hidden">
                            
                            <label for="photo" 
                                   class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 dark:border-gray-600">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6" x-show="!preview">
                                    <svg class="w-8 h-8 mb-2 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="font-semibold">Klik untuk upload</span> atau seret dan lepas
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        JPG, JPEG, PNG
                                    </p>
                                </div>
                            </label>

                            <div x-show="preview" class="mt-4">
                                <img :src="preview" alt="Preview" class="w-32 h-32 object-cover rounded-lg border border-gray-300">
                                <button type="button" 
                                        @click="file = null; preview = null; document.getElementById('photo').value = ''"
                                        class="mt-2 text-sm text-red-600 hover:text-red-800">
                                    Hapus foto
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

