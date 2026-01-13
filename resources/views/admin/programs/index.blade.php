@extends('admin.layouts.app')

@section('title', 'Program Management')

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
<div class="container px-6 mx-auto overflow-x-hidden max-w-full" x-data="programTable()">
    <div class="my-6 flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Program Management</h2>
        <a href="{{ route('admin.programs.create') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-orange-600 rounded-lg hover:bg-orange-700 focus:ring-4 focus:ring-orange-300 transition-all duration-200 transform hover:scale-105">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Program
        </a>
    </div>

    <!-- Search & Filter -->
    <div class="mb-6 p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-orange-100 dark:border-gray-700">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3"><svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg></span>
                <input type="text" x-model="search" @input.debounce.300ms="filterTable()" placeholder="Cari program, kategori, jenis..." class="w-full pl-10 pr-4 py-2.5 text-sm border-2 border-orange-200 rounded-lg focus:border-orange-500 focus:ring-4 focus:ring-orange-100 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all duration-200">
                <span x-show="search" @click="search=''; filterTable()" class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer"><svg class="w-4 h-4 text-gray-400 hover:text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></span>
            </div>
            <select x-model="category" @change="filterTable()" class="px-4 py-2.5 text-sm border-2 border-orange-200 rounded-lg focus:border-orange-500 focus:ring-4 focus:ring-orange-100 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="">Semua Kategori</option>
                <option value="kursus">Kursus</option>
                <option value="pelatihan">Pelatihan</option>
                <option value="sertifikasi">Sertifikasi</option>
                <option value="outing-class">Outing Class</option>
                <option value="outboard">Outboard</option>
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
                <span>dari</span><span class="font-semibold text-orange-600" x-text="total"></span><span>program</span>
            </div>
            <div x-show="search" class="text-orange-600 text-xs sm:text-sm">Hasil pencarian untuk "<span x-text="search" class="font-semibold"></span>"</div>
        </div>
    </div>

    <!-- Mobile Card View -->
    <div class="block lg:hidden space-y-3 mb-6">
        <template x-for="(program, index) in programs" :key="program.id">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4" :style="'animation-delay:' + (index * 50) + 'ms'">
                <div class="flex items-start gap-3">
                    <template x-if="program.image">
                        <img :src="'/' + program.image" class="w-16 h-12 rounded-lg object-cover border-2 border-orange-200 cursor-pointer" @click="window.open('/' + program.image, '_blank')">
                    </template>
                    <template x-if="!program.image">
                        <div class="w-16 h-12 rounded-lg bg-gradient-to-br from-orange-100 to-orange-200 flex items-center justify-center">
                            <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    </template>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-gray-800 dark:text-white truncate" x-html="highlightText(program.title)"></h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400" x-text="program.category"></p>
                        <p class="text-sm text-orange-600 font-medium" x-text="program.price"></p>
                    </div>
                    <span class="px-2 py-1 text-xs font-bold rounded-full whitespace-nowrap bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300" x-text="program.type"></span>
                </div>
                <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <span class="text-xs text-gray-500" x-text="program.start_date"></span>
                    <div class="flex items-center gap-2">
                        <a :href="'/admin/programs/' + program.id + '/edit'" class="px-3 py-1.5 text-sm font-medium text-orange-600 hover:bg-orange-50 dark:hover:bg-gray-700 rounded-lg transition-colors">Edit</a>
                        <button @click="deleteProgram(program.id)" class="px-3 py-1.5 text-sm font-medium text-red-600 hover:bg-red-50 dark:hover:bg-gray-700 rounded-lg transition-colors">Hapus</button>
                    </div>
                </div>
            </div>
        </template>
        <template x-if="programs.length === 0">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-8 text-center">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                <p class="text-gray-500 dark:text-gray-400">Tidak ada program ditemukan</p>
                <a href="{{ route('admin.programs.create') }}" class="mt-2 inline-block text-orange-600 hover:text-orange-700 font-medium">+ Tambah program baru</a>
            </div>
        </template>
    </div>

    <!-- Desktop Table -->
    <div class="hidden lg:block bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-orange-100 dark:border-gray-700 mb-6">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-orange-500 to-orange-600 text-white">
                    <tr>
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
                    <template x-for="(program, index) in programs" :key="program.id">
                        <tr class="hover:bg-orange-50 dark:hover:bg-gray-700 transition-colors duration-150" :style="'animation-delay:' + (index * 50) + 'ms'">
                            <td class="px-6 py-4"><div class="font-semibold text-gray-800 dark:text-white" x-html="highlightText(program.title)"></div></td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300" x-text="program.category"></td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300" x-text="program.start_date"></td>
                            <td class="px-6 py-4"><span class="px-2 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300" x-text="program.type"></span></td>
                            <td class="px-6 py-4 text-sm font-medium text-orange-600" x-text="program.price"></td>
                            <td class="px-6 py-4">
                                <template x-if="program.image"><img :src="'/' + program.image" class="w-16 h-12 rounded-lg object-cover border-2 border-orange-200 hover:border-orange-400 transition-all cursor-pointer hover:scale-110" @click="window.open('/' + program.image, '_blank')"></template>
                                <template x-if="!program.image"><div class="w-16 h-12 rounded-lg bg-gradient-to-br from-orange-100 to-orange-200 flex items-center justify-center"><svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div></template>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <a :href="'/admin/programs/' + program.id + '/edit'" class="p-2 text-orange-600 hover:bg-orange-100 rounded-lg transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></a>
                                    <button @click="deleteProgram(program.id)" class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <template x-if="programs.length === 0">
                        <tr><td colspan="7" class="px-6 py-12 text-center text-gray-500"><svg class="w-16 h-16 mx-auto mb-4 text-orange-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg><p class="text-lg font-medium">Tidak ada program ditemukan</p><a href="{{ route('admin.programs.create') }}" class="mt-2 inline-block text-orange-600 hover:text-orange-700 font-medium">+ Tambah program baru</a></td></tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- Desktop Pagination -->
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 border-t border-orange-100 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <button @click="prevPage()" :disabled="currentPage === 1" :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-orange-100'" class="px-4 py-2 text-sm font-medium text-orange-600 rounded-lg transition-colors flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>Sebelumnya</button>
                <div class="flex items-center gap-1">
                    <template x-for="page in visiblePages" :key="page">
                        <button @click="page !== '...' && goToPage(page)" :class="page === currentPage ? 'bg-orange-500 text-white' : page === '...' ? 'cursor-default' : 'hover:bg-orange-100 text-gray-700'" class="w-10 h-10 text-sm font-medium rounded-lg transition-colors" x-text="page"></button>
                    </template>
                </div>
                <button @click="nextPage()" :disabled="currentPage === totalPages" :class="currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-orange-100'" class="px-4 py-2 text-sm font-medium text-orange-600 rounded-lg transition-colors flex items-center gap-2">Selanjutnya<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></button>
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
function programTable() {
    return {
        programs: @json($programs->items()),
        search: '{{ request("search") }}',
        category: '{{ request("category") }}',
        perPage: {{ $programs->perPage() }},
        currentPage: {{ $programs->currentPage() }},
        sortKey: '{{ request("sort", "created_at") }}',
        sortDir: '{{ request("dir", "desc") }}',
        total: {{ $programs->total() }},
        lastPage: {{ $programs->lastPage() }},
        loading: false,
        columns: [
            { key: 'program', label: 'Judul', sortable: true },
            { key: 'category', label: 'Kategori', sortable: true },
            { key: 'start_date', label: 'Tanggal Mulai', sortable: true },
            { key: 'type', label: 'Jenis', sortable: true },
            { key: 'price', label: 'Harga', sortable: true },
            { key: 'image', label: 'Gambar', sortable: false },
            { key: 'actions', label: 'Action', sortable: false }
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
        filterTable() { this.currentPage = 1; this.fetchData(); },
        sortBy(key) { this.sortDir = this.sortKey === key && this.sortDir === 'asc' ? 'desc' : 'asc'; this.sortKey = key; this.fetchData(); },
        goToPage(page) { if (page !== '...') { this.currentPage = page; this.fetchData(); } },
        prevPage() { if (this.currentPage > 1) { this.currentPage--; this.fetchData(); } },
        nextPage() { if (this.currentPage < this.totalPages) { this.currentPage++; this.fetchData(); } },
        fetchData() {
            this.loading = true;
            const params = new URLSearchParams({
                search: this.search || '',
                category: this.category || '',
                per_page: String(this.perPage),
                page: String(this.currentPage),
                sort: this.sortKey || 'created_at',
                dir: this.sortDir || 'desc'
            });
            fetch('{{ route('admin.programs.index') }}?' + params.toString(), {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(r => r.json())
            .then(json => {
                this.programs = json.data || [];
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
        deleteProgram(id) {
            Swal.fire({ title: "Hapus program?", text: "Data tidak dapat dikembalikan!", icon: "warning", showCancelButton: true, confirmButtonColor: "#f97316", cancelButtonColor: "#6b7280", confirmButtonText: "Ya, hapus!" }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/admin/programs/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } })
                    .then(r => r.json()).then(res => { if (res.success) { Swal.fire("Dihapus!", res.message, "success").then(() => this.fetchData()); } else { Swal.fire("Error!", res.message, "error"); } });
                }
            });
        }
    }
}
</script>
@endsection