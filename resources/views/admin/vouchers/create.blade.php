@extends('admin.layouts.app')

@section('title', 'Tambah Voucher')

@section('content')

    <div class="container px-6 mx-auto">

        <!-- Page Header -->
        <div class="my-6">
            <div class="flex items-start justify-between">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Tambah Voucher</h2>
                <div class="flex flex-col items-end" style="gap: 16px;">
                    <a href="{{ route('elearning.admin.vouchers.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                    <button type="submit" 
                            form="voucherForm"
                            class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-lg active:bg-green-600 hover:bg-green-700 focus:outline-none focus:shadow-outline-green">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Simpan Voucher</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="w-full mb-8 overflow-hidden rounded-lg shadow-md bg-white dark:bg-gray-800">
            <form id="voucherForm" action="{{ route('elearning.admin.vouchers.store') }}" method="POST">
                @csrf
                <div class="px-6 py-6 space-y-6">

                    <!-- Nama Voucher -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="name">
                            Nama Voucher <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" required
                               placeholder="Masukkan nama voucher"
                               class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring focus:ring-purple-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-purple-300 dark:placeholder-gray-500">
                    </div>

                    <!-- Diskon -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="discount">
                            Diskon <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="discount" id="discount" required
                               placeholder="10%"
                               class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring focus:ring-purple-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-purple-300 dark:placeholder-gray-500">
                    </div>

                    <!-- Program/Event -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="program_event">
                            Program/Event <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="program_event" id="program_event" required
                               placeholder="Workshop Branding"
                               class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring focus:ring-purple-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-purple-300 dark:placeholder-gray-500">
                    </div>

                    <!-- Kode -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="code">
                            Kode <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="code" id="code" required
                               placeholder="NCEFLAT20"
                               class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring focus:ring-purple-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-purple-300 dark:placeholder-gray-500">
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="status">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" id="status" required
                                class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:ring focus:ring-purple-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-purple-300">
                            <option value="">Pilih Status</option>
                            <option value="Aktif">Aktif</option>
                            <option value="Non-Aktif">Non-Aktif</option>
                        </select>
                    </div>

                </div>
            </form>
        </div>

    </div>

@endsection

