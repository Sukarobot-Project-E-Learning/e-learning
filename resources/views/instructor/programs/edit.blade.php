@extends('instructor.layouts.app')

@section('title', 'Edit Program')

@section('content')
    <div class="container px-6 mx-auto">
        <!-- Page Header -->
        <div class="my-6">
            <div class="flex items-start justify-between">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Edit Program</h2>
                <div class="flex flex-col items-end" style="gap: 16px;">
                    <a href="{{ route('instructor.programs.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                    <button type="submit" 
                            form="programForm"
                            class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="w-full mb-8 overflow-hidden rounded-lg shadow-md bg-white dark:bg-gray-800">
            <form id="programForm" action="{{ route('instructor.programs.update', $id ?? 1) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="px-6 py-6 space-y-6">
                    <!-- Kategori -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="category">
                            Kategori <span class="text-red-500">*</span>
                        </label>
                        <select name="category" id="category" required
                                class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring focus:ring-purple-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-purple-300">
                            <option value="">Pilih Kategori</option>
                            <option value="pelatihan">Pelatihan</option>
                            <option value="sertifikasi">Sertifikasi</option>
                            <option value="training">Training</option>
                            <option value="event">Event</option>
                        </select>
                    </div>

                    <!-- Judul -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="title">
                            Judul Program <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" id="title" required
                               placeholder="Masukkan judul program"
                               class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring focus:ring-purple-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-purple-300 dark:placeholder-gray-500">
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="description">
                            Deskripsi <span class="text-red-500">*</span>
                        </label>
                        <textarea name="description" id="description" rows="4" required
                                  placeholder="Masukkan deskripsi program"
                                  class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring focus:ring-purple-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-purple-300 dark:placeholder-gray-500"></textarea>
                    </div>

                    <!-- Harga -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="price">
                            Harga <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="price" id="price" required
                               placeholder="Masukkan harga program"
                               class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring focus:ring-purple-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-purple-300 dark:placeholder-gray-500">
                    </div>

                    <!-- Jenis -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="type">
                            Jenis <span class="text-red-500">*</span>
                        </label>
                        <select name="type" id="type" required
                                class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring focus:ring-purple-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-purple-300">
                            <option value="">Pilih Jenis</option>
                            <option value="offline">Offline</option>
                            <option value="online">Online</option>
                            <option value="video">Video</option>
                        </select>
                    </div>

                    <!-- Tanggal Mulai -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="start_date">
                            Tanggal Mulai <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="start_date" id="start_date" required
                               class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring focus:ring-purple-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-purple-300">
                    </div>

                    <!-- Lokasi Section -->
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Lokasi</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Provinsi -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="province">
                                    Provinsi
                                </label>
                                <select name="province" id="province"
                                        class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring focus:ring-purple-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-purple-300">
                                    <option value="">Pilih Provinsi</option>
                                </select>
                            </div>

                            <!-- Kabupaten/Kota -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="city">
                                    Kabupaten/Kota
                                </label>
                                <select name="city" id="city"
                                        class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring focus:ring-purple-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-purple-300">
                                    <option value="">Pilih Kabupaten/Kota</option>
                                </select>
                            </div>

                            <!-- Kecamatan -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="district">
                                    Kecamatan
                                </label>
                                <select name="district" id="district"
                                        class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring focus:ring-purple-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-purple-300">
                                    <option value="">Pilih Kecamatan</option>
                                </select>
                            </div>

                            <!-- Kelurahan/Desa -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="village">
                                    Kelurahan/Desa
                                </label>
                                <select name="village" id="village"
                                        class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring focus:ring-purple-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-purple-300">
                                    <option value="">Pilih Kelurahan/Desa</option>
                                </select>
                            </div>
                        </div>

                        <!-- List Alamat Lengkap -->
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="full_address">
                                Alamat Lengkap
                            </label>
                            <textarea name="full_address" id="full_address" rows="3"
                                      placeholder="Masukkan alamat lengkap"
                                      class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring focus:ring-purple-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-purple-300 dark:placeholder-gray-500"></textarea>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@push('scripts')
<script src="{{ asset('assets/elearning/region-selector.js') }}"></script>
@endpush

@endsection

