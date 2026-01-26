@extends('panel.layouts.app')

@section('title', 'Tambah User')

@section('content')

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-4 sm:py-8">
        <div class="container px-4 sm:px-6 mx-auto max-w-3xl">

            <!-- Form Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl shadow-gray-200/50 dark:shadow-none overflow-hidden"
                x-data="userForm()">
                <form id="userForm" action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Section 1: Account Information -->
                    <div class="p-5 sm:p-8 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-3 mb-6">
                            <div
                                class="flex items-center justify-center w-10 h-10 rounded-xl bg-orange-100 dark:bg-orange-900/30">
                                <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Informasi Akun</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Detail dasar akun pengguna</p>
                            </div>
                        </div>

                        <div class="space-y-5">
                            <!-- Status -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="status">
                                    Status <span class="text-orange-500">*</span>
                                </label>
                                <select name="status" id="status" required
                                    class="block w-full px-4 py-3.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:border-orange-500 focus:ring-0 transition-colors appearance-none bg-no-repeat bg-right"
                                    style="background-image: url('data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 fill=%27none%27 viewBox=%270 0 24 24%27 stroke=%27%23F97316%27%3E%3Cpath stroke-linecap=%27round%27 stroke-linejoin=%27round%27 stroke-width=%272%27 d=%27M19 9l-7 7-7-7%27/%3E%3C/svg%3E'); background-position: right 1rem center; background-size: 1.25rem;">
                                    <option value="Aktif" selected>✅ Aktif</option>
                                    <option value="Non-Aktif">❌ Non-Aktif</option>
                                </select>
                            </div>

                            <!-- Nama & Username Grid -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                        for="name">
                                        Nama Lengkap <span class="text-orange-500">*</span>
                                    </label>
                                    <input type="text" name="name" id="name" required value="{{ old('name') }}"
                                        placeholder="Masukkan nama lengkap"
                                        class="block w-full px-4 py-3.5 text-sm text-gray-900 dark:text-white placeholder-gray-400 bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:border-orange-500 focus:ring-0 transition-colors">
                                    @error('name')
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

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                        for="username">
                                        Username <span class="text-orange-500">*</span>
                                    </label>
                                    <input type="text" name="username" id="username" required value="{{ old('username') }}"
                                        placeholder="Masukkan username"
                                        class="block w-full px-4 py-3.5 text-sm text-gray-900 dark:text-white placeholder-gray-400 bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:border-orange-500 focus:ring-0 transition-colors">
                                    @error('username')
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
                            </div>

                            <!-- Email & Phone Grid -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                        for="email">
                                        Email <span class="text-orange-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                        <input type="email" name="email" id="email" required value="{{ old('email') }}"
                                            placeholder="user@example.com"
                                            class="block w-full pl-12 pr-4 py-3.5 text-sm text-gray-900 dark:text-white placeholder-gray-400 bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:border-orange-500 focus:ring-0 transition-colors">
                                    </div>
                                    @error('email')
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

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                        for="phone">
                                        Nomor Telepon <span class="text-orange-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                                </path>
                                            </svg>
                                        </div>
                                        <input type="tel" name="phone" id="phone" required value="{{ old('phone') }}"
                                            placeholder="08xxxxxxxxxx"
                                            class="block w-full pl-12 pr-4 py-3.5 text-sm text-gray-900 dark:text-white placeholder-gray-400 bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:border-orange-500 focus:ring-0 transition-colors">
                                    </div>
                                    @error('phone')
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
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Additional Info -->
                    <div class="p-5 sm:p-8 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-3 mb-6">
                            <div
                                class="flex items-center justify-center w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/30">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Informasi Tambahan</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Data opsional pengguna</p>
                            </div>
                        </div>

                        <div class="space-y-5">
                            <!-- Pekerjaan & Negara Grid -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                        for="job">
                                        Pekerjaan
                                    </label>
                                    <input type="text" name="job" id="job" value="{{ old('job') }}"
                                        placeholder="Masukkan pekerjaan"
                                        class="block w-full px-4 py-3.5 text-sm text-gray-900 dark:text-white placeholder-gray-400 bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:border-orange-500 focus:ring-0 transition-colors">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                        for="country">
                                        Negara
                                    </label>
                                    <input type="text" name="country" id="country" value="{{ old('country', 'Indonesia') }}"
                                        placeholder="Masukkan negara"
                                        class="block w-full px-4 py-3.5 text-sm text-gray-900 dark:text-white placeholder-gray-400 bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:border-orange-500 focus:ring-0 transition-colors">
                                </div>
                            </div>

                            <!-- Alamat -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                    for="address">
                                    Alamat
                                </label>
                                <textarea name="address" id="address" rows="3" placeholder="Masukkan alamat lengkap"
                                    class="block w-full px-4 py-3.5 text-sm text-gray-900 dark:text-white placeholder-gray-400 bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:border-orange-500 focus:ring-0 transition-colors resize-none">{{ old('address') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Security -->
                    <div class="p-5 sm:p-8 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-3 mb-6">
                            <div
                                class="flex items-center justify-center w-10 h-10 rounded-xl bg-red-100 dark:bg-red-900/30">
                                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Keamanan</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Password akun pengguna</p>
                            </div>
                        </div>

                        <div class="space-y-5">
                            <!-- Password Grid -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                        for="password">
                                        Password <span class="text-orange-500">*</span>
                                    </label>
                                    <input type="password" name="password" id="password" required minlength="8"
                                        x-model="password" @input="validate" placeholder="Minimal 8 karakter"
                                        class="block w-full px-4 py-3.5 text-sm text-gray-900 dark:text-white placeholder-gray-400 bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:border-orange-500 focus:ring-0 transition-colors">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1.5" x-show="!passwordError">
                                        Password minimal 8 karakter</p>
                                    <p x-show="passwordError" x-text="passwordError" class="text-red-500 text-xs mt-1.5">
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                        for="password_confirmation">
                                        Konfirmasi Password <span class="text-orange-500">*</span>
                                    </label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" required
                                        x-model="confirmPassword" @input="validate" placeholder="Ulangi password"
                                        class="block w-full px-4 py-3.5 text-sm text-gray-900 dark:text-white placeholder-gray-400 bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:border-orange-500 focus:ring-0 transition-colors">
                                    <p x-show="confirmError" x-text="confirmError" class="text-red-500 text-xs mt-1.5"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 4: Photo Upload -->
                    <div class="p-5 sm:p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div
                                class="flex items-center justify-center w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-900/30">
                                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Foto Profil</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Upload foto pengguna</p>
                            </div>
                        </div>

                        <!-- Photo Upload with Cropper -->
                        <div x-data="imageUploader()">
                            <!-- Preview Area dengan kontrol -->
                            <div x-show="previewUrl" x-cloak class="mb-4">
                                <div
                                    class="border-2 border-gray-200 dark:border-gray-600 rounded-xl p-4 bg-gray-50 dark:bg-gray-700">
                                    <div class="relative overflow-hidden rounded-xl bg-gray-100 dark:bg-gray-800 mb-3 flex items-center justify-center"
                                        style="height: 200px;" x-ref="previewContainer" @wheel.prevent="handleZoom($event)">
                                        <img x-ref="previewImage" x-bind:src="previewUrl" alt="Preview"
                                            class="max-h-full cursor-move select-none"
                                            x-bind:style="'transform: scale(' + zoom + ') translate(' + posX + 'px, ' + posY + 'px);'"
                                            @mousedown.prevent="startDrag($event)" @mousemove="onDrag($event)"
                                            @mouseup="stopDrag()" @mouseleave="stopDrag()"
                                            @touchstart.prevent="startDrag($event.touches[0])"
                                            @touchmove="onDrag($event.touches[0])" @touchend="stopDrag()"
                                            @load="onImageLoad()" draggable="false">
                                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                            <div class="w-32 h-32 rounded-full border-4 border-orange-400 dark:border-orange-300 shadow-lg"
                                                style="box-shadow: 0 0 0 9999px rgba(0,0,0,0.5);"></div>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between gap-4">
                                        <div class="flex items-center gap-3 flex-1">
                                            <span class="text-sm text-gray-500">-</span>
                                            <input type="range" min="10" max="300" x-bind:value="Math.round(zoom * 100)"
                                                @input="setZoom($event.target.value / 100)"
                                                class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-600 accent-orange-500">
                                            <span class="text-sm text-gray-500">+</span>
                                            <span
                                                class="text-sm font-medium text-gray-700 dark:text-gray-200 min-w-[50px] text-center"
                                                x-text="Math.round(zoom * 100) + '%'"></span>
                                            <button type="button" @click="resetPosition()"
                                                class="p-2 rounded-lg bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 transition-colors"
                                                title="Reset">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>
                                        <button type="button" @click="clearImage()"
                                            class="text-sm text-red-500 hover:text-red-700 font-medium">Hapus Foto</button>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">💡 Scroll untuk zoom, drag
                                        untuk geser.</p>
                                </div>
                            </div>

                            <div class="flex items-center justify-center w-full" x-show="!previewUrl">
                                <label for="photo"
                                    class="flex flex-col items-center justify-center w-full h-40 border-2 border-gray-200 border-dashed rounded-xl cursor-pointer bg-gray-50 dark:bg-gray-900 hover:bg-gray-100 dark:hover:bg-gray-800 dark:border-gray-700 dark:hover:border-gray-600 transition-colors"
                                    @dragover.prevent="isDragging = true" @dragleave.prevent="isDragging = false"
                                    @drop.prevent="handleDrop($event)"
                                    :class="{'border-orange-400 bg-orange-50 dark:bg-orange-900/20': isDragging}">
                                    <div class="flex flex-col items-center justify-center pt-2 pb-3">
                                        <svg class="w-10 h-10 mb-3 text-orange-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                            </path>
                                        </svg>
                                        <p class="mb-1 text-sm text-gray-500 dark:text-gray-400"><span
                                                class="font-semibold">Seret dan lepas berkas, atau</span> <span
                                                class="text-orange-600 font-medium">Telusuri</span></p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">JPG, JPEG, PNG (Maks. 2MB)</p>
                                    </div>
                                    <input id="photo" type="file" class="hidden" accept="image/jpeg,image/png,image/jpg"
                                        x-ref="photoInput" @change="handleFileSelect($event)">
                                </label>
                            </div>
                            <input type="hidden" name="cropped_photo" x-ref="croppedPhotoInput">
                            <canvas x-ref="cropCanvas" style="display: none;"></canvas>
                        </div>
                    </div>

                    <!-- Navigation Footer -->
                    <div
                        class="sticky bottom-0 px-5 sm:px-8 py-4 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between gap-4">
                            <a href="{{ route('admin.users.index') }}"
                                class="flex items-center gap-2 px-4 sm:px-5 py-2.5 sm:py-3 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 active:scale-95 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                <span class="hidden sm:inline">Kembali</span>
                            </a>

                            <button type="submit"
                                class="flex items-center gap-2 px-5 sm:px-6 py-2.5 sm:py-3 text-sm font-medium text-white bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl hover:from-orange-600 hover:to-orange-700 active:scale-95 transition-all shadow-lg shadow-orange-500/30">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Simpan User</span>
                            </button>
                        </div>
                    </div>

                </form>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            // User Form Alpine Component
            function userForm() {
                return {
                    password: '',
                    confirmPassword: '',
                    passwordError: '',
                    confirmError: '',

                    validate() {
                        this.passwordError = '';
                        this.confirmError = '';

                        if (this.password.length > 0 && this.password.length < 8) {
                            this.passwordError = 'Password minimal 8 karakter.';
                        }

                        if (this.confirmPassword && this.password !== this.confirmPassword) {
                            this.confirmError = 'Konfirmasi password tidak cocok.';
                        }
                    }
                }
            }

            // Image Uploader Function
            function imageUploader() {
                return {
                    previewUrl: null, zoom: 1, posX: 0, posY: 0, isDragging: false, isMoving: false, startX: 0, startY: 0, originalImage: null, circleSize: 128,
                    handleFileSelect(event) { const file = event.target.files[0]; if (file) this.processFile(file); },
                    handleDrop(event) { this.isDragging = false; const file = event.dataTransfer.files[0]; if (file) { const dt = new DataTransfer(); dt.items.add(file); this.$refs.photoInput.files = dt.files; this.processFile(file); } },
                    processFile(file) {
                        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg']; const maxSize = 2 * 1024 * 1024;
                        if (!allowedTypes.includes(file.type)) {
                            if (typeof SwalConfig !== 'undefined') { SwalConfig.errorToast('Upload Gagal', 'Format file tidak sesuai.'); }
                            else { Swal.fire({ icon: 'error', title: 'Upload Gagal', text: 'Format file tidak sesuai.' }); }
                            this.$refs.photoInput.value = ''; return;
                        }
                        if (file.size > maxSize) {
                            if (typeof SwalConfig !== 'undefined') { SwalConfig.errorToast('Upload Gagal', 'Ukuran file melebihi 2MB.'); }
                            else { Swal.fire({ icon: 'error', title: 'Upload Gagal', text: 'Ukuran file melebihi 2MB.' }); }
                            this.$refs.photoInput.value = ''; return;
                        }
                        const reader = new FileReader(); reader.onload = (e) => { this.previewUrl = e.target.result; this.resetPosition(); this.originalImage = new Image(); this.originalImage.src = e.target.result; }; reader.readAsDataURL(file);
                    },
                    onImageLoad() { this.$nextTick(() => this.updateCroppedImage()); },
                    setZoom(value) { this.zoom = Math.max(0.1, Math.min(3, value)); this.updateCroppedImage(); },
                    handleZoom(event) { if (event.deltaY < 0) this.zoom = Math.min(3, this.zoom + 0.05); else this.zoom = Math.max(0.1, this.zoom - 0.05); this.updateCroppedImage(); },
                    startDrag(event) { this.isMoving = true; this.startX = event.clientX - this.posX; this.startY = event.clientY - this.posY; },
                    onDrag(event) { if (!this.isMoving) return; this.posX = event.clientX - this.startX; this.posY = event.clientY - this.startY; },
                    stopDrag() { if (this.isMoving) { this.isMoving = false; this.updateCroppedImage(); } },
                    resetPosition() { this.zoom = 1; this.posX = 0; this.posY = 0; this.$nextTick(() => this.updateCroppedImage()); },
                    clearImage() { this.previewUrl = null; this.originalImage = null; this.$refs.photoInput.value = ''; this.$refs.croppedPhotoInput.value = ''; this.resetPosition(); },
                    updateCroppedImage() {
                        if (!this.originalImage || !this.previewUrl) return;
                        const canvas = this.$refs.cropCanvas; const ctx = canvas.getContext('2d'); const previewImg = this.$refs.previewImage; const container = this.$refs.previewContainer;
                        if (!previewImg || !container) return;
                        const outputSize = 256; canvas.width = outputSize; canvas.height = outputSize;
                        const natW = this.originalImage.width; const natH = this.originalImage.height; const dispW = previewImg.offsetWidth; const dispH = previewImg.offsetHeight;
                        const scaleX = natW / dispW; const scaleY = natH / dispH; const containerCenterX = container.offsetWidth / 2; const containerCenterY = container.offsetHeight / 2;
                        const translateOffsetX = this.posX * this.zoom; const translateOffsetY = this.posY * this.zoom;
                        const circleInDisplayedX = (containerCenterX - containerCenterX - translateOffsetX) / this.zoom + dispW / 2;
                        const circleInDisplayedY = (containerCenterY - containerCenterY - translateOffsetY) / this.zoom + dispH / 2;
                        const circleSizeInDisplayed = this.circleSize / this.zoom;
                        const cropDisplayedX = circleInDisplayedX - circleSizeInDisplayed / 2; const cropDisplayedY = circleInDisplayedY - circleSizeInDisplayed / 2;
                        const srcX = cropDisplayedX * scaleX; const srcY = cropDisplayedY * scaleY; const srcW = circleSizeInDisplayed * scaleX; const srcH = circleSizeInDisplayed * scaleY;
                        ctx.clearRect(0, 0, outputSize, outputSize); ctx.beginPath(); ctx.arc(outputSize / 2, outputSize / 2, outputSize / 2, 0, Math.PI * 2); ctx.closePath(); ctx.clip();
                        ctx.fillStyle = '#ffffff'; ctx.fillRect(0, 0, outputSize, outputSize);
                        try { ctx.drawImage(this.originalImage, srcX, srcY, Math.max(srcW, srcH), Math.max(srcW, srcH), 0, 0, outputSize, outputSize); } catch (e) { }
                        this.$refs.croppedPhotoInput.value = canvas.toDataURL('image/png');
                    }
                }
            }
        </script>
    @endpush

@endsection