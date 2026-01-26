@extends('panel.layouts.app')

@section('title', 'Edit Broadcast')

@section('content')

    <div class="container px-6 mx-auto">

        <!-- Page Header -->
        <div class="my-6">
            <div class="flex items-start justify-between">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Edit Broadcast</h2>
                <!-- Button moved to bottom -->
            </div>
        </div>

        <!-- Form Card -->
        <div class="w-full mb-8 overflow-hidden rounded-lg shadow-md bg-white dark:bg-gray-800">
            <form id="broadcastForm" action="{{ route('admin.broadcasts.update', $broadcast['id']) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="px-6 py-6 space-y-6">

                    <!-- Pesan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="message">
                            Pesan <span class="text-red-500">*</span>
                        </label>
                        <textarea name="message" id="message" rows="8" required
                                  class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-orange-300 dark:placeholder-gray-500">{{ $broadcast['message'] }}</textarea>
                    </div>

                    <!-- Buttons -->
                    <div class="flex flex-row justify-end items-end mt-6" style="gap: 16px;">
                        <a href="{{ route('admin.broadcasts.index') }}"
                           class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                            Kembali
                        </a>
                        <button type="submit" 
                                form="broadcastForm"
                                class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-lg active:bg-green-600 hover:bg-green-700 focus:outline-none focus:shadow-outline-green cursor-pointer">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Simpan Edit</span>
                        </button>
                    </div>

                </div>
            </form>
        </div>

    </div>

@endsection

