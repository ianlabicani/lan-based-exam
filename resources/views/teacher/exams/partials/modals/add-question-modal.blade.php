<!-- Add Question Modal -->
<div id="addQuestionModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-900">
                    <i class="fas fa-plus-circle text-indigo-600 mr-2"></i>Add Question
                </h3>
                <button onclick="hideAddQuestionModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <div class="space-y-4">
                <p class="text-gray-600 mb-6">Select the type of question you want to add:</p>

                <div class="grid grid-cols-2 gap-4">
                    <button type="button" onclick="showQuestionForm('mcq')" class="p-6 border-2 border-gray-200 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition duration-200 text-left">
                        <i class="fas fa-check-square text-3xl text-blue-600 mb-3"></i>
                        <h4 class="font-bold text-gray-900 mb-1">Multiple Choice</h4>
                        <p class="text-sm text-gray-600">Question with multiple options</p>
                    </button>

                    <button type="button" onclick="showQuestionForm('trueFalse')" class="p-6 border-2 border-gray-200 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition duration-200 text-left">
                        <i class="fas fa-toggle-on text-3xl text-green-600 mb-3"></i>
                        <h4 class="font-bold text-gray-900 mb-1">True/False</h4>
                        <p class="text-sm text-gray-600">Binary choice question</p>
                    </button>

                    <button type="button" onclick="showQuestionForm('shortAnswer')" class="p-6 border-2 border-gray-200 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition duration-200 text-left">
                        <i class="fas fa-pencil-alt text-3xl text-yellow-600 mb-3"></i>
                        <h4 class="font-bold text-gray-900 mb-1">Short Answer</h4>
                        <p class="text-sm text-gray-600">Brief text response</p>
                    </button>

                    <button type="button" onclick="showQuestionForm('essay')" class="p-6 border-2 border-gray-200 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition duration-200 text-left">
                        <i class="fas fa-file-alt text-3xl text-purple-600 mb-3"></i>
                        <h4 class="font-bold text-gray-900 mb-1">Essay</h4>
                        <p class="text-sm text-gray-600">Detailed written response</p>
                    </button>

                    <button type="button" onclick="showQuestionForm('matching')" class="p-6 border-2 border-gray-200 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition duration-200 text-left">
                        <i class="fas fa-link text-3xl text-pink-600 mb-3"></i>
                        <h4 class="font-bold text-gray-900 mb-1">Matching</h4>
                        <p class="text-sm text-gray-600">Match items to answers</p>
                    </button>

                    <button type="button" onclick="showQuestionForm('fillBlank')" class="p-6 border-2 border-gray-200 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition duration-200 text-left">
                        <i class="fas fa-edit text-3xl text-orange-600 mb-3"></i>
                        <h4 class="font-bold text-gray-900 mb-1">Fill in the Blank</h4>
                        <p class="text-sm text-gray-600">Complete the sentence</p>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
