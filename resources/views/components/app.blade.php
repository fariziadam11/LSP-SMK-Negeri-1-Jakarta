<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="bg-white shadow-md sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <span class="text-2xl font-bold text-blue-600">OkFly</span>
                    </div>

                    <div class="sm:hidden flex items-center">
                        <button id="mobile-menu-toggle" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                            <svg id="mobile-menu-icon" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="{{ route('dashboard') }}"
                        class="{{ request()->routeIs('dashboard') ? 'border-b-2 border-blue-500 text-gray-900' : 'border-transparent hover:border-gray-300 text-gray-500 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 text-sm font-medium">
                            Dashboard
                        </a>
                        <a href="{{ route('flights.search') }}"
                        class="{{ request()->routeIs('flights.search') ? 'border-b-2 border-blue-500 text-gray-900' : 'border-transparent hover:border-gray-300 text-gray-500 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 text-sm font-medium">
                            Book Flight
                        </a>
                    </div>

                    <!-- User Menu -->
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-700 hidden md:inline">{{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}" id="logout-form">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg text-sm font-medium hover:bg-red-600 transition duration-300">Logout</button>
                        </form>
                    </div>
                </div>
            </div>

            <div id="mobile-menu" class="sm:hidden absolute top-16 left-0 w-full bg-white shadow-md hidden">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <a href="{{ route('dashboard') }}" class="text-gray-500 hover:bg-gray-100 block px-3 py-2 rounded-md text-base font-medium">
                        Dashboard
                    </a>
                    <a href="{{ route('flights.search') }}" class="text-gray-500 hover:bg-gray-100 block px-3 py-2 rounded-md text-base font-medium">
                        Book Flight
                    </a>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="flex-1 py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                @yield('content')
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        const mobileMenuIcon = document.getElementById('mobile-menu-icon');
        const mobileLogoutForm = document.getElementById('mobile-logout-form');

        // Toggle mobile menu
        mobileMenuToggle.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');

            // Change menu icon
            if (mobileMenu.classList.contains('hidden')) {
                mobileMenuIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />';
            } else {
                mobileMenuIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />';
            }
        });

        // Logout confirmation for mobile
        if (mobileLogoutForm) {
            mobileLogoutForm.addEventListener('submit', function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, logout!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        }

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!mobileMenu.classList.contains('hidden') &&
                !mobileMenuToggle.contains(event.target) &&
                !mobileMenu.contains(event.target)) {
                mobileMenu.classList.add('hidden');
                mobileMenuIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />';
            }
        });
    });
    </script>
</body>
</html>
