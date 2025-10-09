@extends('teacher.shell')

@section('teacher-content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">
                <i class="fas fa-user-cog text-indigo-600 mr-3"></i>Profile Settings
            </h1>
            <p class="text-gray-600 mt-2">Manage your account settings and preferences</p>
        </div>

        <div class="space-y-6">
            <!-- Profile Information -->
            <div class="bg-white shadow-md rounded-xl p-6 sm:p-8">
                <div class="max-w-3xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Update Password -->
            <div class="bg-white shadow-md rounded-xl p-6 sm:p-8">
                <div class="max-w-3xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete Account -->
            <div class="bg-white shadow-md rounded-xl p-6 sm:p-8">
                <div class="max-w-3xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
