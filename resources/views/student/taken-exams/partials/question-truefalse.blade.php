<!-- True/False Question -->
<div class="space-y-3">
    <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 transition-all duration-200"
           :class="answers[currentQuestion.id] === 'true' ? 'border-green-600 bg-green-50' : 'border-gray-200'">
        <input type="radio"
               :name="`question-${currentQuestion.id}`"
               value="true"
               x-model="answers[currentQuestion.id]"
               @change="saveAnswer()"
               class="h-4 w-4 text-green-600">
        <span class="ml-3 text-gray-900 font-medium">True</span>
    </label>
    <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 transition-all duration-200"
           :class="answers[currentQuestion.id] === 'false' ? 'border-red-600 bg-red-50' : 'border-gray-200'">
        <input type="radio"
               :name="`question-${currentQuestion.id}`"
               value="false"
               x-model="answers[currentQuestion.id]"
               @change="saveAnswer()"
               class="h-4 w-4 text-red-600">
        <span class="ml-3 text-gray-900 font-medium">False</span>
    </label>
</div>
