@extends('admin.layouts.app')

@section('title', 'Tambah Admin')

@section('content')

    <div class="container px-6 mx-auto">

        <!-- Page Header -->
        <div class="my-6">
            <div class="flex items-start justify-between">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Tambah Admin</h2>

            </div>
        </div>

        <!-- Form Card -->
        <div class="w-full mb-8 p-6 overflow-hidden rounded-lg shadow-md bg-white dark:bg-gray-800">
            <form id="adminForm" action="{{ route('admin.admins.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-6">

                    <!-- Status -->
                    <div class="mb-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="status">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select name="status" id="status" required
                                    class="block w-full px-4 py-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-orange-300">
                                <option value="aktif" selected>Aktif</option>
                                <option value="non-aktif">Non-Aktif</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Name -->
                    <div class="mb-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="name">
                            Nama <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" required
                               placeholder="Masukkan nama"
                               class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-orange-300 dark:placeholder-gray-500">
                    </div>

                    <!-- Username -->
                    <div class="mb-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="username">
                            Username <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="username" id="username" required
                               placeholder="Masukkan username"
                               class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-orange-300 dark:placeholder-gray-500">
                    </div>

                    <!-- Email -->
                    <div class="mb-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="email">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" id="email" required
                               placeholder="Masukkan email"
                               class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-orange-300 dark:placeholder-gray-500">
                    </div>

                    <!-- Phone -->
                    <div class="mb-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="phone">
                            Nomor Telepon <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" name="phone" id="phone" required
                               placeholder="Masukkan nomor telepon"
                               class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-orange-300 dark:placeholder-gray-500">
                    </div>

                    <!-- Password -->
                    <div class="mb-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="password">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password" id="password" required minlength="8"
                               placeholder="Masukkan password"
                               class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-orange-300 dark:placeholder-gray-500">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Password minimal 8 karakter</p>
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="password_confirmation">
                            Konfirmasi Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                               placeholder="Konfirmasi password"
                               class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-orange-300 dark:placeholder-gray-500">
                    </div>

                    <!-- Photo Upload with Cropper -->
                    <div class="mb-2" x-data="imageUploader()">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Upload Foto
                        </label>
                        
                        <!-- Preview Area dengan kontrol -->
                        <div x-show="previewUrl" x-cloak class="mb-4">
                            <div class="border-2 border-gray-200 dark:border-gray-600 rounded-lg p-4 bg-gray-50 dark:bg-gray-700">
                                <div class="relative overflow-hidden rounded-lg bg-gray-100 dark:bg-gray-800 mb-3 flex items-center justify-center" 
                                     style="height: 200px;" x-ref="previewContainer" @wheel.prevent="handleZoom($event)">
                                    <img x-ref="previewImage" x-bind:src="previewUrl" alt="Preview" class="max-h-full cursor-move select-none"
                                         x-bind:style="'transform: scale(' + zoom + ') translate(' + posX + 'px, ' + posY + 'px);'"
                                         @mousedown.prevent="startDrag($event)" @mousemove="onDrag($event)" @mouseup="stopDrag()" @mouseleave="stopDrag()"
                                         @touchstart.prevent="startDrag($event.touches[0])" @touchmove="onDrag($event.touches[0])" @touchend="stopDrag()"
                                         @load="onImageLoad()" draggable="false">
                                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                        <div class="w-32 h-32 rounded-full border-4 border-white dark:border-gray-300 shadow-lg" style="box-shadow: 0 0 0 9999px rgba(0,0,0,0.5);"></div>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between gap-4">
                                    <div class="flex items-center gap-3 flex-1">
                                        <span class="text-sm text-gray-500">-</span>
                                        <input type="range" min="10" max="300" x-bind:value="Math.round(zoom * 100)" @input="setZoom($event.target.value / 100)"
                                               class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-600 accent-orange-500">
                                        <span class="text-sm text-gray-500">+</span>
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-200 min-w-[50px] text-center" x-text="Math.round(zoom * 100) + '%'"></span>
                                        <button type="button" @click="resetPosition()" class="p-2 rounded-lg bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 transition-colors" title="Reset">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                        </button>
                                    </div>
                                    <button type="button" @click="clearImage()" class="text-sm text-red-500 hover:text-red-700 font-medium">Hapus Foto</button>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">💡 Scroll untuk zoom, drag untuk geser.</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-center w-full" x-show="!previewUrl">
                            <label for="photo" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500"
                                   @dragover.prevent="isDragging = true" @dragleave.prevent="isDragging = false" @drop.prevent="handleDrop($event)"
                                   :class="{'border-orange-400 bg-orange-50 dark:bg-orange-900/20': isDragging}">
                                <div class="flex flex-col items-center justify-center pt-2 pb-3">
                                    <svg class="w-8 h-8 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    <p class="mb-1 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Seret dan lepas berkas, atau</span> <span class="text-orange-600">Telusuri</span></p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">JPG, JPEG, PNG (Maks. 2MB)</p>
                                </div>
                                <input id="photo" type="file" class="hidden" accept="image/jpeg,image/png,image/jpg" x-ref="photoInput" @change="handleFileSelect($event)">
                            </label>
                        </div>
                        <input type="hidden" name="cropped_photo" x-ref="croppedPhotoInput">
                        <canvas x-ref="cropCanvas" style="display: none;"></canvas>
                    </div>

                </div>

            </form>

            <div class="flex flex-row justify-end items-end mt-6" style="gap: 16px;">
                <a href="{{ route('admin.admins.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
                <button type="submit"
                        form="adminForm"
                        class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-orange-600 border border-transparent rounded-lg active:bg-orange-600 hover:bg-orange-700 focus:outline-none focus:shadow-outline-orange cursor-pointer">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan Admin
                </button>
            </div>
        </div>

    </div>

@push('scripts')
<script>
    // Form Validation
    document.getElementById('adminForm').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;
        const photoInput = document.getElementById('photo');
        
        // Password Validation
        if (password && password.length < 8) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                text: 'Password minimal harus 8 karakter!'
            });
            return;
        }

        // Confirm Password Validation
        if (password && password !== confirmPassword) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                text: 'Konfirmasi password tidak cocok!'
            });
            return;
        }

        // Photo Validation Removed (Handled by Alpine.js instant validation)
    });

    // Image Uploader Function
    function imageUploader() {
        return {
            previewUrl: null, zoom: 1, posX: 0, posY: 0, isDragging: false, isMoving: false, startX: 0, startY: 0, originalImage: null, circleSize: 128,
            handleFileSelect(event) { const file = event.target.files[0]; if (file) this.processFile(file); },
            handleDrop(event) { this.isDragging = false; const file = event.dataTransfer.files[0]; if (file) { const dt = new DataTransfer(); dt.items.add(file); this.$refs.photoInput.files = dt.files; this.processFile(file); } },
            processFile(file) {
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg']; const maxSize = 2 * 1024 * 1024;
                if (!allowedTypes.includes(file.type)) { Swal.fire({ icon: 'error', title: 'Upload Gagal', text: 'Format file tidak sesuai.' }); this.$refs.photoInput.value = ''; return; }
                if (file.size > maxSize) { Swal.fire({ icon: 'error', title: 'Upload Gagal', text: 'Ukuran file melebihi 2MB.' }); this.$refs.photoInput.value = ''; return; }
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
                try { ctx.drawImage(this.originalImage, srcX, srcY, Math.max(srcW, srcH), Math.max(srcW, srcH), 0, 0, outputSize, outputSize); } catch (e) {}
                this.$refs.croppedPhotoInput.value = canvas.toDataURL('image/png');
            }
        }
    }

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

