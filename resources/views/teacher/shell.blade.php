@extends('shell')

@section('content')

<!-- Teacher Navbar -->
<nav class="bg-white shadow-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo and Brand -->
            <div class="flex items-center space-x-8">
                <a href="{{ route('teacher.dashboard') }}" class="flex items-center space-x-3">
                    <i class="fas fa-graduation-cap text-3xl text-indigo-600"></i>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">CBEMS</h1>
                        <p class="text-xs text-gray-600">Teacher Portal</p>
                    </div>
                </a>

                <!-- Main Navigation Links -->
                <div class="hidden md:flex items-center space-x-1">
                    <a href="{{ route('teacher.dashboard') }}" class="px-4 py-2 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 font-medium transition duration-200 {{ request()->routeIs('teacher.dashboard') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                        <i class="fas fa-home mr-2"></i>Dashboard
                    </a>
                    <a href="{{ route('teacher.exams.index') }}" class="px-4 py-2 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 font-medium transition duration-200 {{ request()->routeIs('teacher.exams.*') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                        <i class="fas fa-file-alt mr-2"></i>Exams
                    </a>
                    <a href="{{ route('teacher.analytics') }}" class="px-4 py-2 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 font-medium transition duration-200 {{ request()->routeIs('teacher.analytics') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                        <i class="fas fa-chart-line mr-2"></i>Analytics
                    </a>
                    <a href="{{ route('teacher.grading.index') }}" class="px-4 py-2 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 font-medium transition duration-200 {{ request()->routeIs('teacher.grading.*') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                        <i class="fas fa-clipboard-check mr-2"></i>Grading
                    </a>
                </div>
            </div>

            <!-- Right Side Actions -->
            <div class="flex items-center space-x-4">
                <!-- Active Takers Indicator -->
                <a href="#" class="hidden lg:flex items-center space-x-2 px-3 py-2 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition duration-200">
                    <div class="relative">
                        <i class="fas fa-user-clock text-lg"></i>
                        <span class="absolute -top-1 -right-1 w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                    </div>
                    <span class="text-sm font-medium">12 Active</span>
                </a>

                <!-- Notifications -->
                <div class="relative">
                    <button onclick="toggleNotifications()" class="relative p-2 text-gray-600 hover:text-indigo-600 hover:bg-gray-100 rounded-lg transition duration-200">
                        <i class="fas fa-bell text-xl"></i>
                        <span class="absolute top-0 right-0 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">3</span>
                    </button>

                    <!-- Notifications Dropdown -->
                    <div id="notificationsDropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 overflow-hidden">
                        <div class="p-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
                            <h3 class="font-semibold">Notifications</h3>
                            <p class="text-xs text-indigo-100">You have 3 unread notifications</p>
                        </div>
                        <div class="max-h-96 overflow-y-auto">
                            <!-- Notification Item -->
                            <a href="#" class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100">
                                <div class="flex items-start space-x-3">
                                    <div class="bg-orange-100 p-2 rounded-full">
                                        <i class="fas fa-exclamation-triangle text-orange-600"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">Suspicious activity detected</p>
                                        <p class="text-xs text-gray-600">Student switched tabs 5 times during exam</p>
                                        <p class="text-xs text-gray-500 mt-1">2 minutes ago</p>
                                    </div>
                                </div>
                            </a>
                            <a href="#" class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100">
                                <div class="flex items-start space-x-3">
                                    <div class="bg-green-100 p-2 rounded-full">
                                        <i class="fas fa-check-circle text-green-600"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">Exam completed</p>
                                        <p class="text-xs text-gray-600">Sarah Williams submitted Quiz 3</p>
                                        <p class="text-xs text-gray-500 mt-1">15 minutes ago</p>
                                    </div>
                                </div>
                            </a>
                            <a href="#" class="block px-4 py-3 hover:bg-gray-50">
                                <div class="flex items-start space-x-3">
                                    <div class="bg-blue-100 p-2 rounded-full">
                                        <i class="fas fa-calendar-alt text-blue-600"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">Exam scheduled</p>
                                        <p class="text-xs text-gray-600">Midterm Exam starts in 2 hours</p>
                                        <p class="text-xs text-gray-500 mt-1">1 hour ago</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="p-3 bg-gray-50 text-center">
                            <a href="#" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">View All Notifications</a>
                        </div>
                    </div>
                </div>

                <!-- User Profile Dropdown -->
                <div class="relative">
                    <button onclick="toggleProfileMenu()" class="flex items-center space-x-3 px-3 py-2 hover:bg-gray-100 rounded-lg transition duration-200">
                        <div class="bg-indigo-600 text-white w-10 h-10 rounded-full flex items-center justify-center font-semibold">
                            SJ
                        </div>
                        <div class="hidden md:block text-left">
                            <p class="text-sm font-semibold text-gray-900">Dr. Sarah Johnson</p>
                            <p class="text-xs text-gray-600">Teacher</p>
                        </div>
                        <i class="fas fa-chevron-down text-gray-600 text-sm"></i>
                    </button>

                    <!-- Profile Dropdown -->
                    <div id="profileDropdown" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl border border-gray-200 overflow-hidden">
                        <div class="p-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
                            <p class="font-semibold">Dr. Sarah Johnson</p>
                            <p class="text-xs text-indigo-100">sarah.johnson@school.edu</p>
                        </div>
                        <div class="py-2">
                            <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition duration-200">
                                <i class="fas fa-user-circle mr-3 w-5"></i>My Profile
                            </a>
                            <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition duration-200">
                                <i class="fas fa-cog mr-3 w-5"></i>Settings
                            </a>
                            <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition duration-200">
                                <i class="fas fa-question-circle mr-3 w-5"></i>Help & Support
                            </a>
                            <hr class="my-2 border-gray-200">
                            <button onclick="showLogoutModal()" class="w-full flex items-center px-4 py-2 text-red-600 hover:bg-red-50 transition duration-200 text-left">
                                <i class="fas fa-sign-out-alt mr-3 w-5"></i>Logout
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Mobile Menu Button -->
                <button onclick="toggleMobileMenu()" class="md:hidden p-2 text-gray-600 hover:text-indigo-600 hover:bg-gray-100 rounded-lg">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="hidden md:hidden border-t border-gray-200">
        <div class="px-4 py-3 space-y-2">
            <a href="{{ route('teacher.dashboard') }}" class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 font-medium {{ request()->routeIs('teacher.dashboard') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                <i class="fas fa-home mr-2"></i>Dashboard
            </a>
            <a href="{{ route('teacher.exams.index') }}" class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 font-medium {{ request()->routeIs('teacher.exams.*') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                <i class="fas fa-file-alt mr-2"></i>Exams
            </a>
            <a href="{{ route('teacher.analytics') }}" class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 font-medium {{ request()->routeIs('teacher.analytics') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                <i class="fas fa-chart-line mr-2"></i>Analytics
            </a>
            <a href="{{ route('teacher.grading.index') }}" class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 font-medium {{ request()->routeIs('teacher.grading.*') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                <i class="fas fa-clipboard-check mr-2"></i>Grading
            </a>
            <a href="#" class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-green-50 hover:text-green-600 font-medium">
                <i class="fas fa-user-clock mr-2"></i>Active Takers (12)
            </a>
        </div>
    </div>
</nav>

<!-- Logout Confirmation Modal -->
<div id="logoutModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6">
        <div class="text-center">
            <div class="bg-red-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-sign-out-alt text-3xl text-red-600"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Confirm Logout</h3>
            <p class="text-gray-600 mb-6">Are you sure you want to logout from your account?</p>
        </div>
        <div class="flex space-x-3">
            <button onclick="hideLogoutModal()" class="flex-1 px-4 py-3 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition duration-200">
                Cancel
            </button>
            <form method="POST" action="{{ route('logout') }}" class="flex-1">
                @csrf
                <button type="submit" class="w-full px-4 py-3 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition duration-200">
                    Yes, Logout
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleNotifications() {
        const dropdown = document.getElementById('notificationsDropdown');
        dropdown.classList.toggle('hidden');
        // Close profile menu if open
        document.getElementById('profileDropdown').classList.add('hidden');
    }

    function toggleProfileMenu() {
        const dropdown = document.getElementById('profileDropdown');
        dropdown.classList.toggle('hidden');
        // Close notifications if open
        document.getElementById('notificationsDropdown').classList.add('hidden');
    }

    function toggleMobileMenu() {
        const menu = document.getElementById('mobileMenu');
        menu.classList.toggle('hidden');
    }

    function showLogoutModal() {
        document.getElementById('logoutModal').classList.remove('hidden');
        document.getElementById('profileDropdown').classList.add('hidden');
    }

    function hideLogoutModal() {
        document.getElementById('logoutModal').classList.add('hidden');
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        const notificationsBtn = event.target.closest('button[onclick="toggleNotifications()"]');
        const profileBtn = event.target.closest('button[onclick="toggleProfileMenu()"]');
        const notificationsDropdown = document.getElementById('notificationsDropdown');
        const profileDropdown = document.getElementById('profileDropdown');

        if (!notificationsBtn && !notificationsDropdown.contains(event.target)) {
            notificationsDropdown.classList.add('hidden');
        }

        if (!profileBtn && !profileDropdown.contains(event.target)) {
            profileDropdown.classList.add('hidden');
        }
    });
</script>

    <main class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
         <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-md px-4 sm:px-6 lg:px-8">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-500 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-green-700 font-medium">{{ session('success') }}</p>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-green-500 hover:text-green-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-md px-4 sm:px-6 lg:px-8">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-red-700 font-medium">{{ session('error') }}</p>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-red-500 hover:text-red-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        @endif
    </div>
    @yield('teacher-content')
    </main>

@endsection
