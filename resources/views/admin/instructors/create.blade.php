@extends('admin.layouts.app')

@section('title', 'Tambah Instruktur')

@section('content')

    <div class="container px-6 mx-auto">

        <!-- Page Header -->
        <div class="my-6">
            <div class="flex items-start justify-between">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Tambah Instruktur</h2>
                <div class="flex flex-col items-end" style="gap: 16px;">
                    <a href="{{ route('admin.instructors.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                    <button type="submit" 
                            form="instructorForm"
                            class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Simpan Instruktur</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="w-full mb-8 overflow-hidden rounded-lg shadow-md bg-white dark:bg-gray-800">
            <form id="instructorForm" action="{{ route('admin.instructors.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="px-6 py-6 space-y-6">

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="status">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select name="status" id="status" required
                                    class="block w-full px-4 py-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring focus:ring-purple-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-purple-300">
                                <option value="Approved" selected>Approved</option>
                                <option value="Pending">Pending</option>
                                <option value="Rejected">Rejected</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="name">
                            Nama <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" required
                               placeholder="Masukkan nama instruktur"
                               class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring focus:ring-purple-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-purple-300 dark:placeholder-gray-500">
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="email">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" id="email" required
                               placeholder="Masukkan email instruktur"
                               class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring focus:ring-purple-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-purple-300 dark:placeholder-gray-500">
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="phone">
                            Nomor Telepon <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" name="phone" id="phone" required
                               placeholder="Masukkan nomor telepon"
                               class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring focus:ring-purple-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-purple-300 dark:placeholder-gray-500">
                    </div>

                    <!-- Expertise -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="expertise">
                            Keahlian <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="expertise" id="expertise" required
                               placeholder="Masukkan keahlian instruktur"
                               class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring focus:ring-purple-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-purple-300 dark:placeholder-gray-500">
                    </div>

                    <!-- Job -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="job">
                            Pekerjaan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="job" id="job" required
                               placeholder="Contoh: Web Developer, UI/UX Designer, Digital Marketer"
                               class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring focus:ring-purple-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-purple-300 dark:placeholder-gray-500">
                    </div>

                    <!-- Experience -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="experience">
                            Pengalaman <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="experience" id="experience" required
                               placeholder="Masukkan pengalaman instruktur (contoh: 5 Tahun, 10 Tahun)"
                               class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring focus:ring-purple-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-purple-300 dark:placeholder-gray-500">
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="password">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password" id="password" required
                               placeholder="Masukkan password"
                               class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring focus:ring-purple-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-purple-300 dark:placeholder-gray-500">
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="password_confirmation">
                            Konfirmasi Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                               placeholder="Konfirmasi password"
                               class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring focus:ring-purple-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-purple-300 dark:placeholder-gray-500">
                    </div>

                    <!-- Photo Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Upload Foto
                        </label>
                        <div class="flex items-center justify-center w-full">
                            <label for="photo" 
                                   class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500"
                                   x-data="{ photoPreview: null }"
                                   @dragover.prevent
                                   @drop.prevent="
                                       let file = $event.dataTransfer.files[0];
                                       if (file && file.type.startsWith('image/')) {
                                           let reader = new FileReader();
                                           reader.onload = (e) => { photoPreview = e.target.result };
                                           reader.readAsDataURL(file);
                                           $refs.photoInput.files = $event.dataTransfer.files;
                                       }
                                   ">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6" x-show="!photoPreview">
                                    <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="font-semibold">Seret dan lepas berkas, atau</span> 
                                        <span class="text-purple-600 hover:text-purple-700 dark:text-purple-400">Telusuri</span>
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        Unggah berkas dalam bentuk: JPG, JPEG, PNG
                                    </p>
                                </div>
                                <div x-show="photoPreview" class="relative w-full h-full p-4">
                                    <img :src="photoPreview" alt="Preview" class="w-full h-full object-contain rounded-lg">
                                    <button type="button" 
                                            @click.stop.prevent="photoPreview = null; $refs.photoInput.value = ''"
                                            class="absolute top-6 right-6 p-1.5 bg-red-500 text-white rounded-full hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
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
                                               let reader = new FileReader();
                                               reader.onload = (e) => { photoPreview = e.target.result };
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

@push('scripts')
<script>
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

