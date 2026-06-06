@extends('panel.layouts.app')

@section('title', 'Rekap Program')

@section('content')
    <div class="container px-6 mx-auto max-w-full">
        <div class="flex flex-col gap-8">
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-slate-100 dark:border-gray-700 p-6">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Rekap Program</h1>
                        <p class="text-sm text-slate-500 dark:text-gray-400">Ringkasan daftar peserta dan pemasukan per program.</p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                        <div class="relative w-full sm:w-64">
                            <input id="rekap-search" type="text" placeholder="Cari program..."
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm text-slate-700 dark:text-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>

                        <div class="relative w-full sm:w-48">
                            <select id="rekap-sort" class="w-full appearance-none border border-slate-200 dark:border-gray-700 rounded-xl px-4 py-2.5 pr-9 text-sm bg-white dark:bg-gray-900 text-slate-700 dark:text-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none">
                                <option value="newest">Terbaru</option>
                                <option value="oldest">Terlama</option>
                                <option value="earned">Pemasukan Terbesar</option>
                                <option value="users">Peserta Terbanyak</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-slate-100 dark:border-gray-700 p-6">
                <div class="flex flex-wrap gap-3" id="rekap-category-tabs">
                    @foreach ($categories as $category)
                        <button type="button"
                            class="rekap-tab px-4 py-2 rounded-full text-sm font-semibold border transition
                                {{ $activeCategory === $category['key'] ? 'bg-orange-600 text-white border-orange-600' : 'bg-white dark:bg-gray-900 text-slate-600 dark:text-gray-300 border-slate-200 dark:border-gray-700 hover:border-orange-500 hover:text-orange-600' }}"
                            data-category="{{ $category['key'] }}">
                            {{ $category['label'] }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div id="rekap-grid" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @forelse ($programs as $program)
                    @php
                        $programImageUrl = ($program->image && str_starts_with($program->image, 'images/'))
                            ? asset($program->image)
                            : ($program->image ? asset('storage/' . $program->image) : 'https://picsum.photos/400/250?random=' . $program->id);
                    @endphp
                    <article class="rekap-card group bg-white dark:bg-gray-900 rounded-2xl border border-slate-100 dark:border-gray-700 shadow-sm hover:shadow-lg transition overflow-hidden"
                        data-category="{{ $program->category_key }}"
                        data-title="{{ strtolower($program->title ?? '') }}"
                        data-created="{{ $program->created_at }}"
                        data-earned="{{ $program->total_earned }}"
                        data-users="{{ $program->total_users }}">
                        <div class="relative">
                            <img src="{{ $programImageUrl }}" alt="{{ $program->title }}" class="w-full h-44 object-cover">
                            <span class="absolute top-3 left-3 bg-white/90 dark:bg-gray-900/90 text-orange-600 dark:text-orange-400 text-xs font-semibold px-3 py-1 rounded-full">
                                {{ $program->category ?? 'Lainnya' }}
                            </span>
                        </div>
                        <div class="p-5 flex flex-col gap-4">
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 dark:text-white line-clamp-2">{{ $program->title }}</h3>
                                <p class="text-xs text-slate-500 dark:text-gray-400 mt-2">Jadwal: {{ $program->schedule_text }}</p>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div class="rounded-xl border border-slate-100 dark:border-gray-700 p-3 bg-slate-50 dark:bg-gray-800">
                                    <p class="text-xs text-slate-500 dark:text-gray-400">Peserta Terdaftar</p>
                                    <p class="text-lg font-bold text-slate-900 dark:text-white">{{ number_format($program->total_users) }}</p>
                                </div>
                                <div class="rounded-xl border border-slate-100 dark:border-gray-700 p-3 bg-slate-50 dark:bg-gray-800">
                                    <p class="text-xs text-slate-500 dark:text-gray-400">Total Pemasukan</p>
                                    <p class="text-lg font-bold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($program->total_earned, 0, ',', '.') }}</p>
                                </div>
                            </div>

                            @if (!empty($program->slug))
                                <a href="{{ route('admin.rekap-program.show', $program->slug) }}"
                                    class="text-sm font-semibold text-orange-600 dark:text-orange-400 inline-flex items-center gap-2">
                                    Lihat detail
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            @else
                                <span class="text-sm font-semibold text-slate-400">Detail tidak tersedia</span>
                            @endif
                        </div>
                    </article>
                @empty
                    <div class="col-span-full text-center py-16 text-slate-500 dark:text-gray-400">
                        Belum ada program yang bisa direkap.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const rekapTabs = document.querySelectorAll('.rekap-tab');
        const rekapCards = document.querySelectorAll('.rekap-card');
        const searchInput = document.getElementById('rekap-search');
        const sortSelect = document.getElementById('rekap-sort');
        const grid = document.getElementById('rekap-grid');

        const applyFilters = () => {
            const activeTab = document.querySelector('.rekap-tab.bg-orange-600');
            const activeCategory = activeTab ? activeTab.dataset.category : 'all';
            const searchTerm = (searchInput?.value || '').toLowerCase();

            rekapCards.forEach((card) => {
                const matchCategory = activeCategory === 'all' || card.dataset.category === activeCategory;
                const matchSearch = card.dataset.title.includes(searchTerm);
                card.classList.toggle('hidden', !(matchCategory && matchSearch));
            });
        };

        const applySort = () => {
            const cards = Array.from(rekapCards);
            const mode = sortSelect?.value || 'newest';

            cards.sort((a, b) => {
                if (mode === 'oldest') {
                    return new Date(a.dataset.created) - new Date(b.dataset.created);
                }
                if (mode === 'earned') {
                    return Number(b.dataset.earned) - Number(a.dataset.earned);
                }
                if (mode === 'users') {
                    return Number(b.dataset.users) - Number(a.dataset.users);
                }
                return new Date(b.dataset.created) - new Date(a.dataset.created);
            });

            cards.forEach((card) => grid.appendChild(card));
        };

        rekapTabs.forEach((tab) => {
            tab.addEventListener('click', () => {
                rekapTabs.forEach((btn) => {
                    btn.classList.remove('bg-orange-600', 'text-white', 'border-orange-600');
                    btn.classList.add('bg-white', 'dark:bg-gray-900', 'text-slate-600', 'dark:text-gray-300', 'border-slate-200', 'dark:border-gray-700');
                });
                tab.classList.add('bg-orange-600', 'text-white', 'border-orange-600');
                tab.classList.remove('bg-white', 'dark:bg-gray-900', 'text-slate-600', 'dark:text-gray-300', 'border-slate-200', 'dark:border-gray-700');

                applyFilters();
            });
        });

        searchInput?.addEventListener('input', applyFilters);
        sortSelect?.addEventListener('change', applySort);

        applyFilters();
        applySort();
    </script>
@endpush
