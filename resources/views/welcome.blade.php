@extends('shell')

@section('title', 'Welcome To CBEMS')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <!-- Header -->
    <header class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-graduation-cap text-4xl text-indigo-600"></i>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">CBEMS</h1>
                        <p class="text-sm text-gray-600">Computer-Based Examination and Monitoring System</p>
                    </div>
                </div>
                <div class="flex space-x-4">
                    <a href="/login" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-200 font-medium">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center mb-16">
            <h2 class="text-5xl font-bold text-gray-900 mb-4">
                Welcome to CBEMS
            </h2>
            <p class="text-xl text-gray-700 max-w-3xl mx-auto">
                A comprehensive web-based examination management platform for conducting, scheduling, and monitoring student examinations in real time with advanced activity tracking.
            </p>
        </div>

        <!-- User Roles -->
        <div class="grid md:grid-cols-3 gap-8 mb-16">
            <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition duration-300 border-t-4 border-blue-500">
                <div class="text-center">
                    <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-chalkboard-teacher text-3xl text-blue-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Teacher</h3>
                    <p class="text-gray-600">Create and manage exams, track student performance, and grade submissions efficiently.</p>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition duration-300 border-t-4 border-green-500">
                <div class="text-center">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user-graduate text-3xl text-green-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Student</h3>
                    <p class="text-gray-600">Take exams, view results, and track your academic progress in a secure environment.</p>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition duration-300 border-t-4 border-purple-500">
                <div class="text-center">
                    <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user-shield text-3xl text-purple-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Administrator</h3>
                    <p class="text-gray-600">Oversee system operations, manage users, and ensure smooth platform functionality.</p>
                </div>
            </div>
        </div>

        <!-- Core Features -->
        <div class="bg-white rounded-xl shadow-lg p-8 mb-16">
            <h3 class="text-3xl font-bold text-gray-900 mb-8 text-center">
                <i class="fas fa-star text-yellow-500 mr-3"></i>Core Features
            </h3>
            <div class="grid md:grid-cols-2 gap-8">
                <!-- Authentication -->
                <div class="flex items-start space-x-4">
                    <div class="bg-indigo-100 p-3 rounded-lg flex-shrink-0">
                        <i class="fas fa-lock text-2xl text-indigo-600"></i>
                    </div>
                    <div>
                        <h4 class="text-xl font-bold text-gray-900 mb-2">Secure Authentication</h4>
                        <p class="text-gray-600">Robust user authentication system with session management and role-based access control.</p>
                    </div>
                </div>

                <!-- Monitoring -->
                <div class="flex items-start space-x-4">
                    <div class="bg-red-100 p-3 rounded-lg flex-shrink-0">
                        <i class="fas fa-eye text-2xl text-red-600"></i>
                    </div>
                    <div>
                        <h4 class="text-xl font-bold text-gray-900 mb-2">Real-Time Monitoring</h4>
                        <p class="text-gray-600">Advanced activity tracking monitors tab changes, visibility, focus, and idle time during exams.</p>
                    </div>
                </div>

                <!-- Exam Management -->
                <div class="flex items-start space-x-4">
                    <div class="bg-green-100 p-3 rounded-lg flex-shrink-0">
                        <i class="fas fa-tasks text-2xl text-green-600"></i>
                    </div>
                    <div>
                        <h4 class="text-xl font-bold text-gray-900 mb-2">Comprehensive Exam Management</h4>
                        <p class="text-gray-600">Create exams with multiple question types including MCQ, True/False, Essay, Matching, and more.</p>
                    </div>
                </div>

                <!-- Scheduling -->
                <div class="flex items-start space-x-4">
                    <div class="bg-blue-100 p-3 rounded-lg flex-shrink-0">
                        <i class="fas fa-calendar-alt text-2xl text-blue-600"></i>
                    </div>
                    <div>
                        <h4 class="text-xl font-bold text-gray-900 mb-2">Smart Scheduling</h4>
                        <p class="text-gray-600">Automated exam publishing based on schedules with precise timing and duration controls.</p>
                    </div>
                </div>

                <!-- Dashboard -->
                <div class="flex items-start space-x-4">
                    <div class="bg-purple-100 p-3 rounded-lg flex-shrink-0">
                        <i class="fas fa-chart-line text-2xl text-purple-600"></i>
                    </div>
                    <div>
                        <h4 class="text-xl font-bold text-gray-900 mb-2">Interactive Dashboards</h4>
                        <p class="text-gray-600">Role-specific dashboards with analytics, performance metrics, and activity summaries.</p>
                    </div>
                </div>

                <!-- Grading -->
                <div class="flex items-start space-x-4">
                    <div class="bg-yellow-100 p-3 rounded-lg flex-shrink-0">
                        <i class="fas fa-check-circle text-2xl text-yellow-600"></i>
                    </div>
                    <div>
                        <h4 class="text-xl font-bold text-gray-900 mb-2">Flexible Grading System</h4>
                        <p class="text-gray-600">Automated grading for objective questions with manual review options for subjective answers.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Question Types -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl shadow-lg p-8 mb-16 text-white">
            <h3 class="text-3xl font-bold mb-6 text-center">
                <i class="fas fa-list-alt mr-3"></i>Supported Question Types
            </h3>
            <div class="grid md:grid-cols-3 gap-4">
                <div class="flex items-center space-x-3 bg-white bg-opacity-20 rounded-lg p-4">
                    <i class="fas fa-check-square text-2xl"></i>
                    <span class="font-medium">Multiple Choice</span>
                </div>
                <div class="flex items-center space-x-3 bg-white bg-opacity-20 rounded-lg p-4">
                    <i class="fas fa-toggle-on text-2xl"></i>
                    <span class="font-medium">True or False</span>
                </div>
                <div class="flex items-center space-x-3 bg-white bg-opacity-20 rounded-lg p-4">
                    <i class="fas fa-pencil-alt text-2xl"></i>
                    <span class="font-medium">Short Answer</span>
                </div>
                <div class="flex items-center space-x-3 bg-white bg-opacity-20 rounded-lg p-4">
                    <i class="fas fa-link text-2xl"></i>
                    <span class="font-medium">Matching Type</span>
                </div>
                <div class="flex items-center space-x-3 bg-white bg-opacity-20 rounded-lg p-4">
                    <i class="fas fa-file-alt text-2xl"></i>
                    <span class="font-medium">Essay</span>
                </div>
                <div class="flex items-center space-x-3 bg-white bg-opacity-20 rounded-lg p-4">
                    <i class="fas fa-edit text-2xl"></i>
                    <span class="font-medium">Fill in the Blanks</span>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="text-center bg-white rounded-xl shadow-lg p-12">
            <h3 class="text-3xl font-bold text-gray-900 mb-4">Ready to Get Started?</h3>
            <p class="text-gray-600 mb-8 text-lg">Join CBEMS today and experience a modern approach to examination management.</p>
            <a href="/login" class="inline-block px-8 py-4 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-200 font-bold text-lg shadow-lg">
                <i class="fas fa-rocket mr-2"></i>Login Now
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-gray-400">
                <i class="fas fa-copyright mr-1"></i>{{ date('Y') }} Computer-Based Examination and Monitoring System. All rights reserved.
            </p>
        </div>
    </footer>
</div>
@endsection
