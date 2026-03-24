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
                        'subtitle' => 'Detail pengaturan promosi diskon',
                        'color' => 'orange',
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>'
                    ])

                    <div class="space-y-5">
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Nama Voucher -->
                            @include('panel.partials.forms.input-text', [
                                'name' => 'name',
                                'label' => 'Nama Voucher',
                                'required' => true,
                                'placeholder' => 'Misal: Promo Nyepi 2026'
                            ])

                            @include('panel.partials.forms.input-text', [
                                'name' => 'code',
                                'label' => 'Kode Voucher (Unik)',
                                'required' => true,
                                'placeholder' => 'Misal: NYEPI26'
                            ])
                        </div>

                        <!-- Diskon Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @include('panel.partials.forms.select', [
                                'name' => 'discount_type',
                                'label' => 'Tipe Diskon',
                                'required' => true,
                                'options' => [
                                    'percentage' => 'Persentase (%)',
                                    'fixed' => 'Potongan Tetap (Rp)'
                                ],
                                'selected' => 'percentage'
                            ])

                            @include('panel.partials.forms.input-text', [
                                'type' => 'number',
                                'name' => 'discount_value',
                                'label' => 'Nilai Diskon',
                                'required' => true,
                                'placeholder' => 'Misal: 10 atau 50000',
                                'attributes' => 'min="0"'
                            ])
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @include('panel.partials.forms.input-text', [
                                'type' => 'date',
                                'name' => 'start_date',
                                'label' => 'Tanggal Berlaku (Mulai)',
                                'required' => false
                            ])

                            @include('panel.partials.forms.input-text', [
                                'type' => 'date',
                                'name' => 'end_date',
                                'label' => 'Tanggal Berlaku (Selesai)',
                                'required' => false
                            ])
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @include('panel.partials.forms.input-text', [
                                'type' => 'number',
                                'name' => 'max_usages',
                                'label' => 'Batas Penggunaan (Opsional)',
                                'required' => false,
                                'placeholder' => 'Misal: 100',
                                'attributes' => 'min="1"'
                            ])

                            @include('panel.partials.forms.select', [
                                'name' => 'is_active',
                                'label' => 'Status Voucher',
                                'required' => true,
                                'options' => [
                                    '1' => 'Aktif',
                                    '0' => 'Non-Aktif'
                                ],
                                'selected' => '1'
                            ])
                        </div>

                    </div>
                </div>

                <!-- Action Buttons -->
                @include('panel.partials.forms.action-buttons', [
                    'backUrl' => route('admin.vouchers.index'),
                    'formId' => 'voucherForm',
                    'submitText' => 'Simpan Voucher'
                ])
            </form>
        </div>

    </div>
</div>

@endsection
