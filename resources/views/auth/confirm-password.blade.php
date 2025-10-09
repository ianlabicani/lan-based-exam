@extends('shell')

@section('title', 'Confirm Password - CBEMS')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <!-- Logo and Header -->
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center justify-center space-x-3 mb-6">
                <i class="fas fa-graduation-cap text-5xl text-indigo-600"></i>
                <div class="text-left">
                    <h1 class="text-3xl font-bold text-gray-900">CBEMS</h1>
                    <p class="text-xs text-gray-600">Examination System</p>
                </div>
            </a>
            <h2 class="text-2xl font-bold text-gray-900">Confirm Password</h2>
            <p class="text-gray-600 mt-2">Secure area - verification required</p>
        </div>

        <!-- Confirm Card -->
        <div class="bg-white rounded-xl shadow-xl p-8">
            <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <p class="text-sm text-yellow-800 flex items-start">
                    <i class="fas fa-shield-alt mt-0.5 mr-2 flex-shrink-0"></i>
                    <span>This is a secure area of the application. Please confirm your password before continuing.</span>
                </p>
            </div>

            <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
                @csrf

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2 text-gray-500"></i>Password
                    </label>
                    <div class="relative">
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                            placeholder="Enter your password"
                            autofocus
                        />
                        <button
                            type="button"
                            onclick="togglePassword()"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700"
                        >
                            <i id="toggleIcon" class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full bg-indigo-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 transition duration-200 flex items-center justify-center space-x-2"
                >
                    <i class="fas fa-check-circle"></i>
                    <span>Confirm</span>
                </button>
            </form>
        </div>

        <!-- Back -->
        <div class="text-center mt-6">
            <a href="javascript:history.back()" class="text-sm text-gray-600 hover:text-gray-900 flex items-center justify-center space-x-2">
                <i class="fas fa-arrow-left"></i>
                <span>Go Back</span>
            </a>
        </div>
    </div>
</div>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }
</script>
@endsection
