@extends('panel.layouts.app')

@section('title', 'Tambah User')

@section('content')

<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-4 sm:py-8">
    <div class="container px-4 sm:px-6 mx-auto max-w-3xl">

        <!-- Form Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl shadow-gray-200/50 dark:shadow-none overflow-hidden">
            <form id="userForm" action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Section 1: Account Information -->
                <div class="p-5 sm:p-8 border-b border-gray-200 dark:border-gray-700">
                    @include('panel.partials.forms.section-header', [
                        'title' => 'Informasi Akun',
                        'subtitle' => 'Detail dasar akun pengguna',
                        'color' => 'orange',
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>'
                    ])

                    <div class="space-y-5">
                        <!-- Status -->
                        @include('panel.partials.forms.select', [
                            'name' => 'status',
                            'label' => 'Status',
                            'required' => true,
                            'value' => old('status', 'Aktif'),
                            'options' => [
                                'Aktif' => '✅ Aktif',
                                'Non-Aktif' => '❌ Non-Aktif'
                            ]
                        ])

                        <!-- Nama & Username Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @include('panel.partials.forms.input-text', [
                                'name' => 'name',
                                'label' => 'Nama Lengkap',
                                'required' => true,
                                'placeholder' => 'Masukkan nama lengkap'
                            ])

                            @include('panel.partials.forms.input-text', [
                                'name' => 'username',
                                'label' => 'Username',
                                'required' => true,
                                'placeholder' => 'Masukkan username'
                            ])
                        </div>

                        <!-- Email & Phone Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @include('panel.partials.forms.input-text', [
                                'name' => 'email',
                                'type' => 'email',
                                'label' => 'Email',
                                'required' => true,
                                'placeholder' => 'user@example.com',
                                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>'
                            ])

                            @include('panel.partials.forms.input-text', [
                                'name' => 'phone',
                                'type' => 'tel',
                                'label' => 'Nomor Telepon',
                                'required' => true,
                                'placeholder' => '08xxxxxxxxxx',
                                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>'
                            ])
                        </div>
                    </div>
                </div>

                <!-- Section 2: Additional Info -->
                <div class="p-5 sm:p-8 border-b border-gray-200 dark:border-gray-700">
                    @include('panel.partials.forms.section-header', [
                        'title' => 'Informasi Tambahan',
                        'subtitle' => 'Data opsional pengguna',
                        'color' => 'blue',
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>'
                    ])

                    <div class="space-y-5">
                        <!-- Pekerjaan & Negara Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @include('panel.partials.forms.input-text', [
                                'name' => 'job',
                                'label' => 'Pekerjaan',
                                'placeholder' => 'Masukkan pekerjaan'
                            ])

                            @include('panel.partials.forms.input-text', [
                                'name' => 'country',
                                'label' => 'Negara',
                                'value' => old('country', 'Indonesia'),
                                'placeholder' => 'Masukkan negara'
                            ])
                        </div>

                        <!-- Alamat -->
                        @include('panel.partials.forms.textarea', [
                            'name' => 'address',
                            'label' => 'Alamat',
                            'rows' => 3,
                            'placeholder' => 'Masukkan alamat lengkap'
                        ])
                    </div>
                </div>

                <!-- Section 3: Security -->
                <div class="p-5 sm:p-8 border-b border-gray-200 dark:border-gray-700">
                    @include('panel.partials.forms.section-header', [
                        'title' => 'Keamanan',
                        'subtitle' => 'Password akun pengguna',
                        'color' => 'red',
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>'
                    ])

                    <div class="space-y-5">
                        <!-- Password Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @include('panel.partials.forms.input-password', [
                                'name' => 'password',
                                'label' => 'Password',
                                'required' => true,
                                'placeholder' => 'Minimal 8 karakter',
                                'help' => 'Password minimal 8 karakter',
                                'showValidation' => true
                            ])

                            @include('panel.partials.forms.input-password', [
                                'name' => 'password_confirmation',
                                'label' => 'Konfirmasi Password',
                                'required' => true,
                                'placeholder' => 'Ulangi password',
                                'showValidation' => true
                            ])
                        </div>
                    </div>
                </div>

                <!-- Section 4: Photo Upload -->
                <div class="p-5 sm:p-8">
                    @include('panel.partials.forms.section-header', [
                        'title' => 'Foto Profil',
                        'subtitle' => 'Upload foto pengguna',
                        'color' => 'purple',
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>'
                    ])

                    @include('panel.partials.forms.image-cropper', [
                        'name' => 'cropped_photo',
                        'id' => 'photo',
                        'maxSize' => 2
                    ])
                </div>

                <!-- Navigation Footer -->
                @include('panel.partials.forms.action-buttons', [
                    'backUrl' => route('admin.users.index'),
                    'backText' => 'Kembali',
                    'submitText' => 'Simpan User'
                ])

            </form>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('assets/elearning/admin/js/components/image-cropper.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/elearning/admin/js/components/password-validator.js') }}?v={{ time() }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Image Cropper
    const cropperContainers = document.querySelectorAll('[data-image-cropper]');
    cropperContainers.forEach(container => {
        new ImageCropper(container);
    });

    // Initialize Password Validation
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');
    if (passwordInput && confirmInput) {
        new PasswordValidator(passwordInput, confirmInput, {
            minLength: 8,
            required: true
        });
    }

    // Form Validation
    const form = document.getElementById('userForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            
            if (password.length < 8) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    text: 'Password minimal harus 8 karakter!'
                });
                return;
            }

            if (password !== confirmPassword) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    text: 'Konfirmasi password tidak cocok!'
                });
                return;
            }
        });
    }
});
</script>
@endpush