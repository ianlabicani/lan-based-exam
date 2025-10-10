<!-- Edit Matching Modal Form -->
<div id="editMatchingQuestionForm" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <form id="editMatchingForm" action="" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="type" value="matching">

            <div class="sticky top-0 bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-6 rounded-t-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold">Edit Matching Type Question</h3>
                        <p class="text-indigo-100 text-sm mt-1">Update pairs of items to be matched</p>
                    </div>
                    <button type="button" onclick="this.closest('.fixed').classList.add('hidden')" class="text-white hover:text-gray-200">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>
            </div>

            <div class="p-6 space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-question-circle text-indigo-600 mr-2"></i>Question / Instructions *
                    </label>
                    <textarea name="question" rows="2" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                        placeholder="e.g., Match the terms with their definitions..."></textarea>
                </div>

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
                            <i class="fas fa-signal text-indigo-600 mr-2"></i>Difficulty *
                        </label>
                        <input type="hidden" name="level" id="editMatchingLevel">
                        <select disabled
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-600 cursor-not-allowed">
                            <option value="easy">Easy</option>
                            <option value="moderate" selected>Moderate</option>
                            <option value="difficult">Difficult</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-lock mr-1"></i>Difficulty level cannot be changed
                        </p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        <i class="fas fa-link text-indigo-600 mr-2"></i>Matching Pairs *
                    </label>
                    <p class="text-xs text-gray-500 mb-3">
                        <i class="fas fa-info-circle mr-1"></i>
                        Create pairs where students will match items from the left column with the correct items from the right column.
                        The right column items will be shuffled for students.
                    </p>
                    <div class="grid grid-cols-2 gap-4 mb-2">
                        <div class="text-sm font-semibold text-indigo-700">
                            <i class="fas fa-arrow-left mr-1"></i>Left Side (Questions/Terms)
                        </div>
                        <div class="text-sm font-semibold text-emerald-700">
                            <i class="fas fa-arrow-right mr-1"></i>Right Side (Answers/Matches)
                        </div>
                    </div>
                    <div id="editMatchingPairsContainer" class="space-y-3">
                        <!-- Pairs will be populated by JavaScript -->
                    </div>
                    <button type="button" onclick="addEditMatchingPair()"
                        class="mt-3 text-indigo-600 hover:text-indigo-700 text-sm font-medium">
                        <i class="fas fa-plus-circle mr-1"></i>Add Another Pair
                    </button>
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
let editMatchingPairIndex = 2;

function addEditMatchingPair() {
    const container = document.getElementById('editMatchingPairsContainer');
    const pairDiv = document.createElement('div');
    pairDiv.className = 'flex items-center space-x-3';
    pairDiv.innerHTML = `
        <input type="text" name="pairs[${editMatchingPairIndex}][left]" placeholder="Left item ${editMatchingPairIndex + 1}" required
            class="flex-1 px-4 py-2 border border-indigo-300 rounded-lg focus:ring-2 focus:ring-indigo-500 bg-indigo-50">
        <i class="fas fa-arrows-alt-h text-gray-400"></i>
        <input type="text" name="pairs[${editMatchingPairIndex}][right]" placeholder="Right match ${editMatchingPairIndex + 1}" required
            class="flex-1 px-4 py-2 border border-emerald-300 rounded-lg focus:ring-2 focus:ring-emerald-500 bg-emerald-50">
        <button type="button" onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-700">
            <i class="fas fa-times"></i>
        </button>
    `;
    container.appendChild(pairDiv);
    editMatchingPairIndex++;
}

function setEditMatchingAction(examId, itemId) {
    document.getElementById('editMatchingForm').action = `/teacher/exams/${examId}/items/${itemId}?tab=items`;
}

function populateEditMatchingForm(item) {
    document.querySelector('#editMatchingQuestionForm textarea[name="question"]').value = item.question || '';
    document.querySelector('#editMatchingQuestionForm input[name="points"]').value = item.points || 1;

    // Set both the hidden input and the disabled select
    const level = item.level || 'moderate';
    document.querySelector('#editMatchingLevel').value = level;
    document.querySelector('#editMatchingQuestionForm select[disabled]').value = level;

    // Clear and populate pairs
    const container = document.getElementById('editMatchingPairsContainer');
    container.innerHTML = '';
    editMatchingPairIndex = 0;

    if (item.pairs && item.pairs.length > 0) {
        item.pairs.forEach((pair, index) => {
            const pairDiv = document.createElement('div');
            pairDiv.className = 'flex items-center space-x-3';

            // Create left input
            const leftInput = document.createElement('input');
            leftInput.type = 'text';
            leftInput.name = `pairs[${index}][left]`;
            leftInput.placeholder = `Item ${index + 1}`;
            leftInput.value = pair.left || '';
            leftInput.required = true;
            leftInput.className = 'flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500';

            // Create right input
            const rightInput = document.createElement('input');
            rightInput.type = 'text';
            rightInput.name = `pairs[${index}][right]`;
            rightInput.placeholder = `Match ${index + 1}`;
            rightInput.value = pair.right || '';
            rightInput.required = true;
            rightInput.className = 'flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500';

            pairDiv.appendChild(leftInput);
            pairDiv.appendChild(rightInput);

            // Add remove button for pairs beyond the first two
            if (index > 1) {
                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'text-red-600 hover:text-red-700';
                removeBtn.innerHTML = '<i class="fas fa-times"></i>';
                removeBtn.onclick = function() { this.parentElement.remove(); };
                pairDiv.appendChild(removeBtn);
            }

            container.appendChild(pairDiv);
            editMatchingPairIndex++;
        });
    }
}
</script>
