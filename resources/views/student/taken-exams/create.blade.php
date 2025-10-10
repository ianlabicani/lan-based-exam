@extends('student.shell')

@section('student-content')

<div class="min-h-screen bg-gray-100 py-8"
     x-data="examInterface()"
     x-init="init()"
     @beforeunload.window="handleBeforeUnload($event)">

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        @include('student.taken-exams.partials.exam-header')

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

            @include('student.taken-exams.partials.navigation-sidebar')

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
                                    @include('student.taken-exams.partials.question-mcq')
                                </template>

                                <!-- True/False -->
                                <template x-if="currentQuestion.type === 'truefalse'">
                                    @include('student.taken-exams.partials.question-truefalse')
                                </template>

                                <!-- Fill in the Blank / Short Answer -->
                                <template x-if="currentQuestion.type === 'fillblank' || currentQuestion.type === 'fill_blank' || currentQuestion.type === 'shortanswer'">
                                    @include('student.taken-exams.partials.question-fillblank')
                                </template>

                                <!-- Essay -->
                                <template x-if="currentQuestion.type === 'essay'">
                                    @include('student.taken-exams.partials.question-essay')
                                </template>

                                <!-- Matching -->
                                <template x-if="currentQuestion.type === 'matching'">
                                    @include('student.taken-exams.partials.question-matching')
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

    @include('student.taken-exams.partials.submit-modal')
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
        shuffledOptions: {}, // Cache for shuffled matching options
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
                        }
                        // For matching, parse JSON if it's a string
                        else if (question.type === 'matching' && typeof answer === 'string') {
                            try {
                                const parsedAnswer = JSON.parse(answer);
                                console.log('Parsed matching answer for question', numericItemId, ':', parsedAnswer); // Debug
                                console.log('Question pairs:', question.pairs); // Debug - see the full pairs structure
                                // The saved answer format is {"0":"0","1":"1","2":"2"}
                                // where key is the left index and value is the right index
                                // Convert indices to text for display in dropdowns
                                const convertedAnswer = {};
                                for (const [leftIndex, rightIndex] of Object.entries(parsedAnswer)) {
                                    const leftIndexNum = parseInt(leftIndex);  // Convert to number for array index
                                    const rightIndexNum = parseInt(rightIndex);
                                    if (question.pairs && question.pairs[rightIndexNum]) {
                                        convertedAnswer[leftIndexNum] = question.pairs[rightIndexNum].right;
                                        console.log(`Converting: leftIndex=${leftIndexNum} (was "${leftIndex}"), rightIndex=${rightIndex} -> rightText="${question.pairs[rightIndexNum].right}"`); // Debug
                                    } else {
                                        console.warn(`Pair not found at index ${rightIndexNum} for question ${numericItemId}`);
                                    }
                                }
                                console.log('Converted answer for display:', convertedAnswer); // Debug
                                console.log('Type of keys:', Object.keys(convertedAnswer).map(k => typeof k)); // Debug key types
                                this.answers[numericItemId] = convertedAnswer;
                            } catch (e) {
                                console.error('Error parsing matching answer:', e); // Debug
                                this.answers[numericItemId] = {};
                            }
                        } else {
                            this.answers[numericItemId] = answer;
                        }
                    } else {
                        this.answers[numericItemId] = answer;
                    }
                }
            }

            console.log('Loaded answers:', this.answers); // Debug log

            // Initialize empty objects for matching questions that don't have saved answers
            this.questions.forEach(question => {
                if (question.type === 'matching' && !this.answers[question.id]) {
                    this.answers[question.id] = {};
                }
            });

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
                // For matching questions, check if all pairs are answered
                const question = this.questions.find(q => q.id === parseInt(key));
                if (question && question.type === 'matching') {
                    if (typeof answer !== 'object' || answer === null) return false;
                    const pairCount = question.pairs?.length || 0;
                    const answeredPairs = Object.keys(answer).filter(k => answer[k] !== null && answer[k] !== '').length;
                    return answeredPairs === pairCount;
                }
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

        // Matching question helpers
        getMatchingAnswer(questionId, pairIndex) {
            if (!this.answers[questionId]) {
                this.answers[questionId] = {};
            }
            return this.answers[questionId][pairIndex] || '';
        },

        updateMatchingAnswer(questionId, pairIndex, value) {
            if (!this.answers[questionId]) {
                this.answers[questionId] = {};
            }
            this.answers[questionId][pairIndex] = value;
            console.log('Updated matching answer:', questionId, pairIndex, value, this.answers[questionId]); // Debug
            this.saveAnswer();
        },

        populateMatchingDropdown(selectElement, questionId, pairIndex, pairs) {
            // Get shuffled options
            const shuffled = this.getShuffledRightOptions(pairs);

            // Add options to select element
            shuffled.forEach(option => {
                const opt = document.createElement('option');
                opt.value = option;
                opt.textContent = option;
                selectElement.appendChild(opt);
            });

            // Set the saved value
            const savedValue = this.answers[questionId]?.[pairIndex] || '';
            selectElement.value = savedValue;

            console.log('Dropdown populated - Q:', questionId, 'Pair:', pairIndex, 'Set value to:', savedValue, 'Actual value:', selectElement.value);
        },

        getShuffledRightOptions(pairs) {
            // Create a unique key for this set of pairs
            const pairsKey = JSON.stringify(pairs);

            // Return cached shuffle if it exists
            if (this.shuffledOptions[pairsKey]) {
                console.log('Returning cached shuffle:', this.shuffledOptions[pairsKey]); // Debug
                return this.shuffledOptions[pairsKey];
            }

            // Extract right options and shuffle them
            const rightOptions = pairs.map(pair => pair.right);
            console.log('Original right options:', rightOptions); // Debug
            // Simple shuffle (Fisher-Yates)
            const shuffled = [...rightOptions];
            for (let i = shuffled.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [shuffled[i], shuffled[j]] = [shuffled[j], shuffled[i]];
            }

            console.log('Shuffled options:', shuffled); // Debug
            // Cache the result
            this.shuffledOptions[pairsKey] = shuffled;
            return shuffled;
        },        async saveAnswer() {
            if (!this.currentQuestion || !this.answers[this.currentQuestion.id]) return;

            this.isSaving = true;

            // Prepare answer based on question type
            let answerToSave = this.answers[this.currentQuestion.id];

            // For matching questions, convert text values to indices before saving
            // Save format: {"0":"0","1":"1","2":"2"} where both key and value are indices
            if (this.currentQuestion.type === 'matching' && typeof answerToSave === 'object') {
                const indexBasedAnswer = {};
                for (const [leftIndex, rightText] of Object.entries(answerToSave)) {
                    // Find the index of the right text in pairs
                    const rightIndex = this.currentQuestion.pairs.findIndex(pair => pair.right === rightText);
                    if (rightIndex !== -1) {
                        indexBasedAnswer[leftIndex] = rightIndex.toString();
                    }
                }
                answerToSave = JSON.stringify(indexBasedAnswer);
            }

            console.log('Saving answer:', {
                item_id: this.currentQuestion.id,
                answer: answerToSave
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
                        answer: answerToSave
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
