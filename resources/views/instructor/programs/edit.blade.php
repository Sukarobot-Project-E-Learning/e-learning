@extends('instructor.layouts.app')

@section('title', 'Edit Pengajuan Program')

@section('content')

    <div class="container px-6 mx-auto">

        <!-- Page Header -->
        <div class="my-6">
            <div class="flex items-start justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Edit Pengajuan Program</h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        @if($submission->status === 'rejected')
                            <span class="text-red-600">Program ini ditolak. Silakan perbaiki dan ajukan ulang.</span>
                        @else
                            Perbarui data pengajuan program Anda.
                        @endif
                    </p>
                </div>
                <div class="flex flex-col items-end" style="gap: 16px;">
                    <a href="{{ route('instructor.programs.index') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                    <button type="submit" form="programForm"
                        class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Simpan & Ajukan Ulang</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Status Badge -->
        @if($submission->status === 'rejected')
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg dark:bg-red-900/20 dark:border-red-800">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <p class="text-sm font-medium text-red-800 dark:text-red-200">Program Ditolak</p>
                    @if($submission->rejection_reason)
                        <p class="mt-1 text-sm text-red-600 dark:text-red-300">Alasan: {{ $submission->rejection_reason }}</p>
                    @endif
                </div>
            </div>
        </div>
        @elseif($submission->status === 'pending')
        <div class="mb-6">
            <span class="px-3 py-1.5 text-sm font-semibold text-yellow-700 bg-yellow-100 rounded-full">
                ⏳ Menunggu Persetujuan
            </span>
        </div>
        @endif

        <!-- Form Card -->
        <div class="w-full mb-8 overflow-hidden rounded-lg shadow-md bg-white dark:bg-gray-800">
            <form id="programForm" action="{{ route('instructor.programs.update', $submission->id) }}" method="POST" enctype="multipart/form-data"
                x-data="programForm()">
                @csrf
                @method('PUT')
                <div class="px-6 py-6 space-y-6">

                    <!-- Informasi Dasar -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Informasi Dasar</h3>
                        <div class="space-y-4">

                            <!-- Judul Program -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Judul Program <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="title" required placeholder="Masukkan judul program"
                                    class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring focus:ring-purple-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"
                                    value="{{ old('title', $submission->title) }}">
                            </div>

                            <!-- Kategori -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Kategori <span class="text-red-500">*</span>
                                </label>
                                <select name="category" required
                                    class="block w-full px-4 py-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring focus:ring-purple-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                    <option value="">Pilih Kategori</option>
                                    <option value="kursus" {{ old('category', $submission->category) == 'kursus' ? 'selected' : '' }}>Kursus</option>
                                    <option value="pelatihan" {{ old('category', $submission->category) == 'pelatihan' ? 'selected' : '' }}>Pelatihan</option>
                                    <option value="sertifikasi" {{ old('category', $submission->category) == 'sertifikasi' ? 'selected' : '' }}>Sertifikasi</option>
                                    <option value="outing-class" {{ old('category', $submission->category) == 'outing-class' ? 'selected' : '' }}>Outing Class</option>
                                    <option value="outboard" {{ old('category', $submission->category) == 'outboard' ? 'selected' : '' }}>Outboard</option>
                                </select>
                            </div>

                            <!-- Jenis Pelaksanaan -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Jenis Pelaksanaan <span class="text-red-500">*</span>
                                </label>
                                <select name="type" required x-model="type"
                                    class="block w-full px-4 py-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring focus:ring-purple-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                    <option value="">Pilih Jenis</option>
                                    <option value="online" {{ old('type', $submission->type) == 'online' ? 'selected' : '' }}>Online</option>
                                    <option value="offline" {{ old('type', $submission->type) == 'offline' ? 'selected' : '' }}>Offline</option>
                                </select>
                            </div>

                            <!-- Deskripsi -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Deskripsi <span class="text-red-500">*</span>
                                </label>
                                <textarea name="description" rows="4" required placeholder="Masukkan deskripsi program"
                                    class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring focus:ring-purple-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">{{ old('description', $submission->description) }}</textarea>
                            </div>

                        </div>
                    </div>

                    <!-- Detail Program -->
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Detail Program</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <!-- Kuota -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Kuota Peserta <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="available_slots" required min="1" placeholder="Contoh: 50"
                                    class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring focus:ring-purple-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"
                                    value="{{ old('available_slots', $submission->available_slots) }}">
                            </div>

                            <!-- Harga -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Harga (Rp) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="price" required min="0" step="0.01" placeholder="Contoh: 350000"
                                    class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring focus:ring-purple-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"
                                    value="{{ old('price', $submission->price) }}">
                            </div>

                        </div>
                    </div>

                    <!-- Tools yang Digunakan (Dynamic) -->
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Tools yang Digunakan</h3>
                            <button type="button" @click="addTool()"
                                class="flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-purple-600 bg-purple-50 rounded-lg hover:bg-purple-100 dark:bg-purple-900 dark:text-purple-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                Tambah Tool
                            </button>
                        </div>
                        <div class="space-y-2">
                            <template x-for="(tool, index) in tools" :key="index">
                                <div class="flex gap-2">
                                    <input type="text" :name="'tools[' + index + ']'" x-model="tools[index]"
                                        placeholder="Contoh: VS Code"
                                        class="flex-1 px-4 py-2 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring focus:ring-purple-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                    <button type="button" @click="removeTool(index)"
                                        class="px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg dark:hover:bg-red-900">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </template>
                            <div x-show="tools.length === 0" class="text-sm text-gray-500 italic">
                                Belum ada tools. Klik "Tambah Tool" untuk menambahkan.
                            </div>
                        </div>
                    </div>

                    <!-- Kamu Akan Mendapatkan (Dynamic) -->
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Kamu Akan Mendapatkan</h3>
                            <button type="button" @click="addBenefit()"
                                class="flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-purple-600 bg-purple-50 rounded-lg hover:bg-purple-100 dark:bg-purple-900 dark:text-purple-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                Tambah
                            </button>
                        </div>
                        <div class="space-y-2">
                            <template x-for="(benefit, index) in benefits" :key="index">
                                <div class="flex gap-2">
                                    <input type="text" :name="'benefits[' + index + ']'" x-model="benefits[index]"
                                        placeholder="Contoh: Sertifikat"
                                        class="flex-1 px-4 py-2 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring focus:ring-purple-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                    <button type="button" @click="removeBenefit(index)"
                                        class="px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg dark:hover:bg-red-900">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </template>
                            <div x-show="benefits.length === 0" class="text-sm text-gray-500 italic">
                                Belum ada benefit. Klik "Tambah" untuk menambahkan.
                            </div>
                        </div>
                    </div>

                    <!-- Lokasi (Offline Only) -->
                    <div x-show="type === 'offline'" x-transition
                        class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Lokasi Pelaksanaan</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Provinsi <span x-show="type === 'offline'" class="text-red-500">*</span>
                                </label>
                                <input type="hidden" name="province" id="province_text" :required="type === 'offline'" value="{{ old('province', $submission->province) }}">
                                <select id="province" :required="type === 'offline'"
                                    onchange="document.getElementById('province_text').value = this.options[this.selectedIndex].textContent.trim()"
                                    class="block w-full px-4 py-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring focus:ring-purple-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                    <option value="">Pilih Provinsi</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Kabupaten/Kota <span x-show="type === 'offline'" class="text-red-500">*</span>
                                </label>
                                <input type="hidden" name="city" id="city_text" :required="type === 'offline'" value="{{ old('city', $submission->city) }}">
                                <select id="city" :required="type === 'offline'"
                                    onchange="document.getElementById('city_text').value = this.options[this.selectedIndex].textContent.trim()"
                                    class="block w-full px-4 py-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring focus:ring-purple-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                    <option value="">Pilih Kabupaten/Kota</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Kecamatan <span x-show="type === 'offline'" class="text-red-500">*</span>
                                </label>
                                <input type="hidden" name="district" id="district_text" :required="type === 'offline'" value="{{ old('district', $submission->district) }}">
                                <select id="district" :required="type === 'offline'"
                                    onchange="document.getElementById('district_text').value = this.options[this.selectedIndex].textContent.trim()"
                                    class="block w-full px-4 py-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring focus:ring-purple-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                    <option value="">Pilih Kecamatan</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Kelurahan/Desa <span x-show="type === 'offline'" class="text-red-500">*</span>
                                </label>
                                <input type="hidden" name="village" id="village_text" :required="type === 'offline'" value="{{ old('village', $submission->village) }}">
                                <select id="village" :required="type === 'offline'"
                                    onchange="document.getElementById('village_text').value = this.options[this.selectedIndex].textContent.trim()"
                                    class="block w-full px-4 py-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring focus:ring-purple-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
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
                                class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring focus:ring-purple-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">{{ old('full_address', $submission->full_address) }}</textarea>
                        </div>
                    </div>

                    <!-- Jadwal Pelaksanaan -->
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Jadwal Pelaksanaan</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Tanggal Mulai <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="start_date" required
                                    class="block w-full px-4 py-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring focus:ring-purple-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"
                                    value="{{ old('start_date', $submission->start_date) }}">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Waktu Mulai <span class="text-red-500">*</span>
                                </label>
                                <input type="time" name="start_time" required
                                    @change="updateAllMaterialDurations()"
                                    class="block w-full px-4 py-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring focus:ring-purple-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"
                                    value="{{ old('start_time', $submission->start_time) }}">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Tanggal Berakhir <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="end_date" required
                                    class="block w-full px-4 py-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring focus:ring-purple-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"
                                    value="{{ old('end_date', $submission->end_date) }}">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Waktu Berakhir <span class="text-red-500">*</span>
                                </label>
                                <input type="time" name="end_time" required
                                    @change="updateAllMaterialDurations()"
                                    class="block w-full px-4 py-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring focus:ring-purple-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"
                                    value="{{ old('end_time', $submission->end_time) }}">
                            </div>
                        </div>
                    </div>

                    <!-- Materi Pembelajaran (Dynamic) -->
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Materi Pembelajaran</h3>
                            <button type="button" @click="addMaterial()"
                                class="flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-purple-600 bg-purple-50 rounded-lg hover:bg-purple-100 dark:bg-purple-900 dark:text-purple-300">
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
                                        <span class="text-sm font-semibold text-purple-600 dark:text-purple-400"
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
                                                class="block w-full px-3 py-2 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring focus:ring-purple-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                        </div>
                                        <!-- Durasi -->
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Durasi <span class="text-gray-400">(otomatis terisi)</span></label>
                                            <div class="flex items-center gap-2">
                                                <input type="number" x-model="materials[index].duration_hours" min="0" max="23"
                                                    class="block w-16 px-3 py-2 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring focus:ring-purple-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"
                                                    placeholder="0">
                                                <span class="text-sm text-gray-600 dark:text-gray-400">Jam</span>
                                                <input type="number" x-model="materials[index].duration_minutes" min="0" max="59"
                                                    class="block w-16 px-3 py-2 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring focus:ring-purple-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"
                                                    placeholder="0">
                                                <span class="text-sm text-gray-600 dark:text-gray-400">Menit</span>
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
• Presentasi Strategi Digital" class="block w-full px-3 py-2 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring focus:ring-purple-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"></textarea>
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
                        
                        @if($submission->image)
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Gambar saat ini:</p>
                            @php
                                $submissionEditImageUrl = str_starts_with($submission->image, 'images/') 
                                    ? asset($submission->image) 
                                    : asset('storage/' . $submission->image);
                            @endphp
                            <img src="{{ $submissionEditImageUrl }}" alt="Current Image" class="max-w-xs rounded-lg shadow">
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
                                        <span class="font-semibold">Klik untuk upload gambar baru</span>
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

                </div>
            </form>
        </div>

    </div>

    @push('scripts')
        <script src="{{ asset('assets/elearning/region-selector.js') }}?v={{ time() }}"></script>
        <script>
            function programForm() {
                return {
                    type: '{{ old("type", $submission->type) }}' || '',
                    tools: {!! json_encode($submission->tools ?? []) !!},
                    materials: [],
                    benefits: {!! json_encode($submission->benefits ?? []) !!},

                    init() {
                        // Parse existing materials and convert duration to hours/minutes
                        const existingMaterials = {!! json_encode($submission->materials ?? []) !!};
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
