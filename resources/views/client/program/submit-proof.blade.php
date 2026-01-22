@extends('client.main')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    @keyframes fade-in-up {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .animate-fade-in-up {
        animation: fade-in-up 0.3s ease-out forwards;
    }
</style>
@endsection

@section('body')
<div class="min-h-screen bg-white pt-24 pb-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Berikan Penilaian Anda</h1>
            <p class="text-gray-500 mt-2">Bagaimana pengalaman Anda dengan program ini? Masukan Anda sangat berharga bagi kami.</p>
        </div>

        @if(session('success'))
        <div class="bg-green-50 text-green-700 p-4 rounded-xl mb-6 flex items-center gap-3">
            <i class="fa-solid fa-circle-check"></i>
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-50 text-red-700 p-4 rounded-xl mb-6 flex items-center gap-3">
            <i class="fa-solid fa-triangle-exclamation"></i>
            {{ session('error') }}
        </div>
        @endif

        <!-- Program Card Info -->
        <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-sm mb-10 flex flex-col md:flex-row gap-6 items-center md:items-start relative overflow-hidden">
            <div class="flex-1 z-10">
                <span class="inline-block bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full mb-3">PROGRAM AKTIF</span>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $program->program }}</h2>
                <p class="text-gray-500 text-sm mb-4">{{ $program->start_date == $program->end_date ? \Carbon\Carbon::parse($program->start_date)->format('d F Y') : \Carbon\Carbon::parse($program->start_date)->format('d F Y') . ' - ' . \Carbon\Carbon::parse($program->end_date)->format('d F Y') }}</p>
                
                <a href="{{ route('client.program.detail', $program->slug) }}" class="inline-flex items-center text-green-600 font-bold text-sm hover:underline">
                    Lihat Detail <i class="fa-solid fa-arrow-right ml-2 text-xs"></i>
                </a>
            </div>
            
            <!-- Decoration / Image -->
            <div class="w-full md:w-40 h-32 md:h-28 rounded-2xl bg-gradient-to-br from-green-400 to-blue-500 shrink-0 shadow-lg relative overflow-hidden group">
                 @if($program->image)
                    @php
                        $proofImageUrl = str_starts_with($program->image, 'images/') 
                            ? asset($program->image) 
                            : asset('storage/' . $program->image);
                    @endphp
                    <img src="{{ $proofImageUrl }}" class="w-full h-full object-cover mix-blend-overlay opacity-90 group-hover:opacity-100 transition duration-500" alt="{{ $program->program }}">
                 @endif
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('client.program.proof.store', $program->slug) }}" method="POST" enctype="multipart/form-data" class="space-y-10">
            @csrf
            
            <!-- 1. Rating -->
            <div>
                <label class="block text-gray-900 font-bold text-lg mb-4">1. Berapa bintang yang Anda berikan?</label>
                <div class="flex flex-col gap-2">
                    <div class="flex items-center gap-4">
                        <div class="flex gap-2" id="star-rating">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" class="star-btn text-4xl text-gray-200 hover:text-yellow-400 transition-all transform hover:scale-110 focus:outline-none" data-value="{{ $i }}">
                                    <i class="fa-solid fa-star"></i>
                                </button>
                            @endfor
                        </div>
                        <span id="rating-text" class="text-green-600 font-bold text-xl ml-2">0.0/5.0</span>
                    </div>
                    <p id="rating-label" class="text-gray-400 text-sm font-medium">Klik bintang untuk menilai</p>
                    <input type="hidden" name="rating" id="rating-input" value="{{ old('rating') }}" required>
                </div>
                @error('rating')
                    <p class="text-red-500 text-sm mt-1">Harap berikan penilaian bintang.</p>
                @enderror
            </div>

            <!-- 2. Review -->
            <div>
                <label class="block text-gray-900 font-bold text-lg mb-4">2. Tulis ulasan Anda</label>
                <div class="relative group">
                    <textarea name="review" rows="6" class="w-full border-2 border-gray-100 rounded-3xl p-5 focus:ring-4 focus:ring-green-100 focus:border-green-500 transition resize-none outline-none text-gray-700 bg-gray-50/50 group-hover:bg-white" placeholder="Ceritakan pengalaman Anda, apa yang Anda pelajari, dan apa yang bisa ditingkatkan dari program ini..." required>{{ old('review') }}</textarea>
                    <div class="absolute bottom-4 right-4 text-xs text-gray-400 bg-white/90 px-2 py-1 rounded-md border border-gray-100 shadow-sm">
                        <span id="char-count">0</span>/500 karakter
                    </div>
                </div>
                @error('review')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- 3. Proof Upload -->
            <div>
                <label class="block text-gray-900 font-bold text-lg mb-4">3. Unggah bukti program</label>
                <div class="border-3 border-dashed border-gray-200 rounded-3xl p-12 flex flex-col items-center justify-center text-center cursor-pointer hover:bg-green-50/30 hover:border-green-400 transition-all group relative" id="dropzone">
                    <input type="file" name="proof_file" id="proof_file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept=".pdf,.jpg,.jpeg,.png" required>
                    
                    <div class="bg-gray-50 p-5 rounded-full mb-4 group-hover:scale-110 group-hover:bg-green-100 transition duration-300">
                        <i class="fa-solid fa-cloud-arrow-up text-4xl text-gray-400 group-hover:text-green-600 transition"></i>
                    </div>
                    
                    <p class="text-gray-700 font-semibold text-lg"><span class="text-green-600 underline">Klik untuk unggah</span> atau seret file</p>
                    <p class="text-gray-400 text-sm mt-2">PDF, JPG, atau PNG (Maks. 2MB)</p>
                </div>
                
                <!-- Preview File -->
                <div id="file-preview" class="mt-4 hidden animate-fade-in-up">
                    <div class="flex items-center justify-between bg-white border border-gray-200 rounded-2xl p-4 shadow-sm hover:shadow-md transition">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center text-gray-500" id="icon-container">
                                <i class="fa-solid fa-file text-xl" id="file-icon"></i>
                            </div>
                            <div>
                                <p class="text-gray-900 font-bold text-sm truncate max-w-[200px] md:max-w-xs" id="filename">filename.pdf</p>
                                <p class="text-gray-500 text-xs" id="filesize">2.4 MB</p>
                            </div>
                        </div>
                        <button type="button" id="remove-file" class="text-gray-400 hover:text-red-500 hover:bg-red-50 p-2 rounded-lg transition" title="Hapus file">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>
                 @error('proof_file')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror

                <!-- Bantuan Jika Lupa Dokumentasi -->
                <div class="mt-4 text-center">
                    <p class="text-sm text-gray-500">
                        Lupa mengambil dokumentasi saat kegiatan? 
                        <a href="https://wa.me/6285795899901?text=Halo%20Admin,%20saya%20terdaftar%20di%20program%20{{ urlencode($program->program) }}%20namun%20lupa/tidak%20sempat%20mengambil%20dokumentasi%20bukti%20kegiatan.%20Mohon%20bantuannya." 
                           target="_blank" 
                           class="text-green-600 font-semibold hover:text-green-700 hover:underline transition-colors">
                           Hubungi Admin
                        </a>
                    </p>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-100">
                <a href="{{ route('client.dashboard.program') }}" class="px-8 py-3.5 rounded-xl text-gray-500 font-semibold hover:bg-gray-100 transition">Batal</a>
                <button type="submit" class="px-10 py-3.5 rounded-xl bg-green-600 text-white font-bold hover:bg-green-700 hover:translate-y-[-2px] transition-all shadow-xl shadow-green-600/20 flex items-center gap-2">
                    Kirim Ulasan <i class="fa-solid fa-paper-plane text-sm"></i>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Star Rating Logic
    const stars = document.querySelectorAll('.star-btn');
    const ratingInput = document.getElementById('rating-input');
    const ratingText = document.getElementById('rating-text');
    const ratingLabel = document.getElementById('rating-label');
    const labels = ['Sangat Buruk', 'Buruk', 'Cukup', 'Bagus', 'Sangat Bagus'];
    let currentRating = 0;

    function updateStars(value) {
        stars.forEach((star, index) => {
            const starIcon = star.querySelector('i');
            if (index < value) {
                star.classList.remove('text-gray-200');
                star.classList.add('text-yellow-400');
            } else {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-200');
            }
        });
    }

    stars.forEach((star, index) => {
        star.addEventListener('click', () => {
            currentRating = index + 1;
            ratingInput.value = currentRating;
            ratingText.textContent = currentRating + '.0/5.0';
            ratingLabel.textContent = labels[index];
            ratingLabel.className = 'text-gray-900 text-sm font-medium'; // Highlight label
            updateStars(currentRating);
        });
        
        star.addEventListener('mouseenter', () => {
            updateStars(index + 1);
        });

        star.addEventListener('mouseleave', () => {
            updateStars(currentRating);
        });
    });

    // File Upload Logic
    const fileInput = document.getElementById('proof_file');
    const dropzone = document.getElementById('dropzone');
    const filePreview = document.getElementById('file-preview');
    const filenameDisplay = document.getElementById('filename');
    const filesizeDisplay = document.getElementById('filesize');
    const fileIcon = document.getElementById('file-icon');
    const iconContainer = document.getElementById('icon-container');
    const removeFileBtn = document.getElementById('remove-file');

    fileInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            handleFile(file);
        }
    });

    // Drag and drop handlers
    dropzone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropzone.classList.add('bg-green-50', 'border-green-400');
    });

    dropzone.addEventListener('dragleave', (e) => {
        e.preventDefault();
        dropzone.classList.remove('bg-green-50', 'border-green-400');
    });

    dropzone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropzone.classList.remove('bg-green-50', 'border-green-400');
        const file = e.dataTransfer.files[0];
        if (file) {
            fileInput.files = e.dataTransfer.files;
            handleFile(file);
        }
    });

    function handleFile(file) {
        // Validasi ukuran file (max 2MB)
        if (file.size > 2 * 1024 * 1024) {
            Swal.fire({
                icon: 'error',
                title: 'Ukuran File Terlalu Besar',
                text: 'Maksimal ukuran file adalah 2MB',
                confirmButtonColor: '#16a34a'
            });
            fileInput.value = '';
            return;
        }

        filenameDisplay.textContent = file.name;
        filesizeDisplay.textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
        
        // Icon based on type
        if (file.type.includes('image')) {
            fileIcon.className = 'fa-solid fa-image text-2xl text-blue-500';
            iconContainer.className = 'w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center';
        } else {
            fileIcon.className = 'fa-solid fa-file-pdf text-2xl text-red-500';
            iconContainer.className = 'w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center';
        }

        filePreview.classList.remove('hidden');
    }

    removeFileBtn.addEventListener('click', () => {
        fileInput.value = '';
        filePreview.classList.add('hidden');
    });

    // Char Count
    const textarea = document.querySelector('textarea[name="review"]');
    const charCount = document.getElementById('char-count');
    textarea.addEventListener('input', () => {
        charCount.innerText = textarea.value.length;
    });
});
</script>
@endsection
