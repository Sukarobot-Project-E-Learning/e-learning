{{--
    Simple Image Upload Component (No Cropping)
    For article featured images, etc.
    
    Props:
    - name: string (required) - Input name attribute
    - label: string - Label text
    - currentImage: string - URL of current image (for edit forms)
    - required: bool - Whether field is required
    - maxSize: int - Max file size in MB (default: 5)
    - acceptTypes: string - Accepted file types
    - id: string - Custom ID (defaults to name)
    - aspectHint: string - Aspect ratio hint (e.g., "800 x 600 pixel")
--}}

@php
    $inputId = $id ?? $name;
    $maxSizeMB = $maxSize ?? 5;
    $accept = $acceptTypes ?? 'image/jpeg,image/png,image/jpg';
    $isRequired = $required ?? false;
    $hint = $aspectHint ?? '800 x 600 pixel atau 1200 x 675 pixel';
@endphp

<div class="form-group">
    @if(!empty($label))
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            {{ $label }}
            @if($isRequired)
                <span class="text-orange-500">*</span>
            @endif
        </label>
    @endif

    <div class="image-upload-simple" data-simple-image-upload data-max-size="{{ $maxSizeMB }}">
        <!-- Upload/Preview Container -->
        <div class="relative w-full min-h-48 border-2 border-gray-200 border-dashed rounded-xl bg-gray-50 dark:bg-gray-900 dark:border-gray-700 overflow-hidden transition-colors hover:border-orange-400 dark:hover:border-orange-500"
             data-upload-container>
            
            <!-- Upload State -->
            <label for="{{ $inputId }}" 
                   class="upload-state flex flex-col items-center justify-center w-full h-48 cursor-pointer {{ !empty($currentImage) ? 'hidden' : '' }}"
                   data-upload-state>
                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                    <svg class="w-10 h-10 mb-3 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                        </path>
                    </svg>
                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                        <span class="font-semibold">Seret dan lepas berkas, atau</span>
                        <span class="text-orange-600 font-medium">Telusuri</span>
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Format: JPG, JPEG, PNG · Maks {{ $maxSizeMB }}MB
                    </p>
                    @if($hint)
                        <p class="text-xs text-orange-600 dark:text-orange-400 font-semibold mt-1">
                            📐 Rekomendasi: {{ $hint }}
                        </p>
                    @endif
                </div>
            </label>

            <!-- Preview State -->
            <div class="preview-state relative w-full h-48 {{ empty($currentImage) ? 'hidden' : '' }}" data-preview-state>
                <img src="{{ $currentImage ?? '' }}" 
                     alt="Preview" 
                     class="w-full h-full object-contain rounded-lg p-2"
                     data-preview-image>
                <button type="button" 
                        class="absolute top-3 right-3 p-1.5 bg-red-500 text-white rounded-full hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 shadow-lg transition-colors"
                        data-clear-button>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <input id="{{ $inputId }}" 
                   name="{{ $name }}" 
                   type="file" 
                   class="hidden" 
                   accept="{{ $accept }}"
                   @if($isRequired && empty($currentImage)) required @endif
                   data-file-input>
        </div>
    </div>

    @error($name)
        <p class="mt-2 text-sm text-red-500 flex items-center gap-1">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            {{ $message }}
        </p>
    @enderror
</div>
