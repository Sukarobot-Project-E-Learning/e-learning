@extends('panel.layouts.app')

@section('title', 'Tambah Voucher')

@section('content')

<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-4 sm:py-8">
    <div class="container px-4 sm:px-6 mx-auto max-w-3xl">

        <!-- Form Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl shadow-gray-200/50 dark:shadow-none overflow-hidden">
            <form id="voucherForm" action="{{ route('admin.vouchers.store') }}" method="POST">
                @csrf

                <!-- Section 1: Voucher Info -->
                <div class="p-5 sm:p-8">
                    @include('panel.partials.forms.section-header', [
                        'title' => 'Informasi Voucher',
                        'subtitle' => 'Detail voucher diskon',
                        'color' => 'orange',
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>'
                    ])

                    <div class="space-y-5">
                        <!-- Nama Voucher -->
                        @include('panel.partials.forms.input-text', [
                            'name' => 'name',
                            'label' => 'Nama Voucher',
                            'required' => true,
                            'placeholder' => 'Masukkan nama voucher'
                        ])

                        <!-- Diskon & Kode Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @include('panel.partials.forms.input-text', [
                                'name' => 'discount',
                                'label' => 'Diskon',
                                'required' => true,
                                'placeholder' => '10%'
                            ])

                            @include('panel.partials.forms.input-text', [
                                'name' => 'code',
                                'label' => 'Kode Voucher',
                                'required' => true,
                                'placeholder' => 'NCEFLAT20'
                            ])
                        </div>

                        <!-- Program/Event -->
                        @include('panel.partials.forms.input-text', [
                            'name' => 'program_event',
                            'label' => 'Program/Event',
                            'required' => true,
                            'placeholder' => 'Workshop Branding'
                        ])

                        <!-- Status -->
                        @include('panel.partials.forms.select', [
                            'name' => 'status',
                            'label' => 'Status',
                            'required' => true,
                            'placeholder' => 'Pilih Status',
                            'options' => [
                                'Aktif' => '✅ Aktif',
                                'Non-Aktif' => '❌ Non-Aktif'
                            ]
                        ])
                    </div>
                </div>

            </form>

            <!-- Action Buttons -->
            @include('panel.partials.forms.action-buttons', [
                'backUrl' => route('admin.vouchers.index'),
                'formId' => 'voucherForm',
                'submitText' => 'Simpan Voucher'
            ])
        </div>

    </div>
</div>

@endsection
