<!DOCTYPE html>
<html :class="{ 'theme-dark': dark }" x-data="data()" lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - E-Learning Sukarobot</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    />
    <link rel="stylesheet" href="{{ asset('assets/elearning/admin/css/tailwind.output.css') }}" />
    <script
        src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js"
        defer
    ></script>
    <script>
        function data() {
            return {
                dark: false,
            }
        }
    </script>
</head>
<body>
    <div class="flex items-center min-h-screen p-6 bg-gray-50 dark:bg-gray-900">
        <div
            class="flex-1 h-full max-w-4xl mx-auto overflow-hidden bg-white rounded-lg shadow-xl dark:bg-gray-800"
        >
            <div class="flex flex-col overflow-y-auto md:flex-row">
                <!-- Image Section -->
                <div class="h-32 md:h-auto md:w-1/2">
                    <img
                        aria-hidden="true"
                        class="object-cover w-full h-full dark:hidden"
                        src="{{ asset('assets/img/login-office.jpeg') }}"
                        alt="Office"
                        onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'800\' height=\'600\'%3E%3Crect fill=\'%23e5e7eb\' width=\'800\' height=\'600\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' fill=\'%239ca3af\' font-family=\'sans-serif\' font-size=\'24\'%3ELogin%3C/text%3E%3C/svg%3E'"
                    />
                    <img
                        aria-hidden="true"
                        class="hidden object-cover w-full h-full dark:block"
                        src="{{ asset('assets/img/login-office-dark.jpeg') }}"
                        alt="Office"
                        onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'800\' height=\'600\'%3E%3Crect fill=\'%231f2937\' width=\'800\' height=\'600\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' fill=\'%236b7280\' font-family=\'sans-serif\' font-size=\'24\'%3ELogin%3C/text%3E%3C/svg%3E'"
                    />
                </div>
                
                <!-- Form Section -->
                <div class="flex items-center justify-center p-6 sm:p-12 md:w-1/2">
                    <div class="w-full">
                        <h1 class="mb-4 text-xl font-semibold text-gray-700 dark:text-gray-200">
                            Login {{ isset($type) && $type === 'admin' ? 'Admin' : 'Instruktur' }}
                        </h1>
                        
                        <!-- Error Messages -->
                        @if ($errors->any())
                            <div class="mb-4 p-3 text-sm text-red-700 bg-red-100 border border-red-400 rounded dark:bg-red-800 dark:text-red-200 dark:border-red-600">
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Success Message -->
                        @if (session('success'))
                            <div class="mb-4 p-3 text-sm text-green-700 bg-green-100 border border-green-400 rounded dark:bg-green-800 dark:text-green-200 dark:border-green-600">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ isset($type) && $type === 'admin' ? route('admin.login') : route('instructor.login') }}">
                            @csrf
                            
                            <label class="block text-sm">
                                <span class="text-gray-700 dark:text-gray-400">Email</span>
                                <input
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                    placeholder="admin@sukarobot.com"
                                    required
                                    autofocus
                                />
                            </label>
                            
                            <label class="block mt-4 text-sm">
                                <span class="text-gray-700 dark:text-gray-400">Password</span>
                                <input
                                    type="password"
                                    name="password"
                                    class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                    placeholder="***************"
                                    required
                                />
                            </label>

                            <label class="flex items-center mt-4 text-sm">
                                <input
                                    type="checkbox"
                                    name="remember"
                                    class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500 dark:focus:ring-purple-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600"
                                />
                                <span class="ml-2 text-gray-700 dark:text-gray-400">Remember me</span>
                            </label>

                            <button
                                type="submit"
                                class="block w-full px-4 py-2 mt-4 text-sm font-medium leading-5 text-center text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple"
                            >
                                Log in
                            </button>
                        </form>

                        <hr class="my-8" />

                        @if(isset($type) && $type === 'instructor')
                        <p class="mt-4">
                            <a
                                class="text-sm font-medium text-purple-600 dark:text-purple-400 hover:underline"
                                href="{{ route('client.reset-password') }}"
                            >
                                Forgot your password?
                            </a>
                        </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

