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
    // Pass runtime data to external JS
    window.__exam_data = {
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
        timeRemaining: @php
            $startsAt = \Carbon\Carbon::parse($exam->starts_at);
            $endsAt = \Carbon\Carbon::parse($exam->ends_at);
            $startedAt = \Carbon\Carbon::parse($takenExam->started_at);
            $now = now();

            $examDuration = $startsAt->diffInSeconds($endsAt);
            $elapsedTime = $startedAt->diffInSeconds($now);
            $timeFromStart = max(0, $examDuration - $elapsedTime);
            $timeUntilExamEnds = $now->diffInSeconds($endsAt, false);

            if ($timeUntilExamEnds < 0) {
                $timeRemaining = 0;
            } else {
                $timeRemaining = min($timeFromStart, $timeUntilExamEnds);
            }

            echo max(0, $timeRemaining);
        @endphp,
        totalTime: {{ \Carbon\Carbon::parse($exam->starts_at)->diffInSeconds(\Carbon\Carbon::parse($exam->ends_at)) }},
        activityLogging: true  // Enable activity monitoring
    };
</script>
<script src="/js/exam-interface.js"></script>

<style>
[x-cloak] { display: none !important; }
</style>

@endsection
