<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="w-full max-w-md bg-white shadow-lg rounded-lg p-6">
            <div class="text-center">
                <h2 class="text-2xl font-bold text-gray-800">Create an Account</h2>
                <p class="text-gray-600 text-sm">Sign up to get started</p>
            </div>

            <form class="mt-6 space-y-4" action="{{ route('register') }}" method="POST">
                @csrf

                <!-- Full Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input id="name" name="name" type="text" required
                        class="w-full mt-1 px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                        value="{{ old('name') }}">
                    @error('name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <input id="email" name="email" type="email" required
                        class="w-full mt-1 px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                        value="{{ old('email') }}">
                    @error('email')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input id="password" name="password" type="password" required
                        class="w-full mt-1 px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                    @error('password')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                        class="w-full mt-1 px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                </div>

                <!-- Phone Number -->
                <div>
                    <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number</label>
                    <input id="phone_number" name="phone_number" type="text" required
                        class="w-full mt-1 px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                        value="{{ old('phone_number') }}">
                    @error('phone_number')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Address -->
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                    <textarea id="address" name="address" rows="3" required
                        class="w-full mt-1 px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500 shadow-sm">{{ old('address') }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role Selection -->
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700">Register as</label>
                    <select id="role" name="role" required
                        class="w-full mt-1 px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                        <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('role')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Register Button -->
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-md text-sm font-semibold transition">
                    Register
                </button>
            </form>

            <!-- Login Link -->
            <p class="mt-4 text-center text-sm text-gray-600">
                Already have an account?
                <a href="{{ route('login') }}" class="text-blue-500 hover:underline">Sign In</a>
            </p>
        </div>
    </div>
</x-guest-layout>
