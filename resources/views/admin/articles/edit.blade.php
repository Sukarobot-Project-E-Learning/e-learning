@extends('panel.layouts.app')

@section('title', 'Edit Artikel')

@section('content')

@php
    $categoryOptions = [];
    foreach($categories ?? ['Riset & AI', 'Produk', 'Event'] as $cat) {
        $categoryOptions[$cat] = $cat;
    }
@endphp

<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-4 sm:py-8">
    <div class="container px-4 sm:px-6 mx-auto max-w-4xl">

        <!-- Form Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl shadow-gray-200/50 dark:shadow-none overflow-hidden">
            <form id="articleForm" action="{{ route('admin.articles.update', $article['id']) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Section 1: Article Info -->
                <div class="p-5 sm:p-8 border-b border-gray-200 dark:border-gray-700">
                    @include('panel.partials.forms.section-header', [
                        'title' => 'Informasi Artikel',
                        'subtitle' => 'Detail dasar artikel',
                        'color' => 'orange',
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>'
                    ])

                    <div class="space-y-5">
                        <!-- Status -->
                        @include('panel.partials.forms.select', [
                            'name' => 'status',
                            'label' => 'Status',
                            'required' => true,
                            'value' => old('status', $article['status']),
                            'placeholder' => 'Pilih Status',
                            'options' => [
                                'Publish' => '✅ Publish',
                                'Draft' => '📝 Draft'
                            ]
                        ])

                        <!-- Title -->
                        @include('panel.partials.forms.input-text', [
                            'name' => 'title',
                            'label' => 'Judul',
                            'required' => true,
                            'value' => old('title', $article['title']),
                            'placeholder' => 'Lengan Robotik Baru untuk Industri Otomotif'
                        ])

                        <!-- Category with Dynamic Select -->
                        @include('panel.partials.forms.dynamic-select', [
                            'name' => 'category',
                            'label' => 'Kategori',
                            'required' => true,
                            'value' => old('category', $article['category']),
                            'options' => $categoryOptions,
                            'placeholder' => 'Pilih Kategori',
                            'addNewText' => '➕ Tambah Kategori Baru...',
                            'customPlaceholder' => 'Ketik nama kategori baru'
                        ])

                        <!-- Tanggal (Info Only) -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tanggal Publish
                            </label>
                            <div class="block w-full px-4 py-3.5 text-sm text-gray-600 bg-gray-100 dark:bg-gray-700 dark:text-gray-400 border-2 border-gray-200 dark:border-gray-600 rounded-xl">
                                {{ $article['date'] ?? 'Belum dipublish' }}
                            </div>
                        </div>

                        <!-- Excerpt -->
                        @include('panel.partials.forms.textarea', [
                            'name' => 'excerpt',
                            'label' => 'Excerpt',
                            'value' => old('excerpt', $article['excerpt']),
                            'placeholder' => 'Ringkasan singkat artikel...',
                            'rows' => 3
                        ])
                    </div>
                </div>

                <!-- Section 2: SEO Config -->
                <div class="p-5 sm:p-8 border-b border-gray-200 dark:border-gray-700">
                    @include('panel.partials.forms.section-header', [
                        'title' => 'Konfigurasi SEO',
                        'subtitle' => 'Optimasi untuk mesin pencari',
                        'color' => 'green',
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>'
                    ])

                    <div class="space-y-5">
                        @include('panel.partials.forms.input-text', [
                            'name' => 'meta_title',
                            'label' => 'Meta Title',
                            'value' => old('meta_title', $article['meta_title']),
                            'placeholder' => 'Judul untuk mesin pencari (opsional)'
                        ])

                        @include('panel.partials.forms.textarea', [
                            'name' => 'meta_description',
                            'label' => 'Meta Description',
                            'value' => old('meta_description', $article['meta_description']),
                            'placeholder' => 'Deskripsi untuk mesin pencari (opsional)',
                            'rows' => 2
                        ])

                        @include('panel.partials.forms.input-text', [
                            'name' => 'meta_keywords',
                            'label' => 'Meta Keywords',
                            'value' => old('meta_keywords', $article['meta_keywords']),
                            'placeholder' => 'keyword1, keyword2, keyword3 (pisahkan dengan koma)'
                        ])
                    </div>
                </div>

                <!-- Section 3: Content -->
                <div class="p-5 sm:p-8 border-b border-gray-200 dark:border-gray-700">
                    @include('panel.partials.forms.section-header', [
                        'title' => 'Konten Artikel',
                        'subtitle' => 'Isi artikel dengan editor',
                        'color' => 'blue',
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>'
                    ])

                    <div class="space-y-5">
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="editor">
                                Konten <span class="text-orange-500">*</span>
                            </label>
                            <textarea name="content" id="editor" rows="10">{{ $article['content'] }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Section 4: Featured Image -->
                <div class="p-5 sm:p-8">
                    @include('panel.partials.forms.section-header', [
                        'title' => 'Gambar Artikel',
                        'subtitle' => 'Upload gambar utama artikel',
                        'color' => 'purple',
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>'
                    ])

                    @include('panel.partials.forms.image-upload-simple', [
                        'name' => 'image',
                        'maxSize' => 5,
                        'currentImage' => $article['image'] ?? null,
                        'aspectHint' => '800 x 600 pixel atau 1200 x 675 pixel'
                    ])
                </div>

            </form>

            <!-- Action Buttons -->
            @include('panel.partials.forms.action-buttons', [
                'backUrl' => route('admin.articles.index'),
                'formId' => 'articleForm',
                'submitText' => 'Simpan Artikel'
            ])
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/41.0.0/classic/ckeditor.js"></script>
<script src="{{ asset('assets/elearning/admin/js/components/dynamic-select.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/elearning/admin/js/components/simple-image-upload.js') }}?v={{ time() }}"></script>
<script>
(function() {
    // Custom upload adapter for CKEditor
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
        abort() {}
    }

    function MyUploadAdapterPlugin(editor) {
        editor.plugins.get('FileRepository').createUploadAdapter = loader => new MyUploadAdapter(loader);
    }

    function initEditor() {
        const editorEl = document.querySelector('#editor');
        if (!editorEl) return;
        
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
            
            const form = document.getElementById('articleForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const content = editor.getData();
                    document.querySelector('textarea[name="content"]').value = content;

                    let errors = [];
                    const title = form.querySelector('input[name="title"]');
                    const category = form.querySelector('input[name="category"]') || form.querySelector('select[name="category"]');
                    const status = form.querySelector('select[name="status"]');

                    if (!title || !title.value.trim()) {
                        errors.push('Judul artikel harus diisi');
                    }
                    if (!category || !category.value.trim()) {
                        errors.push('Kategori artikel harus diisi');
                    }
                    if (!status || !status.value) {
                        errors.push('Status artikel harus dipilih');
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

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initEditor);
    } else {
        initEditor();
    }
})();
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
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
