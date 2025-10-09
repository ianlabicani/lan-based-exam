<!-- Delete Confirmation Modal -->
<div id="deleteQuestionModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6">
            <div class="flex items-center justify-center mb-4">
                <div class="bg-red-100 rounded-full p-3">
                    <i class="fas fa-exclamation-triangle text-red-600 text-3xl"></i>
                </div>
            </div>

            <h3 class="text-xl font-bold text-gray-900 text-center mb-2">Delete Question?</h3>
            <p class="text-gray-600 text-center mb-4">
                Are you sure you want to delete this question? This action cannot be undone.
            </p>

            <div class="bg-gray-50 rounded-lg p-3 mb-6">
                <p class="text-sm text-gray-700 italic" id="deleteQuestionText"></p>
            </div>

            <form id="deleteQuestionForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex space-x-3">
                    <button type="button" onclick="hideDeleteModal()"
                        class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition duration-200">
                        Cancel
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition duration-200">
                        <i class="fas fa-trash mr-2"></i>Delete
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
