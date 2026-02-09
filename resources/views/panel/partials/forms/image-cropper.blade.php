{{--
    Image Cropper Component
    
    Props:
    - name: string (required) - Input name for cropped image data
    - label: string - Label text
    - currentImage: string - URL of current image (for edit forms)
    - currentImageAlt: string - Alt text for current image
    - maxSize: int - Max file size in MB (default: 2)
    - acceptTypes: string - Accepted file types (default: image/jpeg,image/png,image/jpg)
    - id: string - Custom ID (defaults to name)
--}}

@php
    $inputId = $id ?? $name;
    $maxSizeMB = $maxSize ?? 2;
    $accept = $acceptTypes ?? 'image/jpeg,image/png,image/jpg';
@endphp

<div class="form-group">
    @if(!empty($label))
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            {{ $label }}
        </label>
    @endif

    <div class="image-cropper-wrapper" data-image-cropper data-max-size="{{ $maxSizeMB }}">
        <!-- Google Image URL Input -->
        <div class="mb-4 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl" data-google-url-section>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Atau gunakan URL gambar dari Google/eksternal
            </label>
            <input type="url" 
                   placeholder="Contoh: https://lh3.googleusercontent.com/a/..." 
                   data-google-url-input
                   class="w-full px-4 py-2.5 text-sm text-gray-900 dark:text-white placeholder-gray-400 bg-white dark:bg-gray-800 border-2 border-blue-200 dark:border-blue-700 rounded-lg focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/30 transition-colors">
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1.5">💡 Paste link gambar Google/eksternal</p>
        </div>

        <!-- Preview Area with controls -->
        <div class="image-cropper-preview hidden mb-4" data-cropper-preview-wrapper>
            <div class="border-2 border-gray-200 dark:border-gray-600 rounded-xl p-4 bg-gray-50 dark:bg-gray-700">
                <div class="image-cropper-container relative overflow-hidden rounded-xl bg-gray-100 dark:bg-gray-800 mb-3 flex items-center justify-center"
                    style="height: 200px;" data-cropper-container>
                    <img data-cropper-preview-image alt="Preview" class="max-h-full cursor-move select-none" draggable="false">
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                        <div class="w-32 h-32 rounded-full border-4 border-orange-400 dark:border-orange-300 shadow-lg"
                            style="box-shadow: 0 0 0 9999px rgba(0,0,0,0.5);"></div>
                    </div>
                </div>
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3 flex-1">
                        <span class="text-sm text-gray-500">-</span>
                        <input type="range" min="10" max="300" value="100" data-cropper-zoom-slider
                            class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-600 accent-orange-500">
                        <span class="text-sm text-gray-500">+</span>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-200 min-w-[50px] text-center" data-cropper-zoom-label>100%</span>
                        <button type="button" data-cropper-reset-btn
                            class="p-2 rounded-lg bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 transition-colors"
                            title="Reset">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                </path>
                            </svg>
                        </button>
                    </div>
                    <button type="button" data-cropper-clear-btn
                        class="text-sm text-red-500 hover:text-red-700 font-medium">Hapus Foto</button>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">💡 Scroll untuk zoom, drag untuk geser.</p>
            </div>
        </div>

        <!-- Current Image (for edit forms) -->
        @if(!empty($currentImage))
            <div class="mb-4 flex items-center gap-4" data-current-image-wrapper>
                <div class="relative">
                    <img src="{{ $currentImage }}" alt="{{ $currentImageAlt ?? 'Current photo' }}" 
                        class="w-24 h-24 rounded-lg object-cover border-2 border-gray-200 dark:border-gray-600">
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    <p class="font-medium text-gray-700 dark:text-gray-300">Foto saat ini</p>
                    <p>Pilih file baru untuk mengganti foto</p>
                </div>
            </div>
        @endif

        <!-- Drop Zone -->
        <div class="flex items-center justify-center w-full" data-cropper-dropzone-wrapper>
            <label for="{{ $inputId }}"
                class="image-cropper-dropzone flex flex-col items-center justify-center w-full h-40 border-2 border-gray-200 border-dashed rounded-xl cursor-pointer bg-gray-50 dark:bg-gray-900 hover:bg-gray-100 dark:hover:bg-gray-800 dark:border-gray-700 dark:hover:border-gray-600 transition-colors"
                data-cropper-dropzone>
                <div class="flex flex-col items-center justify-center pt-2 pb-3">
                    <svg class="w-10 h-10 mb-3 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                        </path>
                    </svg>
                    <p class="mb-1 text-sm text-gray-500 dark:text-gray-400">
                        <span class="font-semibold">Seret dan lepas berkas, atau</span>
                        <span class="text-orange-600 font-medium">Telusuri</span>
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">JPG, JPEG, PNG (Maks. {{ $maxSizeMB }}MB)</p>
                </div>
                <input id="{{ $inputId }}" type="file" class="hidden" accept="{{ $accept }}" data-cropper-file-input>
            </label>
        </div>

        <input type="hidden" name="{{ $name }}" data-cropper-output>
        <canvas data-cropper-canvas style="display: none;"></canvas>
    </div>
</div>
