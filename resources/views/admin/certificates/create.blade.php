@extends('admin.layouts.app')

@section('title', 'Tambah Template Sertifikat')

@section('content')

{{-- Load fonts for preview --}}
<style>
    @font-face {
        font-family: 'Lobster';
        src: url('{{ asset("fonts/Lobster-Regular.ttf") }}') format('truetype');
    }

    @font-face {
        font-family: 'Lato';
        src: url('{{ asset("fonts/Lato-Regular.ttf") }}') format('truetype');
    }

    .font-lobster {
        font-family: 'Lobster', cursive;
    }

    .font-lato {
        font-family: 'Lato', sans-serif;
    }
</style>

<div class="container px-6 mx-auto">

    @if ($errors->any())
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form id="templateForm" action="{{ route('admin.certificates.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

            <!-- Left Column: Form Fields -->
            <div class="space-y-6">

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <h3 class="text-md font-semibold text-gray-800 dark:text-gray-200 mb-4">📍 Posisi & Ukuran Font</h3>

                    <div class="space-y-4">
                        <div class="p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200">
                            <label class="block text-sm font-medium text-yellow-800 mb-2">🟡 Nama (Lobster)</label>
                            <div class="grid grid-cols-3 gap-2">
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-white">X (%)</label>
                                    <input type="number" name="name_x" id="name_x" value="{{ $defaults['name_x'] }}" min="0" max="100" step="0.5"
                                        class="block w-full px-2 py-1 text-sm border rounded dark:bg-white dark:border-white dark:text-gray-900" oninput="updatePreview()">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-white">Y (%)</label>
                                    <input type="number" name="name_y" id="name_y" value="{{ $defaults['name_y'] }}" min="0" max="100" step="0.5"
                                        class="block w-full px-2 py-1 text-sm border rounded dark:bg-white dark:border-white dark:text-gray-900" oninput="updatePreview()">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-white">Size (pt)</label>
                                    <input type="number" name="name_font_size" id="name_font_size" value="{{ $defaults['name_font_size'] }}" min="8" max="100"
                                        class="block w-full px-2 py-1 text-sm border rounded dark:bg-white dark:border-white dark:text-gray-900" oninput="updatePreview()">
                                </div>
                            </div>
                        </div>

                        <div class="p-3 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200">
                            <label class="block text-sm font-medium text-green-800 mb-2">🟢 Nomor Sertifikat (Lato)</label>
                            <div class="grid grid-cols-3 gap-2">
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-white">X (%)</label>
                                    <input type="number" name="number_x" id="number_x" value="{{ $defaults['number_x'] }}" min="0" max="100" step="0.5"
                                        class="block w-full px-2 py-1 text-sm border rounded dark:bg-white dark:border-white dark:text-gray-900" oninput="updatePreview()">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-white">Y (%)</label>
                                    <input type="number" name="number_y" id="number_y" value="{{ $defaults['number_y'] }}" min="0" max="100" step="0.5"
                                        class="block w-full px-2 py-1 text-sm border rounded dark:bg-white dark:border-white dark:text-gray-900" oninput="updatePreview()">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-white">Size (pt)</label>
                                    <input type="number" name="number_font_size" id="number_font_size" value="{{ $defaults['number_font_size'] }}" min="8" max="100"
                                        class="block w-full px-2 py-1 text-sm border rounded dark:bg-white dark:border-white dark:text-gray-900" oninput="updatePreview()">
                                </div>
                            </div>
                        </div>

                        <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200">
                            <label class="block text-sm font-medium text-blue-800 mb-2">🔵 Deskripsi (Lato)</label>
                            <div class="grid grid-cols-3 gap-2">
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-white">X (%)</label>
                                    <input type="number" name="desc_x" id="desc_x" value="{{ $defaults['desc_x'] }}" min="0" max="100" step="0.5"
                                        class="block w-full px-2 py-1 text-sm border rounded dark:bg-white dark:border-white dark:text-gray-900" oninput="updatePreview()">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-white">Y (%)</label>
                                    <input type="number" name="desc_y" id="desc_y" value="{{ $defaults['desc_y'] }}" min="0" max="100" step="0.5"
                                        class="block w-full px-2 py-1 text-sm border rounded dark:bg-white dark:border-white dark:text-gray-900" oninput="updatePreview()">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-white">Size (pt)</label>
                                    <input type="number" name="desc_font_size" id="desc_font_size" value="{{ $defaults['desc_font_size'] }}" min="8" max="100"
                                        class="block w-full px-2 py-1 text-sm border rounded dark:bg-white dark:border-white dark:text-gray-900" oninput="updatePreview()">
                                </div>
                            </div>
                        </div>

                        <div class="p-3 bg-orange-50 dark:bg-orange-900/20 rounded-lg border border-orange-200">
                            <label class="block text-sm font-medium text-orange-800 mb-2">🟣 Tanggal (Lato)</label>
                            <div class="grid grid-cols-3 gap-2">
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-white">X (%)</label>
                                    <input type="number" name="date_x" id="date_x" value="{{ $defaults['date_x'] }}" min="0" max="100" step="0.5"
                                        class="block w-full px-2 py-1 text-sm border rounded dark:bg-white dark:border-white dark:text-gray-900" oninput="updatePreview()">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-white">Y (%)</label>
                                    <input type="number" name="date_y" id="date_y" value="{{ $defaults['date_y'] }}" min="0" max="100" step="0.5"
                                        class="block w-full px-2 py-1 text-sm border rounded dark:bg-white dark:border-white dark:text-gray-900" oninput="updatePreview()">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-white">Size (pt)</label>
                                    <input type="number" name="date_font_size" id="date_font_size" value="{{ $defaults['date_font_size'] }}" min="8" max="100"
                                        class="block w-full px-2 py-1 text-sm border rounded dark:bg-white dark:border-white dark:text-gray-900" oninput="updatePreview()">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Informasi Template</h3>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Program <span class="text-red-500">*</span></label>
                        @if($programs->isEmpty())
                        <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-lg text-sm text-yellow-700">
                            Semua program sudah memiliki template.
                        </div>
                        @else
                        <select name="program_id" required class="block w-full px-4 py-2 text-sm border border-gray-300 rounded-lg focus:border-orange-400 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                            <option value="">Pilih program</option>
                            @foreach($programs as $program)
                            <option value="{{ $program->id }}">{{ $program->program }}</option>
                            @endforeach
                        </select>
                        @endif
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Prefix Nomor <span class="text-red-500">*</span></label>
                        <input type="text" name="number_prefix" value="{{ old('number_prefix', 'B-1/PT.STG') }}" required
                            class="block w-full px-4 py-2 text-sm border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Deskripsi</label>
                        <textarea name="description" rows="2" id="descriptionInput"
                            class="block w-full px-4 py-2 text-sm border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
                            oninput="updatePreview()">{{ old('description') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Blanko <span class="text-red-500">*</span></label>
                        <input type="file" name="blanko" id="blankoInput" accept="image/*" required
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-orange-50 file:text-orange-700"
                            onchange="handleImageUpload(this)">
                    </div>
                </div>

            </div>

            <!-- Right Column: Live Preview -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 sticky top-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">👁️ Live Preview</h3>

                <label for="blankoInput" id="previewPlaceholder"
                    class="flex flex-col items-center justify-center h-64 bg-gray-100 dark:bg-gray-700 rounded-lg border-2 border-dashed border-gray-300 cursor-pointer hover:border-orange-500 hover:bg-gray-50 dark:hover:bg-gray-600 transition-all">

                    <div class="text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="font-medium">Upload blanko untuk melihat preview</p>
                        <p class="text-xs mt-1 text-gray-400">(Klik di sini)</p>
                    </div>
                </label>

                <div id="previewContainer" class="hidden relative">
                    <img id="previewImage" src="" alt="Preview" class="w-full h-auto rounded-lg border border-gray-300">

                    <div id="markerName" class="absolute font-lobster text-gray-800 whitespace-nowrap" style="transform: translate(-50%, -50%);">
                        Nama Penerima
                    </div>
                    <div id="markerNumber" class="absolute font-lato text-gray-800 whitespace-nowrap" style="transform: translate(-50%, -50%);">
                        No: 001/B-1/PT.STG/{{ $currentMonth }}/{{ $currentYear }}
                    </div>
                    <div id="markerDesc" class="absolute font-lato text-gray-800 text-center" style="transform: translate(-50%, 0); min-width: 60%; max-width: 85%; line-height: 1.4;">
                        <div id="markerDescLines">Deskripsi</div>
                    </div>
                    <div id="markerDate" class="absolute font-lato text-gray-800 whitespace-nowrap" style="transform: translate(-50%, -50%);">
                        {{ now()->format('d') }} {{ ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][now()->month - 1] }} {{ now()->year }}
                    </div>
                </div>

                <div class="mt-4 text-xs text-gray-500">
                    <p><strong>Tips:</strong> Ubah nilai untuk mengatur posisi dan ukuran font.</p>
                </div>

                <div class="mt-8 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.certificates.index') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>

                    <button type="submit" form="templateForm"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-orange-600 rounded-lg hover:bg-orange-700 focus:ring-4 focus:ring-orange-300 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Template
                    </button>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
    function handleImageUpload(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewPlaceholder').classList.add('hidden');
                document.getElementById('previewContainer').classList.remove('hidden');
                document.getElementById('previewImage').src = e.target.result;
                updatePreview();
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function updatePreview() {
        const container = document.getElementById('previewContainer');
        if (container.classList.contains('hidden')) return;

        // Get values
        const nameX = document.getElementById('name_x').value;
        const nameY = document.getElementById('name_y').value;
        const nameFontSize = document.getElementById('name_font_size').value;
        const numberX = document.getElementById('number_x').value;
        const numberY = document.getElementById('number_y').value;
        const numberFontSize = document.getElementById('number_font_size').value;
        const descX = document.getElementById('desc_x').value;
        const descY = document.getElementById('desc_y').value;
        const descFontSize = document.getElementById('desc_font_size').value;
        const dateX = document.getElementById('date_x').value;
        const dateY = document.getElementById('date_y').value;
        const dateFontSize = document.getElementById('date_font_size').value;
        const description = document.getElementById('descriptionInput').value || 'Deskripsi';

        // Update markers - position and font size
        const markerName = document.getElementById('markerName');
        markerName.style.left = nameX + '%';
        markerName.style.top = nameY + '%';
        markerName.style.fontSize = (nameFontSize * 0.5) + 'px'; // Scale for preview

        const markerNumber = document.getElementById('markerNumber');
        markerNumber.style.left = numberX + '%';
        markerNumber.style.top = numberY + '%';
        markerNumber.style.fontSize = (numberFontSize * 0.5) + 'px';

        const markerDesc = document.getElementById('markerDesc');
        markerDesc.style.left = descX + '%';
        markerDesc.style.top = descY + '%';
        markerDesc.style.fontSize = (descFontSize * 0.5) + 'px';

        // Word wrap description into multiple lines (matching PHP wordwrap 60 chars)
        const linesContainer = document.getElementById('markerDescLines');
        if (description.length > 50) {
            const words = description.split(' ');
            let lines = [];
            let currentLine = '';
            const maxChars = 60; // match PHP wordwrap(60) for longer lines

            words.forEach(word => {
                if ((currentLine + ' ' + word).trim().length <= maxChars) {
                    currentLine = (currentLine + ' ' + word).trim();
                } else {
                    if (currentLine) lines.push(currentLine);
                    currentLine = word;
                }
            });
            if (currentLine) lines.push(currentLine);

            linesContainer.innerHTML = lines.map(line => `<div style="margin-bottom: 2px;">${line}</div>`).join('');
        } else {
            linesContainer.textContent = description;
        }

        const markerDate = document.getElementById('markerDate');
        markerDate.style.left = dateX + '%';
        markerDate.style.top = dateY + '%';
        markerDate.style.fontSize = (dateFontSize * 0.5) + 'px';
    }
</script>

@endsection