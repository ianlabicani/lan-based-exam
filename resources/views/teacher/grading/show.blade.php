@extends('teacher.shell')

@section('teacher-content')

<div class="min-h-screen bg-gray-100 py-8"
     x-data="gradingPanel()"
     x-init="init()">

    <!-- Success Modal -->
    <div x-show="modal.show && modal.type === 'success'"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title"
         role="dialog"
         aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="modal.show"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                 aria-hidden="true"
                 @click="closeModal()"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="modal.show"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full sm:p-6">
                <div>
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                        <i class="fas fa-check text-green-600 text-xl"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-5">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title" x-text="modal.title">
                            Success
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500" x-text="modal.message">
                                Operation completed successfully
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-6">
                    <button type="button"
                            @click="closeModal()"
                            class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div x-show="modal.show && modal.type === 'error'"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title"
         role="dialog"
         aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="modal.show"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                 aria-hidden="true"
                 @click="closeModal()"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="modal.show"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full sm:p-6">
                <div>
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                        <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-5">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title" x-text="modal.title">
                            Error
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500" x-text="modal.message">
                                An error occurred
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-6">
                    <button type="button"
                            @click="closeModal()"
                            class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirm Modal -->
    <div x-show="modal.show && modal.type === 'confirm'"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title"
         role="dialog"
         aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="modal.show"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                 aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="modal.show"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-exclamation text-yellow-600 text-xl"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title" x-text="modal.title">
                            Confirm Action
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500" x-text="modal.message">
                                Are you sure you want to proceed?
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                    <button type="button"
                            @click="modal.onConfirm(); closeModal()"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Confirm
                    </button>
                    <button type="button"
                            @click="closeModal()"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header with Navigation -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <a href="{{ route('teacher.grading.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium mb-2 inline-flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Pending Grading
                </a>
                <h1 class="text-3xl font-bold text-gray-900 mt-2">Grade Submission</h1>
            </div>

            <!-- Status Badge -->
            <div>
                @if($takenExam->status === 'submitted')
                <span class="px-4 py-2 bg-yellow-100 text-yellow-800 rounded-full text-sm font-semibold">
                    <i class="fas fa-clock mr-2"></i>Pending Grading
                </span>
                @elseif($takenExam->status === 'graded')
                <span class="px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-semibold">
                    <i class="fas fa-check-circle mr-2"></i>Graded
                </span>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Main Content -->
            <div class="lg:col-span-2">

                <!-- Student Info Card -->
                <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Student Information</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Name</p>
                            <p class="font-semibold text-gray-900">{{ $student->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Email</p>
                            <p class="font-semibold text-gray-900">{{ $student->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Year & Section</p>
                            <p class="font-semibold text-gray-900">{{ $student->year }}-{{ strtoupper($student->section) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Submitted At</p>
                            <p class="font-semibold text-gray-900">{{ $takenExam->submitted_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Exam Title -->
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl shadow-md p-6 mb-6">
                    <h2 class="text-2xl font-bold mb-2">{{ $exam->title }}</h2>
                    <p class="text-indigo-100">{{ $exam->description }}</p>
                </div>

                <!-- Questions List -->
                <div class="space-y-6">
                    @foreach($takenExam->answers as $answer)
                    @php
                        $item = $answer->item;
                        $needsManualGrading = in_array($item->type, ['essay', 'shortanswer']);
                    @endphp

                    <div class="bg-white rounded-xl shadow-md p-6"
                         :class="gradedItems[{{ $item->id }}] ? 'border-2 border-green-300' : ''"
                         x-data="{ itemId: {{ $item->id }} }">

                        <!-- Question Header -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-full uppercase mr-3">
                                        {{ str_replace('_', ' ', $item->type) }}
                                    </span>
                                    <span class="px-3 py-1 bg-gray-100 text-gray-700 text-sm font-semibold rounded-full">
                                        <i class="fas fa-star text-yellow-500 mr-1"></i>{{ $item->points }} pts
                                    </span>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900">{{ $item->question }}</h3>
                            </div>

                            <!-- Grading Status Icon -->
                            @if($needsManualGrading)
                            <div x-show="!gradedItems[itemId]" class="ml-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                    <i class="fas fa-pen mr-1"></i>Needs Grading
                                </span>
                            </div>
                            <div x-show="gradedItems[itemId]" class="ml-4" x-cloak>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i>Graded
                                </span>
                            </div>
                            @else
                            <div class="ml-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-robot mr-1"></i>Auto-Graded
                                </span>
                            </div>
                            @endif
                        </div>

                        <!-- Student Answer -->
                        <div class="mb-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <p class="text-sm font-semibold text-gray-700 mb-2">Student's Answer:</p>

                            @if($item->type === 'mcq')
                                @php
                                    $selectedOption = $answer->answer;
                                    $options = $item->options;
                                @endphp
                                <p class="text-gray-900">{{ $options[$selectedOption]['text'] ?? $options[$selectedOption] ?? 'No answer' }}</p>
                            @elseif(in_array($item->type, ['essay', 'shortanswer']))
                                <div class="prose max-w-none">
                                    <p class="text-gray-900 whitespace-pre-wrap">{{ $answer->answer ?? 'No answer provided' }}</p>
                                </div>
                            @else
                                <p class="text-gray-900">{{ $answer->answer ?? 'No answer' }}</p>
                            @endif
                        </div>

                        <!-- Expected Answer (if applicable) -->
                        @if($item->expected_answer && in_array($item->type, ['fillblank', 'fill_blank', 'shortanswer']))
                        <div class="mb-4 p-4 bg-green-50 rounded-lg border border-green-200">
                            <p class="text-sm font-semibold text-green-700 mb-2">Expected Answer:</p>
                            <p class="text-green-900">{{ $item->expected_answer }}</p>
                        </div>
                        @endif

                        <!-- Grading Section (for manual grading items) -->
                        @if($needsManualGrading)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Score Input -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Score (0 - {{ $item->points }})
                                    </label>
                                    <input type="number"
                                           x-model="scores[itemId]"
                                           @input="markAsModified(itemId)"
                                           @change="markAsModified(itemId)"
                                           min="0"
                                           max="{{ $item->points }}"
                                           step="0.5"
                                           class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                           placeholder="Enter score">
                                </div>

                                <!-- Save Button -->
                                <div class="flex items-end">
                                    <button type="button"
                                            @click.prevent="saveScore(itemId, {{ $item->points }})"
                                            x-show="modifiedItems[itemId] || !gradedItems[itemId]"
                                            :disabled="savingItems[itemId]"
                                            class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                                        <span x-show="!savingItems[itemId]">
                                            <i class="fas fa-save mr-2"></i>
                                            <span x-text="gradedItems[itemId] ? 'Update Score' : 'Save Score'">Save Score</span>
                                        </span>
                                        <span x-show="savingItems[itemId]" x-cloak>
                                            <i class="fas fa-spinner fa-spin mr-2"></i>Saving...
                                        </span>
                                    </button>
                                </div>
                            </div>

                            <!-- Feedback -->
                            <div class="mt-4">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Feedback (Optional)
                                </label>
                                <textarea x-model="feedbacks[itemId]"
                                          @input="markAsModified(itemId)"
                                          @change="markAsModified(itemId)"
                                          rows="3"
                                          class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                          placeholder="Provide feedback to the student..."></textarea>
                            </div>

                            <!-- Already Graded Indicator -->
                            <div x-show="gradedItems[itemId] && !modifiedItems[itemId]" class="mt-3 p-3 bg-green-50 border border-green-200 rounded-lg" x-cloak>
                                <p class="text-sm text-green-700">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    This item has been graded. You can modify the score and feedback above if needed.
                                </p>
                            </div>

                            <!-- Current Score Display for Manual Items -->
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-semibold text-gray-700">Points Earned:</span>
                                    <template x-if="scores[itemId] !== null && scores[itemId] !== undefined">
                                        <span class="text-lg font-bold"
                                              :class="parseFloat(scores[itemId]) === {{ $item->points }} ? 'text-green-600' : 'text-orange-600'">
                                            <span x-text="scores[itemId] + ' / {{ $item->points }}'"></span>
                                        </span>
                                    </template>
                                    <template x-if="scores[itemId] === null || scores[itemId] === undefined">
                                        <span class="text-lg font-bold text-gray-400">
                                            Not graded yet
                                        </span>
                                    </template>
                                </div>
                            </div>
                        </div>
                        @else
                        <!-- Auto-Graded Score Display -->
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold text-gray-700">Score:</span>
                                <span class="text-lg font-bold" :class="{{ $answer->points_earned }} === {{ $item->points }} ? 'text-green-600' : 'text-red-600'">
                                    {{ $answer->points_earned }} / {{ $item->points }}
                                </span>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>

                <!-- Activity Logs Section -->
                <div class="mt-6">
                    @include('partials.activity-logs', [
                        'activityLogs' => $activityLogs,
                        'takenExam' => $takenExam,
                        'title' => 'Student Activity Log',
                        'showStudentInfo' => true
                    ])
                </div>


            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-md p-6 sticky top-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Grading Summary</h3>

                    <!-- Statistics -->
                    <div class="space-y-4 mb-6">
                        <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                            <span class="text-sm text-blue-700">Total Items</span>
                            <span class="font-bold text-blue-900">{{ $totalItems }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                            <span class="text-sm text-green-700">Auto-Graded</span>
                            <span class="font-bold text-green-900">{{ $autoGradedItems }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-orange-50 rounded-lg">
                            <span class="text-sm text-orange-700">Needs Grading</span>
                            <span class="font-bold text-orange-900" x-text="pendingCount">{{ $pendingGradingItems }}</span>
                        </div>
                    </div>

                    <!-- Score Breakdown -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h4 class="font-semibold text-gray-900 mb-3">Score Breakdown</h4>
                        <div class="space-y-2">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Auto-Graded:</span>
                                <span class="font-semibold text-gray-900">{{ $autoGradedScore }} pts</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Manual:</span>
                                <span class="font-semibold text-gray-900" x-text="`${manualScore} pts`">{{ $manualGradedScore }} pts</span>
                            </div>
                            <div class="pt-2 border-t border-gray-300 flex items-center justify-between">
                                <span class="font-bold text-gray-900">Total Score:</span>
                                <span class="text-xl font-bold text-indigo-600" x-text="`${totalScore} / ${maxScore}`">
                                    {{ $takenExam->total_points }} / {{ $exam->total_points }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Finalize Button -->
                    <button @click="finalizeGrade()"
                            :disabled="pendingCount > 0 || isSubmitting"
                            class="w-full px-6 py-3 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed shadow-md hover:shadow-lg">
                        <span x-show="!isSubmitting">
                            <i class="fas fa-check-double mr-2"></i>
                            <span x-text="isFinalized ? 'Update & Re-Finalize' : 'Finalize Grades'">Finalize Grades</span>
                        </span>
                        <span x-show="isSubmitting">
                            <i class="fas fa-spinner fa-spin mr-2"></i>Finalizing...
                        </span>
                    </button>

                    <p class="mt-3 text-xs text-gray-500 text-center" x-show="pendingCount > 0">
                        Grade all items before finalizing
                    </p>

                    <p class="mt-3 text-xs text-indigo-600 text-center font-medium" x-show="isFinalized && pendingCount === 0" x-cloak>
                        <i class="fas fa-info-circle mr-1"></i>
                        You can still edit scores and re-finalize
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
function gradingPanel() {
    return {
        takenExamId: {{ $takenExam->id }},
        scores: @json($takenExam->answers->pluck('points_earned', 'exam_item_id')),
        feedbacks: @json($takenExam->answers->pluck('feedback', 'exam_item_id')),
        gradedItems: @json($gradedItems),
        modifiedItems: {},
        savingItems: {},
        isSubmitting: false,
        isFinalized: {{ $takenExam->status === 'graded' ? 'true' : 'false' }},

        // Modal state
        modal: {
            show: false,
            type: 'success', // success, error, confirm
            title: '',
            message: '',
            onConfirm: null
        },

        // Scores
        autoGradedScore: {{ $autoGradedScore }},
        manualScore: {{ $manualGradedScore }},
        maxScore: {{ $exam->total_points }},

        init() {
            console.log('Grading panel initialized');
            console.log('Scores:', this.scores);
            console.log('Feedbacks:', this.feedbacks);
            console.log('Graded Items:', this.gradedItems);
        },

        get totalScore() {
            return this.autoGradedScore + this.manualScore;
        },

        get pendingCount() {
            return {{ $pendingGradingItems }} - Object.keys(this.gradedItems).length;
        },

        markAsModified(itemId) {
            this.modifiedItems[itemId] = true;
        },

        showModal(type, title, message, onConfirm = null) {
            this.modal = {
                show: true,
                type: type,
                title: title,
                message: message,
                onConfirm: onConfirm
            };
        },

        closeModal() {
            this.modal.show = false;
            // Reset modal after animation
            setTimeout(() => {
                this.modal = {
                    show: false,
                    type: 'success',
                    title: '',
                    message: '',
                    onConfirm: null
                };
            }, 300);
        },

        async saveScore(itemId, maxPoints) {
            const score = parseFloat(this.scores[itemId]);

            if (isNaN(score) || score < 0 || score > maxPoints) {
                this.showModal('error', 'Invalid Score', `Please enter a valid score between 0 and ${maxPoints}`);
                return;
            }

            this.savingItems[itemId] = true;

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (!csrfToken) {
                    this.showModal('error', 'Session Expired', 'Your session has expired. Please refresh the page and try again.');
                    this.savingItems[itemId] = false;
                    return;
                }

                const response = await fetch(`/teacher/grading/taken-exams/${this.takenExamId}/items/${itemId}/score`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken.content
                    },
                    body: JSON.stringify({
                        teacher_score: score,
                        feedback: this.feedbacks[itemId] || null
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // Update graded status
                    const previousScore = this.gradedItems[itemId] ?
                        (parseFloat(this.scores[itemId]) || 0) : 0;

                    this.gradedItems[itemId] = true;

                    // Update manual score (subtract old score, add new score)
                    this.manualScore = this.manualScore - previousScore + score;

                    // Update the scores object AFTER calculating the difference
                    this.scores[itemId] = score;
                    this.modifiedItems[itemId] = false;

                    // Show success modal
                    this.showModal('success', 'Score Saved', `Score of ${score} points has been saved successfully!`);
                } else {
                    this.showModal('error', 'Failed to Save', data.message || 'An error occurred while saving the score. Please try again.');
                }
            } catch (error) {
                console.error('Error saving score:', error);
                this.showModal('error', 'Network Error', 'An error occurred while saving. Please check your connection and try again.');
            } finally {
                this.savingItems[itemId] = false;
            }
        },

        async finalizeGrade() {
            if (this.pendingCount > 0) {
                this.showModal('error', 'Cannot Finalize', 'Please grade all items before finalizing the submission.');
                return;
            }

            // Show confirmation modal
            const confirmMessage = this.isFinalized
                ? 'Are you sure you want to update and re-finalize these grades? The student will see the updated results.'
                : 'Are you sure you want to finalize these grades? This will mark the submission as graded and the student will be able to view their results.';

            this.showModal(
                'confirm',
                this.isFinalized ? 'Update Grades' : 'Finalize Grades',
                confirmMessage,
                async () => {
                    await this.performFinalize();
                }
            );
        },

        async performFinalize() {
            this.isSubmitting = true;

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (!csrfToken) {
                    this.showModal('error', 'Session Expired', 'Your session has expired. Please refresh the page and try again.');
                    this.isSubmitting = false;
                    return;
                }

                const response = await fetch(`/teacher/grading/taken-exams/${this.takenExamId}/finalize`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken.content
                    }
                });

                const data = await response.json();

                if (data.success) {
                    this.isFinalized = true;
                    const message = this.isFinalized
                        ? 'Grades have been updated and re-finalized successfully!'
                        : 'Grades have been finalized successfully!';

                    this.showModal('success', 'Success', message + ' Redirecting...');

                    // Redirect after showing success message
                    setTimeout(() => {
                        window.location.href = '/teacher/grading';
                    }, 2000);
                } else {
                    this.showModal('error', 'Finalization Failed', data.message || 'Failed to finalize grades. Please try again.');
                }
            } catch (error) {
                console.error('Error finalizing grades:', error);
                this.showModal('error', 'Network Error', 'An error occurred while finalizing. Please check your connection and try again.');
            } finally {
                this.isSubmitting = false;
            }
        }
    }
}
</script>


<style>
[x-cloak] { display: none !important; }
</style>

@endsection
