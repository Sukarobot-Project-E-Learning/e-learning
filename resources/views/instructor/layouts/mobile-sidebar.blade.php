{{-- Mobile Sidebar - mobile-sidebar.blade.php --}}
{{-- Required: Tailwind CSS CDN and Alpine.js CDN in your layout --}}
{{-- <script src="https://cdn.tailwindcss.com"></script> --}}
{{-- <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}

<div x-data="{ open: false, coursesOpen: false }" 
     @toggle-mobile-sidebar.window="open = !open"
     class="md:hidden">
    
    {{-- Backdrop --}}
    <div x-show="open" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="open = false"
         class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm"></div>

    {{-- Sidebar Panel --}}
    <aside x-show="open"
           x-transition:enter="transition ease-out duration-300 transform"
           x-transition:enter-start="-translate-x-full"
           x-transition:enter-end="translate-x-0"
           x-transition:leave="transition ease-in duration-300 transform"
           x-transition:leave-start="translate-x-0"
           x-transition:leave-end="-translate-x-full"
           class="fixed inset-y-0 left-0 z-50 w-80 overflow-y-auto bg-white dark:bg-gray-800 shadow-2xl transition-colors duration-200">
        
        <div class="flex flex-col h-full">
            
            {{-- Header with Logo & Close --}}
            <div class="flex items-center justify-between p-5 border-b border-slate-100 dark:border-gray-700">
                <a href="{{ route('instructor.dashboard') }}" class="flex items-center space-x-3 group">
                    <div class="relative">
                        <div class="w-10 h-10 bg-blue-700 rounded-xl flex items-center justify-center shadow-lg shadow-blue-700/30">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                            </svg>
                        </div>
                        <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-orange-500 rounded-full border-2 border-white dark:border-gray-800"></div>
                    </div>
                    <div>
                        <h1 class="text-base font-extrabold text-blue-700 dark:text-blue-400 tracking-tight">Sukarobot</h1>
                        <p class="text-[9px] font-bold text-orange-500 uppercase tracking-widest -mt-0.5">LMS</p>
                    </div>
                </a>
                <button @click="open = false" 
                        class="p-2.5 text-slate-400 dark:text-gray-500 rounded-xl hover:bg-slate-100 dark:hover:bg-gray-700 hover:text-slate-600 dark:hover:text-gray-300 focus:outline-none transition-colors duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>


            {{-- Navigation Menu --}}
            <nav class="flex-1 px-4 pb-4 overflow-y-auto">
                <ul class="space-y-1">
                    {{-- Dashboard --}}
                    <li>
                        <a href="{{ route('instructor.dashboard') }}"
                           @click="open = false"
                           class="group flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200
                                  {{ request()->routeIs('instructor.dashboard') 
                                     ? 'bg-blue-700 text-white shadow-lg shadow-blue-700/30' 
                                     : 'text-slate-600 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-700 dark:hover:text-blue-400' }}">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center mr-3 transition-all duration-200
                                        {{ request()->routeIs('instructor.dashboard') ? 'bg-white/20' : 'bg-blue-100 dark:bg-gray-700' }}">
                                <svg class="w-5 h-5 {{ request()->routeIs('instructor.dashboard') ? 'text-white' : 'text-blue-700 dark:text-blue-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                </svg>
                            </div>
                            Dashboard
                        </a>
                    </li>

                    {{-- Program Saya --}}
                    <li>
                        <a href="{{ route('instructor.programs.index') }}"
                           @click="open = false"
                           class="group flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200
                                  {{ request()->routeIs('instructor.programs.*') 
                                     ? 'bg-blue-700 text-white shadow-lg shadow-blue-700/30' 
                                     : 'text-slate-600 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-700 dark:hover:text-blue-400' }}">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center mr-3 transition-all duration-200
                                        {{ request()->routeIs('instructor.programs.*') ? 'bg-white/20' : 'bg-blue-100 dark:bg-gray-700' }}">
                                <svg class="w-5 h-5 {{ request()->routeIs('instructor.programs.*') ? 'text-white' : 'text-blue-700 dark:text-blue-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                            Program Saya
                        </a>
                    </li>

                    {{-- Tugas/Post Test --}}
                    <li>
                        <a href="{{ route('instructor.quizzes.index') }}"
                           @click="open = false"
                           class="group flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200
                                  {{ request()->routeIs('instructor.quizzes.*') 
                                     ? 'bg-blue-700 text-white shadow-lg shadow-blue-700/30' 
                                     : 'text-slate-600 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-700 dark:hover:text-blue-400' }}">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center mr-3 transition-all duration-200
                                        {{ request()->routeIs('instructor.quizzes.*') ? 'bg-white/20' : 'bg-blue-100 dark:bg-gray-700' }}">
                                <svg class="w-5 h-5 {{ request()->routeIs('instructor.quizzes.*') ? 'text-white' : 'text-blue-700 dark:text-blue-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                            </div>
                            Tugas/Post Test
                        </a>
                    </li>
                </ul>
            </nav>

            {{-- Bottom Section --}}
            <div class="p-4 border-t border-slate-100 dark:border-gray-700">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                            class="flex items-center justify-center w-full px-4 py-3 text-sm font-semibold text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/30 rounded-xl hover:bg-red-100 dark:hover:bg-red-900/50 transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </aside>
</div>
