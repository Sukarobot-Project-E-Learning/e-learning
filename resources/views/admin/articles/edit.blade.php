@extends('panel.layouts.app')

@section('title', 'Edit Artikel')

@section('content')

    <div class="container px-6 mx-auto">

        <!-- Page Header -->
        <div class="my-6">
            <div class="flex items-start justify-between">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Edit Artikel</h2>
                <div class="flex flex-col items-end" style="gap: 16px;">
                    <!-- Buttons moved to bottom -->
                </div>
            </div>
        </div>

        <!-- Alert Messages -->

        <!-- Form Card -->
        <div class="w-full mb-8 overflow-hidden rounded-lg shadow-md bg-white dark:bg-gray-800">
            <form id="articleForm" action="{{ route('admin.articles.update', $article['id']) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="px-6 py-6 space-y-6">

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="status">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" id="status" required
                            class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-orange-300">
                            <option value="">Pilih Status</option>
                            <option value="Publish" {{ $article['status'] === 'Publish' ? 'selected' : '' }}>Publish</option>
                            <option value="Draft" {{ $article['status'] === 'Draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                    </div>

                    <!-- Judul -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="title">
                            Judul <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" id="title" required value="{{ $article['title'] }}"
                            placeholder="Lengan Robotik Baru untuk Industri Otomotif"
                            class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-orange-300 dark:placeholder-gray-500">
                    </div>

                    <!-- Kategori -->
                    <div x-data="{ 
                            showCustom: {{ !in_array($article['category'], $categories ?? ['Riset & AI', 'Produk', 'Event']) ? 'true' : 'false' }},
                            customValue: '{{ !in_array($article['category'], $categories ?? ['Riset & AI', 'Produk', 'Event']) ? $article['category'] : '' }}'
                        }">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                            for="category_select">
                            Kategori <span class="text-red-500">*</span>
                        </label>
                        <select id="category_select" name="category" x-bind:name="showCustom ? '' : 'category'"
                            @change="showCustom = ($event.target.value === '__other__')"
                            class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-orange-300">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories ?? [] as $cat)
                                <option value="{{ $cat }}" {{ $article['category'] === $cat ? 'selected' : '' }}>{{ $cat }}
                                </option>
                            @endforeach
                            @if(empty($categories))
                                <option value="Riset & AI" {{ $article['category'] === 'Riset & AI' ? 'selected' : '' }}>Riset &
                                    AI</option>
                                <option value="Produk" {{ $article['category'] === 'Produk' ? 'selected' : '' }}>Produk</option>
                                <option value="Event" {{ $article['category'] === 'Event' ? 'selected' : '' }}>Event</option>
                            @endif
                            <option value="__other__" {{ !in_array($article['category'], $categories ?? ['Riset & AI', 'Produk', 'Event']) && $article['category'] ? 'selected' : '' }}>➕ Tambah Kategori Baru...
                            </option>
                        </select>

                        <!-- Input untuk kategori baru -->
                        <div x-show="showCustom" x-transition class="mt-2">
                            <input type="text" x-bind:name="showCustom ? 'category' : ''" x-model="customValue"
                                placeholder="Ketik nama kategori baru"
                                class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-orange-300 dark:placeholder-gray-500">
                        </div>
                    </div>

                    <!-- Tanggal Publish -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tanggal Publish
                        </label>
                        <div
                            class="block w-full px-4 py-3 text-sm text-gray-600 bg-gray-50 border border-gray-200 rounded-lg dark:bg-gray-700 dark:text-gray-400 dark:border-gray-600">
                            {{ $article['date'] ?? 'Belum dipublish' }}
                        </div>
                    </div>

                    <!-- Excerpt -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="excerpt">
                            Excerpt
                        </label>
                        <textarea name="excerpt" id="excerpt" rows="3" placeholder="Ringkasan singkat artikel..."
                            class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-orange-300 dark:placeholder-gray-500">{{ $article['excerpt'] }}</textarea>
                    </div>

                    <!-- Meta SEO -->
                    <div class="p-4 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-700/50 dark:border-gray-600">
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-4">Konfigurasi SEO</h3>

                        <div class="space-y-4">
                            <!-- Meta Title -->
                            <div>
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1"
                                    for="meta_title">
                                    Meta Title
                                </label>
                                <input type="text" name="meta_title" id="meta_title" value="{{ $article['meta_title'] }}"
                                    placeholder="Judul untuk mesin pencari (opsional)"
                                    class="block w-full px-3 py-2 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-orange-300">
                            </div>

                            <!-- Meta Description -->
                            <div>
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1"
                                    for="meta_description">
                                    Meta Description
                                </label>
                                <textarea name="meta_description" id="meta_description" rows="2"
                                    placeholder="Deskripsi untuk mesin pencari (opsional)"
                                    class="block w-full px-3 py-2 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-orange-300">{{ $article['meta_description'] }}</textarea>
                            </div>

                            <!-- Meta Keywords -->
                            <div>
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1"
                                    for="meta_keywords">
                                    Meta Keywords
                                </label>
                                <input type="text" name="meta_keywords" id="meta_keywords"
                                    value="{{ $article['meta_keywords'] }}"
                                    placeholder="keyword1, keyword2, keyword3 (pisahkan dengan koma)"
                                    class="block w-full px-3 py-2 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-orange-300">
                            </div>
                        </div>
                    </div>

                    <!-- Konten -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="content">
                            Konten <span class="text-red-500">*</span>
                        </label>
                        <textarea name="content" id="editor" rows="10">{{ $article['content'] }}</textarea>
                    </div>

                    <!-- Unggah Foto -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Unggah Foto <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center justify-center w-full">
                            <label for="image"
                                class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500"
                                x-data="{ imagePreview: '{{ $article['image'] ?? null }}' }" @dragover.prevent
                                @drop.prevent="
                                           let file = $event.dataTransfer.files[0];
                                           if (file && file.type.startsWith('image/')) {
                                               let reader = new FileReader();
                                               reader.onload = (e) => { imagePreview = e.target.result };
                                               reader.readAsDataURL(file);
                                               $refs.imageInput.files = $event.dataTransfer.files;
                                           }
                                       ">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6" x-show="!imagePreview">
                                    <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                        </path>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="font-semibold">Seret dan lepas berkas, atau</span>
                                        <span
                                            class="text-orange-600 hover:text-orange-700 dark:text-orange-400">Telusuri</span>
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        Format: JPG, JPEG, PNG · Maks 5MB
                                    </p>
                                    <p class="text-xs text-orange-600 dark:text-orange-400 font-semibold mt-1">
                                        📐 Rekomendasi: 800 x 600 pixel atau 1200 x 675 pixel
                                    </p>
                                </div>
                                <div x-show="imagePreview" class="relative w-full h-full p-4">
                                    <img :src="imagePreview" alt="Preview" class="w-full h-full object-contain rounded-lg">
                                    <button type="button"
                                        @click.stop.prevent="imagePreview = null; $refs.imageInput.value = ''"
                                        class="absolute top-6 right-6 p-1.5 bg-red-500 text-white rounded-full hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                <input id="image" name="image" type="file" class="hidden"
                                    accept="image/jpeg,image/jpg,image/png" x-ref="imageInput" @change="
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

                    <!-- Buttons -->
                    <div class="flex flex-row justify-end items-end mt-6" style="gap: 16px;">
                        <a href="{{ route('admin.articles.index') }}"
                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali
                        </a>
                        <button type="submit" form="articleForm"
                            class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-orange-600 border border-transparent rounded-lg active:bg-orange-600 hover:bg-orange-700 focus:outline-none focus:shadow-outline-orange cursor-pointer">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span>Simpan Artikel</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/41.0.0/classic/ckeditor.js"></script>
    <script>
        (function () {
            // Custom upload adapter
            class MyUploadAdapter {
                constructor(loader) {
                    this.loader = loader;
                }
                upload() {
                    return this.loader.file.then(file => new Promise((resolve, reject) => {
                        const data = new FormData();
                        data.append('upload', file);
                        data.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                        fetch('{{ route("admin.articles.upload-image") }}', { method: 'POST', body: data })
                            .then(r => r.json())
                            .then(result => result.url ? resolve({ default: result.url }) : reject(result.error))
                            .catch(reject);
                    }));
                }
                abort() { }
            }

            function MyUploadAdapterPlugin(editor) {
                editor.plugins.get('FileRepository').createUploadAdapter = loader => new MyUploadAdapter(loader);
            }

            function initEditor() {
                const editorEl = document.querySelector('#editor');
                if (!editorEl) return;

                // Skip if already initialized
                if (editorEl.nextElementSibling && editorEl.nextElementSibling.classList.contains('ck-editor')) {
                    return;
                }

                ClassicEditor.create(editorEl, {
                    extraPlugins: [MyUploadAdapterPlugin],
                    toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'blockQuote', 'insertTable', '|', 'imageUpload', 'mediaEmbed', '|', 'undo', 'redo'],
                    language: 'id',
                    table: { contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells'] }
                }).then(editor => {
                    window.articleEditor = editor;
                    console.log('CKEditor initialized successfully');

                    // Form submit handler
                    const form = document.getElementById('articleForm');
                    if (form) {
                        form.addEventListener('submit', function (e) {
                            const content = editor.getData();
                            document.querySelector('textarea[name="content"]').value = content;

                            let errors = [];
                            const title = form.querySelector('input[name="title"]');
                            const categorySelect = form.querySelector('select[name="category"]');
                            const categoryInput = form.querySelector('input[name="category"]');
                            const status = form.querySelector('select[name="status"]');
                            const image = form.querySelector('input[name="image"]');

                            if (!title || !title.value.trim()) {
                                errors.push('Judul artikel harus diisi');
                            }
                            if ((!categorySelect || !categorySelect.value) && (!categoryInput || !categoryInput.value.trim())) {
                                errors.push('Kategori artikel harus diisi');
                            }
                            if (!status || !status.value) {
                                errors.push('Status artikel harus dipilih');
                            }
                            if (!image || !image.files[0]) {
                                errors.push('Gambar artikel harus diunggah');
                            } else {
                                const file = image.files[0];
                                if (file.size > 5 * 1024 * 1024) {
                                    errors.push('Ukuran gambar maksimal 5MB');
                                }
                                if (!['image/jpeg', 'image/jpg', 'image/png'].includes(file.type)) {
                                    errors.push('Format gambar harus JPG, JPEG, atau PNG');
                                }
                            }
                            if (!content.trim()) {
                                errors.push('Konten artikel harus diisi');
                            }

                            if (errors.length > 0) {
                                e.preventDefault();
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Validasi Gagal',
                                    html: '<ul style="text-align: left; padding-left: 20px;">' + errors.map(err => '<li>' + err + '</li>').join('') + '</ul>',
                                    confirmButtonColor: '#f97316'
                                });
                                return false;
                            }
                        });
                    }
                }).catch(error => {
                    console.error('CKEditor error:', error);
                });
            }

            // Initialize when DOM is ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initEditor);
            } else {
                initEditor();
            }
        })();
        
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
                    confirmButtonColor: '#f97316'
                });
            @endif

            @if($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    html: '<ul style="text-align: left; padding-left: 20px;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                    confirmButtonColor: '#f97316'
                });
            @endif
        });
    </script>

@endpush