<!-- Question Navigation Sidebar -->
<div class="lg:col-span-1">
    <div class="bg-white rounded-xl shadow-md p-6 sticky top-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">
            <i class="fas fa-list text-indigo-600 mr-2"></i>Questions
        </h3>
        <div class="grid grid-cols-5 lg:grid-cols-4 gap-2">
            <template x-for="(item, index) in questions" :key="item.id">
                <button @click="goToQuestion(index)"
                        :class="{
                            'bg-indigo-600 text-white': currentQuestionIndex === index,
                            'bg-green-100 text-green-700 hover:bg-green-200': currentQuestionIndex !== index && answers[item.id],
                            'bg-gray-100 text-gray-700 hover:bg-gray-200': currentQuestionIndex !== index && !answers[item.id]
                        }"
                        class="aspect-square rounded-lg font-semibold text-sm transition-all duration-200 flex items-center justify-center">
                    <span x-text="index + 1"></span>
                </button>
            </template>
        </div>

        <!-- Legend -->
        <div class="mt-6 space-y-2 text-xs">
            <div class="flex items-center">
                <div class="w-6 h-6 bg-green-100 rounded mr-2"></div>
                <span class="text-gray-600">Answered</span>
            </div>
            <div class="flex items-center">
                <div class="w-6 h-6 bg-gray-100 rounded mr-2"></div>
                <span class="text-gray-600">Unanswered</span>
            </div>
            <div class="flex items-center">
                <div class="w-6 h-6 bg-indigo-600 rounded mr-2"></div>
                <span class="text-gray-600">Current</span>
            </div>
        </div>

        <!-- Submit Button -->
        <button @click="showSubmitModal = true"
                class="w-full mt-6 px-4 py-3 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition duration-200">
            <i class="fas fa-check-circle mr-2"></i>Submit Exam
        </button>
    </div>
</div>
