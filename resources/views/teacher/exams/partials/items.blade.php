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
                                @if($item['type'] === 'mcq')
                                    <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">
                                        <i class="fas fa-check-square mr-1"></i>Multiple Choice
                                    </span>
                                @elseif($item['type'] === 'truefalse')
                                    <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">
                                        <i class="fas fa-toggle-on mr-1"></i>True/False
                                    </span>
                                @elseif($item['type'] === 'shortanswer')
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
                                @elseif($item['type'] === 'fillblank' || $item['type'] === 'fill_blank')
                                    <span class="px-3 py-1 bg-orange-100 text-orange-700 text-xs font-semibold rounded-full">
                                        <i class="fas fa-edit mr-1"></i>Fill in the Blank
                                    </span>
                                @endif
                                <span class="px-3 py-1 bg-gray-200 text-gray-700 text-xs font-semibold rounded-full">
                                    <i class="fas fa-star mr-1"></i>{{ $item['points'] }} pts
                                </span>
                                @if(isset($item['level']))
                                    @if($item['level'] === 'easy')
                                        <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-full">
                                            <i class="fas fa-signal mr-1"></i>Easy
                                        </span>
                                    @elseif($item['level'] === 'moderate')
                                        <span class="px-3 py-1 bg-amber-100 text-amber-700 text-xs font-semibold rounded-full">
                                            <i class="fas fa-signal mr-1"></i>Moderate
                                        </span>
                                    @elseif($item['level'] === 'difficult')
                                        <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-full">
                                            <i class="fas fa-signal mr-1"></i>Difficult
                                        </span>
                                    @endif
                                @endif
                            </div>
                            <p class="text-gray-900 font-medium text-lg">{{ $item['question'] }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2 ml-4">
                        <button onclick="editQuestion({{ $item->id }}, '{{ $item->type }}')" class="p-2 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition duration-200" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form action="{{ route('teacher.exams.items.destroy', [$exam->id, $item->id]) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this question?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition duration-200" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Question Content -->
            <div class="px-6 py-4 bg-white">
                @if($item['type'] === 'mcq')
                    <div class="space-y-2">
                        <p class="text-sm font-medium text-gray-700 mb-3">Options:</p>
                        @foreach($item['options'] as $optIndex => $option)
                        <div class="flex items-center space-x-3 p-3 rounded-lg {{ isset($option['correct']) && $option['correct'] ? 'bg-green-50 border border-green-200' : 'bg-gray-50' }}">
                            <div class="w-6 h-6 rounded-full border-2 {{ isset($option['correct']) && $option['correct'] ? 'border-green-500 bg-green-100' : 'border-gray-300' }} flex items-center justify-center">
                                @if(isset($option['correct']) && $option['correct'])
                                    <i class="fas fa-check text-green-600 text-xs"></i>
                                @endif
                            </div>
                            <span class="text-gray-900 {{ isset($option['correct']) && $option['correct'] ? 'font-semibold' : '' }}">
                                {{ chr(65 + $optIndex) }}. {{ $option['text'] ?? $option }}
                                @if(isset($option['correct']) && $option['correct'])
                                    <span class="text-green-600 text-xs ml-2">(Correct Answer)</span>
                                @endif
                            </span>
                        </div>
                        @endforeach
                    </div>
                @elseif($item['type'] === 'truefalse')
                    <div class="flex items-center space-x-4">
                        <span class="text-sm font-medium text-gray-700">Correct Answer:</span>
                        <span class="px-4 py-2 bg-green-100 text-green-700 font-semibold rounded-lg">
                            <i class="fas fa-{{ ($item['answer'] === 'true') ? 'check' : 'times' }} mr-1"></i>
                            {{ ($item['answer'] === 'true') ? 'True' : 'False' }}
                        </span>
                    </div>
                @elseif($item['type'] === 'shortanswer')
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-sm text-blue-800">
                            <i class="fas fa-info-circle mr-1"></i>
                            This is a short answer question. Manual grading required.
                            @if(!empty($item['expected_answer']))
                                <br><strong>Expected Answer:</strong> {{ $item['expected_answer'] }}
                            @endif
                        </p>
                    </div>
                @elseif($item['type'] === 'essay')
                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                        <p class="text-sm text-purple-800">
                            <i class="fas fa-info-circle mr-1"></i>
                            This is an essay question. Manual grading required.
                            @if(!empty($item['expected_answer']))
                                <br><strong>Grading Rubric:</strong> {{ $item['expected_answer'] }}
                            @endif
                        </p>
                    </div>
                @elseif($item['type'] === 'matching')
                    <div class="space-y-2">
                        <p class="text-sm font-medium text-gray-700 mb-3">Matching Pairs:</p>
                        @foreach($item['pairs'] as $pair)
                        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                            <span class="text-gray-900 font-medium">{{ $pair['left'] ?? '' }}</span>
                            <i class="fas fa-arrow-right text-gray-400"></i>
                            <span class="text-indigo-600 font-semibold">{{ $pair['right'] ?? '' }}</span>
                        </div>
                        @endforeach
                    </div>
                @elseif($item['type'] === 'fillblank' || $item['type'] === 'fill_blank')
                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                        <p class="text-sm text-orange-800">
                            <i class="fas fa-info-circle mr-1"></i>
                            This is a fill in the blank question.
                            @if(!empty($item['expected_answer']))
                                <br><strong>Expected Answer:</strong> {{ $item['expected_answer'] }}
                            @endif
                        </p>
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

<script>
    // Store items data for editing
    const examItems = @json($examItems);
    const examId = {{ $exam->id }};

    function showAddQuestionModal() {
        document.getElementById('addQuestionModal').classList.remove('hidden');
    }

    function hideAddQuestionModal() {
        document.getElementById('addQuestionModal').classList.add('hidden');
        hideAllQuestionForms();
    }

    function hideAllQuestionForms() {
        document.querySelectorAll('[id$="QuestionForm"]').forEach(form => {
            form.classList.add('hidden');
        });
    }

    function showQuestionForm(type) {
        hideAllQuestionForms();
        document.getElementById(type + 'QuestionForm').classList.remove('hidden');
    }

    function editQuestion(itemId, type) {
        const item = examItems.find(i => i.id === itemId);
        if (!item) {
            alert('Question not found');
            return;
        }

        // Map database type to form type
        const typeMap = {
            'mcq': 'mcq',
            'truefalse': 'trueFalse',
            'shortanswer': 'shortAnswer',
            'essay': 'essay',
            'matching': 'matching',
            'fillblank': 'fillBlank',
            'fill_blank': 'fillBlank'
        };

        const formType = typeMap[type] || type;
        const formId = 'edit' + formType.charAt(0).toUpperCase() + formType.slice(1) + 'QuestionForm';

        // Set the form action for this item
        if (type === 'mcq') {
            setEditMcqAction(examId, itemId);
            populateEditMcqForm(item);
        } else if (type === 'truefalse') {
            setEditTrueFalseAction(examId, itemId);
            populateEditTrueFalseForm(item);
        } else if (type === 'shortanswer') {
            setEditShortAnswerAction(examId, itemId);
            populateEditShortAnswerForm(item);
        } else if (type === 'essay') {
            setEditEssayAction(examId, itemId);
            populateEditEssayForm(item);
        } else if (type === 'matching') {
            setEditMatchingAction(examId, itemId);
            populateEditMatchingForm(item);
        } else if (type === 'fillblank' || type === 'fill_blank') {
            setEditFillBlankAction(examId, itemId);
            populateEditFillBlankForm(item);
        }

        // Show the edit form
        document.getElementById(formId).classList.remove('hidden');
    }

    function populateEditMcqForm(item) {
        document.querySelector('#editMcqQuestionForm textarea[name="question"]').value = item.question || '';
        document.querySelector('#editMcqQuestionForm input[name="points"]').value = item.points || 1;
        document.querySelector('#editMcqQuestionForm select[name="level"]').value = item.level || 'moderate';

        // Clear and populate options
        const container = document.getElementById('editMcqOptionsContainer');
        container.innerHTML = '';
        editMcqOptionIndex = 0;

        if (item.options && item.options.length > 0) {
            item.options.forEach((option, index) => {
                const optionDiv = document.createElement('div');
                optionDiv.className = 'flex items-center space-x-2';
                const isChecked = option.correct ? 'checked' : '';
                const text = option.text || option;
                optionDiv.innerHTML = `
                    <input type="checkbox" name="options[${index}][correct]" value="1" ${isChecked} class="w-5 h-5 text-indigo-600">
                    <input type="text" name="options[${index}][text]" placeholder="Option ${index + 1}" value="${text}" required
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    ${index > 1 ? '<button type="button" onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-700"><i class="fas fa-times"></i></button>' : ''}
                `;
                container.appendChild(optionDiv);
                editMcqOptionIndex++;
            });
        }
    }

    function populateEditTrueFalseForm(item) {
        document.querySelector('#editTrueFalseQuestionForm textarea[name="question"]').value = item.question || '';
        document.querySelector('#editTrueFalseQuestionForm input[name="points"]').value = item.points || 1;
        document.querySelector('#editTrueFalseQuestionForm select[name="level"]').value = item.level || 'moderate';
        document.querySelector('#editTrueFalseQuestionForm select[name="answer"]').value = item.answer || 'true';
    }

    function populateEditShortAnswerForm(item) {
        document.querySelector('#editShortAnswerQuestionForm textarea[name="question"]').value = item.question || '';
        document.querySelector('#editShortAnswerQuestionForm input[name="points"]').value = item.points || 1;
        document.querySelector('#editShortAnswerQuestionForm select[name="level"]').value = item.level || 'moderate';
        document.querySelector('#editShortAnswerQuestionForm textarea[name="expected_answer"]').value = item.expected_answer || '';
    }

    function populateEditEssayForm(item) {
        document.querySelector('#editEssayQuestionForm textarea[name="question"]').value = item.question || '';
        document.querySelector('#editEssayQuestionForm input[name="points"]').value = item.points || 10;
        document.querySelector('#editEssayQuestionForm select[name="level"]').value = item.level || 'moderate';
        document.querySelector('#editEssayQuestionForm textarea[name="expected_answer"]').value = item.expected_answer || '';
    }

    function populateEditMatchingForm(item) {
        document.querySelector('#editMatchingQuestionForm textarea[name="question"]').value = item.question || '';
        document.querySelector('#editMatchingQuestionForm input[name="points"]').value = item.points || 1;
        document.querySelector('#editMatchingQuestionForm select[name="level"]').value = item.level || 'moderate';

        // Clear and populate pairs
        const container = document.getElementById('editMatchingPairsContainer');
        container.innerHTML = '';
        editMatchingPairIndex = 0;

        if (item.pairs && item.pairs.length > 0) {
            item.pairs.forEach((pair, index) => {
                const pairDiv = document.createElement('div');
                pairDiv.className = 'flex items-center space-x-3';
                const left = pair.left || '';
                const right = pair.right || '';
                pairDiv.innerHTML = `
                    <input type="text" name="pairs[${index}][left]" placeholder="Item ${index + 1}" value="${left}" required
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    <input type="text" name="pairs[${index}][right]" placeholder="Match ${index + 1}" value="${right}" required
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    ${index > 1 ? '<button type="button" onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-700"><i class="fas fa-times"></i></button>' : ''}
                `;
                container.appendChild(pairDiv);
                editMatchingPairIndex++;
            });
        }
    }

    function populateEditFillBlankForm(item) {
        document.querySelector('#editFillBlankQuestionForm textarea[name="question"]').value = item.question || '';
        document.querySelector('#editFillBlankQuestionForm input[name="points"]').value = item.points || 1;
        document.querySelector('#editFillBlankQuestionForm select[name="level"]').value = item.level || 'moderate';
        document.querySelector('#editFillBlankQuestionForm textarea[name="expected_answer"]').value = item.expected_answer || '';
    }

    function addOption(containerId) {
        const container = document.getElementById(containerId);
        const optionCount = container.children.length + 1;
        const optionDiv = document.createElement('div');
        optionDiv.className = 'flex items-center space-x-2';
        optionDiv.innerHTML = `
            <input type="radio" name="correct_answer" value="${optionCount}" class="w-5 h-5 text-indigo-600">
            <input type="text" name="options[]" placeholder="Option ${optionCount}" required
                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
            <button type="button" onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-700">
                <i class="fas fa-times"></i>
            </button>
        `;
        container.appendChild(optionDiv);
    }

    function addPair(containerId) {
        const container = document.getElementById(containerId);
        const pairDiv = document.createElement('div');
        pairDiv.className = 'grid grid-cols-2 gap-3';
        pairDiv.innerHTML = `
            <input type="text" name="pairs_left[]" placeholder="Item" required
                class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
            <div class="flex items-center space-x-2">
                <input type="text" name="pairs_right[]" placeholder="Match" required
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                <button type="button" onclick="this.parentElement.parentElement.remove()" class="text-red-600 hover:text-red-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        container.appendChild(pairDiv);
    }

    function addBlank() {
        const container = document.getElementById('blanksContainer');
        const blankDiv = document.createElement('div');
        blankDiv.className = 'flex items-center space-x-2';
        blankDiv.innerHTML = `
            <input type="text" name="blanks[]" placeholder="Correct answer for blank" required
                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
            <button type="button" onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-700">
                <i class="fas fa-times"></i>
            </button>
        `;
        container.appendChild(blankDiv);
    }
</script>

<!-- Include Question Type Forms -->
@include('teacher.exams.partials.forms.mcq')
@include('teacher.exams.partials.forms.truefalse')
@include('teacher.exams.partials.forms.shortanswer')
@include('teacher.exams.partials.forms.essay')
@include('teacher.exams.partials.forms.matching')
@include('teacher.exams.partials.forms.fillblank')

<!-- Include Edit Question Type Forms -->
@include('teacher.exams.partials.forms.edit-mcq')
@include('teacher.exams.partials.forms.edit-truefalse')
@include('teacher.exams.partials.forms.edit-shortanswer')
@include('teacher.exams.partials.forms.edit-essay')
@include('teacher.exams.partials.forms.edit-matching')
@include('teacher.exams.partials.forms.edit-fillblank')

