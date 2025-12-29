@extends('admin.layouts.app')

@section('title', 'Edit Program')

@section('content')

    <div class="container px-6 mx-auto">

        <!-- Page Header -->
        <div class="my-6">
            <div class="flex items-start justify-between">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Edit Program</h2>
            </div>
        </div>

        <!-- Form Card -->
        <div class="w-full mb-8 p-6 overflow-hidden rounded-lg shadow-md bg-white dark:bg-gray-800">
            <form id="programForm" action="{{ route('admin.programs.update', $program->id) }}" method="POST"
                enctype="multipart/form-data" x-data="programForm()">
                @csrf
                @method('PUT')
                <div class="space-y-6">

                    <!-- Informasi Dasar -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-6">Informasi Dasar</h3>
                        <div class="space-y-6">

                            <!-- Judul Program -->
                            <div class="mb-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Judul Program <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="program" required placeholder="Masukkan judul program"
                                    class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"
                                    value="{{ old('program', $program->program ?? '') }}">
                                @error('program')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kategori -->
                            <div class="mb-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Kategori <span class="text-red-500">*</span>
                                </label>
                                <select name="category" required
                                    class="block w-full px-4 py-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                    <option value="">Pilih Kategori</option>
                                    <option value="kursus" {{ old('category', $program->category ?? '') == 'kursus' ? 'selected' : '' }}>Kursus</option>
                                    <option value="pelatihan" {{ old('category', $program->category ?? '') == 'pelatihan' ? 'selected' : '' }}>Pelatihan
                                    </option>
                                    <option value="sertifikasi" {{ old('category', $program->category ?? '') == 'sertifikasi' ? 'selected' : '' }}>
                                        Sertifikasi</option>
                                    <option value="outing-class" {{ old('category', $program->category ?? '') == 'outing-class' ? 'selected' : '' }}>
                                        Outing Class</option>
                                    <option value="outboard" {{ old('category', $program->category ?? '') == 'outboard' ? 'selected' : '' }}>Outboard
                                    </option>
                                </select>
                            </div>

                            <!-- Jenis Pelaksanaan -->
                            <div class="mb-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Jenis Pelaksanaan <span class="text-red-500">*</span>
                                </label>
                                <select name="type" required x-model="type"
                                    class="block w-full px-4 py-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                    <option value="">Pilih Jenis</option>
                                    <option value="online" {{ old('type', $program->type ?? '') == 'online' ? 'selected' : '' }}>Online</option>
                                    <option value="offline" {{ old('type', $program->type ?? '') == 'offline' ? 'selected' : '' }}>Offline</option>
                                </select>
                            </div>

                            <!-- Zoom Link (Online Only) -->
                            <div x-show="type === 'online'" x-transition>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Link Zoom <span class="text-red-500">*</span>
                                </label>
                                <input type="url" name="zoom_link" :required="type === 'online'"
                                    placeholder="https://zoom.us/j/..."
                                    class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"
                                    value="{{ old('zoom_link', $program->zoom_link ?? '') }}">
                            </div>

                            <!-- Deskripsi -->
                            <div class="mb-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Deskripsi <span class="text-red-500">*</span>
                                </label>
                                <textarea name="description" rows="4" required placeholder="Masukkan deskripsi program"
                                    class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">{{ old('description', $program->description ?? '') }}</textarea>
                            </div>

                        </div>
                    </div>

                    <!-- Detail Program -->
                    <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-6">Detail Program</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <!-- Instructor -->
                            <div class="mb-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Instruktur
                                </label>
                                <select name="instructor_id"
                                    class="block w-full px-4 py-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                    <option value="">Pilih Instruktur</option>
                                    @foreach(\App\Models\User::where('role', 'instructor')->get() as $instructor)
                                        <option value="{{ $instructor->id }}" {{ old('instructor_id', $program->instructor_id ?? '') == $instructor->id ? 'selected' : '' }}>{{ $instructor->name }}</option>
                                    @endforeach
                                </select>
                            </div>


                            <!-- Kuota -->
                            <div class="mb-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Kuota Peserta <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="quota" required min="1" placeholder="Contoh: 50"
                                    class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"
                                    value="{{ old('quota', $program->quota ?? '') }}">
                            </div>

                            <!-- Harga -->
                            <div class="mb-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Harga (Rp) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="price" required min="0" step="0.01" placeholder="Contoh: 350000"
                                    class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"
                                    value="{{ old('price', $program->price ?? '') }}">
                            </div>

                        </div>
                    </div>

                    <!-- Tools yang Digunakan (Dynamic) -->
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Tools yang Digunakan</h3>
                            <button type="button" @click="addTool()"
                                class="flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-orange-600 bg-orange-50 rounded-lg hover:bg-orange-100 dark:bg-orange-900 dark:text-orange-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                Tambah Tool
                            </button>
                        </div>
                        <div class="space-y-3">
                            <template x-for="(tool, index) in tools" :key="index">
                                <div class="flex gap-2">
                                    <input type="text" :name="'tools[' + index + ']'" x-model="tools[index]"
                                        placeholder="Contoh: VS Code"
                                        class="flex-1 px-4 py-2 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                    <button type="button" @click="removeTool(index)"
                                        class="p-3 text-red-500 hover:bg-red-50 rounded-lg dark:hover:bg-red-900/30 transition-colors"
                                        title="Hapus Tool">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </template>
                            
                            <div x-show="tools.length === 0" 
                                class="flex flex-col items-center justify-center p-8 border-2 border-dashed border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-800 dark:border-gray-700">
                                <svg class="w-12 h-12 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <p class="text-sm text-gray-500 dark:text-gray-400 text-center">Belum ada tools yang ditambahkan.</p>
                                <button type="button" @click="addTool()" class="mt-2 text-sm font-medium text-purple-600 hover:text-purple-700 dark:text-purple-400">
                                    + Tambah Tool Pertama
                                </button>
                            </div>
                            
                            <button type="button" x-show="tools.length > 0" @click="addTool()" 
                                class="flex items-center gap-2 text-sm font-medium text-purple-600 hover:text-purple-700 dark:text-purple-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Tambah Tool Lainnya
                            </button>
                        </div>
                    </div>



                    <!-- Kamu Akan Mendapatkan (Dynamic) -->
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Kamu Akan Mendapatkan</h3>
                            <button type="button" @click="addBenefit()"
                                class="flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-orange-600 bg-orange-50 rounded-lg hover:bg-orange-100 dark:bg-orange-900 dark:text-orange-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                Tambah
                            </button>
                        </div>
                        <div class="space-y-3">
                            <template x-for="(benefit, index) in benefits" :key="index">
                                <div class="flex gap-2">
                                    <input type="text" :name="'benefits[' + index + ']'" x-model="benefits[index]"
                                        placeholder="Contoh: Sertifikat"
                                        class="flex-1 px-4 py-2 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                    <button type="button" @click="removeBenefit(index)"
                                        class="p-3 text-red-500 hover:bg-red-50 rounded-lg dark:hover:bg-red-900/30 transition-colors"
                                        title="Hapus Benefit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </template>
                            
                            <div x-show="benefits.length === 0" 
                                class="flex flex-col items-center justify-center p-8 border-2 border-dashed border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-800 dark:border-gray-700">
                                <svg class="w-12 h-12 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                                </svg>
                                <p class="text-sm text-gray-500 dark:text-gray-400 text-center">Belum ada benefit yang ditambahkan.</p>
                                <button type="button" @click="addBenefit()" class="mt-2 text-sm font-medium text-purple-600 hover:text-purple-700 dark:text-purple-400">
                                    + Tambah Benefit Pertama
                                </button>
                            </div>
                            
                            <button type="button" x-show="benefits.length > 0" @click="addBenefit()" 
                                class="flex items-center gap-2 text-sm font-medium text-purple-600 hover:text-purple-700 dark:text-purple-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Tambah Benefit Lainnya
                            </button>
                        </div>
                    </div>

                    <!-- Lokasi (Offline Only) -->
                    <div x-show="type === 'offline'" x-transition
                        class="pt-6 border-t border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-6">Lokasi Pelaksanaan</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Provinsi <span x-show="type === 'offline'" class="text-red-500">*</span>
                                </label>
                                <input type="hidden" name="province" id="province_text"
                                    value="{{ old('province', $program->province ?? '') }}" :required="type === 'offline'">
                                <select id="province" :required="type === 'offline'"
                                    onchange="document.getElementById('province_text').value = this.options[this.selectedIndex].textContent.trim()"
                                    class="block w-full px-4 py-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                    <option value="">Pilih Provinsi</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Kabupaten/Kota <span x-show="type === 'offline'" class="text-red-500">*</span>
                                </label>
                                <input type="hidden" name="city" id="city_text"
                                    value="{{ old('city', $program->city ?? '') }}" :required="type === 'offline'">
                                <select id="city" :required="type === 'offline'"
                                    onchange="document.getElementById('city_text').value = this.options[this.selectedIndex].textContent.trim()"
                                    class="block w-full px-4 py-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                    <option value="">Pilih Kabupaten/Kota</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Kecamatan <span x-show="type === 'offline'" class="text-red-500">*</span>
                                </label>
                                <input type="hidden" name="district" id="district_text"
                                    value="{{ old('district', $program->district ?? '') }}" :required="type === 'offline'">
                                <select id="district" :required="type === 'offline'"
                                    onchange="document.getElementById('district_text').value = this.options[this.selectedIndex].textContent.trim()"
                                    class="block w-full px-4 py-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                    <option value="">Pilih Kecamatan</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Kelurahan/Desa <span x-show="type === 'offline'" class="text-red-500">*</span>
                                </label>
                                <input type="hidden" name="village" id="village_text"
                                    value="{{ old('village', $program->village ?? '') }}" :required="type === 'offline'">
                                <select id="village" :required="type === 'offline'"
                                    onchange="document.getElementById('village_text').value = this.options[this.selectedIndex].textContent.trim()"
                                    class="block w-full px-4 py-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                    <option value="">Pilih Kelurahan/Desa</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Alamat Lengkap <span x-show="type === 'offline'" class="text-red-500">*</span>
                            </label>
                            <textarea name="full_address" rows="3" :required="type === 'offline'"
                                placeholder="Masukkan alamat lengkap"
                                class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">{{ old('full_address', $program->full_address ?? '') }}</textarea>
                        </div>
                    </div>

                    <!-- Jadwal Pelaksanaan -->
                    <div class="pt-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-6">Jadwal Pelaksanaan</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Tanggal Mulai <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="start_date" required
                                    class="block w-full px-4 py-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"
                                    value="{{ old('start_date', $program->start_date ?? '') }}">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Waktu Mulai <span class="text-red-500">*</span>
                                </label>
                                <input type="time" name="start_time" required
                                    @change="updateAllMaterialDurations()"
                                    class="block w-full px-4 py-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"
                                    value="{{ old('start_time', $program->start_time ?? '') }}">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Tanggal Berakhir <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="end_date" required
                                    class="block w-full px-4 py-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"
                                    value="{{ old('end_date', $program->end_date ?? '') }}">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Waktu Berakhir <span class="text-red-500">*</span>
                                </label>
                                <input type="time" name="end_time" required
                                    @change="updateAllMaterialDurations()"
                                    class="block w-full px-4 py-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"
                                    value="{{ old('end_time', $program->end_time ?? '') }}">
                            </div>
                        </div>
                    </div>

                    <!-- Materi Pembelajaran (Dynamic) - Below Jadwal -->
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Materi Pembelajaran</h3>
                            <button type="button" @click="addMaterial()"
                                class="flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-orange-600 bg-orange-50 rounded-lg hover:bg-orange-100 dark:bg-orange-900 dark:text-orange-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                Tambah Hari
                            </button>
                        </div>
                        <div class="space-y-6">
                            <template x-for="(material, index) in materials" :key="index">
                                <div
                                    class="p-4 border border-gray-200 rounded-lg dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="text-sm font-semibold text-orange-600 dark:text-orange-400"
                                            x-text="'Hari ' + (index + 1)"></span>
                                        <button type="button" @click="removeMaterial(index)"
                                            class="px-2 py-1 text-xs text-red-600 hover:bg-red-50 rounded dark:hover:bg-red-900">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="space-y-3">
                                        <!-- Judul -->
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Judul Materi</label>
                                            <input type="text" :name="'materials[' + index + '][title]'"
                                                x-model="materials[index].title" placeholder="Contoh: Hari 1: Analisis SWOT"
                                                class="block w-full px-3 py-2 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                        </div>
                                        <!-- Durasi (Auto-calculated from Jadwal, but editable) -->
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Durasi <span class="text-gray-400">(otomatis terisi)</span></label>
                                            <div class="flex items-center gap-2">
                                                <input type="number" x-model="materials[index].duration_hours" min="0" max="23"
                                                    class="block w-16 px-3 py-2 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"
                                                    placeholder="0">
                                                <span class="text-sm text-gray-600 dark:text-gray-400">Jam</span>
                                                <input type="number" x-model="materials[index].duration_minutes" min="0" max="59"
                                                    class="block w-16 px-3 py-2 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"
                                                    placeholder="0">
                                                <span class="text-sm text-gray-600 dark:text-gray-400">Menit</span>
                                                <!-- Hidden input to store combined duration -->
                                                <input type="hidden" :name="'materials[' + index + '][duration]'" 
                                                    :value="(materials[index].duration_hours || 0) + ':' + (materials[index].duration_minutes || 0)">
                                            </div>
                                        </div>
                                        <!-- Deskripsi -->
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Detail Aktivitas</label>
                                            <textarea :name="'materials[' + index + '][description]'"
                                                x-model="materials[index].description" rows="4" placeholder="Contoh:
