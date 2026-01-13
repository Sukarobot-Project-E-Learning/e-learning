@extends('admin.layouts.app')

@section('title', 'Pengajuan Program')

@push('styles')
<style>
    .table-row-animate { animation: fadeInUp 0.3s ease-out forwards; opacity: 0; }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .sort-icon { transition: transform 0.2s ease; }
    .sort-icon.asc { transform: rotate(180deg); }
    .search-highlight { background-color: rgb(254 215 170); padding: 0 2px; border-radius: 2px; }
    .dark .search-highlight { background-color: rgb(194 65 12); color: white; }
</style>
@endpush

@section('content')
<div class="container px-4 sm:px-6 mx-auto" x-data="approvalTable()">
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
                <button @click="bulkUpdateStatus()" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-orange-600 rounded-lg hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500">
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
    <div class="mb-6 p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="flex flex-col lg:flex-row gap-4">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3"><svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg></span>
                <input type="text" x-model="search" @input.debounce.300ms="applyFilters()" placeholder="Cari judul, instruktur, kategori..." class="w-full pl-10 pr-10 py-2.5 text-sm border border-gray-300 rounded-lg focus:border-orange-500 focus:ring-2 focus:ring-orange-200 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all duration-200">
                <button x-show="search" @click="search=''; applyFilters()" class="absolute inset-y-0 right-0 flex items-center pr-3"><svg class="w-4 h-4 text-gray-400 hover:text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <select x-model="statusFilter" @change="applyFilters()" class="px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:border-orange-500 focus:ring-2 focus:ring-orange-200 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="">Semua Status</option>
                <option value="pending">Menunggu</option>
                <option value="approved">Disetujui</option>
                <option value="rejected">Ditolak</option>
            </select>
            <select x-model="perPage" @change="applyFilters()" class="px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:border-orange-500 focus:ring-2 focus:ring-orange-200 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="10">10 per halaman</option>
                <option value="25">25 per halaman</option>
                <option value="50">50 per halaman</option>
            </select>
        </div>
        <div class="mt-3 flex flex-wrap items-center justify-between gap-2 text-sm text-gray-500 dark:text-gray-400">
            <div class="flex items-center gap-1">
                <span>Menampilkan</span><span class="font-semibold text-orange-600" x-text="showingFrom"></span>-<span class="font-semibold text-orange-600" x-text="showingTo"></span>
                <span>dari</span><span class="font-semibold text-orange-600" x-text="totalFiltered"></span><span>pengajuan</span>
                <span x-show="selectedIds.length > 0" class="ml-2 px-2 py-0.5 bg-orange-100 text-orange-700 rounded-full text-xs font-medium">(<span x-text="selectedIds.length"></span> dipilih)</span>
            </div>
            <div x-show="search" class="text-orange-600 text-xs sm:text-sm">Hasil pencarian untuk "<span x-text="search" class="font-semibold"></span>"</div>
        </div>
    </div>

    <!-- Mobile Card View -->
    <div class="block lg:hidden space-y-3 mb-6">
        <template x-for="(item, index) in paginatedData" :key="item.id">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 table-row-animate" :style="'animation-delay:' + (index * 50) + 'ms'">
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
                    <a :href="'/admin/program-approvals/' + item.id" class="px-3 py-1.5 text-sm font-medium text-blue-600 hover:bg-blue-50 rounded-lg">Detail</a>
                    <button x-show="item.status == 'pending'" @click="quickApprove(item.id)" class="px-3 py-1.5 text-sm font-medium text-green-600 hover:bg-green-50 rounded-lg">Setujui</button>
                </div>
            </div>
        </template>
        <template x-if="paginatedData.length === 0">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-8 text-center">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <p class="text-gray-500 dark:text-gray-400">Tidak ada program yang menunggu persetujuan.</p>
            </div>
        </template>
    </div>

    <!-- Desktop Table View -->
    <div class="hidden lg:block bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden border border-gray-200 dark:border-gray-700 mb-6">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-orange-500 to-orange-600 text-white">
                    <tr>
                        <th class="px-4 py-4 text-left"><input type="checkbox" :checked="selectAll" @change="toggleSelectAll()" class="w-4 h-4 text-orange-600 bg-white rounded focus:ring-orange-500"></th>
                        <th @click="sortBy('title')" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider cursor-pointer hover:bg-orange-600 select-none">
                            <div class="flex items-center gap-2"><span>Judul Program</span><svg :class="{'sort-icon': true, 'asc': sortKey === 'title' && sortDir === 'asc', 'opacity-30': sortKey !== 'title'}" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
                        </th>
                        <th @click="sortBy('instructor_name')" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider cursor-pointer hover:bg-orange-600 select-none">
                            <div class="flex items-center gap-2"><span>Instruktur</span><svg :class="{'sort-icon': true, 'asc': sortKey === 'instructor_name' && sortDir === 'asc', 'opacity-30': sortKey !== 'instructor_name'}" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
                        </th>
                        <th @click="sortBy('category')" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider cursor-pointer hover:bg-orange-600 select-none">
                            <div class="flex items-center gap-2"><span>Kategori</span><svg :class="{'sort-icon': true, 'asc': sortKey === 'category' && sortDir === 'asc', 'opacity-30': sortKey !== 'category'}" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Tipe</th>
                        <th @click="sortBy('status')" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider cursor-pointer hover:bg-orange-600 select-none">
                            <div class="flex items-center gap-2"><span>Status</span><svg :class="{'sort-icon': true, 'asc': sortKey === 'status' && sortDir === 'asc', 'opacity-30': sortKey !== 'status'}" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
                        </th>
                        <th @click="sortBy('created_at')" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider cursor-pointer hover:bg-orange-600 select-none">
                            <div class="flex items-center gap-2"><span>Tanggal</span><svg :class="{'sort-icon': true, 'asc': sortKey === 'created_at' && sortDir === 'asc', 'opacity-30': sortKey !== 'created_at'}" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    <template x-for="(item, index) in paginatedData" :key="item.id">
                        <tr class="hover:bg-orange-50 dark:hover:bg-gray-700 transition-colors table-row-animate" :style="'animation-delay:' + (index * 50) + 'ms'">
                            <td class="px-4 py-4"><input type="checkbox" :checked="isSelected(item.id)" @change="toggleSelect(item.id)" class="w-4 h-4 text-orange-600 rounded focus:ring-orange-500"></td>
                            <td class="px-6 py-4"><div class="font-semibold text-gray-800 dark:text-white" x-html="highlightText(item.title)"></div><p class="text-xs text-gray-500 mt-1" x-text="item.description ? item.description.substring(0, 50) + '...' : ''"></p></td>
                            <td class="px-6 py-4"><p class="font-medium text-gray-800 dark:text-white" x-html="highlightText(item.instructor_name || '-')"></p><p class="text-xs text-gray-500" x-text="item.instructor_email || ''"></p></td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300" x-html="highlightText(item.category || '-')"></td>
                            <td class="px-6 py-4"><span class="px-2 py-1 text-xs font-semibold rounded" :class="item.type == 'online' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : item.type == 'offline' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200'" x-text="item.type ? item.type.charAt(0).toUpperCase() + item.type.slice(1) : '-'"></span></td>
                            <td class="px-6 py-4"><span class="px-2 py-1 text-xs font-semibold rounded-full" :class="item.status == 'pending' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-700 dark:text-yellow-100' : item.status == 'approved' ? 'bg-green-100 text-green-700 dark:bg-green-700 dark:text-green-100' : 'bg-red-100 text-red-700 dark:bg-red-700 dark:text-red-100'" x-text="item.status == 'pending' ? 'Menunggu' : item.status == 'approved' ? 'Disetujui' : 'Ditolak'"></span></td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300" x-text="item.created_at ? new Date(item.created_at).toLocaleDateString('id-ID', {day: 'numeric', month: 'short', year: 'numeric'}) : '-'"></td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <a :href="'/admin/program-approvals/' + item.id" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 text-sm font-medium">Detail</a>
                                    <button x-show="item.status == 'pending'" @click="quickApprove(item.id)" class="text-green-600 hover:text-green-800 dark:text-green-400 text-sm font-medium">Setujui</button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <template x-if="paginatedData.length === 0">
                        <tr><td colspan="8" class="px-6 py-12 text-center"><svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg><p class="text-gray-500 dark:text-gray-400">Tidak ada program yang menunggu persetujuan.</p></td></tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-8">
        <button @click="prevPage()" :disabled="currentPage === 1" :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-orange-100 dark:hover:bg-gray-700'" class="px-4 py-2 text-sm font-medium text-orange-600 dark:text-orange-400 rounded-lg transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg><span class="hidden sm:inline">Sebelumnya</span>
        </button>
        <div class="flex items-center gap-1">
            <template x-for="page in visiblePages" :key="page">
                        <button @click="page !== '...' && goToPage(page)" :class="page === currentPage ? 'bg-orange-500 text-white' : page === '...' ? 'cursor-default' : 'hover:bg-orange-100 text-gray-700'" class="w-10 h-10 text-sm font-medium rounded-lg transition-colors" x-text="page"></button>
                    </template>
        </div>
        <button @click="nextPage()" :disabled="currentPage === totalPages || totalPages === 0" :class="currentPage === totalPages || totalPages === 0 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-orange-100 dark:hover:bg-gray-700'" class="px-4 py-2 text-sm font-medium text-orange-600 dark:text-orange-400 rounded-lg transition-colors flex items-center gap-2">
            <span class="hidden sm:inline">Selanjutnya</span><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </button>
    </div>
