<!-- Fill in the Blank / Short Answer Question -->
<div>
    <input type="text"
           x-model="answers[currentQuestion.id]"
           @input="debouncedSave()"
           placeholder="Type your answer here..."
           class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
</div>
