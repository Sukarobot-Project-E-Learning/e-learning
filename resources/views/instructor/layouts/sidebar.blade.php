<aside class="z-20 hidden w-64 overflow-y-auto bg-white dark:bg-gray-800 md:flex flex-col flex-shrink-0 border-r border-slate-100 dark:border-gray-700 transition-colors duration-200">
    <div class="flex flex-col h-full">

        {{-- Logo Section --}}
        <div class="flex items-center justify-center h-16 px-6 border-b border-slate-100 dark:border-gray-700">
            <a href="{{ route('instructor.dashboard') }}" class="flex items-center group">
                <img src="{{ asset('assets/elearning/client/img/logo.png') }}"
                    class="w-auto h-8 md:h-10 object-contain transition-all duration-300 ease-in-out hover:scale-105"
                    alt="Sukarobot Logo" />
            </a>
        </div>

        {{-- Navigation Menu --}}
        <nav class="flex-1 px-4 py-6 overflow-y-auto custom-scrollbar">
            <ul class="space-y-2">
                {{-- Dashboard --}}
                <li>
                    <a href="{{ route('instructor.dashboard') }}"
                        class="group flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200
                              {{ request()->routeIs('instructor.dashboard') 
                                 ? 'bg-blue-700 text-white shadow-lg shadow-blue-700/30' 
                                 : 'text-slate-600 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-700 dark:hover:text-blue-400' }}">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center mr-3 transition-all duration-200
                                    {{ request()->routeIs('instructor.dashboard') ? 'bg-white/20' : 'bg-blue-100 dark:bg-gray-700 group-hover:bg-blue-200 dark:group-hover:bg-gray-600' }}">
                            <svg class="w-5 h-5 {{ request()->routeIs('instructor.dashboard') ? 'text-white' : 'text-blue-700 dark:text-blue-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                        </div>
                        Dashboard
                    </a>
                </li>

                {{-- Program Saya --}}
                <li>
                    <a href="{{ route('instructor.programs.index') }}"
                        class="group flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200
                              {{ request()->routeIs('instructor.programs.*') 
                                 ? 'bg-blue-700 text-white shadow-lg shadow-blue-700/30' 
                                 : 'text-slate-600 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-700 dark:hover:text-blue-400' }}">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center mr-3 transition-all duration-200
                                    {{ request()->routeIs('instructor.programs.*') ? 'bg-white/20' : 'bg-blue-100 dark:bg-gray-700 group-hover:bg-blue-200 dark:group-hover:bg-gray-600' }}">
                            <svg class="w-5 h-5 {{ request()->routeIs('instructor.programs.*') ? 'text-white' : 'text-blue-700 dark:text-blue-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        Pengajuan Program 
                    </a>
                </li>

                {{-- Tugas/Post Test --}}
                <li>
                    <a href="{{ route('instructor.quizzes.index') }}"
                        class="group flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200
                              {{ request()->routeIs('instructor.quizzes.*') 
                                 ? 'bg-blue-700 text-white shadow-lg shadow-blue-700/30' 
                                 : 'text-slate-600 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-700 dark:hover:text-blue-400' }}">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center mr-3 transition-all duration-200
                                    {{ request()->routeIs('instructor.quizzes.*') ? 'bg-white/20' : 'bg-blue-100 dark:bg-gray-700 group-hover:bg-blue-200 dark:group-hover:bg-gray-600' }}">
                            <svg class="w-5 h-5 {{ request()->routeIs('instructor.quizzes.*') ? 'text-white' : 'text-blue-700 dark:text-blue-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </div>
                        Tugas/Post Test
                    </a>
                </li>
            </ul>
        </nav>

        {{-- Logout Section (Optional for Sidebar, but good to have) --}}
        <div class="p-4 border-t border-slate-100 dark:border-gray-700">
             <form method="POST" action="{{ route('logout') }}" data-turbo="false">
                @csrf
                <button type="submit" 
                        class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout
                </button>
            </form>
        </div>

    </div>
</aside>