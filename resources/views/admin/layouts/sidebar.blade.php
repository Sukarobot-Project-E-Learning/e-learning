<aside class="z-20 hidden w-64 overflow-y-auto bg-white dark:bg-gray-800 md:block flex-shrink-0">
    <div class="py-4 text-gray-500 dark:text-gray-400">
        <a class="ml-6 text-lg font-bold text-gray-800 dark:text-gray-200" href="{{ route('admin.dashboard') }}">
            Admin Sukarobot
        </a>

        @php
            $highlightClass = 'absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg';
            $linkClass = 'inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200';
            $activeLinkClass = 'text-gray-800 dark:text-gray-100';
            $submenuWrapperClass = 'p-2 mt-2 space-y-2 overflow-hidden text-sm font-medium text-gray-500 rounded-md shadow-inner bg-gray-50 dark:text-gray-400 dark:bg-gray-900';
            $submenuItemClass = 'px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200';
            $submenuActiveClass = 'text-gray-800 dark:text-gray-200 font-semibold';

            $icons = [
                'dashboard' => '<svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>',
                'account' => '<svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>',
                'program' => '<svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>',
                'promo' => '<svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor"><path d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>',
                'article' => '<svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor"><path d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>',
                'broadcast' => '<svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor"><path d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path></svg>',
                'report' => '<svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor"><path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>',
                'transaction' => '<svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor"><path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>',
                'quiz' => '<svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>',
            ];

            $navItems = [
                [
                    'type' => 'link',
                    'label' => 'Dashboard',
                    'icon' => 'dashboard',
                    'route' => 'admin.dashboard',
                    'activePatterns' => ['admin.dashboard'],
                ],
                [
                    'type' => 'dropdown',
                    'label' => 'Akun',
                    'icon' => 'account',
                    'activePatterns' => ['admin.users.*', 'admin.admins.*', 'admin.instructors.*'],
                    'items' => [
                        ['label' => 'Users', 'route' => 'admin.users.index', 'activePatterns' => ['admin.users.*']],
                        ['label' => 'Admin', 'route' => 'admin.admins.index', 'activePatterns' => ['admin.admins.*']],
                        ['label' => 'Instruktur', 'route' => 'admin.instructors.index', 'activePatterns' => ['admin.instructors.*']],
                    ],
                ],
                [
                    'type' => 'dropdown',
                    'label' => 'Program',
                    'icon' => 'program',
                    'activePatterns' => ['admin.programs.*', 'admin.program-approvals.*', 'admin.program-proofs.*', 'admin.certificates.*'],
                    'items' => [
                        ['label' => 'Program', 'route' => 'admin.programs.index', 'activePatterns' => ['admin.programs.*']],
                        ['label' => 'Pengajuan Program', 'route' => 'admin.program-approvals.index', 'activePatterns' => ['admin.program-approvals.*']],
                        ['label' => 'Bukti Program', 'route' => 'admin.program-proofs.index', 'activePatterns' => ['admin.program-proofs.*']],
                        ['label' => 'Sertifikat', 'route' => 'admin.certificates.index', 'activePatterns' => ['admin.certificates.*']],
                    ],
                ],
                [
                    'type' => 'dropdown',
                    'label' => 'Promosi',
                    'icon' => 'promo',
                    'activePatterns' => ['admin.promos.*', 'admin.vouchers.*'],
                    'items' => [
                        ['label' => 'Promo', 'route' => 'admin.promos.index', 'activePatterns' => ['admin.promos.*']],
                        ['label' => 'Voucher', 'route' => 'admin.vouchers.index', 'activePatterns' => ['admin.vouchers.*']],
                    ],
                ],
                [
                    'type' => 'link',
                    'label' => 'Artikel',
                    'icon' => 'article',
                    'route' => 'admin.articles.index',
                    'activePatterns' => ['admin.articles.*'],
                ],
                [
                    'type' => 'link',
                    'label' => 'Broadcast',
                    'icon' => 'broadcast',
                    'route' => 'admin.broadcasts.index',
                    'activePatterns' => ['admin.broadcasts.*'],
                ],
                [
                    'type' => 'link',
                    'label' => 'Laporan',
                    'icon' => 'report',
                    'route' => 'admin.reports.index',
                    'activePatterns' => ['admin.reports.*'],
                ],
                [
                    'type' => 'link',
                    'label' => 'Transaksi',
                    'icon' => 'transaction',
                    'route' => 'admin.transactions.index',
                    'activePatterns' => ['admin.transactions.*'],
                ],
                [
                    'type' => 'link',
                    'label' => 'Tugas/Postest',
                    'icon' => 'quiz',
                    'route' => 'admin.quizzes.index',
                    'activePatterns' => ['admin.quizzes.*'],
                ],
            ];
        @endphp

        <ul class="mt-6">
            @foreach($navItems as $item)
                @php
                    $isActive = request()->routeIs($item['activePatterns']);
                @endphp

                <li class="relative px-6 py-3" @if($item['type'] === 'dropdown') x-data="{ isOpen: {{ $isActive ? 'true' : 'false' }} }" @endif>
                    @if($isActive)
                        <span class="{{ $highlightClass }}" aria-hidden="true"></span>
                    @endif

                    @if($item['type'] === 'link')
                        <a class="{{ $linkClass }} {{ $isActive ? $activeLinkClass : '' }}" href="{{ route($item['route']) }}">
                            {!! $icons[$item['icon']] ?? '' !!}
                            <span class="ml-4">{{ $item['label'] }}</span>
                        </a>
                    @else
                        <button class="{{ $linkClass }} justify-between {{ $isActive ? $activeLinkClass : '' }}"
                                @click="isOpen = !isOpen"
                                aria-haspopup="true">
                            <span class="inline-flex items-center">
                                {!! $icons[$item['icon']] ?? '' !!}
                                <span class="ml-4">{{ $item['label'] }}</span>
                            </span>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': isOpen }" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                        <div x-show="isOpen"
                             x-transition:enter="transition-all ease-in-out duration-300"
                             x-transition:enter-start="opacity-25 max-h-0"
                             x-transition:enter-end="opacity-100 max-h-xl"
                             x-transition:leave="transition-all ease-in-out duration-300"
                             x-transition:leave-start="opacity-100 max-h-xl"
                             x-transition:leave-end="opacity-0 max-h-0"
                             class="{{ $submenuWrapperClass }}"
                             aria-label="submenu">
                            @foreach($item['items'] as $child)
                                @php
                                    $childActive = request()->routeIs($child['activePatterns']);
                                @endphp
                                <div class="{{ $submenuItemClass }} {{ $childActive ? $submenuActiveClass : '' }}">
                                    <a class="w-full block" href="{{ route($child['route']) }}">{{ $child['label'] }}</a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </li>
            @endforeach
        </ul>

    </div>
</aside>
