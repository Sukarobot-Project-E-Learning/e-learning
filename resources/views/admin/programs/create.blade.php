@extends('admin.layouts.app')

@section('title', 'Tambah Program')

@section('content')

    <div class="container px-6 mx-auto">

        <!-- Page Header -->
        <div class="my-6">
            <div class="flex items-start justify-between">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Tambah Program</h2>
            </div>
        </div>

        <!-- Form Card -->
        <div class="w-full mb-8 p-6 overflow-hidden rounded-lg shadow-md bg-white dark:bg-gray-800">
            <form id="programForm" action="{{ route('admin.programs.store') }}" method="POST" enctype="multipart/form-data"
                x-data="programForm()">
                @csrf
                <div class="space-y-6">

                    <!-- Informasi Dasar -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Informasi Dasar</h3>
                        <div class="space-y-6">

                            <!-- Judul Program -->
                            <div class="mb-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Judul Program <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="program" required placeholder="Masukkan judul program"
                                    class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"
                                    value="{{ old('program') }}">
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
                                    <option value="kursus" {{ old('category') == 'kursus' ? 'selected' : '' }}>Kursus</option>
                                    <option value="pelatihan" {{ old('category') == 'pelatihan' ? 'selected' : '' }}>Pelatihan
                                    </option>
                                    <option value="sertifikasi" {{ old('category') == 'sertifikasi' ? 'selected' : '' }}>
                                        Sertifikasi</option>
                                    <option value="outing-class" {{ old('category') == 'outing-class' ? 'selected' : '' }}>
                                        Outing Class</option>
                                    <option value="outboard" {{ old('category') == 'outboard' ? 'selected' : '' }}>Outboard
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
                                    <option value="online" {{ old('type') == 'online' ? 'selected' : '' }}>Online</option>
                                    <option value="offline" {{ old('type') == 'offline' ? 'selected' : '' }}>Offline</option>
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
                                    value="{{ old('zoom_link') }}">
                            </div>

                            <!-- Deskripsi -->
                            <div class="mb-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Deskripsi <span class="text-red-500">*</span>
                                </label>
                                <textarea name="description" rows="4" required placeholder="Masukkan deskripsi program"
                                    class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">{{ old('description') }}</textarea>
                            </div>

                        </div>
                    </div>

                    <!-- Detail Program -->
                    <div class="pt-4 border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Detail Program</h3>
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
                                        <option value="{{ $instructor->id }}">{{ $instructor->name }}</option>
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
                                    value="{{ old('quota') }}">
                            </div>

                            <!-- Harga -->
                            <div class="mb-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Harga (Rp) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="price" required min="0" step="0.01" placeholder="Contoh: 350000"
                                    class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"
                                    value="{{ old('price') }}">
                            </div>

                        </div>
                    </div>

                    <!-- Tools yang Digunakan (Dynamic) -->
                    <div class="pt-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Tools yang Digunakan</h3>
                            <button type="button" @click="addTool()"
                                class="flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-orange-600 bg-orange-50 rounded-lg hover:bg-orange-100 dark:bg-orange-900 dark:text-orange-300 cursor-pointer">
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
                                        class="p-3 text-red-500 hover:bg-red-50 rounded-lg dark:hover:bg-red-900/30 transition-colors cursor-pointer"
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
                                <button type="button" @click="addTool()" class="mt-2 text-sm font-medium text-purple-600 hover:text-purple-700 dark:text-purple-400 cursor-pointer">
                                    + Tambah Tool Pertama
                                </button>
                            </div>
                        </div>
                    </div>



                    <!-- Kamu Akan Mendapatkan (Dynamic) -->
                    <div class="pt-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Kamu Akan Mendapatkan</h3>
                            <button type="button" @click="addBenefit()"
                                class="flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-orange-600 bg-orange-50 rounded-lg hover:bg-orange-100 dark:bg-orange-900 dark:text-orange-300 cursor-pointer">
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
                                        class="p-3 text-red-500 hover:bg-red-50 rounded-lg dark:hover:bg-red-900/30 transition-colors cursor-pointer"
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
                                <button type="button" @click="addBenefit()" class="mt-2 text-sm font-medium text-purple-600 hover:text-purple-700 dark:text-purple-400 cursor-pointer">
                                    + Tambah Benefit Pertama
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Lokasi (Offline Only) -->
                    <div x-show="type === 'offline'" x-transition
                        class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Lokasi Pelaksanaan</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Provinsi <span x-show="type === 'offline'" class="text-red-500">*</span>
                                </label>
                                <input type="hidden" name="province" id="province_text" :required="type === 'offline'">
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
                                <input type="hidden" name="city" id="city_text" :required="type === 'offline'">
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
                                <input type="hidden" name="district" id="district_text" :required="type === 'offline'">
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
                                <input type="hidden" name="village" id="village_text" :required="type === 'offline'">
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
                                class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">{{ old('full_address') }}</textarea>
                        </div>
                    </div>

                    <!-- Jadwal Pelaksanaan -->
                    <div class="pt-4">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Jadwal Pelaksanaan</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Tanggal Mulai <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="start_date" required
                                    class="block w-full px-4 py-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"
                                    value="{{ old('start_date') }}">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Waktu Mulai <span class="text-red-500">*</span>
                                </label>
                                <input type="time" name="start_time" required
                                    @change="updateAllMaterialDurations()"
                                    class="block w-full px-4 py-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"
                                    value="{{ old('start_time') }}">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Tanggal Berakhir <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="end_date" required
                                    class="block w-full px-4 py-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"
                                    value="{{ old('end_date') }}">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Waktu Berakhir <span class="text-red-500">*</span>
                                </label>
                                <input type="time" name="end_time" required
                                    @change="updateAllMaterialDurations()"
                                    class="block w-full px-4 py-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"
                                    value="{{ old('end_time') }}">
                            </div>
                        </div>
                    </div>

                    <!-- Materi Pembelajaran (Dynamic) - Below Jadwal -->
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Materi Pembelajaran</h3>
                            <button type="button" @click="addMaterial()"
                                class="flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-orange-600 bg-orange-50 rounded-lg hover:bg-orange-100 dark:bg-orange-900 dark:text-orange-300 cursor-pointer">
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
                                            class="px-2 py-1 text-xs text-red-600 hover:bg-red-50 rounded dark:hover:bg-red-900 cursor-pointer">
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
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Gambar Program</h3>

                        <!-- Info Box -->
                        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg dark:bg-blue-900/20 dark:border-blue-800">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <h4 class="font-medium text-blue-800 dark:text-blue-300">Spesifikasi Gambar</h4>
                                    <ol class="mt-2 text-sm text-blue-700 dark:text-blue-400 list-decimal list-inside space-y-1">
                                        <li>Format: .png, .jpg, .jpeg</li>
                                        <li>Ukuran: 1920 x 1080</li>
                                        <li>Ukuran file: Maksimal 2MB</li>
                                        <li>Fokus Konten di tengah</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-center w-full">
                            <label for="image"
                                class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-800 transition-colors border-gray-300 dark:border-gray-600"
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
                                            $refs.imageInput.value = '';
                                            return false;
                                        }

                                        if (file.size > maxSize) {
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Upload Gagal',
                                                text: 'Ukuran file melebihi 2MB.'
                                            });
                                            this.fileName = null;
                                            $refs.imageInput.value = '';
                                            return false;
                                        }

                                        this.fileName = file.name;
                                        return true;
                                    }
                                }" 
                                @dragover.prevent 
                                @drop.prevent="
                                       const file = $event.dataTransfer.files[0];
                                       if (file && validateFile(file)) {
                                            $refs.imageInput.files = $event.dataTransfer.files;
                                       }
                                   ">
                                <div class="flex flex-col items-center justify-center pt-2 pb-3" x-show="!fileName">
                                    <svg class="w-8 h-8 mb-2 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                        </path>
                                    </svg>
                                    <p class="mb-1 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="font-semibold">Seret dan lepas berkas, atau</span>
                                        <span class="text-purple-600 hover:text-purple-700 dark:text-purple-400">Telusuri</span>
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        Unggah berkas dalam bentuk: JPG, JPEG, PNG (Maks. 2MB)
                                    </p>
                                </div>
                                <div x-show="fileName" class="flex flex-col items-center justify-center w-full h-full p-4">
                                    <svg class="w-8 h-8 mb-2 text-green-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-200"
                                        x-text="'File terpilih: ' + fileName"></p>
                                    <button type="button"
                                        @click.stop.prevent="fileName = null; $refs.imageInput.value = ''"
                                        class="mt-2 text-xs text-red-500 hover:text-red-700 font-medium cursor-pointer">
                                        Hapus File
                                    </button>
                                </div>
                                <input id="image" name="image" type="file" class="hidden" accept="image/png, image/jpeg, image/jpg"
                                    x-ref="imageInput" @change="
                                           const file = $event.target.files[0];
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
                <a href="{{ route('admin.programs.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
                <button type="submit" form="programForm"
                    class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-orange-600 border border-transparent rounded-lg active:bg-orange-600 hover:bg-orange-700 focus:outline-none focus:shadow-outline-orang cursor-pointer">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan Program
                </button>
            </div>
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
            function programForm() {
                return {
                    type: '{{ old("type") }}' || '',
                    tools: [],
                    materials: [],
                    benefits: [],

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