<section>
    <header class="border-b border-gray-200 pb-4">
        <h2 class="text-xl font-bold text-gray-900">
            <i class="fas fa-key text-indigo-600 mr-2"></i>Update Password
        </h2>
        <p class="mt-2 text-sm text-gray-600">
            Ensure your account is using a long, random password to stay secure.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <!-- Current Password -->
        <div>
            <label for="update_password_current_password" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-lock mr-2 text-gray-500"></i>Current Password
            </label>
            <div class="relative">
                <input
                    id="update_password_current_password"
                    name="current_password"
                    type="password"
                    autocomplete="current-password"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                    placeholder="Enter current password"
                />
                <button
                    type="button"
                    onclick="togglePasswordVisibility('update_password_current_password', 'toggleIcon1')"
                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700"
                >
                    <i id="toggleIcon1" class="fas fa-eye"></i>
                </button>
            </div>
            @if($errors->updatePassword->has('current_password'))
                <p class="mt-2 text-sm text-red-600 flex items-center">
                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->updatePassword->first('current_password') }}
                </p>
            @endif
        </div>

        <!-- New Password -->
        <div>
            <label for="update_password_password" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-lock mr-2 text-gray-500"></i>New Password
            </label>
            <div class="relative">
                <input
                    id="update_password_password"
                    name="password"
                    type="password"
                    autocomplete="new-password"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                    placeholder="Enter new password"
                />
                <button
                    type="button"
                    onclick="togglePasswordVisibility('update_password_password', 'toggleIcon2')"
                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700"
                >
                    <i id="toggleIcon2" class="fas fa-eye"></i>
                </button>
            </div>
            @if($errors->updatePassword->has('password'))
                <p class="mt-2 text-sm text-red-600 flex items-center">
                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->updatePassword->first('password') }}
                </p>
            @endif
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-lock mr-2 text-gray-500"></i>Confirm Password
            </label>
            <div class="relative">
                <input
                    id="update_password_password_confirmation"
                    name="password_confirmation"
                    type="password"
                    autocomplete="new-password"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                    placeholder="Confirm new password"
                />
                <button
                    type="button"
                    onclick="togglePasswordVisibility('update_password_password_confirmation', 'toggleIcon3')"
                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700"
                >
                    <i id="toggleIcon3" class="fas fa-eye"></i>
                </button>
            </div>
            @if($errors->updatePassword->has('password_confirmation'))
                <p class="mt-2 text-sm text-red-600 flex items-center">
                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->updatePassword->first('password_confirmation') }}
                </p>
            @endif
        </div>

        <!-- Save Button -->
        <div class="flex items-center gap-4 pt-4">
            <button
                type="submit"
                class="px-6 py-3 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 transition duration-200 flex items-center space-x-2"
            >
                <i class="fas fa-save"></i>
                <span>Update Password</span>
            </button>

            @if (session('status') === 'password-updated')
                <p class="text-sm text-green-600 flex items-center animate-fade-in">
                    <i class="fas fa-check-circle mr-1"></i>Password updated successfully!
                </p>
            @endif
        </div>
    </form>
</section>

<script>
    function togglePasswordVisibility(inputId, iconId) {
        const passwordInput = document.getElementById(inputId);
        const toggleIcon = document.getElementById(iconId);

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
