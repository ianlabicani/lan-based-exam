@extends('student.shell')

@section('student-content')

<div class="min-h-screen bg-gray-100 py-8"
     x-data="examInterface()"
     x-init="init()"
     @beforeunload.window="handleBeforeUnload($event)">

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Exam Header -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <!-- Title & Info -->
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $exam->title }}</h1>
                    <p class="text-gray-600 text-sm">{{ $exam->description }}</p>
                </div>

                <!-- Timer -->
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-4 rounded-lg shadow-lg">
                    <div class="text-center">
                        <p class="text-xs text-indigo-100 mb-1">Time Remaining</p>
                        <div class="text-3xl font-bold font-mono" x-text="formatTime(timeRemaining)">00:00:00</div>
                        <div class="mt-2 bg-white bg-opacity-20 rounded-full h-1">
                            <div class="bg-white h-1 rounded-full transition-all duration-1000"
                                 :style="`width: ${timePercentage}%`"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="mt-6">
                <div class="flex items-center justify-between text-sm mb-2">
                    <span class="text-gray-600 font-medium">Progress</span>
                    <span class="text-gray-900 font-semibold" x-text="`${answeredCount} / ${totalQuestions} Answered`">0 / {{ $exam->items->count() }} Answered</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-green-500 h-3 rounded-full transition-all duration-300"
                         :style="`width: ${progressPercentage}%`"></div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

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

            <!-- Question Display -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-xl shadow-md p-8">
                    <template x-if="currentQuestion">
                        <div>
                            <!-- Question Header -->
                            <div class="flex items-start justify-between mb-6">
                                <div class="flex-1">
                                    <span class="text-sm font-semibold text-indigo-600 uppercase tracking-wide">
                                        Question <span x-text="currentQuestionIndex + 1"></span> of <span x-text="totalQuestions"></span>
                                    </span>
                                    <h2 class="text-xl font-bold text-gray-900 mt-2" x-text="currentQuestion.question"></h2>
                                </div>
                                <div class="ml-4">
                                    <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-sm font-semibold rounded-full">
                                        <i class="fas fa-star mr-1"></i><span x-text="currentQuestion.points"></span> pts
                                    </span>
                                </div>
                            </div>

                            <!-- Question Type Badge -->
                            <div class="mb-6">
                                <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs font-medium rounded-full uppercase">
                                    <span x-text="getTypeLabel(currentQuestion.type)"></span>
                                </span>
                            </div>

                            <!-- Answer Input Based on Type -->
                            <div class="mb-8">
                                <!-- Multiple Choice -->
                                <template x-if="currentQuestion.type === 'mcq'">
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
                                </template>

                                <!-- True/False -->
                                <template x-if="currentQuestion.type === 'truefalse'">
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
                                </template>

                                <!-- Fill in the Blank / Short Answer -->
                                <template x-if="currentQuestion.type === 'fillblank' || currentQuestion.type === 'fill_blank' || currentQuestion.type === 'shortanswer'">
                                    <div>
                                        <input type="text"
                                               x-model="answers[currentQuestion.id]"
                                               @input="debouncedSave()"
                                               placeholder="Type your answer here..."
                                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                </template>

                                <!-- Essay -->
                                <template x-if="currentQuestion.type === 'essay'">
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
                                </template>
                            </div>

                            <!-- Save Indicator -->
                            <div class="mb-6">
                                <p class="text-sm" :class="isSaving ? 'text-yellow-600' : 'text-green-600'">
                                    <i :class="isSaving ? 'fas fa-spinner fa-spin' : 'fas fa-check-circle'" class="mr-1"></i>
                                    <span x-text="isSaving ? 'Saving...' : 'Answer saved'"></span>
                                </p>
                            </div>

                            <!-- Navigation Buttons -->
                            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                                <button @click="previousQuestion()"
                                        :disabled="currentQuestionIndex === 0"
                                        :class="currentQuestionIndex === 0 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-100'"
                                        class="px-6 py-3 bg-white border-2 border-gray-300 text-gray-700 rounded-lg font-medium transition duration-200">
                                    <i class="fas fa-chevron-left mr-2"></i>Previous
                                </button>

                                <span class="text-gray-500 text-sm font-medium">
                                    <span x-text="currentQuestionIndex + 1"></span> / <span x-text="totalQuestions"></span>
                                </span>

                                <button @click="nextQuestion()"
                                        :disabled="currentQuestionIndex === totalQuestions - 1"
                                        :class="currentQuestionIndex === totalQuestions - 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-indigo-700'"
                                        class="px-6 py-3 bg-indigo-600 text-white rounded-lg font-medium transition duration-200">
                                    Next<i class="fas fa-chevron-right ml-2"></i>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <!-- Submit Confirmation Modal -->
    <div x-show="showSubmitModal"
         x-cloak
         @click.self="showSubmitModal = false"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-8"
             @click.stop
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-90"
             x-transition:enter-end="opacity-100 transform scale-100">
            <div class="text-center mb-6">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-yellow-100 mb-4">
                    <i class="fas fa-exclamation-triangle text-yellow-600 text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Submit Exam?</h3>
                <p class="text-gray-600">Are you sure you want to submit your exam? This action cannot be undone.</p>
            </div>

            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center justify-between text-sm mb-2">
                    <span class="text-gray-600">Answered:</span>
                    <span class="font-semibold text-gray-900" x-text="`${answeredCount} / ${totalQuestions}`"></span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Unanswered:</span>
                    <span class="font-semibold" :class="unansweredCount > 0 ? 'text-red-600' : 'text-green-600'"
                          x-text="unansweredCount"></span>
                </div>
            </div>

            <div class="flex space-x-3">
                <button @click="showSubmitModal = false"
                        class="flex-1 px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition duration-200">
                    Cancel
                </button>
                <button @click="submitExam()"
                        :disabled="isSubmitting"
                        class="flex-1 px-6 py-3 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span x-text="isSubmitting ? 'Submitting...' : 'Submit'"></span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function examInterface() {
    return {
        // Exam data
        examId: {{ $exam->id }},
        takenExamId: {{ $takenExam->id }},
        questions: {!! json_encode($exam->items->map(function($item) {
            return [
                'id' => $item->id,
                'question' => $item->question,
                'type' => $item->type,
                'points' => $item->points,
                'options' => $item->options ?? [],
                'pairs' => $item->pairs ?? [],
                'expected_answer' => $item->expected_answer ?? null
            ];
        })->values()) !!},
        savedAnswers: @json($takenExam->answers->pluck('answer', 'exam_item_id')),

        // State
        currentQuestionIndex: 0,
        answers: {},
        isSaving: false,
        isSubmitting: false,
        showSubmitModal: false,

        // Timer
        timeRemaining: {{ $exam->time_limit ? $exam->time_limit * 60 : 3600 }}, // in seconds
        totalTime: {{ $exam->time_limit ? $exam->time_limit * 60 : 3600 }},
        timerInterval: null,

        // Activity monitoring
        tabSwitchCount: 0,
        windowBlurCount: 0,

        init() {
            // Load saved answers - convert to proper format based on question type
            const savedAnswersObj = this.savedAnswers;
            this.answers = {};

            // Populate answers object with saved data
            for (const [itemId, answer] of Object.entries(savedAnswersObj)) {
                // Convert string keys to numbers for consistency
                const numericItemId = parseInt(itemId);

                if (answer !== null && answer !== undefined && answer !== '') {
                    // Find the question to determine type
                    const question = this.questions.find(q => q.id === numericItemId);

                    if (question) {
                        // For MCQ, convert answer to number if it's a string number
                        if (question.type === 'mcq' && typeof answer === 'string' && !isNaN(answer)) {
                            this.answers[numericItemId] = parseInt(answer);
                        } else {
                            this.answers[numericItemId] = answer;
                        }
                    } else {
                        this.answers[numericItemId] = answer;
                    }
                }
            }

            console.log('Loaded answers:', this.answers); // Debug log

            // Start timer
            this.startTimer();

            // Activity monitoring
            this.setupActivityMonitoring();

            // Debounce setup
            this.debouncedSave = this.debounce(() => this.saveAnswer(), 1000);
        },        get currentQuestion() {
            return this.questions[this.currentQuestionIndex] || null;
        },

        get totalQuestions() {
            return this.questions.length;
        },

        get answeredCount() {
            return Object.keys(this.answers).filter(key => {
                const answer = this.answers[key];
                return answer !== null && answer !== undefined && answer !== '';
            }).length;
        },

        get unansweredCount() {
            return this.totalQuestions - this.answeredCount;
        },

        get progressPercentage() {
            return this.totalQuestions > 0 ? (this.answeredCount / this.totalQuestions) * 100 : 0;
        },

        get timePercentage() {
            return this.totalTime > 0 ? (this.timeRemaining / this.totalTime) * 100 : 0;
        },

        formatTime(seconds) {
            const h = Math.floor(seconds / 3600);
            const m = Math.floor((seconds % 3600) / 60);
            const s = seconds % 60;
            return `${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
        },

        getTypeLabel(type) {
            const labels = {
                'mcq': 'Multiple Choice',
                'truefalse': 'True/False',
                'fillblank': 'Fill in the Blank',
                'fill_blank': 'Fill in the Blank',
                'shortanswer': 'Short Answer',
                'essay': 'Essay',
                'matching': 'Matching'
            };
            return labels[type] || type;
        },

        startTimer() {
            this.timerInterval = setInterval(() => {
                this.timeRemaining--;

                if (this.timeRemaining <= 0) {
                    clearInterval(this.timerInterval);
                    this.autoSubmit();
                }
            }, 1000);
        },

        goToQuestion(index) {
            this.currentQuestionIndex = index;
        },

        previousQuestion() {
            if (this.currentQuestionIndex > 0) {
                this.currentQuestionIndex--;
            }
        },

        nextQuestion() {
            if (this.currentQuestionIndex < this.totalQuestions - 1) {
                this.currentQuestionIndex++;
            }
        },

        async saveAnswer() {
            if (!this.currentQuestion || !this.answers[this.currentQuestion.id]) return;

            this.isSaving = true;

            console.log('Saving answer:', {
                item_id: this.currentQuestion.id,
                answer: this.answers[this.currentQuestion.id]
            }); // Debug log

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (!csrfToken) {
                    console.error('CSRF token not found');
                    return;
                }

                const response = await fetch(`/student/taken-exams/${this.takenExamId}/save-answer`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken.content
                    },
                    body: JSON.stringify({
                        item_id: this.currentQuestion.id,
                        answer: this.answers[this.currentQuestion.id]
                    })
                });

                if (!response.ok) throw new Error('Failed to save answer');

            } catch (error) {
                console.error('Error saving answer:', error);
            } finally {
                setTimeout(() => { this.isSaving = false; }, 500);
            }
        },

        async submitExam() {
            this.isSubmitting = true;

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (!csrfToken) {
                    console.error('CSRF token not found');
                    alert('Session expired. Please refresh the page.');
                    return;
                }

                const response = await fetch(`/student/taken-exams/${this.takenExamId}/submit`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken.content
                    }
                });

                if (response.redirected) {
                    window.location.href = response.url;
                } else {
                    const data = await response.json();
                    if (data.success) {
                        window.location.href = `/student/taken-exams/${this.takenExamId}`;
                    }
                }
            } catch (error) {
                console.error('Error submitting exam:', error);
                alert('Failed to submit exam. Please try again.');
                this.isSubmitting = false;
            }
        },

        autoSubmit() {
            alert('Time is up! Your exam will be automatically submitted.');
            this.submitExam();
        },

        setupActivityMonitoring() {
            // Tab visibility change
            document.addEventListener('visibilitychange', () => {
                if (document.hidden) {
                    this.logActivity('visibility_change');
                }
            });

            // Window blur
            window.addEventListener('blur', () => {
                this.windowBlurCount++;
                this.logActivity('window_blur');
            });
        },

        async logActivity(eventType) {
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (!csrfToken) {
                    console.error('CSRF token not found');
                    return;
                }

                await fetch(`/student/taken-exams/${this.takenExamId}/activity`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken.content
                    },
                    body: JSON.stringify({
                        event_type: eventType,
                        timestamp: new Date().toISOString()
                    })
                });
            } catch (error) {
                console.error('Error logging activity:', error);
            }
        },

        handleBeforeUnload(event) {
            if (!this.isSubmitting) {
                event.preventDefault();
                event.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
            }
        },

        debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
    }
}
</script>

<style>
[x-cloak] { display: none !important; }
</style>

@endsection
