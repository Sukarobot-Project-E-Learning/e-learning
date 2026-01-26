@extends('panel.layouts.app')

@section('title', 'Edit Template Sertifikat')

@section('content')

<div class="container px-6 mx-auto">

    <!-- Page Header -->
    <div class="my-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Edit Template Sertifikat</h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Program: {{ $template->program_name ?? 'N/A' }}</p>
    </div>

    @if ($errors->any())
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form id="templateForm" action="{{ route('admin.certificates.update', $template->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="file_path" id="filePath" value="{{ $template->template_path ?? '' }}">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

            <!-- Left Column: Form Fields -->
            <div class="space-y-6">

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <h3 class="text-md font-semibold text-gray-800 dark:text-gray-200 mb-4">📍 Posisi & Ukuran Font</h3>
                    <p class="text-xs text-gray-500 mb-4">Ubah nilai lalu klik "Refresh Preview" untuk melihat hasil</p>

                    <div class="space-y-4">
                        <div class="p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200">
                            <label class="block text-sm font-medium text-yellow-800 mb-2">🟡 Nama (Lobster)</label>
                            <div class="grid grid-cols-3 gap-2">
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-white">X (%)</label>
                                    <input type="number" name="name_x" id="name_x" value="{{ old('name_x', $template->name_x ?? 50) }}" min="0" max="100" step="0.5"
                                        class="block w-full px-2 py-1 text-sm border rounded dark:bg-white dark:border-white dark:text-gray-900">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-white">Y (%)</label>
                                    <input type="number" name="name_y" id="name_y" value="{{ old('name_y', $template->name_y ?? 28) }}" min="0" max="100" step="0.5"
                                        class="block w-full px-2 py-1 text-sm border rounded dark:bg-white dark:border-white dark:text-gray-900">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-white">Size (pt)</label>
                                    <input type="number" name="name_font_size" id="name_font_size" value="{{ old('name_font_size', $template->name_font_size ?? 38) }}" min="8" max="100"
                                        class="block w-full px-2 py-1 text-sm border rounded dark:bg-white dark:border-white dark:text-gray-900">
                                </div>
                            </div>
                        </div>

                        <div class="p-3 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200">
                            <label class="block text-sm font-medium text-green-800 mb-2">🟢 Nomor Sertifikat (Lato)</label>
                            <div class="grid grid-cols-3 gap-2">
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-white">X (%)</label>
                                    <input type="number" name="number_x" id="number_x" value="{{ old('number_x', $template->number_x ?? 50) }}" min="0" max="100" step="0.5"
                                        class="block w-full px-2 py-1 text-sm border rounded dark:bg-white dark:border-white dark:text-gray-900">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-white">Y (%)</label>
                                    <input type="number" name="number_y" id="number_y" value="{{ old('number_y', $template->number_y ?? 18) }}" min="0" max="100" step="0.5"
                                        class="block w-full px-2 py-1 text-sm border rounded dark:bg-white dark:border-white dark:text-gray-900">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-white">Size (pt)</label>
                                    <input type="number" name="number_font_size" id="number_font_size" value="{{ old('number_font_size', $template->number_font_size ?? 12) }}" min="8" max="100"
                                        class="block w-full px-2 py-1 text-sm border rounded dark:bg-white dark:border-white dark:text-gray-900">
                                </div>
                            </div>
                        </div>

                        <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200">
                            <label class="block text-sm font-medium text-blue-800 mb-2">🔵 Deskripsi (Lato)</label>
                            <div class="grid grid-cols-3 gap-2">
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-white">X (%)</label>
                                    <input type="number" name="desc_x" id="desc_x" value="{{ old('desc_x', $template->desc_x ?? 50) }}" min="0" max="100" step="0.5"
                                        class="block w-full px-2 py-1 text-sm border rounded dark:bg-white dark:border-white dark:text-gray-900">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-white">Y (%)</label>
                                    <input type="number" name="desc_y" id="desc_y" value="{{ old('desc_y', $template->desc_y ?? 48) }}" min="0" max="100" step="0.5"
                                        class="block w-full px-2 py-1 text-sm border rounded dark:bg-white dark:border-white dark:text-gray-900">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-white">Size (pt)</label>
                                    <input type="number" name="desc_font_size" id="desc_font_size" value="{{ old('desc_font_size', $template->desc_font_size ?? 16) }}" min="8" max="100"
                                        class="block w-full px-2 py-1 text-sm border rounded dark:bg-white dark:border-white dark:text-gray-900">
                                </div>
                            </div>
                        </div>

                        <div class="p-3 bg-purple-50 dark:bg-purple-900/20 rounded-lg border border-purple-200">
                            <label class="block text-sm font-medium text-purple-800 mb-2">🟣 Tanggal (Lato)</label>
                            <div class="grid grid-cols-3 gap-2">
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-white">X (%)</label>
                                    <input type="number" name="date_x" id="date_x" value="{{ old('date_x', $template->date_x ?? 50) }}" min="0" max="100" step="0.5"
                                        class="block w-full px-2 py-1 text-sm border rounded dark:bg-white dark:border-white dark:text-gray-900">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-white">Y (%)</label>
                                    <input type="number" name="date_y" id="date_y" value="{{ old('date_y', $template->date_y ?? 88) }}" min="0" max="100" step="0.5"
                                        class="block w-full px-2 py-1 text-sm border rounded dark:bg-white dark:border-white dark:text-gray-900">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-white">Size (pt)</label>
                                    <input type="number" name="date_font_size" id="date_font_size" value="{{ old('date_font_size', $template->date_font_size ?? 14) }}" min="8" max="100"
                                        class="block w-full px-2 py-1 text-sm border rounded dark:bg-white dark:border-white dark:text-gray-900">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Informasi Template</h3>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Program</label>
                        <input type="text" value="{{ $template->program_name }}" readonly
                            class="block w-full px-4 py-2 text-sm bg-gray-100 border border-gray-200 rounded-lg cursor-not-allowed">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Prefix Nomor <span class="text-red-500">*</span></label>
                        <input type="text" name="number_prefix" id="numberPrefixInput" value="{{ old('number_prefix', $template->number_prefix) }}" required
                            class="block w-full px-4 py-2 text-sm border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Deskripsi</label>
                        <textarea name="description" rows="2" id="descriptionInput"
                            class="block w-full px-4 py-2 text-sm border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">{{ old('description', $template->description) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ganti Blanko (opsional)</label>
                        <input type="file" name="blanko" id="blankoInput" accept="image/*"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-orange-50 file:text-orange-700"
                            onchange="handleImageUpload(this)">
                        <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak mengganti</p>
                    </div>
                </div>

            </div>

            <!-- Right Column: Preview -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 sticky top-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">📸 Hasil Preview</h3>
                    <div class="flex gap-2">
                        <button type="button" id="refreshPreviewBtn" onclick="generatePreview()"
                            class="{{ $template->template_path ? '' : 'hidden' }} inline-flex items-center px-3 py-1.5 text-xs font-medium text-orange-700 bg-orange-100 rounded-lg hover:bg-orange-200 transition-colors cursor-pointer">
                            🔄 Refresh Preview
                        </button>
                        <a id="downloadPdfBtn" href="#" target="_blank"
                            class="hidden inline-flex items-center px-3 py-1.5 text-xs font-medium text-green-700 bg-green-100 rounded-lg hover:bg-green-200 transition-colors">
                            📄 Download PDF
                        </a>
                    </div>
                </div>

                <!-- Status message -->
                <div id="statusMessage" class="mb-3 p-2 text-sm rounded hidden"></div>

                <!-- Preview Container -->
                <div id="previewContainer" class="border border-gray-300 rounded-lg overflow-hidden bg-gray-50 min-h-64">
                    @if($template->template_path)
                    <img id="previewImage" src="{{ route('admin.certificates.template-image', $template->id) }}" alt="Preview" class="w-full h-auto">
                    @else
                    <img id="previewImage" src="" alt="Preview" class="w-full h-auto" style="display: none;">
                    <div id="uploadPrompt" class="flex flex-col items-center justify-center h-64 text-gray-500">
                        <label for="blankoInput" class="cursor-pointer text-center hover:text-orange-600">
                            <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="font-medium">Upload blanko baru</p>
                        </label>
                    </div>
                    @endif
                </div>

                <p class="mt-2 text-xs text-gray-500 text-center">
                    ✓ Klik "Refresh Preview" untuk melihat hasil dengan teks
                </p>

                <div class="mt-8 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.certificates.index') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        ← Kembali
                    </a>

                    <button type="submit" form="templateForm"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-orange-600 rounded-lg hover:bg-orange-700 cursor-pointer">
                        ✓ Simpan Perubahan
                    </button>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
    var filePath = '{{ $template->template_path ?? "" }}';
    var imageWidth = 1920;
    var imageHeight = 1357;
    var refreshTimeout = null;

    // Auto-refresh with debounce when values change
    function autoRefreshPreview() {
        if (!filePath) return;
        
        if (refreshTimeout) clearTimeout(refreshTimeout);
        refreshTimeout = setTimeout(function() {
            generatePreview();
        }, 500);
    }

    // Attach auto-refresh to all position/size inputs
    document.addEventListener('DOMContentLoaded', function() {
        var inputs = ['name_x', 'name_y', 'name_font_size', 
                      'number_x', 'number_y', 'number_font_size',
                      'desc_x', 'desc_y', 'desc_font_size',
                      'date_x', 'date_y', 'date_font_size'];
        
        inputs.forEach(function(id) {
            var el = document.getElementById(id);
            if (el) {
                el.addEventListener('input', autoRefreshPreview);
                el.addEventListener('change', autoRefreshPreview);
            }
        });
    });

    function handleImageUpload(input) {
        if (input.files && input.files[0]) {
            uploadTemplate(input.files[0]);
        }
    }

    function showStatus(message, type) {
        var el = document.getElementById('statusMessage');
        el.textContent = message;
        el.className = 'mb-3 p-2 text-sm rounded';
        if (type === 'success') {
            el.className += ' bg-green-100 text-green-800';
        } else if (type === 'error') {
            el.className += ' bg-red-100 text-red-800';
        } else {
            el.className += ' bg-blue-100 text-blue-800';
        }
        el.classList.remove('hidden');
    }

    function uploadTemplate(file) {
        var formData = new FormData();
        formData.append('blanko', file);
        formData.append('previous_file_path', filePath);
        formData.append('_token', '{{ csrf_token() }}');

        showStatus('Mengupload blanko...', 'info');

        fetch('{{ route("admin.certificates.upload-template") }}', {
            method: 'POST',
            body: formData
        })
        .then(function(response) { return response.json(); })
        .then(function(data) {
            if (data.success) {
                filePath = data.file_path;
                document.getElementById('filePath').value = data.file_path;
                imageWidth = data.width;
                imageHeight = data.height;
                
                // Hide upload prompt
                var uploadPrompt = document.getElementById('uploadPrompt');
                if (uploadPrompt) uploadPrompt.style.display = 'none';
                
                // Show refresh button
                document.getElementById('refreshPreviewBtn').classList.remove('hidden');
                
                // Auto-generate preview
                showStatus('Upload berhasil! Generating preview...', 'success');
                generatePreview();
            } else {
                showStatus('Gagal upload: ' + (data.message || 'Unknown error'), 'error');
            }
        })
        .catch(function(error) {
            console.error('Upload error:', error);
            showStatus('Gagal upload file', 'error');
        });
    }

    function generatePreview() {
        if (!filePath) {
            showStatus('Silakan upload blanko terlebih dahulu', 'error');
            return;
        }

        showStatus('Generating preview...', 'info');

        var formData = {
            file_path: filePath,
            name_x: document.getElementById('name_x').value,
            name_y: document.getElementById('name_y').value,
            name_font_size: document.getElementById('name_font_size').value,
            number_x: document.getElementById('number_x').value,
            number_y: document.getElementById('number_y').value,
            number_font_size: document.getElementById('number_font_size').value,
            desc_x: document.getElementById('desc_x').value,
            desc_y: document.getElementById('desc_y').value,
            desc_font_size: document.getElementById('desc_font_size').value,
            date_x: document.getElementById('date_x').value,
            date_y: document.getElementById('date_y').value,
            date_font_size: document.getElementById('date_font_size').value,
            description: document.getElementById('descriptionInput').value || 'Deskripsi sertifikat',
            number_prefix: document.getElementById('numberPrefixInput').value || 'B-1/PT.STG',
            _token: '{{ csrf_token() }}'
        };

        fetch('{{ route("admin.certificates.preview") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(formData)
        })
        .then(function(response) { return response.json(); })
        .then(function(data) {
            if (data.success) {
                var img = document.getElementById('previewImage');
                img.src = data.preview_url;
                img.style.display = 'block';
                
                var pdfBtn = document.getElementById('downloadPdfBtn');
                pdfBtn.href = data.pdf_url;
                pdfBtn.classList.remove('hidden');
                
                showStatus('Preview berhasil! Klik "Download PDF" untuk cek hasil.', 'success');
            } else {
                showStatus('Gagal: ' + (data.message || 'Unknown error'), 'error');
            }
        })
        .catch(function(error) {
            console.error('Preview error:', error);
            showStatus('Gagal generate preview', 'error');
        });
    }
</script>

@endsection
