@extends('panel.layouts.app')

@section('title', 'Tambah Promo')

@section('content')

<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-4 sm:py-8">
    <div class="container px-4 sm:px-6 mx-auto max-w-3xl">

        <!-- Form Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl shadow-gray-200/50 dark:shadow-none overflow-hidden">
            <form id="promoForm" action="{{ route('admin.promos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Section 1: Informasi Dasar -->
                <div class="p-5 sm:p-8 border-b border-gray-200 dark:border-gray-700">
                    @include('panel.partials.forms.section-header', [
                        'title' => 'Informasi Dasar',
                        'subtitle' => 'Detail informasi promo',
                        'color' => 'blue',
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
                    ])

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        @include('panel.partials.forms.input-text', [
                            'name' => 'title',
                            'label' => 'Judul Promo',
                            'placeholder' => 'Masukkan judul promo',
                            'required' => true,
                        ])

                        @include('panel.partials.forms.select', [
                            'name' => 'is_active',
                            'label' => 'Status Promo',
                            'options' => [
                                '1' => 'Aktif',
                                '0' => 'Non-Aktif',
                            ],
                            'required' => true,
                        ])
                    </div>
                </div>

                <!-- Section 2: Poster -->
                <div class="p-5 sm:p-8 border-b border-gray-200 dark:border-gray-700">
                    @include('panel.partials.forms.section-header', [
                        'title' => 'Poster Promo',
                        'subtitle' => 'Upload gambar poster promo',
                        'color' => 'orange',
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>'
                    ])

                    @include('panel.partials.forms.image-upload-simple', [
                        'name' => 'poster',
                        'label' => 'Upload Poster',
                        'required' => true,
                        'maxSize' => 5,
                        'aspectHint' => 'Ukuran rekomendasi untuk poster'
                    ])
                </div>



            </form>

            <!-- Action Buttons -->
            @include('panel.partials.forms.action-buttons', [
                'backUrl' => route('admin.promos.index'),
                'formId' => 'promoForm',
                'submitText' => 'Simpan Promo'
            ])
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('assets/elearning/admin/js/components/simple-image-upload.js') }}?v={{ time() }}"></script>
@endpush

