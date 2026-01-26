{{-- Global SweetAlert2 Configuration for Admin & Instructor Panels --}}
@php
    $role = request()->is('admin*') ? 'admin' : 'instructor';
    $primaryColor = $role === 'admin' ? '#F97316' : '#3B82F6';
    $primaryDarkColor = $role === 'admin' ? '#EA580C' : '#2563EB';
@endphp

<script>
    // Global SweetAlert2 Configuration
    window.SwalConfig = {
        role: '{{ $role }}',
        primaryColor: '{{ $primaryColor }}',

        // Success Toast (for create/update)
        successToast: function (title, text = '') {
            return Swal.fire({
                icon: 'success',
                title: title,
                text: text,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: document.documentElement.classList.contains('dark') ? '#1F2937' : '#FFFFFF',
                color: document.documentElement.classList.contains('dark') ? '#F3F4F6' : '#111827',
                customClass: {
                    popup: 'rounded-xl shadow-lg'
                }
            });
        },

        // Error Toast
        errorToast: function (title, text = '') {
            return Swal.fire({
                icon: 'error',
                title: title,
                text: text,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                background: document.documentElement.classList.contains('dark') ? '#1F2937' : '#FFFFFF',
                color: document.documentElement.classList.contains('dark') ? '#F3F4F6' : '#111827',
                customClass: {
                    popup: 'rounded-xl shadow-lg'
                }
            });
        },

        // Delete Confirmation
        confirmDelete: function (itemName = 'data ini') {
            return Swal.fire({
                title: 'Konfirmasi Hapus',
                html: `<p class="text-gray-600 dark:text-gray-400">Apakah Anda yakin ingin menghapus <strong>${itemName}</strong>?</p><p class="text-sm text-red-500 mt-2">Tindakan ini tidak dapat dibatalkan.</p>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                confirmButtonText: '<i class="mr-1">🗑️</i> Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                focusCancel: true,
                background: document.documentElement.classList.contains('dark') ? '#1F2937' : '#FFFFFF',
                color: document.documentElement.classList.contains('dark') ? '#F3F4F6' : '#111827',
                customClass: {
                    popup: 'rounded-2xl shadow-2xl',
                    confirmButton: 'rounded-xl px-5 py-2.5 font-medium shadow-lg',
                    cancelButton: 'rounded-xl px-5 py-2.5 font-medium'
                }
            });
        },

        // Generic Confirmation
        confirm: function (title, text, confirmText = 'Ya, Lanjutkan') {
            const primaryColor = this.role === 'admin' ? '#F97316' : '#3B82F6';
            return Swal.fire({
                title: title,
                text: text,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: primaryColor,
                cancelButtonColor: '#6B7280',
                confirmButtonText: confirmText,
                cancelButtonText: 'Batal',
                reverseButtons: true,
                background: document.documentElement.classList.contains('dark') ? '#1F2937' : '#FFFFFF',
                color: document.documentElement.classList.contains('dark') ? '#F3F4F6' : '#111827',
                customClass: {
                    popup: 'rounded-2xl shadow-2xl',
                    confirmButton: 'rounded-xl px-5 py-2.5 font-medium shadow-lg',
                    cancelButton: 'rounded-xl px-5 py-2.5 font-medium'
                }
            });
        },

        // Validation Error Alert
        validationError: function (errors) {
            let errorList = '';
            if (Array.isArray(errors)) {
                errorList = '<ul class="text-left text-sm space-y-1 mt-3">' +
                    errors.map(e => `<li class="flex items-start gap-2"><span class="text-red-500">•</span>${e}</li>`).join('') +
                    '</ul>';
            } else if (typeof errors === 'string') {
                errorList = `<p class="text-sm mt-2">${errors}</p>`;
            }

            return Swal.fire({
                title: 'Validasi Gagal',
                html: `<p class="text-gray-600 dark:text-gray-400">Mohon periksa kembali data yang Anda masukkan:</p>${errorList}`,
                icon: 'error',
                confirmButtonColor: this.role === 'admin' ? '#F97316' : '#3B82F6',
                confirmButtonText: 'Perbaiki',
                background: document.documentElement.classList.contains('dark') ? '#1F2937' : '#FFFFFF',
                color: document.documentElement.classList.contains('dark') ? '#F3F4F6' : '#111827',
                customClass: {
                    popup: 'rounded-2xl shadow-2xl',
                    confirmButton: 'rounded-xl px-5 py-2.5 font-medium shadow-lg'
                }
            });
        }
    };

    // Auto-show session flash messages
    document.addEventListener('DOMContentLoaded', function () {
        @if(session('success'))
            SwalConfig.successToast('Berhasil!', '{{ session('success') }}');
        @endif

        @if(session('error'))
            SwalConfig.errorToast('Gagal!', '{{ session('error') }}');
        @endif

            @if($errors->any())
                const errorMessages = @json($errors->all());
                SwalConfig.validationError(errorMessages);
            @endif
    });

    // Helper function for delete forms
    function confirmDeleteForm(form, itemName = 'data ini') {
        SwalConfig.confirmDelete(itemName).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
        return false;
    }
</script>