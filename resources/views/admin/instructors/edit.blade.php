@extends('panel.layouts.app')

@section('title', 'Edit Instruktur')

@section('content')

@php
    $avatarUrl = null;
    if (!empty($instructor->photo)) {
        $avatarUrl = str_starts_with($instructor->photo, 'images/') 
            ? asset($instructor->photo) 
            : asset('storage/' . $instructor->photo);
    }
@endphp

<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-4 sm:py-8">
    <div class="container px-4 sm:px-6 mx-auto max-w-3xl">

        <!-- Form Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl shadow-gray-200/50 dark:shadow-none overflow-hidden">
            <form id="instructorForm" data-instructor-form action="{{ route('admin.instructors.update', $instructor->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Section 1: Account Information -->
                <div class="p-5 sm:p-8 border-b border-gray-200 dark:border-gray-700">
                    @include('panel.partials.forms.section-header', [
                        'title' => 'Informasi Akun Instruktur',
                        'subtitle' => 'Detail dasar akun instruktur',
                        'color' => 'orange',
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>'
                    ])

                    <div class="space-y-5">
                        <!-- Status -->
                        @include('panel.partials.forms.select', [
                            'name' => 'status',
                            'label' => 'Status',
                            'required' => true,
                            'value' => old('status', $instructor->status ?? 'Aktif'),
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
                                'value' => old('name', $instructor->name ?? ''),
                                'placeholder' => 'Masukkan nama instruktur'
                            ])

                            @include('panel.partials.forms.input-text', [
                                'name' => 'username',
                                'label' => 'Username',
                                'required' => true,
                                'value' => old('username', $instructor->username ?? ''),
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
                                'value' => old('email', $instructor->email ?? ''),
                                'placeholder' => 'instructor@example.com',
                                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>'
                            ])

                            @include('panel.partials.forms.input-text', [
                                'name' => 'phone',
                                'type' => 'tel',
                                'label' => 'Nomor Telepon',
                                'required' => true,
                                'value' => old('phone', $instructor->phone ?? ''),
                                'placeholder' => '08xxxxxxxxxx',
                                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>'
                            ])
                        </div>
                    </div>
                </div>

                <!-- Section 2: Professional Info -->
                <div class="p-5 sm:p-8 border-b border-gray-200 dark:border-gray-700">
                    @include('panel.partials.forms.section-header', [
                        'title' => 'Informasi Profesional',
                        'subtitle' => 'Detail keahlian dan pengalaman',
                        'color' => 'blue',
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>'
                    ])

                    <div class="space-y-5">
                        <!-- Expertise with Dynamic Select -->
                        @include('panel.partials.forms.dynamic-select', [
                            'name' => 'expertise',
                            'label' => 'Keahlian',
                            'required' => true,
                            'value' => old('expertise', $instructor->expertise ?? ''),
                            'options' => [
                                'Web Development' => 'Web Development',
                                'Mobile Development' => 'Mobile Development',
                                'Data Science' => 'Data Science',
                                'Digital Marketing' => 'Digital Marketing',
                                'UI/UX Design' => 'UI/UX Design',
                                'Project Management' => 'Project Management',
                                'Business Analytics' => 'Business Analytics'
                            ],
                            'placeholder' => 'Pilih Keahlian',
                            'addNewText' => '➕ Tambah Keahlian Baru...',
                            'customPlaceholder' => 'Masukkan keahlian baru...'
                        ])

                        <!-- Job & Experience Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @include('panel.partials.forms.input-text', [
                                'name' => 'job',
                                'label' => 'Pekerjaan',
                                'required' => true,
                                'value' => old('job', $instructor->job ?? ''),
                                'placeholder' => 'Contoh: Web Developer, UI/UX Designer'
                            ])

                            @include('panel.partials.forms.input-text', [
                                'name' => 'experience',
                                'label' => 'Pengalaman',
                                'required' => true,
                                'value' => old('experience', $instructor->experience ?? ''),
                                'placeholder' => 'Contoh: 5 Tahun, 10 Tahun'
                            ])
                        </div>

                        <!-- Bio -->
                        @include('panel.partials.forms.textarea', [
                            'name' => 'bio',
                            'label' => 'Bio',
                            'required' => true,
                            'value' => old('bio', $instructor->bio ?? ''),
                            'placeholder' => 'Masukkan bio instruktur',
                            'rows' => 4
                        ])
                    </div>
                </div>

                <!-- Section 3: Security -->
                <div class="p-5 sm:p-8 border-b border-gray-200 dark:border-gray-700">
                    @include('panel.partials.forms.section-header', [
                        'title' => 'Keamanan',
                        'subtitle' => 'Ubah password instruktur (opsional)',
                        'color' => 'red',
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>'
                    ])

                    <div class="space-y-5">
                        @include('panel.partials.forms.input-password', [
                            'name' => 'password',
                            'label' => 'Password Baru',
                            'labelHint' => '(Kosongkan jika tidak ingin mengubah)',
                            'placeholder' => 'Masukkan password baru',
                            'help' => 'Password minimal 8 karakter'
                        ])

                        @include('panel.partials.forms.input-password', [
                            'name' => 'password_confirmation',
                            'label' => 'Konfirmasi Password Baru',
                            'placeholder' => 'Konfirmasi password baru'
                        ])
                    </div>
                </div>

                <!-- Section 4: Profile Photo -->
                <div class="p-5 sm:p-8">
                    @include('panel.partials.forms.section-header', [
                        'title' => 'Foto Profil',
                        'subtitle' => 'Upload foto untuk profil instruktur',
                        'color' => 'purple',
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>'
                    ])

                    @include('panel.partials.forms.image-cropper', [
                        'name' => 'cropped_photo',
                        'id' => 'photo',
                        'maxSize' => 2,
                        'currentImage' => $avatarUrl,
                        'currentImageAlt' => 'Foto ' . $instructor->name
                    ])
                </div>

            </form>

            <!-- Action Buttons -->
            @include('panel.partials.forms.action-buttons', [
                'backUrl' => route('admin.instructors.index'),
                'formId' => 'instructorForm',
                'submitText' => 'Simpan Perubahan'
            ])
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('assets/elearning/admin/js/components/image-cropper.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/elearning/admin/js/components/password-validator.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/elearning/admin/js/components/dynamic-select.js') }}?v={{ time() }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Image Cropper
    const cropperContainers = document.querySelectorAll('[data-image-cropper]');
    cropperContainers.forEach(container => {
        new ImageCropper(container);
    });

    // Initialize Password Validation (optional for edit)
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');
    if (passwordInput && confirmInput) {
        new PasswordValidator(passwordInput, confirmInput, {
            minLength: 8,
            required: false
        });
    }

    // Form Validation
    const form = document.getElementById('instructorForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            
            // Only validate if password is being changed
            if (password) {
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
            }
        });
    }
});
</script>
@endpush
