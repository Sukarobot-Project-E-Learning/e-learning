@extends('admin.layouts.app')

@section('title', 'Tambah Sertifikat')

@section('content')

    <div class="container px-6 mx-auto">

        <!-- Page Header -->
        <div class="my-6">
            <div class="flex items-start justify-between">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Tambah Sertifikat</h2>
                <div class="flex flex-col items-end" style="gap: 16px;">
                    <a href="{{ route('elearning.admin.certificates.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                    <button type="submit" 
                            form="certificateForm"
                            class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Tambah Sertifikat</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="w-full mb-8 overflow-hidden rounded-lg shadow-md bg-white dark:bg-gray-800">
            <form id="certificateForm" action="{{ route('elearning.admin.certificates.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="px-6 py-6 space-y-6">

                    <!-- Nama -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="name">
                            Nama <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" required
                               placeholder="Nama user"
                               class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring focus:ring-purple-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-purple-300 dark:placeholder-gray-500">
                    </div>

                    <!-- Judul -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="title">
                            Judul <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" id="title" required
                               placeholder="Workshop Branding"
                               class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring focus:ring-purple-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-purple-300 dark:placeholder-gray-500">
                    </div>

                    <!-- Blanko -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Blanko <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center justify-center w-full">
                            <label for="blanko" 
                                   class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500"
                                   x-data="{ imagePreview: null }"
                                   @dragover.prevent
                                   @drop.prevent="
                                       let file = $event.dataTransfer.files[0];
                                       if (file && file.type.startsWith('image/')) {
                                           let reader = new FileReader();
                                           reader.onload = (e) => { imagePreview = e.target.result };
                                           reader.readAsDataURL(file);
                                           $refs.blankoInput.files = $event.dataTransfer.files;
                                       }
                                   ">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6" x-show="!imagePreview">
                                    <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="font-semibold">Seret dan lepas berkas, atau</span> 
                                        <span class="text-purple-600 hover:text-purple-700 dark:text-purple-400">Telusuri</span>
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        Unggah berkas dalam bentuk JPG, JPEG, PNG.
                                    </p>
                                </div>
                                <div x-show="imagePreview" class="relative w-full h-full p-4">
                                    <img :src="imagePreview" alt="Preview" class="w-full h-full object-contain rounded-lg">
                                    <button type="button" 
                                            @click.stop.prevent="imagePreview = null; $refs.blankoInput.value = ''"
                                            class="absolute top-6 right-6 p-1.5 bg-red-500 text-white rounded-full hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                <input id="blanko" 
                                       name="blanko" 
                                       type="file" 
                                       class="hidden" 
                                       accept="image/jpeg,image/jpg,image/png"
                                       x-ref="blankoInput"
                                       required
                                       @change="
                                           let file = $event.target.files[0];
                                           if (file) {
                                               let reader = new FileReader();
                                               reader.onload = (e) => { imagePreview = e.target.result };
                                               reader.readAsDataURL(file);
                                           }
                                       ">
                            </label>
                        </div>
                    </div>

                </div>
            </form>
        </div>

    </div>

@endsection

