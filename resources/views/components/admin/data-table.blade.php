@props(['data'])

@php
    $items = $data['items'] ?? [];
    $columns = $data['columns'] ?? [];
    $pagination = $data['pagination'] ?? [];
    $actions = $data['actions'] ?? ['edit', 'delete'];
    $route = $data['route'] ?? '';
    $routeParam = $data['routeParam'] ?? null;  // e.g., 'id', 'program', 'article'
    $title = $data['title'] ?? 'Data Management';
    $description = $data['description'] ?? null;  // Optional description text
    $entity = $data['entity'] ?? 'item';
    $createLabel = $data['createLabel'] ?? 'Tambah';
    $showCreate = $data['showCreate'] ?? true;
    $headerAction = $data['headerAction'] ?? null;
    $bulkActions = $data['bulkActions'] ?? null;  // Bulk action config
    $search = $data['search'] ?? '';
    $filterValue = $data['filterValue'] ?? '';
    $filterKey = $data['filterKey'] ?? 'status';
    $filter = $data['filter'] ?? null;
    $showFilter = $data['showFilter'] ?? true;
    $sortKey = $data['sortKey'] ?? 'created_at';
    $sortDir = $data['sortDir'] ?? 'desc';
    $searchPlaceholder = $data['searchPlaceholder'] ?? 'Cari...';
    $badgeClasses = $data['badgeClasses'] ?? [];
    
    $total = $pagination['total'] ?? 0;
    $perPage = $pagination['perPage'] ?? 10;
    $currentPage = $pagination['currentPage'] ?? 1;
    $lastPage = $pagination['lastPage'] ?? 1;
    $from = $pagination['from'] ?? 0;
    $to = $pagination['to'] ?? 0;
    
    // Auto-detect route parameter name from the route
    if (!$routeParam && $route) {
        // For resource routes, the param is typically the singular form of the last segment
        // e.g., admin.programs -> program, admin.articles -> article
        $segments = explode('.', $route);
        $lastSegment = end($segments);
        // Remove trailing 's' for plural -> singular (simple heuristic)
        $routeParam = rtrim($lastSegment, 's');
    }
    $routeParam = $routeParam ?: 'id';
    // Build visible pages array
    $pages = [];
    if ($lastPage <= 7) {
        for ($i = 1; $i <= $lastPage; $i++) $pages[] = $i;
    } else {
        $pages[] = 1;
        if ($currentPage > 3) $pages[] = '...';
        for ($i = max(2, $currentPage - 1); $i <= min($lastPage - 1, $currentPage + 1); $i++) $pages[] = $i;
        if ($currentPage < $lastPage - 2) $pages[] = '...';
        $pages[] = $lastPage;
    }
    
    // Helper to get image URL
    $getImageUrl = function($path) {
        if (!$path) return null;
        if (str_starts_with($path, 'http')) return $path;
        if (str_starts_with($path, 'images/')) return '/' . $path;
        return '/storage/' . ltrim($path, '/');
    };
    
    // Helper to highlight search term
    $highlight = function($text, $search) {
        if (empty($search) || empty($text)) return e($text ?? '-');
        return preg_replace('/(' . preg_quote($search, '/') . ')/i', '<span class="search-highlight">$1</span>', e($text));
    };
    
    // Helper to get badge class
    $getBadgeClass = function($value) use ($badgeClasses) {
        if (isset($badgeClasses[$value])) {
            return $badgeClasses[$value];
        }
        // Default badge classes
        $defaultClasses = [
            'Aktif' => 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300',
            'Draft' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300',
            'Menunggu' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300',
            'Disetujui' => 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300',
            'Ditolak' => 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300',
            'Lunas' => 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300',
            'Gagal' => 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300',
            'pending' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300',
            'approved' => 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300',
            'rejected' => 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300',
        ];
        return $defaultClasses[$value] ?? 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300';
    };
    
    // Helper to check if show view exists
    $hasShowView = function() use ($route) {
        if (!$route) return false;
        $viewPath = $route . '.show';
        return view()->exists($viewPath);
    };
    
    // Determine if show action should be visible
    $showActionExists = $hasShowView();
    
    // Check if there are any actions available
    $hasAnyActions = $showActionExists || (in_array('edit', $actions) && $route) || (in_array('delete', $actions) && $route);
@endphp

