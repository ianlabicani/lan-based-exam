<!-- Matching Question -->
<div>
    <p class="text-sm text-gray-600 mb-4">
        <i class="fas fa-info-circle mr-1"></i>Match each item on the left with the correct item on the right.
    </p>
    <div class="space-y-3">
        <template x-for="(pair, pairIndex) in currentQuestion.pairs" :key="pairIndex">
            <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                <!-- Left side (question) -->
                <div class="flex-1">
                    <div class="flex items-center">
                        <span class="font-semibold text-indigo-600 mr-2" x-text="`${pairIndex + 1}.`"></span>
                        <span class="text-gray-900" x-text="pair.left"></span>
                    </div>
                </div>

                <!-- Arrow -->
                <i class="fas fa-arrow-right text-gray-400"></i>

                <!-- Right side (dropdown) -->
                <div class="flex-1">
                    <select :name="`matching_${currentQuestion.id}_${pairIndex}`"
                            :id="`matching_${currentQuestion.id}_${pairIndex}`"
                            @change="updateMatchingAnswer(currentQuestion.id, pairIndex, $event.target.value)"
                            x-init="populateMatchingDropdown($el, currentQuestion.id, pairIndex, currentQuestion.pairs)"
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Select answer...</option>
                    </select>
                </div>
            </div>
        </template>
    </div>
</div>
