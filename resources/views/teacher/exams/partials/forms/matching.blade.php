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
                            <i class="fas fa-star text-indigo-600 mr-2"></i>Points *
                        </label>
                        <input type="number" name="points" min="1" value="1" required
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
                        <i class="fas fa-link text-indigo-600 mr-2"></i>Matching Pairs *
                    </label>
                    <div id="matchingPairsContainer" class="space-y-3">
                        <div class="flex items-center space-x-3">
                            <input type="text" name="pairs[0][left]" placeholder="Item 1" required
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <input type="text" name="pairs[0][right]" placeholder="Match 1" required
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div class="flex items-center space-x-3">
                            <input type="text" name="pairs[1][left]" placeholder="Item 2" required
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <input type="text" name="pairs[1][right]" placeholder="Match 2" required
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
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
function addMatchingPair() {
    const container = document.getElementById('matchingPairsContainer');
    const pairDiv = document.createElement('div');
    pairDiv.className = 'flex items-center space-x-3';
    pairDiv.innerHTML = `
        <input type="text" name="pairs[${matchingPairIndex}][left]" placeholder="Item ${matchingPairIndex + 1}" required
            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
        <input type="text" name="pairs[${matchingPairIndex}][right]" placeholder="Match ${matchingPairIndex + 1}" required
            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
        <button type="button" onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-700">
            <i class="fas fa-times"></i>
        </button>
    `;
    container.appendChild(pairDiv);
    matchingPairIndex++;
}
</script>
