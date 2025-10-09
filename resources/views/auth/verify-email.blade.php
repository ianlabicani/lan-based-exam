@extends('shell')

@section('title', 'Verify Email - CBEMS')

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
            <h2 class="text-2xl font-bold text-gray-900">Verify Your Email</h2>
            <p class="text-gray-600 mt-2">Check your inbox for verification link</p>
        </div>

        <!-- Verify Card -->
        <div class="bg-white rounded-xl shadow-xl p-8">
            <div class="text-center mb-6">
                <div class="bg-indigo-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-envelope-open-text text-4xl text-indigo-600"></i>
                </div>
            </div>

            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-sm text-blue-800">
                    Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.
                </p>
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex items-start">
                    <i class="fas fa-check-circle mt-0.5 mr-2 flex-shrink-0"></i>
                    <span class="text-sm">A new verification link has been sent to the email address you provided during registration.</span>
                </div>
            @endif

            <div class="space-y-4">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button
                        type="submit"
                        class="w-full bg-indigo-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 transition duration-200 flex items-center justify-center space-x-2"
                    >
                        <i class="fas fa-paper-plane"></i>
                        <span>Resend Verification Email</span>
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                        type="submit"
                        class="w-full bg-white text-gray-700 py-3 px-4 rounded-lg font-medium border border-gray-300 hover:bg-gray-50 focus:ring-4 focus:ring-gray-200 transition duration-200 flex items-center justify-center space-x-2"
                    >
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Log Out</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Help Text -->
        <div class="text-center mt-6">
            <p class="text-sm text-gray-600">
                <i class="fas fa-question-circle mr-1"></i>
                Didn't receive the email? Check your spam folder or click resend.
            </p>
        </div>
    </div>
</div>
@endsection
