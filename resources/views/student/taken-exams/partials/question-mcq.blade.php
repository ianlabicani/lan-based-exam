<!-- Multiple Choice Question -->
<div class="space-y-3">
    <template x-for="(option, index) in currentQuestion.options" :key="index">
        <label class="flex items-start p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 transition-all duration-200"
               :class="answers[currentQuestion.id] == index ? 'border-indigo-600 bg-indigo-50' : 'border-gray-200'">
            <input type="radio"
                   :name="`question-${currentQuestion.id}`"
                   :value="index"
                   x-model="answers[currentQuestion.id]"
                   @change="saveAnswer()"
                   class="mt-1 h-4 w-4 text-indigo-600">
            <span class="ml-3 text-gray-900" x-text="option.text || option"></span>
        </label>
    </template>
</div>
