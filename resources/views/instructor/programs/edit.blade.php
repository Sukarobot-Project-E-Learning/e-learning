@extends('panel.layouts.app')

@section('title', 'Edit Pengajuan Program')

@section('content')

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-4 sm:py-8">
        <div class="container px-4 sm:px-6 mx-auto max-w-4xl">

            <!-- Form Card with Progress Header -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl shadow-gray-200/50 dark:shadow-none overflow-hidden"
                x-data="programForm({
                    isAdmin: false,
                    data: {
                        title: {{ Js::from(old('title', $submission->title)) }},
                        category: {{ Js::from(old('category', $submission->category)) }},
                        type: {{ Js::from(old('type', $submission->type)) }},
                        description: {{ Js::from(old('description', $submission->description)) }},
                        quota: {{ Js::from(old('available_slots', $submission->available_slots)) }},
                        price: {{ Js::from(old('price', $submission->price)) }},
                        start_date: {{ Js::from(old('start_date', $submission->start_date)) }},
                        start_time: {{ Js::from(old('start_time', $submission->start_time)) }},
                        end_date: {{ Js::from(old('end_date', $submission->end_date)) }},
                        end_time: {{ Js::from(old('end_time', $submission->end_time)) }},
                        province: {{ Js::from(old('province', $submission->province)) }},
                        city: {{ Js::from(old('city', $submission->city)) }},
                        district: {{ Js::from(old('district', $submission->district)) }},
                        village: {{ Js::from(old('village', $submission->village)) }},
                        full_address: {{ Js::from(old('full_address', $submission->full_address)) }},
                        zoom_link: {{ Js::from(old('zoom_link', $submission->zoom_link)) }},
                        tools: {{ Js::from(old('tools', $submission->tools ?? [])) }},
                        materials: {{ Js::from(old('materials', $submission->materials ?? [])) }}.map(m => {
                            let h = 0, min = 0;
                            if (m.duration) {
                                const parts = String(m.duration).split(':');
                                h = parseInt(parts[0]) || 0;
                                min = parseInt(parts[1]) || 0;
                            }
                            return {
                                title: m.title || '',
                                duration_hours: h,
                                duration_minutes: min,
                                description: m.description || ''
                            };
                        }),
                        benefits: {{ Js::from(old('benefits', $submission->benefits ?? [])) }}
                    }
                })">
                <!-- Progress Header -->
                <div class="px-5 sm:px-8 pt-6 pb-4 border-b border-gray-200 dark:border-gray-700">

                    <!-- Step Indicators -->
                    <div class="flex items-center justify-center gap-2 sm:gap-3">
                        <template x-for="step in totalSteps" :key="step">
                            <div class="flex items-center">
                                <div class="flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 rounded-full text-xs sm:text-sm font-semibold transition-all duration-300"
                                    :class="currentStep >= step ? 'bg-blue-500 text-white shadow-lg shadow-blue-500/30' : 'bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400'">
                                    <span x-text="step"></span>
                                </div>
                                <div x-show="step < totalSteps"
                                    class="w-6 sm:w-12 h-1 mx-1 sm:mx-2 rounded-full transition-all duration-300"
                                    :class="currentStep > step ? 'bg-blue-500' : 'bg-gray-200 dark:bg-gray-700'">
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Step Labels (Hidden on Mobile) -->
                    <div class="hidden sm:flex justify-between mt-3 px-2 text-xs text-gray-500 dark:text-gray-400">
                        <span class="w-20 text-center"
                            :class="currentStep >= 1 ? 'text-blue-600 dark:text-blue-400 font-medium' : ''"></span>
                        <span class="w-20 text-center"
                            :class="currentStep >= 2 ? 'text-blue-600 dark:text-blue-400 font-medium' : ''"></span>
                        <span class="w-20 text-center"
                            :class="currentStep >= 3 ? 'text-blue-600 dark:text-blue-400 font-medium' : ''"></span>
                        <span class="w-20 text-center"
                            :class="currentStep >= 4 ? 'text-blue-600 dark:text-blue-400 font-medium' : ''"></span>
                        <span class="w-20 text-center"
                            :class="currentStep >= 5 ? 'text-blue-600 dark:text-blue-400 font-medium' : ''"></span>
                    </div>
                </div>
                <form id="programForm" action="{{ route('instructor.programs.update', $submission->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Step 1: Informasi Dasar -->
                    <div x-show="currentStep === 1" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-x-4"
                        x-transition:enter-end="opacity-100 translate-x-0">
                        <div class="p-5 sm:p-8">
                            <div class="flex items-center gap-3 mb-6">
                                <div
                                    class="flex items-center justify-center w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/30">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Informasi Dasar</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Isi detail dasar program Anda</p>
                                </div>
                            </div>

                            <div class="space-y-5">
                                <!-- Judul Program -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Judul Program <span class="text-orange-500">*</span>
                                    </label>
                                    <input type="text" name="title" required placeholder="Masukkan judul program"
                                        class="block w-full px-4 py-3.5 text-sm text-gray-900 dark:text-white placeholder-gray-400 bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:border-blue-500 focus:ring-0 transition-colors"
                                        value="{{ old('title', $submission->title) }}" x-model="title">
                                    <span class="text-red-500 text-sm mt-1" x-text="errors.title" x-show="errors.title"></span>
                                    @error('title')
                                        <p class="mt-2 text-sm text-red-500 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Kategori & Jenis Grid -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Kategori <span class="text-orange-500">*</span>
                                        </label>
                                        <select name="category" required x-model="category"
                                            class="block w-full px-4 py-3.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:border-blue-500 focus:ring-0 transition-colors appearance-none bg-no-repeat bg-right"
                                            style="background-image: url('data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 fill=%27none%27 viewBox=%270 0 24 24%27 stroke=%27%236B7280%27%3E%3Cpath stroke-linecap=%27round%27 stroke-linejoin=%27round%27 stroke-width=%272%27 d=%27M19 9l-7 7-7-7%27/%3E%3C/svg%3E'); background-position: right 1rem center; background-size: 1.25rem;">
                                            <option value="">Pilih Kategori</option>
                                            <option value="kursus" {{ old('category', $submission->category) == 'kursus' ? 'selected' : '' }}>Kursus
                                            </option>
                                            <option value="pelatihan" {{ old('category', $submission->category) == 'pelatihan' ? 'selected' : '' }}>
                                                Pelatihan</option>
                                            <option value="sertifikasi" {{ old('category', $submission->category) == 'sertifikasi' ? 'selected' : '' }}>Sertifikasi
                                            </option>
                                            <option value="outing-class" {{ old('category', $submission->category) == 'outing-class' ? 'selected' : '' }}>Outing Class
                                            </option>
                                            <option value="outboard" {{ old('category', $submission->category) == 'outboard' ? 'selected' : '' }}>
                                                Outboard</option>
                                        </select>
                                        <span class="text-red-500 text-sm mt-1" x-text="errors.category" x-show="errors.category"></span>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Jenis Pelaksanaan <span class="text-orange-500">*</span>
                                        </label>
                                        <select name="type" required x-model="type"
                                            class="block w-full px-4 py-3.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:border-blue-500 focus:ring-0 transition-colors appearance-none bg-no-repeat bg-right"
                                            style="background-image: url('data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 fill=%27none%27 viewBox=%270 0 24 24%27 stroke=%27%236B7280%27%3E%3Cpath stroke-linecap=%27round%27 stroke-linejoin=%27round%27 stroke-width=%272%27 d=%27M19 9l-7 7-7-7%27/%3E%3C/svg%3E'); background-position: right 1rem center; background-size: 1.25rem;">
                                            <option value="">Pilih Jenis</option>
                                            <option value="online" {{ old('type', $submission->type) == 'online' ? 'selected' : '' }}>🌐 Online
                                            </option>
                                            <option value="offline" {{ old('type', $submission->type) == 'offline' ? 'selected' : '' }}>📍
                                                Offline</option>
                                        </select>
                                        <span class="text-red-500 text-sm mt-1" x-text="errors.type" x-show="errors.type"></span>
                                    </div>
                                </div>

                                <!-- Deskripsi -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Deskripsi <span class="text-orange-500">*</span>
                                    </label>
                                    <textarea name="description" rows="4" required x-model="description"
                                        placeholder="Jelaskan tentang program Anda secara singkat..."
                                        class="block w-full px-4 py-3.5 text-sm text-gray-900 dark:text-white placeholder-gray-400 bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:border-blue-500 focus:ring-0 transition-colors resize-none">{{ old('description', $submission->description) }}</textarea>
                                    <span class="text-red-500 text-sm mt-1" x-text="errors.description" x-show="errors.description"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Detail Program -->
                    <div x-show="currentStep === 2" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-x-4"
                        x-transition:enter-end="opacity-100 translate-x-0">
                        <div class="p-5 sm:p-8">
                            <div class="flex items-center gap-3 mb-6">
                                <div
                                    class="flex items-center justify-center w-10 h-10 rounded-xl bg-orange-100 dark:bg-orange-900/30">
                                    <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Detail Program</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Kuota, harga, dan benefit</p>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <!-- Kuota & Harga -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Kuota Peserta <span class="text-orange-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <input type="number" name="available_slots" required min="1" placeholder="50" x-model="quota"
                                                class="block w-full pl-12 pr-4 py-3.5 text-sm text-gray-900 dark:text-white placeholder-gray-400 bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:border-blue-500 focus:ring-0 transition-colors"
                                                value="{{ old('available_slots', $submission->available_slots) }}">
                                        </div>
                                        <span class="text-red-500 text-sm mt-1" x-text="errors.quota" x-show="errors.quota"></span>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Harga <span class="text-orange-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                <span class="text-sm font-medium text-gray-500">Rp</span>
                                            </div>
                                            <input type="number" name="price" required min="0" step="0.01" x-model="price"
                                                placeholder="350000"
                                                class="block w-full pl-12 pr-4 py-3.5 text-sm text-gray-900 dark:text-white placeholder-gray-400 bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:border-blue-500 focus:ring-0 transition-colors"
                                                value="{{ old('price', $submission->price) }}">
                                        </div>
                                        <span class="text-red-500 text-sm mt-1" x-text="errors.price" x-show="errors.price"></span>
                                    </div>
                                </div>

                                <!-- Tools Section -->
                                <div class="bg-gray-50 dark:bg-gray-900 rounded-xl p-4 sm:p-5">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                                </path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            <span class="text-sm font-semibold text-gray-900 dark:text-white">Tools yang
                                                Digunakan</span>
                                        </div>
                                        <button type="button" @click="addTool()"
                                            class="flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-white bg-blue-500 rounded-lg hover:bg-blue-600 active:scale-95 transition-all shadow-lg shadow-blue-500/25">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            <span class="hidden sm:inline">Tambah</span>
                                        </button>
                                    </div>
                                    <div class="space-y-2">
                                        <template x-for="(tool, index) in tools" :key="index">
                                            <div class="flex gap-2 animate-fade-in">
                                                <input type="text" :name="'tools[' + index + ']'" x-model="tools[index]"
                                                    placeholder="Contoh: VS Code, Figma, Zoom..."
                                                    class="flex-1 px-4 py-3 text-sm text-gray-900 dark:text-white placeholder-gray-400 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:border-blue-500 focus:ring-0 transition-colors">
                                                <button type="button" @click="removeTool(index)"
                                                    class="flex items-center justify-center w-12 h-12 text-red-500 bg-red-50 dark:bg-red-900/20 rounded-xl hover:bg-red-100 dark:hover:bg-red-900/40 active:scale-95 transition-all">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </template>
                                        <div x-show="tools.length === 0" class="py-8 text-center">
                                            <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                                </path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            <p class="text-sm text-gray-400 dark:text-gray-500">Belum ada tools ditambahkan
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Benefits Section -->
                                <div class="bg-gray-50 dark:bg-gray-900 rounded-xl p-4 sm:p-5">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7">
                                                </path>
                                            </svg>
                                            <span class="text-sm font-semibold text-gray-900 dark:text-white">Kamu Akan
                                                Mendapatkan</span>
                                        </div>
                                        <button type="button" @click="addBenefit()"
                                            class="flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-white bg-orange-500 rounded-lg hover:bg-orange-600 active:scale-95 transition-all shadow-lg shadow-orange-500/25">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            <span class="hidden sm:inline">Tambah</span>
                                        </button>
                                    </div>
                                    <div class="space-y-2">
                                        <template x-for="(benefit, index) in benefits" :key="index">
                                            <div class="flex gap-2 animate-fade-in">
                                                <input type="text" :name="'benefits[' + index + ']'"
                                                    x-model="benefits[index]"
                                                    placeholder="Contoh: Sertifikat, E-book, Akses Selamanya..."
                                                    class="flex-1 px-4 py-3 text-sm text-gray-900 dark:text-white placeholder-gray-400 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:border-orange-500 focus:ring-0 transition-colors">
                                                <button type="button" @click="removeBenefit(index)"
                                                    class="flex items-center justify-center w-12 h-12 text-red-500 bg-red-50 dark:bg-red-900/20 rounded-xl hover:bg-red-100 dark:hover:bg-red-900/40 active:scale-95 transition-all">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </template>
                                        <div x-show="benefits.length === 0" class="py-8 text-center">
                                            <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7">
                                                </path>
                                            </svg>
                                            <p class="text-sm text-gray-400 dark:text-gray-500">Belum ada benefit
                                                ditambahkan</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Jadwal & Lokasi -->
                    <div x-show="currentStep === 3" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-x-4"
                        x-transition:enter-end="opacity-100 translate-x-0">
                        <div class="p-5 sm:p-8">
                            <div class="flex items-center gap-3 mb-6">
                                <div
                                    class="flex items-center justify-center w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/30">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Jadwal Pelaksanaan</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Tentukan waktu program</p>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <!-- Date & Time Grid -->
                                <div class="grid grid-cols-2 gap-3 sm:gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Tanggal Mulai <span class="text-orange-500">*</span>
                                        </label>
                                        <input type="date" name="start_date" required x-model="start_date"
                                            class="block w-full px-3 sm:px-4 py-3.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:border-blue-500 focus:ring-0 transition-colors">
                                        <span class="text-red-500 text-sm mt-1" x-text="errors.start_date" x-show="errors.start_date"></span>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Waktu Mulai <span class="text-orange-500">*</span>
                                        </label>
                                        <input type="time" name="start_time" required x-model="start_time"
                                            class="block w-full px-3 sm:px-4 py-3.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:border-blue-500 focus:ring-0 transition-colors">
                                        <span class="text-red-500 text-sm mt-1" x-text="errors.start_time" x-show="errors.start_time"></span>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Tanggal Berakhir <span class="text-orange-500">*</span>
                                        </label>
                                        <input type="date" name="end_date" required x-model="end_date"
                                            class="block w-full px-3 sm:px-4 py-3.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:border-blue-500 focus:ring-0 transition-colors">
                                        <span class="text-red-500 text-sm mt-1" x-text="errors.end_date" x-show="errors.end_date"></span>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Waktu Berakhir <span class="text-orange-500">*</span>
                                        </label>
                                        <input type="time" name="end_time" required x-model="end_time"
                                            class="block w-full px-3 sm:px-4 py-3.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:border-blue-500 focus:ring-0 transition-colors">
                                        <span class="text-red-500 text-sm mt-1" x-text="errors.end_time" x-show="errors.end_time"></span>
                                    </div>
                                </div>

                                <!-- Lokasi (Offline Only) -->
                                <div x-show="type === 'offline'" x-transition class="space-y-4">
                                    <div class="flex items-center gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                                        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <span class="text-sm font-semibold text-gray-900 dark:text-white">Lokasi
                                            Pelaksanaan</span>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Provinsi <span class="text-orange-500">*</span>
                                            </label>
                                            <input type="hidden" name="province" id="province_text"
                                                :required="type === 'offline'" x-model="province">
                                            <select id="province" :required="type === 'offline'"
                                                onchange="document.getElementById('province_text').value = this.options[this.selectedIndex].textContent.trim(); document.getElementById('province_text').dispatchEvent(new Event('input'))"
                                                class="block w-full px-4 py-3.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:border-blue-500 focus:ring-0 transition-colors appearance-none bg-no-repeat bg-right"
                                                style="background-image: url('data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 fill=%27none%27 viewBox=%270 0 24 24%27 stroke=%27%236B7280%27%3E%3Cpath stroke-linecap=%27round%27 stroke-linejoin=%27round%27 stroke-width=%272%27 d=%27M19 9l-7 7-7-7%27/%3E%3C/svg%3E'); background-position: right 1rem center; background-size: 1.25rem;">
                                                <option value="">Pilih Provinsi</option>
                                            </select>
                                            <span class="text-red-500 text-sm mt-1" x-text="errors.province" x-show="errors.province"></span>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Kabupaten/Kota <span class="text-orange-500">*</span>
                                            </label>
                                            <input type="hidden" name="city" id="city_text" :required="type === 'offline'" x-model="city">
                                            <select id="city" :required="type === 'offline'"
                                                onchange="document.getElementById('city_text').value = this.options[this.selectedIndex].textContent.trim(); document.getElementById('city_text').dispatchEvent(new Event('input'))"
                                                class="block w-full px-4 py-3.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:border-blue-500 focus:ring-0 transition-colors appearance-none bg-no-repeat bg-right"
                                                style="background-image: url('data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 fill=%27none%27 viewBox=%270 0 24 24%27 stroke=%27%236B7280%27%3E%3Cpath stroke-linecap=%27round%27 stroke-linejoin=%27round%27 stroke-width=%272%27 d=%27M19 9l-7 7-7-7%27/%3E%3C/svg%3E'); background-position: right 1rem center; background-size: 1.25rem;">
                                                <option value="">Pilih Kabupaten/Kota</option>
                                            </select>
                                            <span class="text-red-500 text-sm mt-1" x-text="errors.city" x-show="errors.city"></span>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Kecamatan <span class="text-orange-500">*</span>
                                            </label>
                                            <input type="hidden" name="district" id="district_text"
                                                :required="type === 'offline'" x-model="district">
                                            <select id="district" :required="type === 'offline'"
                                                onchange="document.getElementById('district_text').value = this.options[this.selectedIndex].textContent.trim(); document.getElementById('district_text').dispatchEvent(new Event('input'))"
                                                class="block w-full px-4 py-3.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:border-blue-500 focus:ring-0 transition-colors appearance-none bg-no-repeat bg-right"
                                                style="background-image: url('data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 fill=%27none%27 viewBox=%270 0 24 24%27 stroke=%27%236B7280%27%3E%3Cpath stroke-linecap=%27round%27 stroke-linejoin=%27round%27 stroke-width=%272%27 d=%27M19 9l-7 7-7-7%27/%3E%3C/svg%3E'); background-position: right 1rem center; background-size: 1.25rem;">
                                                <option value="">Pilih Kecamatan</option>
                                            </select>
                                            <span class="text-red-500 text-sm mt-1" x-text="errors.district" x-show="errors.district"></span>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Kelurahan/Desa <span class="text-orange-500">*</span>
                                            </label>
                                            <input type="hidden" name="village" id="village_text"
                                                :required="type === 'offline'" x-model="village">
                                            <select id="village" :required="type === 'offline'"
                                                onchange="document.getElementById('village_text').value = this.options[this.selectedIndex].textContent.trim(); document.getElementById('village_text').dispatchEvent(new Event('input'))"
                                                class="block w-full px-4 py-3.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:border-blue-500 focus:ring-0 transition-colors appearance-none bg-no-repeat bg-right"
                                                style="background-image: url('data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 fill=%27none%27 viewBox=%270 0 24 24%27 stroke=%27%236B7280%27%3E%3Cpath stroke-linecap=%27round%27 stroke-linejoin=%27round%27 stroke-width=%272%27 d=%27M19 9l-7 7-7-7%27/%3E%3C/svg%3E'); background-position: right 1rem center; background-size: 1.25rem;">
                                                <option value="">Pilih Kelurahan/Desa</option>
                                            </select>
                                            <span class="text-red-500 text-sm mt-1" x-text="errors.village" x-show="errors.village"></span>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Alamat Lengkap <span class="text-orange-500">*</span>
                                        </label>
                                        <textarea name="full_address" rows="3" :required="type === 'offline'"
                                            placeholder="Masukkan alamat lengkap tempat pelaksanaan..." x-model="full_address"
                                            class="block w-full px-4 py-3.5 text-sm text-gray-900 dark:text-white placeholder-gray-400 bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:border-blue-500 focus:ring-0 transition-colors resize-none">{{ old('full_address', $submission->full_address) }}</textarea>
                                        <span class="text-red-500 text-sm mt-1" x-text="errors.full_address" x-show="errors.full_address"></span>
                                    </div>
                                </div>

                                <!-- Link Zoom (Online Only) -->
                                <div x-show="type === 'online'" x-transition class="space-y-4">
                                    <div class="flex items-center gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                                        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <span class="text-sm font-semibold text-gray-900 dark:text-white">Link Meeting
                                            Online</span>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Link Zoom/Google Meet <span class="text-orange-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1">
                                                    </path>
                                                </svg>
                                            </div>
                                            <input type="url" name="zoom_link" :required="type === 'online'" x-model="zoom_link"
                                                placeholder="https://zoom.us/j/xxxxxxxxxx atau https://meet.google.com/xxx-xxxx-xxx"
                                                class="block w-full pl-12 pr-4 py-3.5 text-sm text-gray-900 dark:text-white placeholder-gray-400 bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:border-blue-500 focus:ring-0 transition-colors"
                                                value="{{ old('zoom_link', $submission->zoom_link) }}">
                                        </div>
                                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                            💡 Masukkan link Zoom, Google Meet, atau platform meeting online lainnya
                                        </p>
                                        <span class="text-red-500 text-sm mt-1" x-text="errors.zoom_link" x-show="errors.zoom_link"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4: Materi Pembelajaran -->
                    <div x-show="currentStep === 4" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-x-4"
                        x-transition:enter-end="opacity-100 translate-x-0">
                        <div class="p-5 sm:p-8">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex items-center justify-center w-10 h-10 rounded-xl bg-orange-100 dark:bg-orange-900/30">
                                        <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Materi Pembelajaran
                                        </h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Susun materi per hari</p>
                                    </div>
                                </div>
                                <button type="button" @click="addMaterial()"
                                    class="flex items-center gap-1.5 px-4 py-2.5 text-sm font-medium text-white bg-orange-500 rounded-xl hover:bg-orange-600 active:scale-95 transition-all shadow-lg shadow-orange-500/25">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    <span class="hidden sm:inline">Tambah Hari</span>
                                </button>
                            </div>

                            <div class="space-y-4">
                                <template x-for="(material, index) in materials" :key="index">
                                    <div
                                        class="bg-gray-50 dark:bg-gray-900 rounded-xl overflow-hidden border-2 border-gray-200 dark:border-gray-700 animate-fade-in">
                                        <!-- Material Header -->
                                        <div
                                            class="flex items-center justify-between px-4 py-3 bg-gradient-to-r from-blue-500 to-blue-600 dark:from-blue-600 dark:to-blue-700">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="flex items-center justify-center w-8 h-8 bg-white/20 rounded-lg">
                                                    <span class="text-sm font-bold text-white" x-text="index + 1"></span>
                                                </div>
                                                <span class="text-sm font-semibold text-white"
                                                    x-text="'Hari ' + (index + 1)"></span>
                                            </div>
                                            <button type="button" @click="removeMaterial(index)"
                                                class="flex items-center justify-center w-8 h-8 text-white/80 hover:text-white hover:bg-white/20 rounded-lg transition-all">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>

                                        <!-- Material Content -->
                                        <div class="p-4 space-y-4">
                                            <div>
                                                <label
                                                    class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Judul
                                                    Materi</label>
                                                <input type="text" :name="'materials[' + index + '][title]'"
                                                    x-model="materials[index].title"
                                                    placeholder="Contoh: Hari 1: Analisis SWOT"
                                                    class="block w-full px-4 py-3 text-sm text-gray-900 dark:text-white placeholder-gray-400 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:border-blue-500 focus:ring-0 transition-colors">
                                            </div>

                                            <div>
                                                <label
                                                    class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                                                    Durasi <span class="text-gray-400 normal-case">(otomatis dari
                                                        jadwal)</span>
                                                </label>
                                                <div class="flex items-center gap-2">
                                                    <div
                                                        class="flex items-center gap-2 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 rounded-xl px-3 py-2">
                                                        <input type="number" x-model="materials[index].duration_hours"
                                                            min="0" max="23"
                                                            class="w-12 text-center text-sm text-gray-900 dark:text-white bg-transparent border-0 focus:ring-0 p-0"
                                                            placeholder="0">
                                                        <span class="text-xs text-gray-500">jam</span>
                                                    </div>
                                                    <div
                                                        class="flex items-center gap-2 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 rounded-xl px-3 py-2">
                                                        <input type="number" x-model="materials[index].duration_minutes"
                                                            min="0" max="59"
                                                            class="w-12 text-center text-sm text-gray-900 dark:text-white bg-transparent border-0 focus:ring-0 p-0"
                                                            placeholder="0">
                                                        <span class="text-xs text-gray-500">menit</span>
                                                    </div>
                                                    <input type="hidden" :name="'materials[' + index + '][duration]'"
                                                        :value="(materials[index].duration_hours || 0) + ':' + (materials[index].duration_minutes || 0)">
                                                </div>
                                            </div>

                                            <div>
                                                <label
                                                    class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Detail
                                                    Aktivitas</label>
                                                <textarea :name="'materials[' + index + '][description]'"
                                                    x-model="materials[index].description" rows="4"
                                                    placeholder="Contoh:&#10;• Pembukaan & Pengenalan Materi&#10;• Presentasi Strategi Digital&#10;• Praktik Pembuatan Kampanye&#10;• Evaluasi & Tanya Jawab"
                                                    class="block w-full px-4 py-3 text-sm text-gray-900 dark:text-white placeholder-gray-400 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:border-blue-500 focus:ring-0 transition-colors resize-none"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <div x-show="materials.length === 0"
                                    class="py-12 text-center bg-gray-50 dark:bg-gray-900 rounded-xl border-2 border-dashed border-gray-200 dark:border-gray-700">
                                    <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                        </path>
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400 mb-2">Belum ada materi pembelajaran</p>
                                    <p class="text-sm text-gray-400 dark:text-gray-500">Klik "Tambah Hari" untuk mulai
                                        menyusun materi</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 5: Gambar Program -->
                    <div x-show="currentStep === 5" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-x-4"
                        x-transition:enter-end="opacity-100 translate-x-0">
                        <div class="p-5 sm:p-8">
                            <div class="flex items-center gap-3 mb-6">
                                <div
                                    class="flex items-center justify-center w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/30">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Gambar Program</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Upload gambar thumbnail</p>
                                </div>
                            </div>

                            <div
                                x-data="{ imagePreview: '{{ $submission->image ? asset('storage/' . $submission->image) : '' }}' }">
                                <label for="image"
                                    class="relative flex flex-col items-center justify-center w-full h-64 sm:h-80 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-2xl cursor-pointer bg-gray-50 dark:bg-gray-900 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors overflow-hidden group">

                                    <!-- Upload State -->
                                    <div class="flex flex-col items-center justify-center py-6" x-show="!imagePreview">
                                        <div
                                            class="flex items-center justify-center w-16 h-16 mb-4 bg-blue-100 dark:bg-blue-900/30 rounded-2xl group-hover:scale-110 transition-transform">
                                            <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                                </path>
                                            </svg>
                                        </div>
                                        <p class="mb-2 text-sm text-gray-600 dark:text-gray-400">
                                            <span class="font-semibold text-blue-500">Klik untuk upload</span> atau drag &
                                            drop
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-500">PNG, JPG, JPEG (MAX. 2MB)</p>
                                    </div>

                                    <!-- Preview State -->
                                    <div x-show="imagePreview" class="absolute inset-0 p-4">
                                        <img :src="imagePreview" alt="Preview"
                                            class="w-full h-full object-contain rounded-xl">
                                    </div>

                                    <!-- Remove Button -->
                                    <button type="button" x-show="imagePreview"
                                        @click.stop.prevent="imagePreview = null; $refs.imageInput.value = ''"
                                        class="absolute top-6 right-6 flex items-center gap-2 px-3 py-2 bg-red-500 text-white text-sm font-medium rounded-lg hover:bg-red-600 shadow-lg transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Hapus
                                    </button>

                                    <input id="image" name="image" type="file" class="hidden" accept="image/*"
                                        x-ref="imageInput"
                                        @change="validateImage($event); let file = $event.target.files[0]; if (file) { let reader = new FileReader(); reader.onload = (e) => { imagePreview = e.target.result }; reader.readAsDataURL(file); }">
                                </label>
                                <span class="text-red-500 text-sm mt-1" x-text="errors.image" x-show="errors.image"></span>
                            </div>

                            <!-- Summary Preview -->
                            <div
                                class="mt-8 p-5 bg-gradient-to-br from-blue-50 to-orange-50 dark:from-blue-900/20 dark:to-orange-900/20 rounded-2xl border border-blue-100 dark:border-blue-800">
                                <div class="flex items-center gap-2 mb-4">
                                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white">Siap untuk
                                        disimpan!</span>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    Program Anda akan diperbarui dan dikirim ulang untuk direview oleh admin.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Footer -->
                    <div
                        class="sticky bottom-0 px-5 sm:px-8 py-4 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between gap-4">
                            <!-- Back Button -->
                            <button type="button" x-show="currentStep > 1" @click="prevStep()"
                                class="flex items-center gap-2 px-4 sm:px-5 py-2.5 sm:py-3 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 active:scale-95 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7"></path>
                                </svg>
                                <span class="hidden sm:inline">Kembali</span>
                            </button>

                            <!-- Cancel Link (Step 1 only) -->
                            <a href="{{ route('instructor.programs.index') }}" x-show="currentStep === 1"
                                class="flex items-center gap-2 px-4 sm:px-5 py-2.5 sm:py-3 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                <span class="hidden sm:inline">Batal</span>
                            </a>

                            <div class="flex-1"></div>

                            <!-- Next/Submit Button -->
                            <button type="button" x-show="currentStep < 5" @click="nextStep()"
                                class="flex items-center gap-2 px-5 sm:px-6 py-2.5 sm:py-3 text-sm font-medium text-white bg-blue-500 rounded-xl hover:bg-blue-600 active:scale-95 transition-all shadow-lg shadow-blue-500/30">
                                <span>Lanjut</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                    </path>
                                </svg>
                            </button>

                            <button type="submit" x-show="currentStep === 5"
                                class="flex items-center gap-2 px-5 sm:px-6 py-2.5 sm:py-3 text-sm font-medium text-white bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl hover:from-orange-600 hover:to-orange-700 active:scale-95 transition-all shadow-lg shadow-orange-500/30">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Simpan & Ajukan Ulang</span>
                            </button>
                        </div>
                    </div>

                </form>
            </div>

        </div>
    </div>

    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }
    </style>

    @push('scripts')
        <script src="{{ asset('assets/elearning/region-selector.js') }}?v={{ time() }}"></script>
        <script src="{{ asset('assets/elearning/js/program-form.js') }}?v={{ time() }}"></script>

        <!-- Inline SweetAlert Validation Scripts -->
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Session flash message handling
                @if(session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: '{{ session('success') }}',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    });
                @endif

                @if(session('error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: '{{ session('error') }}',
                        confirmButtonColor: '#3b82f6'
                    });
                @endif

                @if($errors->any())
                    Swal.fire({
                        icon: 'error',
                        title: 'Validasi Gagal',
                        html: '<ul style="text-align: left; padding-left: 20px;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                        confirmButtonColor: '#3b82f6'
                    });
                @endif
            });
        </script>
    @endpush

@endsection