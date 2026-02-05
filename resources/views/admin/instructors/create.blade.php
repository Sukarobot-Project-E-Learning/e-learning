@extends('panel.layouts.app')

@section('title', 'Tambah Instruktur')

@section('content')

<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-4 sm:py-8">
    <div class="container px-4 sm:px-6 mx-auto max-w-3xl">

        <!-- Form Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl shadow-gray-200/50 dark:shadow-none overflow-hidden">
            @include('panel.instructors._form', [
                'instructor' => null, 
                'isEdit' => false
            ])
        </div>

    </div>
</div>

@endsection