Belajar menganalisis kekuatan, kelemahan, peluang, dan ancaman bisnis.

• Pembukaan & Pengenalan Materi
• Presentasi Strategi Digital
• Praktik Pembuatan Kampanye
• Evaluasi & Tanya Jawab" class="block w-full px-3 py-2 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <div x-show="materials.length === 0" class="text-sm text-gray-500 italic">
                                Belum ada materi. Klik "Tambah Hari" untuk menambahkan.
                            </div>
                        </div>
                    </div>


                    <!-- Gambar Program -->
                    <div class="pt-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Gambar Program</h3>

                        <!-- Current Image (if exists) -->
                        @if(!empty($program->image))
                            <div class="mb-4">
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Gambar saat ini:</p>
                                <img src="{{ asset($program->image) }}" alt="Current program image"
                                    class="w-48 h-32 object-cover rounded-lg border border-gray-300 dark:border-gray-600">
                            </div>
                        @endif

                        <div class="flex items-center justify-center w-full">
                            <label for="image"
                                class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-800 dark:border-gray-600"
                                x-data="{ imagePreview: null }">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6" x-show="!imagePreview">
                                    <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                        </path>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="font-semibold">Klik untuk upload gambar baru</span> atau drag & drop
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, JPEG (MAX. 2MB)</p>
                                </div>
                                <div x-show="imagePreview" class="relative w-full h-full p-4">
                                    <img :src="imagePreview" alt="Preview" class="w-full h-full object-contain rounded-lg">
                                    <button type="button"
                                        @click.stop.prevent="imagePreview = null; $refs.imageInput.value = ''"
                                        class="absolute top-6 right-6 p-1.5 bg-red-500 text-white rounded-full hover:bg-red-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                <input id="image" name="image" type="file" class="hidden" accept="image/*"
                                    x-ref="imageInput"
                                    @change="let file = $event.target.files[0]; if (file) { let reader = new FileReader(); reader.onload = (e) => { imagePreview = e.target.result }; reader.readAsDataURL(file); }">
                            </label>
                        </div>

                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-row justify-end items-end mt-6 gap-4">
                        <a href="{{ route('admin.programs.index') }}"
                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                            Kembali
                        </a>
                        <button type="submit"
                            class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-orange-600 border border-transparent rounded-lg active:bg-orange-600 hover:bg-orange-700 focus:outline-none focus:shadow-outline-orange">
                            Simpan Perubahan
                        </button>
                    </div>

                </div>
            </form>
        </div>

    </div>

    @push('scripts')
        <script>
            // Intercept form submit to convert region IDs to text names
            document.addEventListener('DOMContentLoaded', function () {
                const form = document.querySelector('form');
                if (form) {
                    form.addEventListener('submit', function (e) {
                        // Get all region selects
                        const provinceSelect = document.getElementById('province');
                        const citySelect = document.getElementById('city');
                        const districtSelect = document.getElementById('district');
                        const villageSelect = document.getElementById('village');

                        // Convert selected IDs to text names
                        if (provinceSelect && provinceSelect.value) {
                            const selectedOption = provinceSelect.options[provinceSelect.selectedIndex];
                            if (selectedOption && selectedOption.textContent.trim()) {
                                provinceSelect.value = selectedOption.textContent.trim();
                            }
                        }

                        if (citySelect && citySelect.value) {
                            const selectedOption = citySelect.options[citySelect.selectedIndex];
                            if (selectedOption && selectedOption.textContent.trim()) {
                                citySelect.value = selectedOption.textContent.trim();
                            }
                        }

                        if (districtSelect && districtSelect.value) {
                            const selectedOption = districtSelect.options[districtSelect.selectedIndex];
                            if (selectedOption && selectedOption.textContent.trim()) {
                                districtSelect.value = selectedOption.textContent.trim();
                            }
                        }

                        if (villageSelect && villageSelect.value) {
                            const selectedOption = villageSelect.options[villageSelect.selectedIndex];
                            if (selectedOption && selectedOption.textContent.trim()) {
                                villageSelect.value = selectedOption.textContent.trim();
                            }
                        }
                    });
                }
            });
        </script>
        <script src="{{ asset('assets/elearning/region-selector.js') }}?v={{ time() }}"></script>
        <script>
            // After region selector loads, populate existing location values
            document.addEventListener('DOMContentLoaded', function () {
                @if(!empty($program->province) && !empty($program->city))
                    // Wait for region selector to initialize and load provinces
                    setTimeout(function () {
                        const provinceSelect = document.getElementById('province');
                        const citySelect = document.getElementById('city');
                        const districtSelect = document.getElementById('district');
                        const villageSelect = document.getElementById('village');

                        const savedProvince = '{{ $program->province ?? '' }}';
                        const savedCity = '{{ $program->city ?? '' }}';
                        const savedDistrict = '{{ $program->district ?? '' }}';
                        const savedVillage = '{{ $program->village ?? '' }}';

                        // Find and select province by text match
                        if (provinceSelect && savedProvince) {
                            const provinceOption = Array.from(provinceSelect.options)
                                .find(opt => opt.textContent.trim().toUpperCase() === savedProvince.toUpperCase());

                            if (provinceOption) {
                                provinceSelect.value = provinceOption.value;
                                // Update hidden input
                                document.getElementById('province_text').value = provinceOption.textContent.trim();
                                // Trigger change to load cities
                                provinceSelect.dispatchEvent(new Event('change'));

                                // Wait for cities to load, then select
                                setTimeout(() => {
                                    if (citySelect && savedCity) {
                                        const cityOption = Array.from(citySelect.options)
                                            .find(opt => opt.textContent.trim().toUpperCase() === savedCity.toUpperCase());

                                        if (cityOption) {
                                            citySelect.value = cityOption.value;
                                            document.getElementById('city_text').value = cityOption.textContent.trim();
                                            citySelect.dispatchEvent(new Event('change'));

                                            // Wait for districts to load, then select
                                            setTimeout(() => {
                                                if (districtSelect && savedDistrict) {
                                                    const districtOption = Array.from(districtSelect.options)
                                                        .find(opt => opt.textContent.trim().toUpperCase() === savedDistrict.toUpperCase());

                                                    if (districtOption) {
                                                        districtSelect.value = districtOption.value;
                                                        document.getElementById('district_text').value = districtOption.textContent.trim();
                                                        districtSelect.dispatchEvent(new Event('change'));

                                                        // Wait for villages to load, then select
                                                        setTimeout(() => {
                                                            if (villageSelect && savedVillage) {
                                                                const villageOption = Array.from(villageSelect.options)
                                                                    .find(opt => opt.textContent.trim().toUpperCase() === savedVillage.toUpperCase());

                                                                if (villageOption) {
                                                                    villageSelect.value = villageOption.value;
                                                                    document.getElementById('village_text').value = villageOption.textContent.trim();
                                                                }
                                                            }
                                                        }, 1000);
                                                    }
                                                }
                                            }, 1000);
                                        }
                                    }
                                }, 1000);
                            }
                        }
                    }, 1500);
                @endif
                    });
        </script>
        <script>
            function programForm() {
                return {
                    type: '{{ old("type", $program->type ?? "") }}',
                    tools: {!! json_encode($program->tools ?? []) !!},
                    materials: [],
                    benefits: {!! json_encode($program->benefits ?? []) !!},

                    init() {
                        // Parse existing materials and convert duration to hours/minutes
                        const existingMaterials = {!! json_encode($program->learning_materials ?? []) !!};
                        this.materials = existingMaterials.map(m => {
                            let hours = 0, minutes = 0;
                            if (m.duration) {
                                const parts = String(m.duration).split(':');
                                hours = parseInt(parts[0]) || 0;
                                minutes = parseInt(parts[1]) || 0;
                            }
                            return {
                                title: m.title || '',
                                duration_hours: hours,
                                duration_minutes: minutes,
                                description: m.description || ''
                            };
                        });
                    },

                    addTool() {
                        this.tools.push('');
                    },
                    removeTool(index) {
                        this.tools.splice(index, 1);
                    },

                    addMaterial() {
                        // Auto-fill duration from Jadwal Pelaksanaan
                        const duration = this.getCalculatedDuration();
                        this.materials.push({ 
                            title: '', 
                            duration_hours: duration.hours, 
                            duration_minutes: duration.minutes, 
                            description: '' 
                        });
                    },
                    removeMaterial(index) {
                        this.materials.splice(index, 1);
                    },

                    getCalculatedDuration() {
                        const startTime = document.querySelector('input[name="start_time"]')?.value;
                        const endTime = document.querySelector('input[name="end_time"]')?.value;
                        
                        if (startTime && endTime) {
                            const start = startTime.split(':');
                            const end = endTime.split(':');
                            
                            const startMinutes = parseInt(start[0]) * 60 + parseInt(start[1]);
                            const endMinutes = parseInt(end[0]) * 60 + parseInt(end[1]);
                            
                            let diffMinutes = endMinutes - startMinutes;
                            if (diffMinutes < 0) diffMinutes += 24 * 60;
                            
                            return {
                                hours: Math.floor(diffMinutes / 60),
                                minutes: diffMinutes % 60
                            };
                        }
                        return { hours: '', minutes: '' };
                    },

                    updateAllMaterialDurations() {
                        const duration = this.getCalculatedDuration();
                        this.materials.forEach((material) => {
                            material.duration_hours = duration.hours;
                            material.duration_minutes = duration.minutes;
                        });
                    },

                    addBenefit() {
                        this.benefits.push('');
                    },
                    removeBenefit(index) {
                        this.benefits.splice(index, 1);
                    }
                }
            }
        </script>
    @endpush

@endsection