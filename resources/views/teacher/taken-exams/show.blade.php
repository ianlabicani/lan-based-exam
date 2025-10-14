@extends('teacher.shell')

@section('teacher-content')

<div class="min-h-screen bg-gray-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header with Navigation -->
        <div class="mb-6">
            <a href="{{ route('teacher.exams.show', $exam->id) }}?tab=takers" class="text-indigo-600 hover:text-indigo-800 font-medium mb-2 inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>Back to Exam Takers
            </a>
            <h1 class="text-3xl font-bold text-gray-900 mt-2">Student Results</h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Main Content -->
            <div class="lg:col-span-2">

                <!-- Student Info Card -->
                <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-gray-900">Student Information</h2>

                        <!-- Status Badge -->
                        @if($takenExam->status === 'graded')
                        <span class="px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-semibold">
                            <i class="fas fa-check-circle mr-2"></i>Graded
                        </span>
                        @elseif($takenExam->status === 'submitted')
                        <span class="px-4 py-2 bg-orange-100 text-orange-800 rounded-full text-sm font-semibold">
                            <i class="fas fa-clock mr-2"></i>Pending Grading
                        </span>
                        @else
                        <span class="px-4 py-2 bg-yellow-100 text-yellow-800 rounded-full text-sm font-semibold">
                            <i class="fas fa-hourglass-half mr-2"></i>In Progress
                        </span>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Name</p>
                            <p class="font-semibold text-gray-900">{{ $takenExam->user->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Email</p>
                            <p class="font-semibold text-gray-900">{{ $takenExam->user->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Year & Section</p>
                            <p class="font-semibold text-gray-900">
                                @if($takenExam->user->year && $takenExam->user->section)
                                    {{ $takenExam->user->year }}-{{ strtoupper($takenExam->user->section) }}
                                @else
                                    <span class="text-gray-400">Not specified</span>
                                @endif
                            </p>
                        </div>
                        @if($takenExam->submitted_at)
                        <div>
                            <p class="text-sm text-gray-600">Submitted At</p>
                            <p class="font-semibold text-gray-900">{{ $takenExam->submitted_at->format('M d, Y h:i A') }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Exam Title -->
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl shadow-md p-6 mb-6">
                    <h2 class="text-2xl font-bold mb-2">{{ $exam->title }}</h2>
                    <p class="text-indigo-100">{{ $exam->description }}</p>
                </div>

                <!-- Questions and Answers -->
                <div class="space-y-6">
                    @foreach($comparison as $item)
                    <div class="bg-white rounded-xl shadow-md p-6">

                        <!-- Question Header -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-full uppercase mr-3">
                                        {{ str_replace('_', ' ', $item['type']) }}
                                    </span>
                                    <span class="px-3 py-1 bg-gray-100 text-gray-700 text-sm font-semibold rounded-full">
                                        <i class="fas fa-star text-yellow-500 mr-1"></i>{{ $item['points'] }} pts
                                    </span>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900">{{ $item['question'] }}</h3>
                            </div>

                            <!-- Score Badge -->
                            <div class="ml-4">
                                @if($item['is_correct'] === true)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i>Correct
                                </span>
                                @elseif($item['is_correct'] === false)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times mr-1"></i>Incorrect
                                </span>
                                @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <i class="fas fa-pen mr-1"></i>Manual
                                </span>
                                @endif
                            </div>
                        </div>

                        <!-- Student Answer -->
                        <div class="mb-4 p-4 rounded-lg border {{ $item['is_correct'] === true ? 'bg-green-50 border-green-200' : ($item['is_correct'] === false ? 'bg-red-50 border-red-200' : 'bg-gray-50 border-gray-200') }}">
                            <p class="text-sm font-semibold text-gray-700 mb-2">Student's Answer:</p>

                            @if($item['type'] === 'mcq' && $item['options'])
                                @php
                                    $selectedOption = $item['student_answer'];
                                    $options = $item['options'];
                                @endphp
                                <p class="text-gray-900">
                                    @if($selectedOption !== null && isset($options[$selectedOption]))
                                        {{ is_array($options[$selectedOption]) ? $options[$selectedOption]['text'] : $options[$selectedOption] }}
                                    @else
                                        <span class="text-gray-400 italic">No answer provided</span>
                                    @endif
                                </p>
                            @elseif(in_array($item['type'], ['essay', 'shortanswer']))
                                <div class="prose max-w-none">
                                    <p class="text-gray-900 whitespace-pre-wrap">{{ $item['student_answer'] ?? 'No answer provided' }}</p>
                                </div>
                            @elseif($item['type'] === 'matching' && is_array($item['student_answer']))
                                <div class="space-y-2">
                                    @foreach($item['student_answer'] as $pair)
                                        <div class="flex items-center text-gray-900">
                                            <span class="font-medium">{{ $pair['left'] ?? $pair[0] ?? '' }}</span>
                                            <i class="fas fa-arrow-right mx-2 text-gray-500"></i>
                                            <span>{{ $pair['right'] ?? $pair[1] ?? '' }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @elseif(is_array($item['student_answer']))
                                <p class="text-gray-900">{{ json_encode($item['student_answer']) }}</p>
                            @else
                                <p class="text-gray-900">{{ $item['student_answer'] ?? 'No answer' }}</p>
                            @endif
                        </div>

                        <!-- Correct Answer (for auto-graded items) -->
                        @if($item['correct_answer'] && !in_array($item['type'], ['essay']))
                        <div class="mb-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <p class="text-sm font-semibold text-blue-700 mb-2">Correct Answer:</p>

                            @if($item['type'] === 'mcq' && $item['options'])
                                @php
                                    $correctIndex = $item['correct_answer'];
                                    $options = $item['options'];
                                @endphp
                                <p class="text-blue-900">
                                    {{ is_array($options[$correctIndex]) ? $options[$correctIndex]['text'] : $options[$correctIndex] }}
                                </p>
                            @elseif($item['type'] === 'matching' && is_array($item['correct_answer']))
                                <div class="space-y-2">
                                    @foreach($item['correct_answer'] as $pair)
                                        <div class="flex items-center text-blue-900">
                                            <span class="font-medium">{{ $pair['left'] ?? $pair[0] ?? '' }}</span>
                                            <i class="fas fa-arrow-right mx-2 text-blue-500"></i>
                                            <span>{{ $pair['right'] ?? $pair[1] ?? '' }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @elseif(is_array($item['correct_answer']))
                                <p class="text-blue-900">{{ json_encode($item['correct_answer']) }}</p>
                            @else
                                <p class="text-blue-900">{{ $item['correct_answer'] }}</p>
                            @endif
                        </div>
                        @endif

                        <!-- Teacher Feedback (if available) -->
                        @php
                            $answer = $takenExam->answers->firstWhere('exam_item_id', $item['exam_item_id']);
                        @endphp

                        @if($answer && ($answer->points_earned !== null || $answer->feedback))
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <div class="p-4 bg-indigo-50 rounded-lg border border-indigo-200">
                                @if($answer->feedback)
                                <div class="mb-2">
                                    <p class="text-sm font-semibold text-indigo-700 mb-1">Teacher Feedback:</p>
                                    <p class="text-indigo-900 whitespace-pre-wrap">{{ $answer->feedback }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Score Display -->
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold text-gray-700">Points Earned:</span>
                                <span class="text-lg font-bold {{ $item['points_earned'] === $item['points'] ? 'text-green-600' : 'text-orange-600' }}">
                                    {{ $item['points_earned'] }} / {{ $item['points'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach

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

            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-md p-6 sticky top-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Score Summary</h3>

                    <!-- Total Score Card -->
                    <div class="p-4 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg text-white mb-6">
                        <p class="text-sm opacity-90 mb-1">Total Score</p>
                        <p class="text-4xl font-bold">{{ $takenExam->total_points ?? 0 }}<span class="text-xl opacity-75">/ {{ $exam->total_points }}</span></p>
                        @php
                            $percentage = $exam->total_points > 0 ? ($takenExam->total_points / $exam->total_points) * 100 : 0;
                        @endphp
                        <p class="text-lg mt-2">{{ number_format($percentage, 1) }}%</p>
                    </div>

                    <!-- Statistics -->
                    <div class="space-y-3 mb-6">
                        @php
                            $totalQuestions = $comparison->count();
                            $answeredQuestions = $comparison->where('answered', true)->count();
                            $correctAnswers = $comparison->where('is_correct', true)->count();
                        @endphp

                        <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                            <span class="text-sm text-blue-700">Total Questions</span>
                            <span class="font-bold text-blue-900">{{ $totalQuestions }}</span>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                            <span class="text-sm text-purple-700">Answered</span>
                            <span class="font-bold text-purple-900">{{ $answeredQuestions }}</span>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                            <span class="text-sm text-green-700">Correct</span>
                            <span class="font-bold text-green-900">{{ $correctAnswers }}</span>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="space-y-3">
                        @if($takenExam->status !== 'graded')
                        <a href="{{ route('teacher.grading.show', $takenExam->id) }}"
                           class="block w-full text-center px-4 py-3 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-edit mr-2"></i>Grade Submission
                        </a>
                        @else
                        <a href="{{ route('teacher.grading.show', $takenExam->id) }}"
                           class="block w-full text-center px-4 py-3 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-edit mr-2"></i>Edit Grades
                        </a>
                        @endif

                        <a href="{{ route('teacher.exams.show', $exam->id) }}?tab=takers"
                           class="block w-full text-center px-4 py-3 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition duration-200">
                            <i class="fas fa-users mr-2"></i>View All Takers
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
