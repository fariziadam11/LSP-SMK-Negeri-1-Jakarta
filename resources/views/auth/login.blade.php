<x-guest-layout>
    <div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8 bg-white">
        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-6 shadow-2xl rounded-xl sm:px-10">
                <h2 class="text-2xl font-extrabold text-gray-900 drop-shadow-lg text-center whitespace-nowrap">
                    Sign in to your account
                </h2>
                
                @if (session('success'))
                    <div class="mb-4 text-sm font-medium text-green-600 text-center">
                        {{ session('success') }}
                    </div>
                @endif
                
                <form class="space-y-6" action="{{ route('login') }}" method="POST">
                    @csrf
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 text-left">
                            Email Address
                        </label>
                        <div class="mt-1 relative">
                            <input id="email" name="email" type="email" required
                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition duration-300">
                        </div>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 text-left">
                            Password
                        </label>
                        <div class="mt-1 relative">
                            <input id="password" name="password" type="password" required
                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition duration-300">
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-lg text-lg font-medium text-white bg-gradient-to-r from-blue-500 to-purple-600 hover:opacity-90 focus:outline-none focus:ring-4 focus:ring-blue-300 transition duration-300">
                            Sign in
                        </button>
                    </div>
                </form>
                <p class="mt-4 text-sm text-gray-600 text-center">
                    Or
                    <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-500 underline">
                        create a new account
                    </a>
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
