<section class="space-y-6">
    <header class="border-b border-gray-200 pb-4">
        <h2 class="text-xl font-bold text-gray-900">
            <i class="fas fa-trash-alt text-red-600 mr-2"></i>Delete Account
        </h2>
        <p class="mt-2 text-sm text-gray-600">
            Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
        </p>
    </header>

    <button
        onclick="showDeleteAccountModal()"
        class="px-6 py-3 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 focus:ring-4 focus:ring-red-300 transition duration-200 flex items-center space-x-2"
    >
        <i class="fas fa-exclamation-triangle"></i>
        <span>Delete Account</span>
    </button>
</section>

<!-- Delete Account Modal -->
<div id="deleteAccountModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-lg w-full p-6">
        <form method="post" action="{{ route('profile.destroy') }}">
            @csrf
            @method('delete')

            <div class="text-center mb-6">
                <div class="bg-red-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-3xl text-red-600"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Delete Account?</h3>
                <p class="text-gray-600">
                    Are you sure you want to delete your account?
                </p>
            </div>

            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-sm text-red-800">
                    <i class="fas fa-info-circle mr-1"></i>
                    Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.
                </p>
            </div>

            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-lock mr-2 text-gray-500"></i>Password
                </label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-200"
                    placeholder="Enter your password to confirm"
                />
                @if($errors->userDeletion->has('password'))
                    <p class="mt-2 text-sm text-red-600 flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->userDeletion->first('password') }}
                    </p>
                @endif
            </div>

            <div class="flex space-x-3">
                <button
                    type="button"
                    onclick="hideDeleteAccountModal()"
                    class="flex-1 px-4 py-3 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition duration-200"
                >
                    Cancel
                </button>
                <button
                    type="submit"
                    class="flex-1 px-4 py-3 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition duration-200"
                >
                    Yes, Delete Account
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function showDeleteAccountModal() {
        document.getElementById('deleteAccountModal').classList.remove('hidden');
    }

    function hideDeleteAccountModal() {
        document.getElementById('deleteAccountModal').classList.add('hidden');
    }

    // Show modal if there are validation errors
    @if($errors->userDeletion->isNotEmpty())
        document.addEventListener('DOMContentLoaded', function() {
            showDeleteAccountModal();
        });
    @endif
</script>
