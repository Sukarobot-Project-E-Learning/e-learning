{{-- Global SweetAlert2 Configuration for Admin & Instructor Panels --}}
@php
    $role = request()->is('admin*') ? 'admin' : 'instructor';
    $primaryColor = $role === 'admin' ? '#F97316' : '#3B82F6';
@endphp

<script>
    // Global SweetAlert2 Configuration
    window.SwalConfig = {
        role: '{{ $role }}',
        primaryColor: '{{ $primaryColor }}',

        // Success Alert
        success: function (title, text = '') {
            return Swal.fire({
                icon: 'success',
                title: title,
                text: text,
                confirmButtonColor: this.primaryColor
            });
        },

        // Error Alert
        error: function (title, text = '') {
            return Swal.fire({
                icon: 'error',
                title: title,
                text: text,
                confirmButtonColor: this.primaryColor
            });
        },

        // Delete Confirmation
        confirmDelete: function (itemName = 'data ini') {
            return Swal.fire({
                title: 'Konfirmasi Hapus',
                text: `Apakah Anda yakin ingin menghapus ${itemName}? Tindakan ini tidak dapat dibatalkan.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            });
        },

        // Generic Confirmation
        confirm: function (title, text, confirmText = 'Ya, Lanjutkan') {
            return Swal.fire({
                title: title,
                text: text,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: this.primaryColor,
                cancelButtonColor: '#6B7280',
                confirmButtonText: confirmText,
                cancelButtonText: 'Batal'
            });
        },

        // Validation Error Alert
        validationError: function (errors) {
            let errorText = '';
            if (Array.isArray(errors)) {
                errorText = errors.join('\n• ');
                errorText = '• ' + errorText;
            } else if (typeof errors === 'string') {
                errorText = errors;
            }

            return Swal.fire({
                title: 'Validasi Gagal',
                text: `Mohon periksa kembali data yang Anda masukkan:\n\n${errorText}`,
                icon: 'error',
                confirmButtonColor: this.primaryColor,
                confirmButtonText: 'Perbaiki'
            });
        }
    };

    // Handle Flash Messages on Page Load
    @php
        $isProgramForm = request()->is('*/programs/create') || request()->is('*/programs/*/edit');
    @endphp
    
    @unless($isProgramForm)
    document.addEventListener('DOMContentLoaded', function () {
        // Flash Messages
        @if(session('success'))
            SwalConfig.success('Berhasil!', '{{ session('success') }}');
        @endif

        @if(session('error'))
            SwalConfig.error('Gagal!', '{{ session('error') }}');
        @endif

        @if($errors->any())
            const errorMessages = @json($errors->all());
            SwalConfig.validationError(errorMessages);
        @endif
    });
    @endunless

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