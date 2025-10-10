<!-- Essay Question -->
<div>
    <textarea x-model="answers[currentQuestion.id]"
              @input="debouncedSave()"
              rows="8"
              placeholder="Write your essay here..."
              class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none"></textarea>
    <p class="mt-2 text-sm text-gray-500">
        <i class="fas fa-info-circle mr-1"></i>This question will be graded manually.
    </p>
</div>
