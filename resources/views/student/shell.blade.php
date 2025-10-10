@extends('shell')

@section('content')

<!-- Student Navbar -->
<nav class="bg-white shadow-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo and Brand -->
            <div class="flex items-center space-x-8">
                <a href="{{ route('student.exams.index') }}" class="flex items-center space-x-3">
                    <i class="fas fa-graduation-cap text-3xl text-indigo-600"></i>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">CBEMS</h1>
                        <p class="text-xs text-gray-600">Student Portal</p>
                    </div>
                </a>

                <!-- Main Navigation Links -->
                <div class="hidden md:flex items-center space-x-1">
                    <a href="{{ route('student.exams.index') }}" class="px-4 py-2 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 font-medium transition duration-200 {{ request()->routeIs('student.exams.*') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                        <i class="fas fa-file-alt mr-2"></i>Available Exams
                    </a>
                    <a href="{{ route('student.myExams') }}" class="px-4 py-2 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 font-medium transition duration-200 {{ request()->routeIs('student.myExams') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                        <i class="fas fa-history mr-2"></i>My Exam History
                    </a>
                </div>
            </div>

            <!-- Right Side Actions -->
            <div class="flex items-center space-x-4">
                <!-- User Profile Dropdown -->
                <div class="relative">
                    <button onclick="toggleProfileMenu()" class="flex items-center space-x-3 px-3 py-2 hover:bg-gray-100 rounded-lg transition duration-200">
                        <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                            <span class="text-indigo-600 font-bold text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                        <div class="hidden lg:block text-left">
                            <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-600">Year {{ Auth::user()->year }} - Section {{ Auth::user()->section }}</p>
                        </div>
                        <i class="fas fa-chevron-down text-gray-600 text-sm"></i>
                    </button>

                    <!-- Profile Dropdown -->
                    <div id="profileDropdown" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl border border-gray-200 overflow-hidden">
                        <div class="p-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
                            <p class="text-sm font-semibold">{{ Auth::user()->name }}</p>
                            <p class="text-xs opacity-90">{{ Auth::user()->email }}</p>
                        </div>
                        <div class="py-2">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 transition duration-200">
                                <i class="fas fa-user mr-2 text-gray-500"></i>Profile
                            </a>
                            <button onclick="showLogoutModal()" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 transition duration-200">
                                <i class="fas fa-sign-out-alt mr-2"></i>Logout
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
            <a href="{{ route('student.exams.index') }}" class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 font-medium {{ request()->routeIs('student.exams.*') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                <i class="fas fa-file-alt mr-2"></i>Available Exams
            </a>
            <a href="{{ route('student.myExams') }}" class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 font-medium {{ request()->routeIs('student.myExams') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                <i class="fas fa-history mr-2"></i>My Exam History
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
    function toggleProfileMenu() {
        const dropdown = document.getElementById('profileDropdown');
        dropdown.classList.toggle('hidden');
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
        const profileBtn = event.target.closest('button[onclick="toggleProfileMenu()"]');
        const profileDropdown = document.getElementById('profileDropdown');

        if (!profileBtn && !profileDropdown.contains(event.target)) {
            profileDropdown.classList.add('hidden');
        }
    });
</script>

<main class="min-h-screen bg-gray-50">
    @yield('student-content')
</main>

@endsection
