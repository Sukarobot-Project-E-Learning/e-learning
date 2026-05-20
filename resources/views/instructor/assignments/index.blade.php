@extends('panel.layouts.app')

@section('title', 'Tugas Akhir')

@push('styles')
    <style>
        .fade-in {
            animation: fadeIn 0.25s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
@endpush

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-slate-100">
        <div class="container px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="relative mb-8 overflow-hidden bg-blue-600 rounded-2xl">
                <div class="relative px-4 py-6 sm:px-6 sm:py-8 lg:px-8 lg:py-10">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div class="text-white max-w-2xl">
                            <h2 class="text-2xl sm:text-3xl font-bold">Tugas Akhir</h2>
                            <p class="text-sm text-blue-100 sm:text-base">Kelola tugas akhir berbasis file untuk setiap program.</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-2">
                                     <a href="{{ route('instructor.assignments.dashboard') }}"
                               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-blue-600 bg-white rounded-xl hover:bg-blue-50 shadow">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Dashboard Tugas Akhir
                            </a>
                            <button type="button" id="openAssignmentModal"
                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-blue-600 bg-white rounded-xl hover:bg-blue-50 shadow">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Buat Tugas Akhir
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="mb-6 p-4 bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="text-xs font-semibold text-gray-500">Program</label>
                        <select id="programSelect" class="mt-1 w-full py-2.5 px-3 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih Program</option>
                            @foreach($programs as $program)
                                <option value="{{ $program->id }}">{{ $program->program }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="lg:col-span-2">
                        <label class="text-xs font-semibold text-gray-500">Cari</label>
                        <input id="searchInput" type="text" placeholder="Cari judul tugas akhir..."
                            class="mt-1 w-full py-2.5 px-3 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="hidden lg:block overflow-hidden bg-white rounded-2xl shadow-sm border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Judul</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Batas</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="assignmentTable" class="divide-y divide-gray-100"></tbody>
                    </table>
                </div>
                <div id="tableEmpty" class="hidden px-6 py-12 text-center text-sm text-gray-500">Belum ada tugas akhir untuk program ini.</div>
            </div>

            <!-- Mobile List -->
            <div id="assignmentCards" class="lg:hidden space-y-4"></div>
        </div>
    </div>

    <!-- Modal -->
    <div id="assignmentModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-gray-900/60 backdrop-blur-sm px-4">
        <div class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden" id="assignmentModalContent">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 id="assignmentModalTitle" class="text-lg font-bold text-gray-800">Buat Tugas Akhir</h3>
                <button type="button" id="closeAssignmentModal" class="text-gray-400 hover:text-red-500">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form id="assignmentForm" class="p-6 space-y-4">
                <input type="hidden" id="assignmentId" value="">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Judul <span class="text-red-500">*</span></label>
                    <input id="assignmentTitle" type="text" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500" placeholder="Contoh: Tugas Akhir" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi <span class="text-red-500">*</span></label>
                    <textarea id="assignmentDescription" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500" placeholder="Jelaskan tugas akhir" required></textarea>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Batas Pengumpulan</label>
                        <input id="assignmentDueDate" type="date" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ekstensi File <span class="text-red-500">*</span></label>
                        <input id="assignmentExtensions" type="text" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500" placeholder="pdf, zip, docx" required>
                        <p class="text-xs text-gray-500 mt-1">Pisahkan dengan koma</p>
                    </div>
                    <div id="passingScoreWrapper">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nilai Kelulusan</label>
                        <input id="assignmentPassingScore" type="number" min="0" max="100" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500" placeholder="70" value="70">
                    </div>
                </div>
                <div class="pt-4 flex justify-end gap-3">
                    <button type="button" id="cancelAssignment" class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg">Batal</button>
                    <button type="submit" class="px-5 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            const assignmentManager = {
                programs: @json($programs),
                items: [],
                programId: '',
                init() {
                    this.cache();
                    this.bind();
                },
                cache() {
                    this.programSelect = document.getElementById('programSelect');
                    this.searchInput = document.getElementById('searchInput');
                    this.tableBody = document.getElementById('assignmentTable');
                    this.tableEmpty = document.getElementById('tableEmpty');
                    this.cardList = document.getElementById('assignmentCards');

                    this.modal = document.getElementById('assignmentModal');
                    this.modalTitle = document.getElementById('assignmentModalTitle');
                    this.openModalBtn = document.getElementById('openAssignmentModal');
                    this.closeModalBtn = document.getElementById('closeAssignmentModal');
                    this.cancelModalBtn = document.getElementById('cancelAssignment');
                    this.form = document.getElementById('assignmentForm');

                    this.formFields = {
                        id: document.getElementById('assignmentId'),
                        title: document.getElementById('assignmentTitle'),
                        description: document.getElementById('assignmentDescription'),
                        dueDate: document.getElementById('assignmentDueDate'),
                        extensions: document.getElementById('assignmentExtensions'),
                        passingScore: document.getElementById('assignmentPassingScore')
                    };
                },
                bind() {
                    this.programSelect.addEventListener('change', () => {
                        this.programId = this.programSelect.value;
                        this.fetchAssignments();
                    });
                    this.searchInput.addEventListener('input', () => this.render());

                    this.openModalBtn.addEventListener('click', () => this.openModal());
                    this.closeModalBtn.addEventListener('click', () => this.closeModal());
                    this.cancelModalBtn.addEventListener('click', () => this.closeModal());

                    this.form.addEventListener('submit', (event) => this.submitForm(event));
                },
                get csrf() {
                    return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                },
                url(template, replacement) {
                    return template.replace('__PROGRAM__', replacement.programId || '').replace('__ASSIGNMENT__', replacement.assignmentId || '');
                },
                get fetchUrl() {
                    return '{{ url('/instructor/programs/__PROGRAM__/lms/assignments') }}';
                },
                get updateUrl() {
                    return '{{ url('/instructor/programs/__PROGRAM__/lms/assignments/__ASSIGNMENT__') }}';
                },
                fetchAssignments() {
                    if (!this.programId) {
                        this.items = [];
                        this.render();
                        return;
                    }
                    fetch(this.url(this.fetchUrl, { programId: this.programId }), {
                        headers: { 'Accept': 'application/json' }
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.items = data.data || [];
                        this.render();
                    })
                    .catch(() => {
                        this.items = [];
                        this.render();
                    });
                },
                render() {
                    const search = this.searchInput.value.toLowerCase();
                    const filtered = this.items.filter(item => {
                        const matchesTitle = (item.title || '').toLowerCase().includes(search);
                        return matchesTitle;
                    });

                    this.openModalBtn.classList.toggle('hidden', this.items.length > 0 || !this.programId);

                    this.tableBody.innerHTML = '';
                    this.cardList.innerHTML = '';

                    if (filtered.length === 0) {
                        this.tableEmpty.classList.remove('hidden');
                        return;
                    }

                    this.tableEmpty.classList.add('hidden');

                    filtered.forEach(item => {
                        const dueDate = item.due_date ? new Date(item.due_date).toLocaleDateString('id-ID') : 'Tidak ada';
                        const row = document.createElement('tr');
                        row.className = 'fade-in';
                        row.innerHTML = `
                            <td class="px-6 py-4 text-sm text-gray-700 font-medium">${this.escape(item.title)}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">${dueDate}</td>
                            <td class="px-6 py-4 text-sm text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button" data-action="edit" data-id="${item.id}" class="px-3 py-1.5 text-xs font-semibold text-blue-600 bg-blue-50 rounded-lg">Edit</button>
                                    <button type="button" data-action="delete" data-id="${item.id}" class="px-3 py-1.5 text-xs font-semibold text-red-600 bg-red-50 rounded-lg">Hapus</button>
                                </div>
                            </td>
                        `;
                        row.querySelectorAll('button').forEach(btn => btn.addEventListener('click', (e) => this.handleAction(e, item)));
                        this.tableBody.appendChild(row);

                        const card = document.createElement('div');
                        card.className = 'bg-white rounded-xl border border-gray-100 p-4 shadow-sm';
                        card.innerHTML = `
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">${this.escape(item.title)}</p>
                                    <p class="text-xs text-gray-500">${dueDate}</p>
                                </div>
                                <div class="flex gap-2">
                                    <button type="button" data-action="edit" data-id="${item.id}" class="text-xs font-semibold text-blue-600">Edit</button>
                                    <button type="button" data-action="delete" data-id="${item.id}" class="text-xs font-semibold text-red-600">Hapus</button>
                                </div>
                            </div>
                        `;
                        card.querySelectorAll('button').forEach(btn => btn.addEventListener('click', (e) => this.handleAction(e, item)));
                        this.cardList.appendChild(card);
                    });
                },
                handleAction(event, item) {
                    const action = event.target.dataset.action;
                    if (action === 'edit') {
                        this.openModal(item);
                    } else if (action === 'delete') {
                        this.deleteAssignment(item);
                    }
                },
                openModal(item = null) {
                    if (!this.programId) {
                        Swal.fire({ icon: 'warning', title: 'Pilih Program', text: 'Silakan pilih program terlebih dahulu.' });
                        return;
                    }
                    this.form.reset();
                    this.formFields.id.value = item ? item.id : '';
                    this.formFields.title.value = item ? item.title : '';
                    this.formFields.description.value = item ? item.description : '';
                    this.formFields.dueDate.value = item && item.due_date ? item.due_date.split('T')[0] : '';
                    this.formFields.extensions.value = item ? item.allowed_extensions : '';
                    this.formFields.passingScore.value = item && item.passing_score ? item.passing_score : 70;
                    this.modalTitle.textContent = item ? 'Edit Tugas Akhir' : 'Buat Tugas Akhir';
                    this.modal.classList.remove('hidden');
                    this.modal.classList.add('flex');
                },
                closeModal() {
                    this.modal.classList.add('hidden');
                    this.modal.classList.remove('flex');
                },
                submitForm(event) {
                    event.preventDefault();
                    if (!this.programId) return;

                    const payload = {
                        title: this.formFields.title.value.trim(),
                        description: this.formFields.description.value.trim(),
                        allowed_extensions: this.formFields.extensions.value.trim(),
                        due_date: this.formFields.dueDate.value || null,
                        passing_score: this.formFields.passingScore.value || 70
                    };

                    const isEdit = !!this.formFields.id.value;
                    const url = isEdit
                        ? this.url(this.updateUrl, { programId: this.programId, assignmentId: this.formFields.id.value })
                        : this.url(this.fetchUrl, { programId: this.programId });

                    fetch(url, {
                        method: isEdit ? 'PUT' : 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': this.csrf
                        },
                        body: JSON.stringify(payload)
                    })
                    .then(res => res.json().then(data => ({ ok: res.ok, data })))
                    .then(({ ok, data }) => {
                        if (!ok || data.success === false) {
                            const message = data.message || 'Tidak dapat menyimpan tugas akhir.';
                            Swal.fire({ icon: 'error', title: 'Gagal', text: message });
                            return;
                        }
                        this.closeModal();
                        this.fetchAssignments();
                    })
                    .catch(() => Swal.fire({ icon: 'error', title: 'Gagal', text: 'Terjadi kesalahan.' }));
                },
                deleteAssignment(item) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Hapus Tugas Akhir?',
                        text: 'Data yang dihapus tidak dapat dikembalikan.',
                        showCancelButton: true,
                        confirmButtonText: 'Hapus',
                        cancelButtonText: 'Batal'
                    }).then(result => {
                        if (!result.isConfirmed) return;
                        fetch(this.url(this.updateUrl, { programId: this.programId, assignmentId: item.id }), {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': this.csrf,
                                'Accept': 'application/json'
                            }
                        })
                        .then(() => this.fetchAssignments());
                    });
                },
                escape(value) {
                    if (!value) return '';
                    return value.replace(/[&<>"]/g, char => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[char]));
                }
            };

            document.addEventListener('DOMContentLoaded', () => assignmentManager.init());
        </script>
    @endpush
@endsection
