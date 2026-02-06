{{-- 
    Reusable Program Form Component
    Usage: @include('panel.programs._form', ['program' => $program ?? null, 'isEdit' => true/false, 'isAdmin' => true/false])
--}}

@php
    $isEdit = $isEdit ?? false;
    $isAdmin = $isAdmin ?? false;
    
    // For instructor edit mode, use $submission instead of $program - define BEFORE using it
    $data = $program ?? $submission ?? null;
    
    $formAction = $isEdit && $data
        ? ($isAdmin ? route('admin.programs.update', $data->id) : route('instructor.programs.update', $data->id))
        : ($isAdmin ? route('admin.programs.store') : route('instructor.programs.store'));
    
    // Theme colors based on role
    $primaryColor = $isAdmin ? 'orange' : 'blue';
    $primaryColorClass = $isAdmin ? 'orange-600' : 'blue-600';
    $primaryDarkClass = $isAdmin ? 'orange-400' : 'blue-400';
    
    // Determine which step has errors (for server-side validation)
    $errorStep = 1;
    if ($errors->any()) {
        $fieldStepMap = [
            'title' => 1, 'program' => 1, 'instructor_id' => 1, 'category' => 1, 'type' => 1, 'description' => 1,
            'quota' => 2, 'available_slots' => 2, 'price' => 2, 'tools' => 2, 'benefits' => 2,
            'start_date' => 3, 'end_date' => 3, 'start_time' => 3, 'end_time' => 3,
            'zoom_link' => 3, 'province' => 3, 'city' => 3, 'district' => 3, 'village' => 3, 'full_address' => 3,
            'materials' => 4,
            'image' => 5
        ];
        foreach ($errors->keys() as $errorKey) {
            // Handle array fields like materials.0.title
            $baseKey = explode('.', $errorKey)[0];
            if (isset($fieldStepMap[$baseKey]) && $fieldStepMap[$baseKey] > $errorStep) {
                $errorStep = $fieldStepMap[$baseKey];
            }
        }
    }
@endphp

