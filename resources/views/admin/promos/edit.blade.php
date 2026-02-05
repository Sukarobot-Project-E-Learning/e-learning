@extends('panel.layouts.app')

@section('title', 'Edit Promo')

@section('content')

<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-4 sm:py-8">
    <div class="container px-4 sm:px-6 mx-auto max-w-3xl">

        <!-- Form Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl shadow-gray-200/50 dark:shadow-none overflow-hidden">
            <form id="promoForm" action="{{ route('admin.promos.update', $promo['id']) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Section 1: Poster -->
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
                        'required' => false,
                        'maxSize' => 5,
                        'aspectHint' => 'Ukuran rekomendasi untuk poster',
                        'currentImage' => $promo['poster'] ?? null,
                        'currentImageAlt' => 'Poster saat ini'
                    ])
                </div>

                <!-- Section 2: Carousel -->
                <div class="p-5 sm:p-8">
                    @include('panel.partials.forms.section-header', [
                        'title' => 'Carousel Promo',
                        'subtitle' => 'Upload gambar untuk carousel',
                        'color' => 'blue',
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path>'
                    ])

                    @include('panel.partials.forms.image-upload-simple', [
                        'name' => 'carousel',
                        'label' => 'Upload Carousel',
                        'required' => false,
                        'maxSize' => 5,
                        'aspectHint' => 'Ukuran rekomendasi untuk carousel',
                        'currentImage' => $promo['carousel'] ?? null,
                        'currentImageAlt' => 'Carousel saat ini'
                    ])
                </div>

            </form>

            <!-- Action Buttons -->
            @include('panel.partials.forms.action-buttons', [
                'backUrl' => route('admin.promos.index'),
                'formId' => 'promoForm',
                'submitText' => 'Update Promo'
            ])
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('assets/elearning/admin/js/components/simple-image-upload.js') }}?v={{ time() }}"></script>
@endpush

