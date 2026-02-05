@extends('panel.layouts.app')

@section('title', 'Tambah Broadcast')

@section('content')

<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-4 sm:py-8">
    <div class="container px-4 sm:px-6 mx-auto max-w-3xl">

        <!-- Form Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl shadow-gray-200/50 dark:shadow-none overflow-hidden">
            <form id="broadcastForm" action="{{ route('admin.broadcasts.store') }}" method="POST">
                @csrf

                <!-- Section 1: Broadcast Message -->
                <div class="p-5 sm:p-8">
                    @include('panel.partials.forms.section-header', [
                        'title' => 'Pesan Broadcast',
                        'subtitle' => 'Kirim pesan ke semua pengguna',
                        'color' => 'green',
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>'
                    ])

                    <div class="space-y-5">
                        @include('panel.partials.forms.textarea', [
                            'name' => 'message',
                            'label' => 'Pesan',
                            'required' => true,
                            'placeholder' => 'Masukkan pesan broadcast...',
                            'rows' => 8
                        ])
                    </div>
                </div>

            </form>

            <!-- Action Buttons -->
            <div class="p-5 sm:p-8 pt-0 flex flex-row justify-end gap-4">
                <a href="{{ route('admin.broadcasts.index') }}"
                   class="inline-flex items-center gap-2 px-5 py-3 text-sm font-medium text-gray-700 bg-white border-2 border-gray-200 rounded-xl hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 transition-colors">
                    Kembali
                </a>
                <button type="submit"
                        form="broadcastForm"
                        class="inline-flex items-center gap-2 px-5 py-3 text-sm font-semibold text-white bg-green-600 rounded-xl hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Sebarkan Broadcast
                </button>
            </div>
        </div>

    </div>
</div>

@endsection
