@extends('student.shell')

@section('student-content')

<div class="min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-md">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                    <p class="text-green-700 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('student.exams.index') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Back to Exams
            </a>
        </div>

        <!-- Results Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl shadow-lg p-8 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">{{ $exam->title }}</h1>
                    <p class="text-indigo-100">Exam completed on {{ $takenExam->submitted_at->format('M d, Y \a\t h:i A') }}</p>
                </div>
                <div class="text-center">
                    <div class="bg-white bg-opacity-20 rounded-lg p-4">
                        <p class="text-sm text-indigo-100 mb-1">Your Score</p>
                        <p class="text-4xl font-bold">{{ $takenExam->total_points }}/{{ $exam->total_points }}</p>
                        <p class="text-xl font-semibold mt-1">{{ number_format($percentage, 2) }}%</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Questions</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalQuestions }}</p>
                    </div>
                    <div class="bg-blue-100 w-12 h-12 rounded-full flex items-center justify-center">
                        <i class="fas fa-question-circle text-xl text-blue-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Answered</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $answeredQuestions }}</p>
                    </div>
                    <div class="bg-purple-100 w-12 h-12 rounded-full flex items-center justify-center">
                        <i class="fas fa-edit text-xl text-purple-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Correct</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $correctAnswers }}</p>
                    </div>
                    <div class="bg-green-100 w-12 h-12 rounded-full flex items-center justify-center">
                        <i class="fas fa-check-circle text-xl text-green-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Score</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($percentage, 0) }}%</p>
                    </div>
                    <div class="bg-indigo-100 w-12 h-12 rounded-full flex items-center justify-center">
                        <i class="fas fa-star text-xl text-indigo-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Answers -->
        <div class="bg-white rounded-xl shadow-md p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">
                <i class="fas fa-list-alt text-indigo-600 mr-2"></i>Detailed Results
            </h2>

            <div class="space-y-6">
                @foreach($exam->items as $index => $item)
                    @php
                        $answer = $takenExam->answers->where('exam_item_id', $item->id)->first();
                        $isCorrect = $answer && $answer->points_earned > 0;
                    @endphp

                    <div class="border-2 rounded-lg p-6 {{ $isCorrect ? 'border-green-200 bg-green-50' : ($answer ? 'border-red-200 bg-red-50' : 'border-gray-200') }}">
                        <!-- Question Header -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <span class="px-3 py-1 {{ $isCorrect ? 'bg-green-100 text-green-700' : ($answer ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700') }} text-sm font-semibold rounded-full">
                                        Question {{ $index + 1 }}
                                    </span>
                                    @if($item->type === 'mcq')
                                        <span class="px-3 py-1 bg-blue-100 text-blue-700 text-sm font-semibold rounded-full">
                                            Multiple Choice
                                        </span>
                                    @elseif($item->type === 'truefalse')
                                        <span class="px-3 py-1 bg-green-100 text-green-700 text-sm font-semibold rounded-full">
                                            True/False
                                        </span>
                                    @elseif($item->type === 'shortanswer')
                                        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-sm font-semibold rounded-full">
                                            Short Answer
                                        </span>
                                    @elseif($item->type === 'essay')
                                        <span class="px-3 py-1 bg-purple-100 text-purple-700 text-sm font-semibold rounded-full">
                                            Essay
                                        </span>
                                    @endif
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $item->question }}</h3>
                            </div>
                            <div class="text-right ml-4">
                                <div class="text-2xl font-bold {{ $isCorrect ? 'text-green-600' : ($answer ? 'text-red-600' : 'text-gray-600') }}">
                                    {{ $answer ? $answer->points_earned : 0 }}/{{ $item->points }}
                                </div>
                                @if($isCorrect)
                                    <i class="fas fa-check-circle text-green-600 text-xl mt-1"></i>
                                @elseif($answer)
                                    <i class="fas fa-times-circle text-red-600 text-xl mt-1"></i>
                                @else
                                    <i class="fas fa-minus-circle text-gray-600 text-xl mt-1"></i>
                                @endif
                            </div>
                        </div>

                        <!-- Your Answer -->
                        @if($answer)
                            <div class="mb-3">
                                <p class="text-sm font-medium text-gray-700 mb-1">Your Answer:</p>
                                <div class="p-3 bg-white rounded-lg border">
                                    @if($item->type === 'mcq')
                                        <p class="text-gray-900">
                                            <strong>{{ chr(65 + (int)$answer->answer) }}.</strong>
                                            {{ is_array($item->options[(int)$answer->answer]) ? $item->options[(int)$answer->answer]['text'] : $item->options[(int)$answer->answer] }}
                                        </p>
                                    @elseif($item->type === 'truefalse')
                                        <p class="text-gray-900 font-semibold">
                                            <i class="fas fa-{{ $answer->answer === 'true' ? 'check' : 'times' }} mr-2"></i>
                                            {{ ucfirst($answer->answer) }}
                                        </p>
                                    @else
                                        <p class="text-gray-900">{{ $answer->answer }}</p>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="mb-3">
                                <p class="text-sm font-medium text-gray-700 mb-1">Your Answer:</p>
                                <div class="p-3 bg-gray-100 rounded-lg border border-gray-300">
                                    <p class="text-gray-500 italic">Not answered</p>
                                </div>
                            </div>
                        @endif

                        <!-- Correct Answer (for objective questions) -->
                        @if($item->type === 'mcq' || $item->type === 'truefalse' || $item->type === 'fillblank' || $item->type === 'fill_blank')
                            <div>
                                <p class="text-sm font-medium text-gray-700 mb-1">Correct Answer:</p>
                                <div class="p-3 bg-green-100 rounded-lg border border-green-300">
                                    @if($item->type === 'mcq')
                                        @php
                                            $correctIndex = collect($item->options)->search(fn($opt) => isset($opt['correct']) && $opt['correct']);
                                        @endphp
                                        <p class="text-green-800 font-semibold">
                                            <strong>{{ chr(65 + $correctIndex) }}.</strong>
                                            {{ is_array($item->options[$correctIndex]) ? $item->options[$correctIndex]['text'] : $item->options[$correctIndex] }}
                                        </p>
                                    @elseif($item->type === 'truefalse')
                                        <p class="text-green-800 font-semibold">
                                            <i class="fas fa-{{ $item->answer === 'true' ? 'check' : 'times' }} mr-2"></i>
                                            {{ ucfirst($item->answer) }}
                                        </p>
                                    @elseif($item->type === 'fillblank' || $item->type === 'fill_blank')
                                        <p class="text-green-800 font-semibold">{{ $item->expected_answer }}</p>
                                    @endif
                                </div>
                            </div>
                        @elseif($item->type === 'essay' || $item->type === 'shortanswer')
                            <div class="p-3 bg-blue-100 rounded-lg border border-blue-300">
                                <p class="text-blue-800 text-sm">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    This question requires manual grading by your teacher.
                                </p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-8 text-center">
            <a href="{{ route('student.exams.index') }}"
               class="inline-block px-8 py-3 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition duration-200">
                <i class="fas fa-home mr-2"></i>Back to Exams
            </a>
        </div>
    </div>
</div>

@endsection
