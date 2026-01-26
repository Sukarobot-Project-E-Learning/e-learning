@extends('panel.layouts.app')

@section('title', 'Pengajuan Program')

@push('styles')
<style>
    .table-row-enter { opacity: 0; transform: translateY(-10px); }
    .table-row-enter-active { opacity: 1; transform: translateY(0); transition: all 0.3s ease; }
    .sort-icon { transition: transform 0.2s ease; }
    .sort-icon.asc { transform: rotate(180deg); }
    .search-highlight { background-color: rgb(254 215 170); padding: 0 2px; border-radius: 2px; }
</style>
@endpush

@section('content')
<div class="container px-4 sm:px-6 mx-auto overflow-x-hidden max-w-full" x-data="approvalTable()">
    <!-- Page Header -->
    <div class="my-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-gray-200">Pengajuan Program dari Instruktur</h2>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Review dan setujui atau tolak program yang diajukan oleh instruktur</p>
            </div>
            <!-- Bulk Actions -->
            <div class="flex flex-wrap items-center gap-3">
                <select x-model="bulkStatus" class="px-3 py-2 text-sm bg-white border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 focus:ring-orange-500 focus:border-orange-500">
                    <option value="">Pilih Status</option>
                    <option value="approved">Disetujui</option>
                    <option value="rejected">Ditolak</option>
                </select>
                <button @click="bulkUpdateStatus()" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-orange-600 rounded-lg hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 transition-all duration-200 transform hover:scale-105">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Ubah Status
                </button>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-4 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded-lg dark:bg-green-900/30 dark:border-green-600 dark:text-green-400">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="mb-4 px-4 py-3 bg-red-100 border border-red-400 text-red-700 rounded-lg dark:bg-red-900/30 dark:border-red-600 dark:text-red-400">{{ session('error') }}</div>
    @endif

    <!-- Search & Filter Bar -->
    <div class="mb-6 p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-orange-100 dark:border-gray-700">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3"><svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg></span>
                <input type="text" x-model="search" @input.debounce.300ms="filterTable()" placeholder="Cari judul, instruktur, kategori..." class="w-full pl-10 pr-4 py-2.5 text-sm border-2 border-orange-200 rounded-lg focus:border-orange-500 focus:ring-4 focus:ring-orange-100 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all duration-200">
                <span x-show="search" @click="search=''; filterTable()" class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer"><svg class="w-4 h-4 text-gray-400 hover:text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></span>
            </div>
            <select x-model="status" @change="filterTable()" class="px-4 py-2.5 text-sm border-2 border-orange-200 rounded-lg focus:border-orange-500 focus:ring-4 focus:ring-orange-100 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="">Semua Status</option>
                <option value="pending">Menunggu</option>
                <option value="approved">Disetujui</option>
                <option value="rejected">Ditolak</option>
            </select>
            <select x-model.number="perPage" @change="filterTable()" class="px-4 py-2.5 text-sm border-2 border-orange-200 rounded-lg focus:border-orange-500 focus:ring-4 focus:ring-orange-100 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="10">10 per halaman</option>
                <option value="25">25 per halaman</option>
                <option value="50">50 per halaman</option>
            </select>
        </div>
        <div class="mt-3 flex flex-wrap items-center justify-between gap-2 text-sm text-gray-500 dark:text-gray-400">
            <div class="flex items-center gap-1">
                <span>Menampilkan</span>
                <span class="font-semibold text-orange-600" x-text="showingFrom"></span>-<span class="font-semibold text-orange-600" x-text="showingTo"></span>
                <span>dari</span><span class="font-semibold text-orange-600" x-text="total"></span><span>pengajuan</span>
                <span x-show="selectedIds.length > 0" class="ml-2 px-2 py-0.5 bg-orange-100 text-orange-700 rounded-full text-xs font-medium">(<span x-text="selectedIds.length"></span> dipilih)</span>
            </div>
            <div x-show="search" class="text-orange-600 text-xs sm:text-sm">Hasil pencarian untuk "<span x-text="search" class="font-semibold"></span>"</div>
        </div>
    </div>

    <!-- Mobile Card View -->
    <div class="block lg:hidden space-y-3 mb-6">
        <template x-for="(item, index) in items" :key="item.id">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4" :style="'animation-delay:' + (index * 50) + 'ms'">
                <div class="flex items-start gap-3">
                    <input type="checkbox" :checked="isSelected(item.id)" @change="toggleSelect(item.id)" class="mt-1 w-4 h-4 text-orange-600 rounded focus:ring-orange-500">
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-gray-800 dark:text-white truncate" x-html="highlightText(item.title)"></h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400" x-html="highlightText(item.instructor_name || '-')"></p>
                        <div class="mt-2 flex flex-wrap gap-2">
                            <span class="px-2 py-1 text-xs rounded" :class="item.type == 'online' ? 'bg-blue-100 text-blue-800' : item.type == 'offline' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800'" x-text="item.type ? item.type.charAt(0).toUpperCase() + item.type.slice(1) : '-'"></span>
                            <span class="px-2 py-1 text-xs rounded-full" :class="item.status == 'pending' ? 'bg-yellow-100 text-yellow-700' : item.status == 'approved' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'" x-text="item.status == 'pending' ? 'Menunggu' : item.status == 'approved' ? 'Disetujui' : 'Ditolak'"></span>
                        </div>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end gap-2">
                    <a :href="'/admin/program-approvals/' + item.id" class="px-3 py-1.5 text-sm font-medium text-blue-600 hover:bg-blue-50 dark:hover:bg-gray-700 rounded-lg transition-colors">Detail</a>
                    <button x-show="item.status == 'pending'" @click="quickApprove(item.id)" class="px-3 py-1.5 text-sm font-medium text-green-600 hover:bg-green-50 dark:hover:bg-gray-700 rounded-lg transition-colors">Setujui</button>
                </div>
            </div>
        </template>
        <template x-if="items.length === 0">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-8 text-center">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <p class="text-gray-500 dark:text-gray-400">Tidak ada program yang menunggu persetujuan.</p>
            </div>
        </template>
    </div>

    <!-- Desktop Table View -->
    <div class="hidden lg:block bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-orange-100 dark:border-gray-700 mb-6">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-orange-500 to-orange-600 text-white">
                    <tr>
                        <th class="px-4 py-4 text-left"><input type="checkbox" :checked="selectAll" @change="toggleSelectAll()" class="w-4 h-4 text-orange-600 bg-white rounded focus:ring-orange-500"></th>
                        <template x-for="col in columns" :key="col.key">
                            <th @click="col.sortable && sortBy(col.key)" :class="{'cursor-pointer hover:bg-orange-600': col.sortable}" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    <span x-text="col.label"></span>
                                    <template x-if="col.sortable">
                                        <svg :class="{'sort-icon': true, 'asc': sortKey === col.key && sortDir === 'asc', 'opacity-30': sortKey !== col.key}" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </template>
                                </div>
                            </th>
                        </template>
                    </tr>
                </thead>
                <tbody class="divide-y divide-orange-100 dark:divide-gray-700">
                    <template x-for="(item, index) in items" :key="item.id">
                        <tr class="hover:bg-orange-50 dark:hover:bg-gray-700 transition-colors duration-150" :style="'animation-delay:' + (index * 50) + 'ms'">
                            <td class="px-4 py-4"><input type="checkbox" :checked="isSelected(item.id)" @change="toggleSelect(item.id)" class="w-4 h-4 text-orange-600 rounded focus:ring-orange-500"></td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-800 dark:text-white" x-html="highlightText(item.title)"></div>
                                <p class="text-xs text-gray-500 mt-1" x-text="item.description ? item.description.substring(0, 50) + '...' : ''"></p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-800 dark:text-white" x-html="highlightText(item.instructor_name || '-')"></p>
                                <p class="text-xs text-gray-500" x-text="item.instructor_email || ''"></p>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300" x-html="highlightText(item.category || '-')"></td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-semibold rounded" :class="item.type == 'online' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : item.type == 'offline' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200'" x-text="item.type ? item.type.charAt(0).toUpperCase() + item.type.slice(1) : '-'"></span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full" :class="item.status == 'pending' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-700 dark:text-yellow-100' : item.status == 'approved' ? 'bg-green-100 text-green-700 dark:bg-green-700 dark:text-green-100' : 'bg-red-100 text-red-700 dark:bg-red-700 dark:text-red-100'" x-text="item.status == 'pending' ? 'Menunggu' : item.status == 'approved' ? 'Disetujui' : 'Ditolak'"></span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300" x-text="item.created_at ? new Date(item.created_at).toLocaleDateString('id-ID', {day: 'numeric', month: 'short', year: 'numeric'}) : '-'"></td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <a :href="'/admin/program-approvals/' + item.id" class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors" title="Detail">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                    <button x-show="item.status == 'pending'" @click="quickApprove(item.id)" class="p-2 text-green-600 hover:bg-green-100 rounded-lg transition-colors" title="Setujui">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <template x-if="items.length === 0">
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                <svg class="w-16 h-16 mx-auto mb-4 text-orange-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <p class="text-lg font-medium">Tidak ada program yang menunggu persetujuan.</p>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- Desktop Pagination -->
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 border-t border-orange-100 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <button @click="prevPage()" :disabled="currentPage === 1" :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-orange-100'" class="px-4 py-2 text-sm font-medium text-orange-600 rounded-lg transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>Sebelumnya
                </button>
                <div class="flex items-center gap-1">
                    <template x-for="page in visiblePages" :key="page">
                        <button @click="page !== '...' && goToPage(page)" :class="page === currentPage ? 'bg-orange-500 text-white' : page === '...' ? 'cursor-default' : 'hover:bg-orange-100 text-gray-700'" class="w-10 h-10 text-sm font-medium rounded-lg transition-colors" x-text="page"></button>
                    </template>
                </div>
                <button @click="nextPage()" :disabled="currentPage === totalPages" :class="currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-orange-100'" class="px-4 py-2 text-sm font-medium text-orange-600 rounded-lg transition-colors flex items-center gap-2">
                    Selanjutnya<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Pagination -->
    <div class="block lg:hidden bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
        <div class="flex items-center justify-between">
            <button @click="prevPage()" :disabled="currentPage === 1" :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-orange-100'" class="px-3 py-2 text-sm font-medium text-orange-600 rounded-lg transition-colors flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </button>
            <div class="flex items-center gap-1">
                <template x-for="page in visiblePages" :key="page">
                    <button @click="page !== '...' && goToPage(page)" :class="page === currentPage ? 'bg-orange-500 text-white' : page === '...' ? 'cursor-default' : 'hover:bg-orange-100 text-gray-700'" class="w-10 h-10 text-sm font-medium rounded-lg transition-colors" x-text="page"></button>
                </template>
            </div>
            <button @click="nextPage()" :disabled="currentPage === totalPages" :class="currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-orange-100'" class="px-3 py-2 text-sm font-medium text-orange-600 rounded-lg transition-colors flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>
        </div>
    </div>
