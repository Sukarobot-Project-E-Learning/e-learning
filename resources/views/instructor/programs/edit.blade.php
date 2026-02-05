@extends('panel.layouts.app')

@section('title', 'Edit Pengajuan Program')

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-4 sm:py-8">
        <div class="container px-4 sm:px-6 mx-auto max-w-4xl">
            @include('panel.programs._form', [
                'submission' => $submission,
                'isEdit' => true,
                'isAdmin' => false,
                'locationData' => $locationData ?? null
            ])
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/elearning/css/program-form.css') }}?v={{ time() }}">
    @endpush

    @push('scripts')
        <script src="{{ asset('assets/elearning/region-selector.js') }}?v={{ time() }}"></script>
        <script src="{{ asset('assets/elearning/js/program-form.js') }}?v={{ time() }}"></script>
        
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
                        confirmButtonColor: '#3B82F6'
                    });
                @endif

                @if($errors->any())
                    Swal.fire({
                        icon: 'error',
                        title: 'Validasi Gagal',
                        html: '<ul style="text-align: left; padding-left: 20px;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                        confirmButtonColor: '#3B82F6'
                    });
                @endif
            });
        </script>
    @endpush
@endsection
