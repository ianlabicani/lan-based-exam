<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-bold text-gray-900">Exam Questions</h3>
            <p class="text-sm text-gray-600 mt-1">Manage all questions for this examination</p>
        </div>
        <button onclick="showAddQuestionModal()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition duration-200 flex items-center space-x-2">
            <i class="fas fa-plus-circle"></i>
            <span>Add Question</span>
        </button>
    </div>

    <!-- Questions List -->
    @if(count($examItems) > 0)
    <div class="space-y-4">
        @foreach($examItems as $index => $item)
        <div class="border border-gray-200 rounded-lg hover:border-indigo-300 hover:shadow-md transition duration-200">
            <!-- Question Header -->
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <div class="flex items-start justify-between">
                    <div class="flex items-start space-x-4 flex-1">
                        <div class="bg-indigo-100 text-indigo-700 w-10 h-10 rounded-full flex items-center justify-center font-bold flex-shrink-0">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-2">
                                @if($item['type'] === 'multiple_choice')
                                    <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">
                                        <i class="fas fa-check-square mr-1"></i>Multiple Choice
                                    </span>
                                @elseif($item['type'] === 'true_false')
                                    <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">
                                        <i class="fas fa-toggle-on mr-1"></i>True/False
                                    </span>
                                @elseif($item['type'] === 'short_answer')
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded-full">
                                        <i class="fas fa-pencil-alt mr-1"></i>Short Answer
                                    </span>
                                @elseif($item['type'] === 'essay')
                                    <span class="px-3 py-1 bg-purple-100 text-purple-700 text-xs font-semibold rounded-full">
                                        <i class="fas fa-file-alt mr-1"></i>Essay
                                    </span>
                                @elseif($item['type'] === 'matching')
                                    <span class="px-3 py-1 bg-pink-100 text-pink-700 text-xs font-semibold rounded-full">
                                        <i class="fas fa-link mr-1"></i>Matching
                                    </span>
                                @endif
                                <span class="px-3 py-1 bg-gray-200 text-gray-700 text-xs font-semibold rounded-full">
                                    <i class="fas fa-star mr-1"></i>{{ $item['points'] }} pts
                                </span>
                            </div>
                            <p class="text-gray-900 font-medium text-lg">{{ $item['question'] }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2 ml-4">
                        <button class="p-2 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition duration-200" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition duration-200" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                        <button class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition duration-200" title="Move">
                            <i class="fas fa-grip-vertical"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Question Content -->
            <div class="px-6 py-4 bg-white">
                @if($item['type'] === 'multiple_choice')
                    <div class="space-y-2">
                        <p class="text-sm font-medium text-gray-700 mb-3">Options:</p>
                        @foreach($item['options'] as $optIndex => $option)
                        <div class="flex items-center space-x-3 p-3 rounded-lg {{ $option === $item['correct_answer'] ? 'bg-green-50 border border-green-200' : 'bg-gray-50' }}">
                            <div class="w-6 h-6 rounded-full border-2 {{ $option === $item['correct_answer'] ? 'border-green-500 bg-green-100' : 'border-gray-300' }} flex items-center justify-center">
                                @if($option === $item['correct_answer'])
                                    <i class="fas fa-check text-green-600 text-xs"></i>
                                @endif
                            </div>
                            <span class="text-gray-900 {{ $option === $item['correct_answer'] ? 'font-semibold' : '' }}">
                                {{ chr(65 + $optIndex) }}. {{ $option }}
                                @if($option === $item['correct_answer'])
                                    <span class="text-green-600 text-xs ml-2">(Correct Answer)</span>
                                @endif
                            </span>
                        </div>
                        @endforeach
                    </div>
                @elseif($item['type'] === 'true_false')
                    <div class="flex items-center space-x-4">
                        <span class="text-sm font-medium text-gray-700">Correct Answer:</span>
                        <span class="px-4 py-2 bg-green-100 text-green-700 font-semibold rounded-lg">
                            <i class="fas fa-{{ $item['correct_answer'] ? 'check' : 'times' }} mr-1"></i>
                            {{ $item['correct_answer'] ? 'True' : 'False' }}
                        </span>
                    </div>
                @elseif($item['type'] === 'short_answer')
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-sm text-blue-800">
                            <i class="fas fa-info-circle mr-1"></i>
                            Students can provide answers up to {{ $item['max_length'] }} characters. This will require manual grading.
                        </p>
                    </div>
                @elseif($item['type'] === 'essay')
                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                        <p class="text-sm text-purple-800">
                            <i class="fas fa-info-circle mr-1"></i>
                            Students must write at least {{ $item['min_words'] }} words. This will require manual grading.
                        </p>
                    </div>
                @elseif($item['type'] === 'matching')
                    <div class="space-y-2">
                        <p class="text-sm font-medium text-gray-700 mb-3">Matching Pairs:</p>
                        @foreach($item['pairs'] as $pair)
                        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                            <span class="text-gray-900 font-medium">{{ $pair[0] }}</span>
                            <i class="fas fa-arrow-right text-gray-400"></i>
                            <span class="text-indigo-600 font-semibold">{{ $pair[1] }}</span>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @else
    <!-- Empty State -->
    <div class="bg-gray-50 rounded-lg p-12 text-center">
        <div class="bg-gray-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-question text-4xl text-gray-400"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">No Questions Yet</h3>
        <p class="text-gray-600 mb-6">Start building your exam by adding questions.</p>
        <button onclick="showAddQuestionModal()" class="inline-flex items-center space-x-2 px-6 py-3 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition duration-200">
            <i class="fas fa-plus-circle"></i>
            <span>Add Your First Question</span>
        </button>
    </div>
    @endif
</div>

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
                    <button class="p-6 border-2 border-gray-200 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition duration-200 text-left">
                        <i class="fas fa-check-square text-3xl text-blue-600 mb-3"></i>
                        <h4 class="font-bold text-gray-900 mb-1">Multiple Choice</h4>
                        <p class="text-sm text-gray-600">Question with multiple options</p>
                    </button>

                    <button class="p-6 border-2 border-gray-200 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition duration-200 text-left">
                        <i class="fas fa-toggle-on text-3xl text-green-600 mb-3"></i>
                        <h4 class="font-bold text-gray-900 mb-1">True/False</h4>
                        <p class="text-sm text-gray-600">Binary choice question</p>
                    </button>

                    <button class="p-6 border-2 border-gray-200 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition duration-200 text-left">
                        <i class="fas fa-pencil-alt text-3xl text-yellow-600 mb-3"></i>
                        <h4 class="font-bold text-gray-900 mb-1">Short Answer</h4>
                        <p class="text-sm text-gray-600">Brief text response</p>
                    </button>

                    <button class="p-6 border-2 border-gray-200 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition duration-200 text-left">
                        <i class="fas fa-file-alt text-3xl text-purple-600 mb-3"></i>
                        <h4 class="font-bold text-gray-900 mb-1">Essay</h4>
                        <p class="text-sm text-gray-600">Detailed written response</p>
                    </button>

                    <button class="p-6 border-2 border-gray-200 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition duration-200 text-left">
                        <i class="fas fa-link text-3xl text-pink-600 mb-3"></i>
                        <h4 class="font-bold text-gray-900 mb-1">Matching</h4>
                        <p class="text-sm text-gray-600">Match items to answers</p>
                    </button>

                    <button class="p-6 border-2 border-gray-200 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition duration-200 text-left">
                        <i class="fas fa-edit text-3xl text-orange-600 mb-3"></i>
                        <h4 class="font-bold text-gray-900 mb-1">Fill in the Blank</h4>
                        <p class="text-sm text-gray-600">Complete the sentence</p>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function showAddQuestionModal() {
        document.getElementById('addQuestionModal').classList.remove('hidden');
    }

    function hideAddQuestionModal() {
        document.getElementById('addQuestionModal').classList.add('hidden');
    }
</script>
