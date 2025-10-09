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
                        <button onclick="showDeleteModal({{ $item->id }}, '{{ addslashes($item->question) }}')" class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition duration-200" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
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

<!-- Include Modals -->
@include('teacher.exams.partials.modals.add-question-modal')
@include('teacher.exams.partials.modals.delete-question-modal')

<!-- Include Core Scripts -->
@include('teacher.exams.partials.items-scripts')

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

