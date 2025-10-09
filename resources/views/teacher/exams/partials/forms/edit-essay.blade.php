<!-- Edit Essay Modal Form -->
<div id="editEssayQuestionForm" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <form id="editEssayForm" action="" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="type" value="essay">

            <div class="sticky top-0 bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-6 rounded-t-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold">Edit Essay Question</h3>
                        <p class="text-indigo-100 text-sm mt-1">Update your detailed written response question</p>
                    </div>
                    <button type="button" onclick="this.closest('.fixed').classList.add('hidden')" class="text-white hover:text-gray-200">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>
            </div>

            <div class="p-6 space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-question-circle text-indigo-600 mr-2"></i>Question *
                    </label>
                    <textarea name="question" rows="4" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                        placeholder="Enter your essay question here..."></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-star text-indigo-600 mr-2"></i>Points *
                        </label>
                        <input type="number" name="points" min="1" value="10" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-signal text-indigo-600 mr-2"></i>Difficulty *
                        </label>
                        <select name="level" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="easy">Easy</option>
                            <option value="moderate" selected>Moderate</option>
                            <option value="difficult">Difficult</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-clipboard-check text-indigo-600 mr-2"></i>Grading Rubric / Expected Answer
                    </label>
                    <textarea name="expected_answer" rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                        placeholder="Describe what you're looking for in the answer..."></textarea>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t">
                    <button type="button" onclick="this.closest('.fixed').classList.add('hidden')"
                        class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        <i class="fas fa-save mr-2"></i>Update Question
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function setEditEssayAction(examId, itemId) {
    document.getElementById('editEssayForm').action = `/teacher/exams/${examId}/items/${itemId}?tab=items`;
}

function populateEditEssayForm(item) {
    document.querySelector('#editEssayQuestionForm textarea[name="question"]').value = item.question || '';
    document.querySelector('#editEssayQuestionForm input[name="points"]').value = item.points || 10;
    document.querySelector('#editEssayQuestionForm select[name="level"]').value = item.level || 'moderate';
    document.querySelector('#editEssayQuestionForm textarea[name="expected_answer"]').value = item.expected_answer || '';
}
</script>