<div class="program-form-container bg-white dark:bg-gray-800 rounded-2xl shadow-xl shadow-gray-200/50 dark:shadow-none overflow-hidden"
     id="programFormContainer"
     data-is-admin="{{ $isAdmin ? 'true' : 'false' }}"
     data-is-edit="{{ $isEdit ? 'true' : 'false' }}"
     data-primary-color="{{ $primaryColor }}"
     @if($errors->any()) data-error-step="{{ $errorStep }}" @endif
     @if(isset($locationData) && $locationData) 
         data-location-province="{{ $locationData['province_id'] ?? '' }}"
         data-location-city="{{ $locationData['city_id'] ?? '' }}"
         data-location-district="{{ $locationData['district_id'] ?? '' }}"
         data-location-village="{{ $locationData['village_id'] ?? '' }}"
     @endif>
    
    <!-- Progress Header -->
    <div class="px-5 sm:px-8 pt-6 pb-4 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-center gap-2 sm:gap-3" id="stepIndicators">
            @for($step = 1; $step <= 5; $step++)
                <div class="flex items-center">
                    <button type="button" 
                            class="step-indicator w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center text-sm font-semibold transition-all duration-300 {{ $step === 1 ? 'bg-'.$primaryColor.'-600 text-white shadow-lg' : 'bg-gray-200 text-gray-500 dark:bg-gray-700 dark:text-gray-400' }}"
                            data-step="{{ $step }}">
                        {{ $step }}
                    </button>
                    @if($step < 5)
                        <div class="step-line w-6 sm:w-12 h-1 mx-1 rounded bg-gray-200 dark:bg-gray-700 transition-all duration-300"></div>
                    @endif
                </div>
            @endfor
        </div>
    </div>

    <form id="programForm" 
          action="{{ $formAction }}" 
          method="POST" 
          enctype="multipart/form-data">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <!-- Step 1: Informasi Dasar -->
        <div class="form-step" data-step="1">
            <div class="p-5 sm:p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-{{ $primaryColor }}-100 dark:bg-{{ $primaryColor }}-900/30 flex items-center justify-center">
                        <svg class="w-5 h-5 text-{{ $primaryColor }}-600 dark:text-{{ $primaryColor }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Informasi Dasar</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Lengkapi informasi dasar program</p>
                    </div>
                </div>

                <div class="space-y-5">
                    <!-- Title -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Judul Program <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="{{ $isAdmin ? 'program' : 'title' }}" 
                               id="input-title"
                               value="{{ old($isAdmin ? 'program' : 'title', $data->{$isAdmin ? 'program' : 'title'} ?? '') }}"
                               class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:border-{{ $primaryColor }}-500 focus:ring-4 focus:ring-{{ $primaryColor }}-100 dark:focus:ring-{{ $primaryColor }}-900/30 transition-all"
                               placeholder="Masukkan judul program">
                        <p class="field-error text-red-500 text-sm mt-1 hidden" data-field="title"></p>
                    </div>

                    <!-- Instructor (Admin only) -->
                    @if($isAdmin)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Instruktur <span class="text-red-500">*</span>
                        </label>
                        <select name="instructor_id" 
                                id="input-instructor_id"
                                class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:border-{{ $primaryColor }}-500 focus:ring-4 focus:ring-{{ $primaryColor }}-100 transition-all">
                            <option value="">Pilih Instruktur</option>
                            @foreach($instructors ?? [] as $instructor)
                                <option value="{{ $instructor->id }}" {{ old('instructor_id', $data->instructor_id ?? '') == $instructor->id ? 'selected' : '' }}>
                                    {{ $instructor->nama }}
                                </option>
                            @endforeach
                        </select>
                        <p class="field-error text-red-500 text-sm mt-1 hidden" data-field="instructor_id"></p>
                    </div>
                    @endif

                    <!-- Category -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Kategori <span class="text-red-500">*</span>
                        </label>
                        <select name="category" 
                                id="input-category"
                                class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:border-{{ $primaryColor }}-500 focus:ring-4 focus:ring-{{ $primaryColor }}-100 transition-all">
                            <option value="">Pilih Kategori</option>
                            @foreach(['Kursus', 'Pelatihan', 'Sertifikasi', 'Outing Class', 'Outboard'] as $cat)
                                <option value="{{ $cat }}" {{ old('category', $data->category ?? '') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                        <p class="field-error text-red-500 text-sm mt-1 hidden" data-field="category"></p>
                    </div>

                    <!-- Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tipe Program <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach(['online' => 'Online', 'offline' => 'Offline'] as $value => $label)
                            <label class="type-option relative cursor-pointer">
                                <input type="radio" name="type" value="{{ $value }}" 
                                       class="sr-only" 
                                       {{ old('type', $data->type ?? '') == $value ? 'checked' : '' }}>
                                <div class="type-card p-4 rounded-xl border-2 border-gray-200 dark:border-gray-600 text-center transition-all hover:border-{{ $primaryColor }}-300 {{ old('type', $data->type ?? '') == $value ? 'border-'.$primaryColor.'-500 bg-'.$primaryColor.'-50 dark:bg-'.$primaryColor.'-900/20' : '' }}">
                                    <div class="w-10 h-10 mx-auto mb-2 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                        @if($value === 'online')
                                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                        @else
                                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        @endif
                                    </div>
                                    <span class="font-medium text-gray-700 dark:text-gray-300">{{ $label }}</span>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        <p class="field-error text-red-500 text-sm mt-1 hidden" data-field="type"></p>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Deskripsi <span class="text-red-500">*</span>
                        </label>
                        <textarea name="description" 
                                  id="input-description"
                                  rows="4"
                                  class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:border-{{ $primaryColor }}-500 focus:ring-4 focus:ring-{{ $primaryColor }}-100 transition-all resize-none"
                                  placeholder="Jelaskan tentang program ini...">{{ old('description', $data->description ?? '') }}</textarea>
                        <p class="field-error text-red-500 text-sm mt-1 hidden" data-field="description"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 2: Detail Program -->
        <div class="form-step hidden" data-step="2">
            <div class="p-5 sm:p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-{{ $primaryColor }}-100 dark:bg-{{ $primaryColor }}-900/30 flex items-center justify-center">
                        <svg class="w-5 h-5 text-{{ $primaryColor }}-600 dark:text-{{ $primaryColor }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Detail Program</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Tentukan kuota dan harga</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <!-- Quota -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Kuota Peserta <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               name="{{ $isAdmin ? 'quota' : 'available_slots' }}" 
                               id="input-quota"
                               min="1"
                               value="{{ old($isAdmin ? 'quota' : 'available_slots', $data->{$isAdmin ? 'quota' : 'available_slots'} ?? '') }}"
                               class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:border-{{ $primaryColor }}-500 focus:ring-4 focus:ring-{{ $primaryColor }}-100 transition-all"
                               placeholder="Masukkan jumlah kuota">
                        <p class="field-error text-red-500 text-sm mt-1 hidden" data-field="quota"></p>
                    </div>

                    <!-- Price -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Harga <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                            <input type="number" 
                                   name="price" 
                                   id="input-price"
                                   min="0"
                                   value="{{ old('price', $data->price ?? '') }}"
                                   class="w-full pl-12 pr-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:border-{{ $primaryColor }}-500 focus:ring-4 focus:ring-{{ $primaryColor }}-100 transition-all"
                                   placeholder="0">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Masukkan 0 untuk program gratis</p>
                        <p class="field-error text-red-500 text-sm mt-1 hidden" data-field="price"></p>
                    </div>

                    <!-- Tools -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Alat/Bahan yang Diperlukan
                            </label>
                            <button type="button" 
                                    id="addToolBtn"
                                    class="text-{{ $primaryColor }}-600 hover:text-{{ $primaryColor }}-700 text-sm font-medium flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Tambah
                            </button>
                        </div>
                        <div id="toolsContainer" class="space-y-2">
                            @php $tools = old('tools', $data->tools ?? []); @endphp
                            @if(is_array($tools) && count($tools) > 0)
                                @foreach($tools as $index => $tool)
                                <div class="tool-item flex gap-2">
                                    <input type="text" name="tools[]" value="{{ $tool }}"
                                           class="flex-1 px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:border-{{ $primaryColor }}-500 transition-all"
                                           placeholder="Nama alat/bahan">
                                    <button type="button" class="remove-tool-btn p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <!-- Benefits -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Manfaat Program
                            </label>
                            <button type="button" 
                                    id="addBenefitBtn"
                                    class="text-{{ $primaryColor }}-600 hover:text-{{ $primaryColor }}-700 text-sm font-medium flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Tambah
                            </button>
                        </div>
                        <div id="benefitsContainer" class="space-y-2">
                            @php $benefits = old('benefits', $data->benefits ?? []); @endphp
                            @if(is_array($benefits) && count($benefits) > 0)
                                @foreach($benefits as $index => $benefit)
                                <div class="benefit-item flex gap-2">
                                    <input type="text" name="benefits[]" value="{{ $benefit }}"
                                           class="flex-1 px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:border-{{ $primaryColor }}-500 transition-all"
                                           placeholder="Manfaat yang didapat">
                                    <button type="button" class="remove-benefit-btn p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 3: Jadwal & Lokasi -->
        <div class="form-step hidden" data-step="3">
            <div class="p-5 sm:p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-{{ $primaryColor }}-100 dark:bg-{{ $primaryColor }}-900/30 flex items-center justify-center">
                        <svg class="w-5 h-5 text-{{ $primaryColor }}-600 dark:text-{{ $primaryColor }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Jadwal & Lokasi</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Tentukan waktu dan tempat pelaksanaan</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <!-- Date Range -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tanggal Mulai <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   name="start_date" 
                                   id="input-start_date"
                                   value="{{ old('start_date', $data->start_date ?? '') }}"
                                   class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:border-{{ $primaryColor }}-500 transition-all">
                            <p class="field-error text-red-500 text-sm mt-1 hidden" data-field="start_date"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tanggal Selesai <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   name="end_date" 
                                   id="input-end_date"
                                   value="{{ old('end_date', $data->end_date ?? '') }}"
                                   class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:border-{{ $primaryColor }}-500 transition-all">
                            <p class="field-error text-red-500 text-sm mt-1 hidden" data-field="end_date"></p>
                        </div>
                    </div>

                    <!-- Time Range -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Waktu Mulai <span class="text-red-500">*</span>
                            </label>
                            <input type="time" 
                                   name="start_time" 
                                   id="input-start_time"
                                   value="{{ old('start_time', $data->start_time ?? '') }}"
                                   class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:border-{{ $primaryColor }}-500 transition-all">
                            <p class="field-error text-red-500 text-sm mt-1 hidden" data-field="start_time"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Waktu Selesai <span class="text-red-500">*</span>
                            </label>
                            <input type="time" 
                                   name="end_time" 
                                   id="input-end_time"
                                   value="{{ old('end_time', $data->end_time ?? '') }}"
                                   class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:border-{{ $primaryColor }}-500 transition-all">
                            <p class="field-error text-red-500 text-sm mt-1 hidden" data-field="end_time"></p>
                        </div>
                    </div>

                    <!-- Online Fields (Zoom Link) -->
                    <div id="onlineFields" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Link Zoom/Google Meet <span class="text-red-500">*</span>
                        </label>
                        <input type="url" 
                               name="zoom_link" 
                               id="input-zoom_link"
                               value="{{ old('zoom_link', $data->zoom_link ?? '') }}"
                               class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:border-{{ $primaryColor }}-500 transition-all"
                               placeholder="https://zoom.us/j/...">
                        <p class="field-error text-red-500 text-sm mt-1 hidden" data-field="zoom_link"></p>
                    </div>

                    <!-- Offline Fields (Location) -->
                    <div id="offlineFields" class="hidden space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Provinsi <span class="text-red-500">*</span>
                                </label>
                                <select id="province-select"
                                        class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:border-{{ $primaryColor }}-500 transition-all">
                                    <option value="">Pilih Provinsi</option>
                                </select>
                                <input type="hidden" name="province" id="input-province" value="{{ old('province', $data->province ?? '') }}">
                                <input type="hidden" name="province_id" id="input-province-id" value="{{ old('province_id', $locationData['province_id'] ?? '') }}">
                                <p class="field-error text-red-500 text-sm mt-1 hidden" data-field="province"></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Kabupaten/Kota <span class="text-red-500">*</span>
                                </label>
                                <select id="city-select" disabled
                                        class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:border-{{ $primaryColor }}-500 transition-all disabled:opacity-50">
                                    <option value="">Pilih Kabupaten/Kota</option>
                                </select>
                                <input type="hidden" name="city" id="input-city" value="{{ old('city', $data->city ?? '') }}">
                                <input type="hidden" name="city_id" id="input-city-id" value="{{ old('city_id', $locationData['city_id'] ?? '') }}">
                                <p class="field-error text-red-500 text-sm mt-1 hidden" data-field="city"></p>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Kecamatan <span class="text-red-500">*</span>
                                </label>
                                <select id="district-select" disabled
                                        class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:border-{{ $primaryColor }}-500 transition-all disabled:opacity-50">
                                    <option value="">Pilih Kecamatan</option>
                                </select>
                                <input type="hidden" name="district" id="input-district" value="{{ old('district', $data->district ?? '') }}">
                                <input type="hidden" name="district_id" id="input-district-id" value="{{ old('district_id', $locationData['district_id'] ?? '') }}">
                                <p class="field-error text-red-500 text-sm mt-1 hidden" data-field="district"></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Kelurahan/Desa <span class="text-red-500">*</span>
                                </label>
                                <select id="village-select" disabled
                                        class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:border-{{ $primaryColor }}-500 transition-all disabled:opacity-50">
                                    <option value="">Pilih Kelurahan/Desa</option>
                                </select>
                                <input type="hidden" name="village" id="input-village" value="{{ old('village', $data->village ?? '') }}">
                                <input type="hidden" name="village_id" id="input-village-id" value="{{ old('village_id', $locationData['village_id'] ?? '') }}">
                                <p class="field-error text-red-500 text-sm mt-1 hidden" data-field="village"></p>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Alamat Lengkap <span class="text-red-500">*</span>
                            </label>
                            <textarea name="full_address" 
                                      id="input-full_address"
                                      rows="3"
                                      class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:border-{{ $primaryColor }}-500 transition-all resize-none"
                                      placeholder="Jalan, nomor, gedung, lantai...">{{ old('full_address', $data->full_address ?? '') }}</textarea>
                            <p class="field-error text-red-500 text-sm mt-1 hidden" data-field="full_address"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 4: Materi Pembelajaran -->
        <div class="form-step hidden" data-step="4">
            <div class="p-5 sm:p-8">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-{{ $primaryColor }}-100 dark:bg-{{ $primaryColor }}-900/30 flex items-center justify-center">
                            <svg class="w-5 h-5 text-{{ $primaryColor }}-600 dark:text-{{ $primaryColor }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Materi Pembelajaran</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Tambahkan materi yang akan dipelajari</p>
                        </div>
                    </div>
                    <button type="button" 
                            id="addMaterialBtn"
                            class="flex items-center gap-2 px-4 py-2 bg-{{ $primaryColor }}-600 hover:bg-{{ $primaryColor }}-700 text-white rounded-lg transition-colors text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah Materi
                    </button>
                </div>

                <div id="materialsContainer" class="space-y-4">
                    @php $materials = old('materials', $data->materials ?? []); @endphp
                    @if(is_array($materials) && count($materials) > 0)
                        @foreach($materials as $index => $material)
                        <div class="material-item bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 border border-gray-200 dark:border-gray-600">
                            <div class="flex items-start justify-between mb-3">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Materi {{ $index + 1 }}</span>
                                <button type="button" class="remove-material-btn p-1 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="space-y-3">
                                <input type="text" name="materials[{{ $index }}][title]" 
                                       value="{{ is_array($material) ? ($material['title'] ?? '') : '' }}"
                                       class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:border-{{ $primaryColor }}-500 transition-all"
                                       placeholder="Judul Materi">
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="text-xs text-gray-500 dark:text-gray-400">Jam</label>
                                        <input type="number" name="materials[{{ $index }}][duration_hours]" min="0"
                                               value="{{ is_array($material) ? ($material['duration_hours'] ?? 0) : 0 }}"
                                               class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white text-sm">
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-500 dark:text-gray-400">Menit</label>
                                        <input type="number" name="materials[{{ $index }}][duration_minutes]" min="0" max="59"
                                               value="{{ is_array($material) ? ($material['duration_minutes'] ?? 0) : 0 }}"
                                               class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white text-sm">
                                    </div>
                                </div>
                                <textarea name="materials[{{ $index }}][description]" rows="2"
                                          class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:border-{{ $primaryColor }}-500 transition-all resize-none text-sm"
                                          placeholder="Deskripsi materi (opsional)">{{ is_array($material) ? ($material['description'] ?? '') : '' }}</textarea>
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>

                <div id="emptyMaterialsState" class="text-center py-8 text-gray-500 dark:text-gray-400 {{ (is_array($materials) && count($materials) > 0) ? 'hidden' : '' }}">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p>Belum ada materi ditambahkan</p>
                    <p class="text-sm">Klik "Tambah Materi" untuk menambahkan</p>
                </div>
            </div>
        </div>

        <!-- Step 5: Gambar Program -->
        <div class="form-step hidden" data-step="5">
            <div class="p-5 sm:p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-{{ $primaryColor }}-100 dark:bg-{{ $primaryColor }}-900/30 flex items-center justify-center">
                        <svg class="w-5 h-5 text-{{ $primaryColor }}-600 dark:text-{{ $primaryColor }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Gambar Program</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Upload gambar cover program</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <!-- Image Upload -->
                    <div id="imageUploadArea" 
                         class="relative border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-2xl p-8 text-center hover:border-{{ $primaryColor }}-400 transition-colors cursor-pointer">
                        <input type="file" name="image" id="input-image" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        
                        <div id="uploadPlaceholder" class="{{ ($isEdit && $data->image) ? 'hidden' : '' }}">
                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <p class="text-gray-600 dark:text-gray-400 mb-2">Klik atau seret gambar ke sini</p>
                            <p class="text-sm text-gray-500">PNG, JPG, WEBP (Max 2MB)</p>
                        </div>

                        <div id="imagePreview" class="{{ ($isEdit && $data->image) ? '' : 'hidden' }}">
                            @if($isEdit && $data->image)
                                @php
                                    $imageUrl = str_starts_with($data->image, 'http') ? $data->image : asset('storage/' . $data->image);
                                @endphp
                                <img src="{{ $imageUrl }}" alt="Preview" class="max-h-64 mx-auto rounded-xl object-cover">
                            @else
                                <img src="" alt="Preview" class="max-h-64 mx-auto rounded-xl object-cover">
                            @endif
                        </div>
                    </div>
                    <p class="field-error text-red-500 text-sm mt-1 {{ $errors->has('image') ? '' : 'hidden' }}" data-field="image">{{ $errors->first('image') }}</p>

                    @if($isEdit && $data->image)
                        <input type="hidden" name="existing_image" id="existing-image" value="{{ $data->image }}">
                    @endif
                </div>
            </div>
        </div>

        <!-- Navigation Footer -->
        <div class="sticky bottom-0 px-5 sm:px-8 py-4 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between gap-4">
                <button type="button" id="prevStepBtn" class="hidden items-center gap-2 px-4 py-2.5 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-xl transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Sebelumnya
                </button>
                
                <a href="{{ $isAdmin ? route('admin.programs.index') : route('instructor.programs.index') }}" 
                   id="cancelLink"
                   class="flex items-center gap-2 px-4 py-2.5 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-xl transition-colors">
                    Batal
                </a>

                <div class="flex-1"></div>

                <button type="button" id="nextStepBtn" class="flex items-center gap-2 px-6 py-2.5 bg-{{ $primaryColor }}-600 hover:bg-{{ $primaryColor }}-700 text-white rounded-xl transition-colors font-medium">
                    Selanjutnya
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>

                <button type="submit" id="submitBtn" class="hidden items-center gap-2 px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-xl transition-colors font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ $isEdit ? 'Simpan Perubahan' : 'Ajukan Program' }}
                </button>
            </div>
        </div>
    </form>
</div>
