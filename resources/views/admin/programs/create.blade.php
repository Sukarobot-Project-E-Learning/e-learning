@extends('panel.layouts.app')

@section('title', 'Ajukan Program Baru')

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-4 sm:py-8">
        <div class="container px-4 sm:px-6 mx-auto max-w-4xl">
            @include('panel.programs._form', [
                'program' => null,
                'isEdit' => false,
                'isAdmin' => true,
                'instructors' => $instructors ?? []
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
            });
        </script>
    @endpush
@endsection