</div>

<script>
function approvalTable() {
    return {
        items: @json($approvals->items()),
        search: '{{ request("search") }}',
        status: '{{ request("status") }}',
        perPage: {{ $approvals->perPage() }},
        currentPage: {{ $approvals->currentPage() }},
        sortKey: '{{ request("sort", "created_at") }}',
        sortDir: '{{ request("dir", "desc") }}',
        total: {{ $approvals->total() }},
        lastPage: {{ $approvals->lastPage() }},
        loading: false,
        selectAll: false,
        selectedIds: [],
        bulkStatus: '',
        columns: [
            { key: 'title', label: 'Judul Program', sortable: true },
            { key: 'instructor_name', label: 'Instruktur', sortable: true },
            { key: 'category', label: 'Kategori', sortable: true },
            { key: 'type', label: 'Tipe', sortable: false },
            { key: 'status', label: 'Status', sortable: true },
            { key: 'created_at', label: 'Tanggal', sortable: true },
            { key: 'actions', label: 'Aksi', sortable: false }
        ],
        get totalPages() { return this.lastPage || 1; },
        get showingFrom() { return this.total ? ((this.currentPage - 1) * this.perPage) + 1 : 0; },
        get showingTo() { return Math.min(this.currentPage * this.perPage, this.total); },
        get visiblePages() {
            const pages = []; const total = this.totalPages; const current = this.currentPage;
            if (total <= 7) { for (let i = 1; i <= total; i++) pages.push(i); }
            else { pages.push(1); if (current > 3) pages.push('...'); for (let i = Math.max(2, current - 1); i <= Math.min(total - 1, current + 1); i++) pages.push(i); if (current < total - 2) pages.push('...'); pages.push(total); }
            return pages;
        },
        filterTable() { this.currentPage = 1; this.selectedIds = []; this.selectAll = false; this.fetchData(); },
        sortBy(key) { this.sortDir = this.sortKey === key && this.sortDir === 'asc' ? 'desc' : 'asc'; this.sortKey = key; this.fetchData(); },
        goToPage(page) { if (page !== '...') { this.currentPage = page; this.fetchData(); } },
        prevPage() { if (this.currentPage > 1) { this.currentPage--; this.fetchData(); } },
        nextPage() { if (this.currentPage < this.totalPages) { this.currentPage++; this.fetchData(); } },
        fetchData() {
            this.loading = true;
            const params = new URLSearchParams({
                search: this.search || '',
                status: this.status || '',
                per_page: String(this.perPage),
                page: String(this.currentPage),
                sort: this.sortKey || 'created_at',
                dir: this.sortDir || 'desc'
            });
            fetch('{{ route('admin.program-approvals.index') }}?' + params.toString(), {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(r => r.json())
            .then(json => {
                this.items = json.data || [];
                this.total = json.total || 0;
                this.lastPage = json.last_page || 1;
                this.perPage = json.per_page || this.perPage;
                this.currentPage = json.current_page || this.currentPage;
            })
            .catch(err => console.error(err))
            .finally(() => { this.loading = false; });
        },
        highlightText(text) {
            if (!this.search || !text) return text;
            const regex = new RegExp(`(${this.search.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
            return String(text).replace(regex, '<span class="search-highlight">$1</span>');
        },
        toggleSelectAll() {
            this.selectAll = !this.selectAll;
            this.selectedIds = this.selectAll ? this.items.map(i => i.id) : [];
        },
        toggleSelect(id) {
            const idx = this.selectedIds.indexOf(id);
            if (idx > -1) this.selectedIds.splice(idx, 1);
            else this.selectedIds.push(id);
            this.selectAll = this.selectedIds.length === this.items.length;
        },
        isSelected(id) { return this.selectedIds.includes(id); },
        quickApprove(id) {
            Swal.fire({
                title: 'Setujui Program?',
                text: 'Program akan langsung dipublikasikan.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#16a34a',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Setujui!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submitStatusUpdate([id], 'approved', null);
                }
            });
        },
        bulkUpdateStatus() {
            if (this.selectedIds.length === 0) {
                Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Pilih minimal satu program untuk diubah statusnya' });
                return;
            }
            if (!this.bulkStatus) {
                Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Pilih status yang ingin diterapkan' });
                return;
            }
            if (this.bulkStatus === 'rejected') {
                Swal.fire({
                    title: 'Alasan Penolakan',
                    input: 'textarea',
                    inputPlaceholder: 'Masukkan alasan penolakan (minimal 15 karakter)...',
                    inputAttributes: { 'aria-label': 'Alasan penolakan' },
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Tolak',
                    cancelButtonText: 'Batal',
                    inputValidator: (value) => {
                        if (!value || value.trim().length < 15) {
                            return 'Alasan penolakan minimal 15 karakter';
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submitStatusUpdate(this.selectedIds, 'rejected', result.value);
                    }
                });
            } else {
                Swal.fire({
                    title: 'Setujui Program?',
                    text: `${this.selectedIds.length} program akan disetujui dan dipublikasikan.`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#16a34a',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Setujui!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submitStatusUpdate(this.selectedIds, 'approved', null);
                    }
                });
            }
        },
        submitStatusUpdate(ids, status, reason) {
            fetch('{{ route('admin.program-approvals.bulk-update') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ ids: ids, status: status, rejection_reason: reason })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: status === 'approved' 
                            ? (ids.length > 1 ? `${ids.length} program berhasil disetujui.` : 'Program berhasil disetujui.')
                            : (ids.length > 1 ? `${ids.length} program berhasil ditolak.` : 'Program berhasil ditolak.'),
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        this.selectedIds = [];
                        this.selectAll = false;
                        this.bulkStatus = '';
                        this.fetchData();
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal!', text: data.message || 'Terjadi kesalahan' });
                }
            })
            .catch(() => Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Terjadi kesalahan' }));
        }
    }
}
</script>
@endsection
