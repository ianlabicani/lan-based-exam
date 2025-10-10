<!-- Submit Confirmation Modal -->
<div x-show="showSubmitModal"
     x-cloak
     @click.self="showSubmitModal = false"
     class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-8"
         @click.stop
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-90"
         x-transition:enter-end="opacity-100 transform scale-100">
        <div class="text-center mb-6">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-yellow-100 mb-4">
                <i class="fas fa-exclamation-triangle text-yellow-600 text-2xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Submit Exam?</h3>
            <p class="text-gray-600">Are you sure you want to submit your exam? This action cannot be undone.</p>
        </div>

        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
            <div class="flex items-center justify-between text-sm mb-2">
                <span class="text-gray-600">Answered:</span>
                <span class="font-semibold text-gray-900" x-text="`${answeredCount} / ${totalQuestions}`"></span>
            </div>
            <div class="flex items-center justify-between text-sm">
                <span class="text-gray-600">Unanswered:</span>
                <span class="font-semibold" :class="unansweredCount > 0 ? 'text-red-600' : 'text-green-600'"
                      x-text="unansweredCount"></span>
            </div>
        </div>

        <div class="flex space-x-3">
            <button @click="showSubmitModal = false"
                    class="flex-1 px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition duration-200">
                Cancel
            </button>
            <button @click="submitExam()"
                    :disabled="isSubmitting"
                    class="flex-1 px-6 py-3 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                <span x-text="isSubmitting ? 'Submitting...' : 'Submit'"></span>
            </button>
        </div>
    </div>
</div>
