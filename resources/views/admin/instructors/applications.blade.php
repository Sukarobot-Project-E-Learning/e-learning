@extends('panel.layouts.app')

@section('title', 'Pengajuan Instruktur')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/elearning/admin/css/admin-table.css') }}">
@endpush

@section('content')
    <div class="container px-6 mx-auto overflow-x-hidden max-w-full">
        <x-admin.data-table :data="$data" />
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/elearning/admin/js/admin-table.js') }}"></script>
    <script src="{{ asset('assets/elearning/admin/js/components/bulk-actions.js') }}"></script>
@endpush
