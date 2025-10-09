@extends('shell')

@section('title', 'Forgot Password - CBEMS')

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
            <h2 class="text-2xl font-bold text-gray-900">Forgot Password?</h2>
            <p class="text-gray-600 mt-2">No problem. We'll send you a reset link.</p>
        </div>

        <!-- Reset Card -->
        <div class="bg-white rounded-xl shadow-xl p-8">
            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-sm text-blue-800 flex items-start">
                    <i class="fas fa-info-circle mt-0.5 mr-2 flex-shrink-0"></i>
                    <span>Enter your email address and we will send you a password reset link that will allow you to choose a new one.</span>
                </p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-2 text-gray-500"></i>Email Address
                    </label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                        placeholder="Enter your email"
                    />
                    @error('email')
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
                    <i class="fas fa-paper-plane"></i>
                    <span>Send Reset Link</span>
                </button>
            </form>
        </div>

        <!-- Back to Login -->
        <div class="text-center mt-6">
            <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900 flex items-center justify-center space-x-2">
                <i class="fas fa-arrow-left"></i>
                <span>Back to Login</span>
            </a>
        </div>
    </div>
</div>
@endsection
