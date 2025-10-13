<!-- Matching Modal Form -->
<div id="matchingQuestionForm" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <form action="{{ route('teacher.exams.items.store', $exam->id) }}" method="POST">
            @csrf
            <input type="hidden" name="type" value="matching">

            <div class="sticky top-0 bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-6 rounded-t-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold">Matching Type Question</h3>
                        <p class="text-indigo-100 text-sm mt-1">Create pairs of items to be matched</p>
                    </div>
                    <button type="button" onclick="hideAddQuestionModal()" class="text-white hover:text-gray-200">
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
                            <i class="fas fa-star text-indigo-600 mr-2"></i>Points (Auto-calculated) *
                        </label>
                        <input type="number" name="points" id="matchingPoints" min="1" value="2" readonly
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed"
                            title="Points are automatically set to the number of pairs (1 point per pair)">
                        <p class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-info-circle mr-1"></i>1 point per pair
                        </p>
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
                    <div id="matchingPairsContainer" class="space-y-3">
                        <div class="flex items-center space-x-3">
                            <input type="text" name="pairs[0][left]" placeholder="e.g., Apple" required
                                class="flex-1 px-4 py-2 border border-indigo-300 rounded-lg focus:ring-2 focus:ring-indigo-500 bg-indigo-50">
                            <i class="fas fa-arrows-alt-h text-gray-400"></i>
                            <input type="text" name="pairs[0][right]" placeholder="e.g., Fruit" required
                                class="flex-1 px-4 py-2 border border-emerald-300 rounded-lg focus:ring-2 focus:ring-emerald-500 bg-emerald-50">
                        </div>
                        <div class="flex items-center space-x-3">
                            <input type="text" name="pairs[1][left]" placeholder="e.g., Carrot" required
                                class="flex-1 px-4 py-2 border border-indigo-300 rounded-lg focus:ring-2 focus:ring-indigo-500 bg-indigo-50">
                            <i class="fas fa-arrows-alt-h text-gray-400"></i>
                            <input type="text" name="pairs[1][right]" placeholder="e.g., Vegetable" required
                                class="flex-1 px-4 py-2 border border-emerald-300 rounded-lg focus:ring-2 focus:ring-emerald-500 bg-emerald-50">
                        </div>
                    </div>
                    <button type="button" onclick="addMatchingPair()"
                        class="mt-3 text-indigo-600 hover:text-indigo-700 text-sm font-medium">
                        <i class="fas fa-plus-circle mr-1"></i>Add Another Pair
                    </button>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t">
                    <button type="button" onclick="hideAddQuestionModal()"
                        class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        <i class="fas fa-save mr-2"></i>Save Question
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
let matchingPairIndex = 2;

function updateMatchingPoints() {
    const container = document.getElementById('matchingPairsContainer');
    const pairCount = container.children.length;
    const pointsInput = document.getElementById('matchingPoints');
    if (pointsInput) {
        pointsInput.value = pairCount;
    }
}

function addMatchingPair() {
    const container = document.getElementById('matchingPairsContainer');
    const pairDiv = document.createElement('div');
    pairDiv.className = 'flex items-center space-x-3';
    pairDiv.innerHTML = `
        <input type="text" name="pairs[${matchingPairIndex}][left]" placeholder="Left item ${matchingPairIndex + 1}" required
            class="flex-1 px-4 py-2 border border-indigo-300 rounded-lg focus:ring-2 focus:ring-indigo-500 bg-indigo-50">
        <i class="fas fa-arrows-alt-h text-gray-400"></i>
        <input type="text" name="pairs[${matchingPairIndex}][right]" placeholder="Right match ${matchingPairIndex + 1}" required
            class="flex-1 px-4 py-2 border border-emerald-300 rounded-lg focus:ring-2 focus:ring-emerald-500 bg-emerald-50">
        <button type="button" onclick="removeMatchingPair(this)" class="text-red-600 hover:text-red-700">
            <i class="fas fa-times"></i>
        </button>
    `;
    container.appendChild(pairDiv);
    matchingPairIndex++;
    updateMatchingPoints();
}

function removeMatchingPair(button) {
    const container = document.getElementById('matchingPairsContainer');
    // Don't allow removal if only 2 pairs remain (minimum)
    if (container.children.length > 2) {
        button.parentElement.remove();
        updateMatchingPoints();
    } else {
        alert('A matching question must have at least 2 pairs.');
    }
}

// Update points when form is shown
document.addEventListener('DOMContentLoaded', function() {
    updateMatchingPoints();
});
</script>