<div class="admin-data-table" 
     data-entity="{{ $entity }}"
     @if($route && in_array('delete', $actions))
     data-delete-route="{{ str_replace('__ID__', ':id', route($route . '.destroy', [$routeParam => '__ID__'])) }}"
     @endif
     @if($bulkActions)
     data-bulk-route="{{ route($bulkActions['route']) }}"
     data-bulk-options="{{ json_encode($bulkActions['options'] ?? []) }}"
     data-requires-rejection-reason="{{ isset($bulkActions['requiresRejectionReason']) && $bulkActions['requiresRejectionReason'] ? 'true' : 'false' }}"
     @endif
     data-csrf="{{ csrf_token() }}"
     data-sort-key="{{ $sortKey }}"
     data-sort-dir="{{ $sortDir }}"
     data-current-page="{{ $currentPage }}"
     data-last-page="{{ $lastPage }}">
    
    <!-- Header -->
    <div class="my-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">{{ $title }}</h2>
            @if($description)
                <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">{!! $description !!}</div>
            @endif
        </div>
        <div class="flex flex-wrap items-center gap-3">
            @if($bulkActions)
                <div class="bulk-actions-container hidden items-center gap-2" data-element="bulk-actions">
                    <button type="button" class="bulk-approve-btn inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition-all duration-200 transform hover:scale-105" aria-label="Terima item yang dipilih">
                        <svg class="w-4 h-4" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Terima
                    </button>
                    <button type="button" class="bulk-reject-btn inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all duration-200 transform hover:scale-105" aria-label="Tolak item yang dipilih">
                        <svg class="w-4 h-4" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        Tolak
                    </button>
                    <span class="bulk-selected-count text-sm text-gray-500 dark:text-gray-400" data-element="bulk-count" aria-live="polite"></span>
                </div>
            @endif
            @if($headerAction)
                <a href="{{ $headerAction['url'] ?? '#' }}"
                   class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-orange-600 rounded-lg hover:bg-orange-700 focus:ring-4 focus:ring-orange-300 transition-all duration-200 transform hover:scale-105">
                    @if(isset($headerAction['icon']))
                        {!! $headerAction['icon'] !!}
                    @else
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    @endif
                    {{ $headerAction['label'] ?? 'Aksi' }}
                </a>
            @endif
            @if($showCreate && $route)
                <a href="{{ route($route . '.create') }}"
                   class="hidden md:inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-orange-600 rounded-lg hover:bg-orange-700 focus:ring-4 focus:ring-orange-300 transition-all duration-200 transform hover:scale-105">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    {{ $createLabel }}
                </a>
            @endif
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="mb-6 p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-orange-100 dark:border-gray-700">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
                <input type="text" 
                       class="table-search-input w-full pl-10 pr-10 py-2.5 text-sm border-2 border-orange-200 rounded-lg focus:border-orange-500 focus:ring-4 focus:ring-orange-100 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all duration-200"
                       placeholder="{{ $searchPlaceholder }}"
                       value="{{ $search }}">
                <span class="table-search-clear absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer {{ empty($search) ? 'hidden' : '' }}">
                    <svg class="w-4 h-4 text-gray-400 hover:text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </span>
            </div>
            @if($showFilter && $filter && isset($filter['options']))
                <select class="table-status-filter px-4 py-2.5 text-sm border-2 border-orange-200 rounded-lg focus:border-orange-500 focus:ring-4 focus:ring-orange-100 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        data-filter-key="{{ $filter['key'] ?? 'status' }}">
                    @foreach($filter['options'] as $optValue => $optLabel)
                        <option value="{{ $optValue }}" {{ $filterValue === (string)$optValue ? 'selected' : '' }}>{{ $optLabel }}</option>
                    @endforeach
                </select>
            @endif
            <select class="table-per-page-filter px-4 py-2.5 text-sm border-2 border-orange-200 rounded-lg focus:border-orange-500 focus:ring-4 focus:ring-orange-100 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 per halaman</option>
                <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25 per halaman</option>
                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 per halaman</option>
            </select>
        </div>
        <div class="mt-3 flex flex-wrap items-center justify-between gap-2 text-sm text-gray-500 dark:text-gray-400">
            <div class="flex items-center gap-1" data-element="count-container">
                <span>Menampilkan</span>
                <span class="font-semibold text-orange-600" data-element="count-from">{{ $from }}</span>-<span class="font-semibold text-orange-600" data-element="count-to">{{ $to }}</span>
                <span>dari</span>
                <span class="font-semibold text-orange-600" data-element="count-total">{{ $total }}</span>
                <span data-element="count-entity">{{ $entity }}</span>
            </div>
            <div class="text-orange-600 text-xs sm:text-sm {{ empty($search) ? 'hidden' : '' }}" data-element="search-result">
                Hasil pencarian untuk "<span class="font-semibold" data-element="search-term">{{ $search }}</span>" (<span data-element="search-count">{{ $total }}</span> hasil)
            </div>
        </div>
    </div>

    <!-- Loading Indicator -->
    <div class="table-loading hidden fixed inset-0 bg-white/50 dark:bg-gray-900/50 z-50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg">
            <svg class="animate-spin h-8 w-8 text-orange-500 mx-auto" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Memuat...</p>
        </div>
    </div>

    <!-- Mobile Card View -->
    <div class="table-mobile-view block lg:hidden space-y-3 mb-6" data-element="mobile-view">
        @forelse($items as $index => $item)
            @php $item = is_array($item) ? (object) $item : $item; @endphp
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4" data-element="mobile-card" data-id="{{ $item->id }}" style="animation-delay: {{ $index * 50 }}ms">
                <div class="flex items-start gap-3">
                    {{-- Avatar/Image --}}
                    @php
                        $avatarCol = collect($columns)->first(fn($c) => (is_array($c) ? ($c['type'] ?? '') : ($c->type ?? '')) === 'avatar');
                        $avatarKey = is_array($avatarCol) ? ($avatarCol['key'] ?? 'avatar') : ($avatarCol->key ?? 'avatar');
                        $avatarPath = $item->$avatarKey ?? null;
                        $avatarUrl = $getImageUrl($avatarPath);
                    @endphp
                    @if($avatarUrl)
                        <img src="{{ $avatarUrl }}"
                             class="w-12 h-12 rounded-full object-cover border-2 border-orange-200 cursor-pointer shrink-0"
                             style="width: 3rem; height: 3rem; min-width: 3rem; object-fit: cover;"
                             onclick="window.open(this.src, '_blank')">
                    @else
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-orange-100 to-orange-200 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        @php
                            $primaryCol = $columns[0] ?? null;
                            $primaryKey = is_array($primaryCol) ? ($primaryCol['key'] ?? 'name') : ($primaryCol->key ?? 'name');
                            $primaryVal = $item->$primaryKey ?? '-';
                        @endphp
                        <h3 class="font-semibold text-gray-800 dark:text-white truncate">
                            {!! $highlight($primaryVal, $search) !!}
                        </h3>
                        @foreach(array_slice($columns, 1, 2) as $col)
                            @php
                                $col = is_array($col) ? (object) $col : $col;
                                $colKey = $col->key ?? '';
                                $colType = $col->type ?? 'text';
                                $colVal = $item->$colKey ?? '-';
                            @endphp
                            @if($colType !== 'actions' && $colType !== 'avatar' && $colType !== 'image')
                                <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                    @if($colType === 'badge')
                                        <span class="{{ $getBadgeClass($colVal) }} px-2 py-0.5 text-xs rounded-full">{{ $colVal }}</span>
                                    @else
                                        {!! $highlight($colVal, $search) !!}
                                    @endif
                                </p>
                            @endif
                        @endforeach
                    </div>
                    @php
                        $statusCol = collect($columns)->first(fn($c) => (is_array($c) ? ($c['type'] ?? '') : ($c->type ?? '')) === 'status');
                        if ($statusCol) {
                            $statusKey = is_array($statusCol) ? ($statusCol['key'] ?? 'is_active') : ($statusCol->key ?? 'is_active');
                            $statusVal = $item->$statusKey ?? null;
                        }
                    @endphp
                    @if(isset($statusCol) && $statusVal !== null)
                        <span class="{{ $statusVal ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' }} px-2 py-1 text-xs font-bold rounded-full whitespace-nowrap">
                            {{ $statusVal ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                    @endif
                </div>
                <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end gap-2">
                    @if($showActionExists && $route)
                        <a href="{{ route($route . '.show', [$routeParam => $item->id]) }}"
                           class="px-3 py-1.5 text-sm font-medium text-blue-600 hover:bg-blue-50 dark:hover:bg-gray-700 rounded-lg transition-colors">Lihat</a>
                    @endif
                    @if(in_array('edit', $actions) && $route)
                        <a href="{{ route($route . '.edit', [$routeParam => $item->id]) }}"
                           class="px-3 py-1.5 text-sm font-medium text-orange-600 hover:bg-orange-50 dark:hover:bg-gray-700 rounded-lg transition-colors">Edit</a>
                    @endif
                    @if(in_array('delete', $actions) && $route)
                        <button type="button" class="table-delete-btn px-3 py-1.5 text-sm font-medium text-red-600 hover:bg-red-50 dark:hover:bg-gray-700 rounded-lg transition-colors"
                                data-id="{{ $item->id }}"
                                data-name="{{ $item->{$primaryKey} ?? 'item ini' }}"
                                aria-label="Hapus {{ $item->{$primaryKey} ?? 'item ini' }}">Hapus</button>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-8 text-center">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <p class="text-gray-500 dark:text-gray-400">Tidak ada {{ $entity }} ditemukan</p>
                @if($showCreate && $route)
                    <a href="{{ route($route . '.create') }}" class="mt-2 inline-block text-orange-600 hover:text-orange-700 font-medium">+ {{ $createLabel }}</a>
                @endif
            </div>
        @endforelse
    </div>

    <!-- Desktop Table -->
    <div class="table-desktop-view hidden lg:block bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-orange-100 dark:border-gray-700 mb-6">
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead class="bg-gradient-to-r from-orange-500 to-orange-600 text-white">
                    <tr>
                        @if($bulkActions)
                            <th class="px-4 py-4 text-left">
                                <input type="checkbox" class="bulk-select-all w-4 h-4 text-orange-600 bg-white rounded focus:ring-orange-500">
                            </th>
                        @endif
                        @foreach($columns as $col)
                            @php $col = is_array($col) ? (object) $col : $col; @endphp
                            @if(!(($col->type ?? '') === 'actions' && !$hasAnyActions))
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider {{ ($col->sortable ?? false) ? 'cursor-pointer hover:bg-orange-600 active:bg-orange-700 select-none transition-colors' : '' }}"
                                @if($col->sortable ?? false)
                                    onclick="window.AdminDataTable.sort('{{ $col->key }}')"
                                @endif>
                                <div class="flex items-center gap-2">
                                    <span>{{ $col->label ?? ucfirst($col->key) }}</span>
                                    @if($col->sortable ?? false)
                                        <svg class="w-4 h-4 sort-icon {{ $sortKey === $col->key ? ($sortDir === 'asc' ? 'asc' : '') : 'opacity-30' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            @endif
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-orange-100 dark:divide-gray-700">
                    @forelse($items as $index => $item)
                        @php $item = is_array($item) ? (object) $item : $item; @endphp
                        <tr class="hover:bg-orange-50 dark:hover:bg-gray-700 transition-colors duration-150" data-element="desktop-row" data-id="{{ $item->id }}" style="animation-delay: {{ $index * 50 }}ms">
                            @if($bulkActions)
                                <td class="px-4 py-4">
                                    <input type="checkbox" class="bulk-select-item w-4 h-4 text-orange-600 rounded focus:ring-orange-500" data-id="{{ $item->id }}">
                                </td>
                            @endif
                            @foreach($columns as $col)
                                @php
                                    $col = is_array($col) ? (object) $col : $col;
                                    $colKey = $col->key ?? '';
                                    $colType = $col->type ?? 'text';
                                    $colVal = $item->$colKey ?? null;
                                @endphp
                                @if(!($colType === 'actions' && !$hasAnyActions))
                                <td class="px-6 py-4">
                                    @switch($colType)
                                        @case('primary')
                                            <div class="font-semibold text-gray-800 dark:text-white">
                                                {!! $highlight($colVal ?? '-', $search) !!}
                                            </div>
                                            @break
                                        @case('avatar')
                                            @php $imgUrl = $getImageUrl($colVal); @endphp
                                            @if($imgUrl)
                                                <img src="{{ $imgUrl }}"
                                                     class="w-12 h-12 rounded-full object-cover border-2 border-orange-200 hover:border-orange-400 transition-all cursor-pointer hover:scale-110 shrink-0"
                                                     style="width: 3rem; height: 3rem; min-width: 3rem; object-fit: cover;"
                                                     onclick="window.open(this.src, '_blank')">
                                            @else
                                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-orange-100 to-orange-200 flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                                    </svg>
                                                </div>
                                            @endif
                                            @break
                                        @case('image')
                                            @php $imgUrl = $getImageUrl($colVal); @endphp
                                            @if($imgUrl)
                                                <img src="{{ $imgUrl }}"
                                                     class="w-16 h-12 rounded-lg object-cover border-2 border-orange-200 hover:border-orange-400 transition-all cursor-pointer hover:scale-110"
                                                     onclick="window.open(this.src, '_blank')">
                                            @else
                                                <div class="w-16 h-12 rounded-lg bg-gradient-to-br from-orange-100 to-orange-200 flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                            @break
                                        @case('status')
                                            <span class="{{ $colVal ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' }} px-3 py-1 text-xs font-bold rounded-full whitespace-nowrap">
                                                {{ $colVal ? ($col->trueLabel ?? 'Aktif') : ($col->falseLabel ?? 'Tidak Aktif') }}
                                            </span>
                                            @break
                                        @case('badge')
                                            <span class="{{ $getBadgeClass($colVal) }} px-3 py-1 text-xs font-bold rounded-full whitespace-nowrap">
                                                {{ $colVal ?? '-' }}
                                            </span>
                                            @break
                                        @case('currency')
                                            @php
                                                $currencyVal = is_numeric($colVal) ? 'Rp ' . number_format($colVal, 0, ',', '.') : ($colVal ?? '-');
                                            @endphp
                                            <span class="text-sm font-medium text-orange-600">{{ $currencyVal }}</span>
                                            @break
                                        @case('date')
                                            <span class="text-sm text-gray-600 dark:text-gray-300">{{ $colVal ?? '-' }}</span>
                                            @break
                                        @case('actions')
                                            <div class="flex items-center gap-3">
                                                @if($showActionExists && $route)
                                                    <a href="{{ route($route . '.show', [$routeParam => $item->id]) }}"
                                                       class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                       title="Lihat Detail"
                                                       aria-label="Lihat detail {{ $entity }}">
                                                        <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                    </a>
                                                @endif
                                                @if(in_array('edit', $actions) && $route)
                                                    <a href="{{ route($route . '.edit', [$routeParam => $item->id]) }}"
                                                       class="p-2 text-orange-600 hover:bg-orange-100 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-orange-500"
                                                       aria-label="Edit {{ $entity }}">
                                                        <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                    </a>
                                                @endif
                                                @if(in_array('delete', $actions) && $route)
                                                    @php
                                                        $primaryCol = $columns[0] ?? null;
                                                        $primaryKey = is_array($primaryCol) ? ($primaryCol['key'] ?? 'name') : ($primaryCol->key ?? 'name');
                                                    @endphp
                                                    <button type="button" class="table-delete-btn p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-red-500"
                                                            data-id="{{ $item->id }}"
                                                            data-name="{{ $item->$primaryKey ?? 'item ini' }}"
                                                            aria-label="Hapus {{ $item->$primaryKey ?? 'item ini' }}">
                                                        <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                @endif
                                            </div>
                                            @break
                                        @default
                                            <span class="text-sm text-gray-600 dark:text-gray-300">
                                                {!! $highlight($colVal ?? '-', $search) !!}
                                            </span>
                                    @endswitch
                                </td>
                                @endif
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            @php
                                $visibleColCount = collect($columns)->filter(fn($c) => !((is_array($c) ? ($c['type'] ?? '') : ($c->type ?? '')) === 'actions' && !$hasAnyActions))->count();
                            @endphp
                            <td colspan="{{ $visibleColCount }}" class="px-6 py-12 text-center text-gray-500">
                                <svg class="w-16 h-16 mx-auto mb-4 text-orange-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                                <p class="text-lg font-medium">Tidak ada {{ $entity }} ditemukan</p>
                                @if($showCreate && $route)
                                    <a href="{{ route($route . '.create') }}" class="mt-2 inline-block text-orange-600 hover:text-orange-700 font-medium">+ {{ $createLabel }}</a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Desktop Pagination -->
        @if($total > 0)
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 border-t border-orange-100 dark:border-gray-700" data-element="desktop-pagination">
                <nav class="flex items-center justify-between" aria-label="Navigasi halaman">
                    <button type="button" onclick="window.AdminDataTable.prevPage()"
                            {{ $currentPage === 1 ? 'disabled' : '' }}
                            class="px-4 py-2 text-sm font-medium text-orange-600 rounded-lg transition-colors flex items-center gap-2 focus:outline-none focus:ring-2 focus:ring-orange-500 {{ $currentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-orange-100' }}"
                            aria-label="Halaman sebelumnya">
                        <svg class="w-4 h-4" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Sebelumnya
                    </button>
                    <div class="flex items-center gap-1" role="list" aria-label="Daftar halaman">
                        @foreach($pages as $page)
                            @if($page === '...')
                                <span class="w-10 h-10 text-sm font-medium rounded-lg flex items-center justify-center text-gray-400" aria-hidden="true">...</span>
                            @else
                                <button type="button" onclick="window.AdminDataTable.goToPage({{ $page }})"
                                        class="w-10 h-10 text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-orange-500 {{ $page === $currentPage ? 'bg-orange-500 text-white' : 'hover:bg-orange-100 text-gray-700' }}"
                                        aria-label="Halaman {{ $page }}"
                                        {{ $page === $currentPage ? 'aria-current=page' : '' }}>{{ $page }}</button>
                            @endif
                        @endforeach
                    </div>
                    <button type="button" onclick="window.AdminDataTable.nextPage()"
                            {{ $currentPage === $lastPage ? 'disabled' : '' }}
                            class="px-4 py-2 text-sm font-medium text-orange-600 rounded-lg transition-colors flex items-center gap-2 focus:outline-none focus:ring-2 focus:ring-orange-500 {{ $currentPage === $lastPage ? 'opacity-50 cursor-not-allowed' : 'hover:bg-orange-100' }}"
                            aria-label="Halaman selanjutnya">
                        Selanjutnya
                        <svg class="w-4 h-4" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </nav>
            </div>
        @endif
    </div>

    <!-- Mobile Pagination -->
    @if($total > 0)
        <div class="block lg:hidden bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4" data-element="mobile-pagination">
            <nav class="flex items-center justify-between" aria-label="Navigasi halaman mobile">
                <button type="button" onclick="window.AdminDataTable.prevPage()"
                        {{ $currentPage === 1 ? 'disabled' : '' }}
                        class="px-3 py-2 text-sm font-medium text-orange-600 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-orange-500 {{ $currentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-orange-100' }}"
                        aria-label="Halaman sebelumnya">
                    <svg class="w-4 h-4" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <div class="flex items-center gap-1" role="list">
                    @foreach($pages as $page)
                        @if($page === '...')
                            <span class="w-10 h-10 text-sm font-medium rounded-lg flex items-center justify-center text-gray-400" aria-hidden="true">...</span>
                        @else
                            <button type="button" onclick="window.AdminDataTable.goToPage({{ $page }})"
                                    class="w-10 h-10 text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-orange-500 {{ $page === $currentPage ? 'bg-orange-500 text-white' : 'hover:bg-orange-100 text-gray-700' }}"
                                    aria-label="Halaman {{ $page }}"
                                    {{ $page === $currentPage ? 'aria-current=page' : '' }}>{{ $page }}</button>
                        @endif
                    @endforeach
                </div>
                <button type="button" onclick="window.AdminDataTable.nextPage()"
                        {{ $currentPage === $lastPage ? 'disabled' : '' }}
                        class="px-3 py-2 text-sm font-medium text-orange-600 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-orange-500 {{ $currentPage === $lastPage ? 'opacity-50 cursor-not-allowed' : 'hover:bg-orange-100' }}"
                        aria-label="Halaman selanjutnya">
                    <svg class="w-4 h-4" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </nav>
        </div>
    @endif

    <!-- Mobile FAB -->
    @if($showCreate && $route)
        <a href="{{ route($route . '.create') }}"
           class="md:hidden btn-float inline-flex items-center justify-center w-14 h-14 text-white bg-orange-600 rounded-full hover:bg-orange-700 focus:outline-none focus:ring-4 focus:ring-orange-300 shadow-lg"
           aria-label="{{ $createLabel }}">
            <svg class="w-6 h-6" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
        </a>
    @endif

    <!-- Bulk Rejection Reason Modal -->
    @if($bulkActions && isset($bulkActions['requiresRejectionReason']) && $bulkActions['requiresRejectionReason'])
    <div id="bulkRejectModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity bulk-reject-modal-backdrop"></div>
            <div class="relative bg-white rounded-lg shadow-xl dark:bg-gray-800 max-w-md w-full p-6">
                <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-gray-100">Tolak Item yang Dipilih</h3>
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Alasan Penolakan</label>
                    <textarea id="bulkRejectionReason" rows="4"
                        class="w-full px-3 py-2 text-sm text-gray-700 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600"
                        placeholder="Masukkan alasan penolakan (minimal 15 karakter)..."></textarea>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Minimal 15 karakter</p>
                </div>
                <div class="flex gap-2">
                    <button type="button" class="bulk-reject-modal-cancel flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-300">
                        Batal
                    </button>
                    <button type="button" class="bulk-reject-modal-submit flex-1 px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                        Tolak
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
