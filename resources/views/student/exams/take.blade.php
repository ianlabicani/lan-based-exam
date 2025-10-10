@extends('student.shell')

@section('student-content')

<div class="min-h-screen bg-gray-100 py-6" x-data="examTaker()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Exam Header -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $exam->title }}</h1>
                    <p class="text-gray-600 mt-1">{{ $exam->description }}</p>
                </div>
                <div class="text-right">
                    <div class="mb-2">
                        <span class="text-sm text-gray-600">Time Remaining:</span>
                        <div class="text-2xl font-bold" :class="timeRemaining < 300 ? 'text-red-600' : 'text-indigo-600'" x-text="formatTime(timeRemaining)"></div>
                    </div>
                    <button @click="confirmSubmit()"
                            class="px-6 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition duration-200">
                        <i class="fas fa-check-circle mr-2"></i>Submit Exam
                    </button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-6">
            <!-- Question Navigator (Left Sidebar) -->
            <div class="col-span-12 lg:col-span-3">
                <div class="bg-white rounded-xl shadow-md p-4 sticky top-24">
                    <h3 class="font-bold text-gray-900 mb-3 pb-3 border-b">
                        <i class="fas fa-list mr-2 text-indigo-600"></i>Questions
                    </h3>
                    <div class="grid grid-cols-5 lg:grid-cols-4 gap-2 max-h-96 overflow-y-auto">
                        @foreach($exam->items as $index => $item)
                        <button @click="currentQuestion = {{ $index }}"
                                :class="{
                                    'bg-indigo-600 text-white': currentQuestion === {{ $index }},
                                    'bg-green-100 text-green-700 border-green-300': currentQuestion !== {{ $index }} && answers[{{ $item->id }}],
                                    'bg-gray-100 text-gray-700 hover:bg-gray-200': currentQuestion !== {{ $index }} && !answers[{{ $item->id }}]
                                }"
                                class="w-10 h-10 rounded-lg font-semibold border-2 transition duration-200">
                            {{ $index + 1 }}
                        </button>
                        @endforeach
                    </div>
                    <div class="mt-4 pt-4 border-t space-y-2 text-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Answered:</span>
                            <span class="font-bold text-green-600" x-text="Object.keys(answers).length"></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Unanswered:</span>
                            <span class="font-bold text-gray-700" x-text="{{ $exam->items->count() }} - Object.keys(answers).length"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Question Display (Main Content) -->
            <div class="col-span-12 lg:col-span-9">
                <div class="bg-white rounded-xl shadow-md p-8">
                    @foreach($exam->items as $index => $item)
                    <div x-show="currentQuestion === {{ $index }}" x-cloak>
                        <!-- Question Header -->
                        <div class="flex items-start justify-between mb-6">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-3">
                                    <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-sm font-semibold rounded-full">
                                        Question {{ $index + 1 }} of {{ $exam->items->count() }}
                                    </span>
                                    <span class="px-3 py-1 bg-gray-100 text-gray-700 text-sm font-semibold rounded-full">
                                        {{ $item->points }} {{ $item->points > 1 ? 'points' : 'point' }}
                                    </span>
                                    @if($item->type === 'mcq')
                                        <span class="px-3 py-1 bg-blue-100 text-blue-700 text-sm font-semibold rounded-full">
                                            <i class="fas fa-check-square mr-1"></i>Multiple Choice
                                        </span>
                                    @elseif($item->type === 'truefalse')
                                        <span class="px-3 py-1 bg-green-100 text-green-700 text-sm font-semibold rounded-full">
                                            <i class="fas fa-toggle-on mr-1"></i>True/False
                                        </span>
                                    @elseif($item->type === 'shortanswer')
                                        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-sm font-semibold rounded-full">
                                            <i class="fas fa-pencil-alt mr-1"></i>Short Answer
                                        </span>
                                    @elseif($item->type === 'essay')
                                        <span class="px-3 py-1 bg-purple-100 text-purple-700 text-sm font-semibold rounded-full">
                                            <i class="fas fa-file-alt mr-1"></i>Essay
                                        </span>
                                    @elseif($item->type === 'fillblank' || $item->type === 'fill_blank')
                                        <span class="px-3 py-1 bg-orange-100 text-orange-700 text-sm font-semibold rounded-full">
                                            <i class="fas fa-edit mr-1"></i>Fill in the Blank
                                        </span>
                                    @endif
                                </div>
                                <h2 class="text-2xl font-bold text-gray-900">{{ $item->question }}</h2>
                            </div>
                        </div>

                        <!-- Answer Input -->
                        <div class="mb-8">
                            @if($item->type === 'mcq')
                                <!-- Multiple Choice -->
                                <div class="space-y-3">
                                    @foreach($item->options as $optIndex => $option)
                                    <label class="flex items-start p-4 border-2 rounded-lg cursor-pointer transition duration-200 hover:border-indigo-300 hover:bg-indigo-50"
                                           :class="answers[{{ $item->id }}] == {{ $optIndex }} ? 'border-indigo-600 bg-indigo-50' : 'border-gray-200'">
                                        <input type="radio"
                                               name="question_{{ $item->id }}"
                                               value="{{ $optIndex }}"
                                               @change="saveAnswer({{ $item->id }}, {{ $optIndex }})"
                                               :checked="answers[{{ $item->id }}] == {{ $optIndex }}"
                                               class="mt-1 w-5 h-5 text-indigo-600">
                                        <span class="ml-3 text-gray-900">
                                            <strong>{{ chr(65 + $optIndex) }}.</strong> {{ is_array($option) ? $option['text'] : $option }}
                                        </span>
                                    </label>
                                    @endforeach
                                </div>

                            @elseif($item->type === 'truefalse')
                                <!-- True/False -->
                                <div class="space-y-3">
                                    <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition duration-200 hover:border-green-300 hover:bg-green-50"
                                           :class="answers[{{ $item->id }}] === 'true' ? 'border-green-600 bg-green-50' : 'border-gray-200'">
                                        <input type="radio"
                                               name="question_{{ $item->id }}"
                                               value="true"
                                               @change="saveAnswer({{ $item->id }}, 'true')"
                                               :checked="answers[{{ $item->id }}] === 'true'"
                                               class="w-5 h-5 text-green-600">
                                        <span class="ml-3 text-lg font-semibold text-gray-900">
                                            <i class="fas fa-check mr-2 text-green-600"></i>True
                                        </span>
                                    </label>
                                    <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition duration-200 hover:border-red-300 hover:bg-red-50"
                                           :class="answers[{{ $item->id }}] === 'false' ? 'border-red-600 bg-red-50' : 'border-gray-200'">
                                        <input type="radio"
                                               name="question_{{ $item->id }}"
                                               value="false"
                                               @change="saveAnswer({{ $item->id }}, 'false')"
                                               :checked="answers[{{ $item->id }}] === 'false'"
                                               class="w-5 h-5 text-red-600">
                                        <span class="ml-3 text-lg font-semibold text-gray-900">
                                            <i class="fas fa-times mr-2 text-red-600"></i>False
                                        </span>
                                    </label>
                                </div>

                            @elseif($item->type === 'shortanswer' || $item->type === 'fillblank' || $item->type === 'fill_blank')
                                <!-- Short Answer / Fill in the Blank -->
                                <input type="text"
                                       :value="answers[{{ $item->id }}] || ''"
                                       @input="saveAnswer({{ $item->id }}, $event.target.value)"
                                       placeholder="Type your answer here..."
                                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">

                            @elseif($item->type === 'essay')
                                <!-- Essay -->
                                <textarea rows="8"
                                          :value="answers[{{ $item->id }}] || ''"
                                          @input="saveAnswer({{ $item->id }}, $event.target.value)"
                                          placeholder="Write your answer here..."
                                          class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                            @endif
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="flex items-center justify-between pt-6 border-t">
                            <button @click="previousQuestion()"
                                    x-show="currentQuestion > 0"
                                    class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition duration-200">
                                <i class="fas fa-arrow-left mr-2"></i>Previous
                            </button>
                            <div class="flex-1"></div>
                            <button @click="nextQuestion()"
                                    x-show="currentQuestion < {{ $exam->items->count() - 1 }}"
                                    class="px-6 py-3 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition duration-200">
                                Next<i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Submit Confirmation Modal -->
    <div x-show="showSubmitModal"
         x-cloak
         class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div @click.away="showSubmitModal = false"
             x-transition
             class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6">
            <div class="text-center">
                <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check-circle text-3xl text-green-600"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Submit Exam?</h3>
                <p class="text-gray-600 mb-6">
                    You have answered <strong x-text="Object.keys(answers).length"></strong> out of <strong>{{ $exam->items->count() }}</strong> questions.
                    <br><br>
                    Once submitted, you cannot change your answers.
                </p>
            </div>
            <div class="flex space-x-3">
                <button @click="showSubmitModal = false"
                        class="flex-1 px-4 py-3 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition duration-200">
                    Cancel
                </button>
                <button @click="submitExam()"
                        :disabled="submitting"
                        class="flex-1 px-4 py-3 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span x-show="!submitting">Yes, Submit</span>
                    <span x-show="submitting"><i class="fas fa-spinner fa-spin mr-2"></i>Submitting...</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function examTaker() {
    return {
        currentQuestion: 0,
        answers: @json($answers->mapWithKeys(function($answer) {
            return [$answer->exam_item_id => $answer->answer];
        })),
        timeRemaining: {{ \Carbon\Carbon::parse($exam->ends_at)->diffInSeconds(now()) }},
        showSubmitModal: false,
        submitting: false,
        autoSaveTimeout: null,

        init() {
            // Start countdown timer
            setInterval(() => {
                if (this.timeRemaining > 0) {
                    this.timeRemaining--;
                } else {
                    // Auto-submit when time runs out
                    this.submitExam();
                }
            }, 1000);
        },

        formatTime(seconds) {
            const hours = Math.floor(seconds / 3600);
            const minutes = Math.floor((seconds % 3600) / 60);
            const secs = seconds % 60;
            return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        },

        saveAnswer(itemId, answer) {
            this.answers[itemId] = answer;

            // Debounce auto-save
            clearTimeout(this.autoSaveTimeout);
            this.autoSaveTimeout = setTimeout(() => {
                this.autoSave(itemId, answer);
            }, 1000);
        },

        async autoSave(itemId, answer) {
            try {
                const response = await fetch('{{ route("student.exams.saveAnswer", $exam->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        item_id: itemId,
                        answer: answer
                    })
                });

                if (!response.ok) {
                    console.error('Failed to save answer');
                }
            } catch (error) {
                console.error('Error saving answer:', error);
            }
        },

        nextQuestion() {
            if (this.currentQuestion < {{ $exam->items->count() - 1 }}) {
                this.currentQuestion++;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        },

        previousQuestion() {
            if (this.currentQuestion > 0) {
                this.currentQuestion--;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        },

        confirmSubmit() {
            this.showSubmitModal = true;
        },

        async submitExam() {
            this.submitting = true;

            try {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("student.exams.submit", $exam->id) }}';

                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = document.querySelector('meta[name="csrf-token"]').content;
                form.appendChild(csrfInput);

                document.body.appendChild(form);
                form.submit();
            } catch (error) {
                console.error('Error submitting exam:', error);
                this.submitting = false;
            }
        }
    }
}
</script>

<style>
[x-cloak] { display: none !important; }
</style>

@endsection