</div>

<!-- Approve Confirmation Modal -->
<div id="approveModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black/50 transition-opacity" onclick="closeApproveModal()"></div>
        <div class="relative bg-white rounded-lg shadow-xl dark:bg-gray-800 max-w-md w-full p-6">
            <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-green-100 rounded-full dark:bg-green-900">
                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h3 class="mb-2 text-lg font-semibold text-center text-gray-900 dark:text-gray-100">Setujui Program</h3>
            <p class="mb-6 text-sm text-center text-gray-600 dark:text-gray-400" id="approveModalMessage">Apakah Anda yakin ingin menyetujui program ini? Program akan langsung dipublikasikan.</p>
            <input type="hidden" id="approveIds" value="">
            <div class="flex gap-2">
                <button type="button"
                    onclick="closeApproveModal()"
                    class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-300">
                    Batal
                </button>
                <button type="button"
                    onclick="submitApprove()"
                    class="flex-1 px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                    Setujui
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Rejection Reason Modal -->
<div id="rejectModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black/50 transition-opacity" onclick="closeRejectModal()"></div>
        <div class="relative bg-white rounded-lg shadow-xl dark:bg-gray-800 max-w-md w-full p-6">
            <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-red-100 rounded-full dark:bg-red-900">
                <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
            <h3 class="mb-4 text-lg font-semibold text-center text-gray-900 dark:text-gray-100">Alasan Penolakan</h3>
            <form id="bulkRejectForm">
                <input type="hidden" id="rejectIds" name="ids" value="">
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Alasan Penolakan</label>
                    <textarea id="rejectionReason"
                        name="rejection_reason"
                        rows="4"
                        required
                        minlength="15"
                        class="w-full px-3 py-2 text-sm text-gray-700 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600"
                        placeholder="Masukkan alasan penolakan program (minimal 15 karakter)..."></textarea>
                </div>
                <div class="flex gap-2">
                    <button type="button"
                        onclick="closeRejectModal()"
                        class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-300">
                        Batal
                    </button>
                    <button type="button"
                        onclick="submitBulkReject()"
                        class="flex-1 px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                        Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function approvalTable() {
    return {
        allData: @json($approvals ?? []),
        search: '',
        statusFilter: '',
        perPage: 10,
        currentPage: 1,
        sortKey: 'created_at',
        sortDir: 'desc',
        selectAll: false,
        selectedIds: [],
        bulkStatus: '',
        get filteredData() {
            let result = [...this.allData];
            if (this.search) {
                const s = this.search.toLowerCase();
                result = result.filter(a => (a.title && a.title.toLowerCase().includes(s)) || (a.instructor_name && a.instructor_name.toLowerCase().includes(s)) || (a.category && a.category.toLowerCase().includes(s)));
            }
            if (this.statusFilter) result = result.filter(a => a.status === this.statusFilter);
            if (this.sortKey) {
                result.sort((a, b) => {
                    let aVal = a[this.sortKey] ?? '', bVal = b[this.sortKey] ?? '';
                    if (typeof aVal === 'string') aVal = aVal.toLowerCase();
                    if (typeof bVal === 'string') bVal = bVal.toLowerCase();
                    if (aVal < bVal) return this.sortDir === 'asc' ? -1 : 1;
                    if (aVal > bVal) return this.sortDir === 'asc' ? 1 : -1;
                    return 0;
                });
            }
            return result;
        },
        get totalFiltered() { return this.filteredData.length; },
        get totalPages() { return Math.ceil(this.totalFiltered / this.perPage) || 1; },
        get paginatedData() { const start = (this.currentPage - 1) * this.perPage; return this.filteredData.slice(start, start + this.perPage); },
        get showingFrom() { return this.totalFiltered ? (this.currentPage - 1) * this.perPage + 1 : 0; },
        get showingTo() { return Math.min(this.currentPage * this.perPage, this.totalFiltered); },
        get visiblePages() {
            const pages = [], total = this.totalPages, current = this.currentPage;
            if (total <= 7) { for (let i = 1; i <= total; i++) pages.push(i); }
            else { pages.push(1); if (current > 3) pages.push('...'); for (let i = Math.max(2, current - 1); i <= Math.min(total - 1, current + 1); i++) pages.push(i); if (current < total - 2) pages.push('...'); pages.push(total); }
            return pages;
        },
        applyFilters() { this.currentPage = 1; this.selectAll = false; this.selectedIds = []; },
        sortBy(key) { this.sortDir = this.sortKey === key && this.sortDir === 'asc' ? 'desc' : 'asc'; this.sortKey = key; },
        goToPage(page) { if (page !== '...' && page >= 1 && page <= this.totalPages) this.currentPage = page; },
        prevPage() { if (this.currentPage > 1) this.currentPage--; },
        nextPage() { if (this.currentPage < this.totalPages) this.currentPage++; },
        toggleSelectAll() { this.selectAll = !this.selectAll; this.selectedIds = this.selectAll ? this.paginatedData.map(i => i.id) : []; },
        toggleSelect(id) { const idx = this.selectedIds.indexOf(id); if (idx > -1) this.selectedIds.splice(idx, 1); else this.selectedIds.push(id); this.selectAll = this.selectedIds.length === this.paginatedData.length; },
        isSelected(id) { return this.selectedIds.includes(id); },
        highlightText(text) {
            if (!this.search || !text) return text;
            const regex = new RegExp(`(${this.search.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
            return String(text).replace(regex, '<span class="search-highlight">$1</span>');
        },
        quickApprove(id) {
            document.getElementById('approveIds').value = JSON.stringify([id]);
            document.getElementById('approveModalMessage').textContent = 'Apakah Anda yakin ingin menyetujui program ini? Program akan langsung dipublikasikan.';
            document.getElementById('approveModal').classList.remove('hidden');
        },
        bulkUpdateStatus() {
            if (this.selectedIds.length === 0) { Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Pilih minimal satu program untuk diubah statusnya' }); return; }
            if (!this.bulkStatus) { Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Pilih status yang ingin diterapkan' }); return; }
            if (this.bulkStatus === 'rejected') { document.getElementById('rejectIds').value = JSON.stringify(this.selectedIds); document.getElementById('rejectModal').classList.remove('hidden'); return; }
            document.getElementById('approveIds').value = JSON.stringify(this.selectedIds);
            document.getElementById('approveModalMessage').textContent = `Apakah Anda yakin ingin mengubah status ${this.selectedIds.length} program menjadi "Disetujui"?`;
            document.getElementById('approveModal').classList.remove('hidden');
        }
    }
}

function closeApproveModal() { document.getElementById('approveModal').classList.add('hidden'); }
function closeRejectModal() { document.getElementById('rejectModal').classList.add('hidden'); document.getElementById('rejectionReason').value = ''; }

function submitApprove() {
    const ids = JSON.parse(document.getElementById('approveIds').value);
    closeApproveModal();
    fetch(`{{ route('admin.program-approvals.bulk-update') }}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
        body: JSON.stringify({ ids: ids, status: 'approved' })
    }).then(r => r.json()).then(data => {
        if (data.success) { Swal.fire({ icon: 'success', title: 'Berhasil!', text: ids.length > 1 ? `${ids.length} program berhasil disetujui.` : 'Program berhasil disetujui.', timer: 2000, showConfirmButton: false }).then(() => window.location.reload()); }
        else { Swal.fire({ icon: 'error', title: 'Gagal!', text: data.message || 'Terjadi kesalahan' }); }
    }).catch(() => Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Terjadi kesalahan' }));
}

function submitBulkReject() {
    const ids = JSON.parse(document.getElementById('rejectIds').value);
    const reason = document.getElementById('rejectionReason').value;
    if (!reason.trim() || reason.trim().length < 15) { Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Alasan penolakan minimal 15 karakter' }); return; }
    closeRejectModal();
    fetch(`{{ route('admin.program-approvals.bulk-update') }}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
        body: JSON.stringify({ ids: ids, status: 'rejected', rejection_reason: reason })
    }).then(r => r.json()).then(data => {
        if (data.success) { Swal.fire({ icon: 'success', title: 'Berhasil!', text: ids.length > 1 ? `${ids.length} program berhasil ditolak.` : 'Program berhasil ditolak.', timer: 2000, showConfirmButton: false }).then(() => window.location.reload()); }
        else { Swal.fire({ icon: 'error', title: 'Gagal!', text: data.message || 'Terjadi kesalahan' }); }
    }).catch(() => Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Terjadi kesalahan' }));
}
</script>
@endsection