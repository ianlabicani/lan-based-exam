<!-- Edit MCQ Modal Form -->
<div id="editMcqQuestionForm" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <form id="editMcqForm" action="" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="type" value="mcq">

            <div class="sticky top-0 bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-6 rounded-t-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold">Edit Multiple Choice Question</h3>
                        <p class="text-indigo-100 text-sm mt-1">Update your question with multiple answer options</p>
                    </div>
                    <button type="button" onclick="this.closest('.fixed').classList.add('hidden')" class="text-white hover:text-gray-200">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>
            </div>

            <div class="p-6 space-y-6">
                <!-- Question Text -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-question-circle text-indigo-600 mr-2"></i>Question *
                    </label>
                    <textarea name="question" rows="3" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                        placeholder="Enter your question here..."></textarea>
                </div>

                <!-- Points and Level -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-star text-indigo-600 mr-2"></i>Points *
                        </label>
                        <input type="number" name="points" min="1" value="1" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-signal text-indigo-600 mr-2"></i>Difficulty Level *
                        </label>
                        <select name="level" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="easy">Easy</option>
                            <option value="moderate" selected>Moderate</option>
                            <option value="difficult">Difficult</option>
                        </select>
                    </div>
                </div>

                <!-- Options -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-list text-indigo-600 mr-2"></i>Answer Options *
                        <span class="text-xs text-gray-500">(Check the correct answer)</span>
                    </label>
                    <div id="editMcqOptionsContainer" class="space-y-3">
                        <!-- Options will be populated by JavaScript -->
                    </div>
                    <button type="button" onclick="addEditMcqOption()"
                        class="mt-3 text-indigo-600 hover:text-indigo-700 text-sm font-medium">
                        <i class="fas fa-plus-circle mr-1"></i>Add Another Option
                    </button>
                </div>

                <!-- Actions -->
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
let editMcqOptionIndex = 2;
function addEditMcqOption() {
    const container = document.getElementById('editMcqOptionsContainer');
    const optionDiv = document.createElement('div');
    optionDiv.className = 'flex items-center space-x-2';
    optionDiv.innerHTML = `
        <input type="checkbox" name="options[${editMcqOptionIndex}][correct]" value="1" class="w-5 h-5 text-indigo-600">
        <input type="text" name="options[${editMcqOptionIndex}][text]" placeholder="Option ${editMcqOptionIndex + 1}" required
            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
        <button type="button" onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-700">
            <i class="fas fa-times"></i>
        </button>
    `;
    container.appendChild(optionDiv);
    editMcqOptionIndex++;
}

// Update form action when editing
function setEditMcqAction(examId, itemId) {
    document.getElementById('editMcqForm').action = `/teacher/exams/${examId}/items/${itemId}?tab=items`;
}
</script>
