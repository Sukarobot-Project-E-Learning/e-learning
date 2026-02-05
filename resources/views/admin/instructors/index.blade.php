@extends('panel.layouts.app')

@section('title', 'Instruktur Management')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/elearning/admin/css/admin-table.css') }}">
@endpush

@section('content')
    <div class="container px-6 mx-auto overflow-x-hidden max-w-full">
        <x-admin.data-table :data="$data" />
    </div>

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ title: "{{ session('success') }}", icon: "success" });
                }
            });
        </script>
    @endif
    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ title: "Error!", text: "{{ session('error') }}", icon: "error" });
                }
            });
        </script>
    @endif
@endsection

@push('scripts')
    <script src="{{ asset('assets/elearning/admin/js/admin-table.js') }}"></script>
@endpush