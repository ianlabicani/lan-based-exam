@extends('student.shell')

@section('student-content')

<div class="min-h-screen bg-gray-100 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header Section -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <!-- Title & Info -->
                <div class="flex-1">
                    <a href="{{ route('student.taken-exams.index') }}"
                       class="inline-flex items-center text-indigo-600 hover:text-indigo-800 mb-3 font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Exam History
                    </a>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $exam->title }}</h1>
                    <p class="text-gray-600">{{ $exam->description }}</p>
                </div>

                <!-- Status Badge -->
                <div class="text-center bg-green-50 px-6 py-4 rounded-lg border-2 border-green-200">
                    <div class="flex items-center justify-center mb-2">
                        <i class="fas fa-check-circle text-green-600 text-3xl mr-2"></i>
                        <span class="text-xl font-bold text-green-700">Submitted</span>
                    </div>
                    <p class="text-sm text-gray-600">
                        {{ \Carbon\Carbon::parse($takenExam->submitted_at)->format('M d, Y h:i A') }}
                    </p>
                </div>
            </div>

            <!-- Score Summary -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Total Score -->
                <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 p-4 rounded-lg border border-indigo-200">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-star text-indigo-600 text-lg mr-2"></i>
                        <h3 class="text-sm font-medium text-gray-700">Your Score</h3>
                    </div>
                    <p class="text-3xl font-bold text-indigo-700">
                        {{ $takenExam->total_points }}<span class="text-lg text-gray-600">/ {{ $exam->total_points }}</span>
                    </p>
                </div>

                <!-- Percentage -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-lg border border-green-200">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-percent text-green-600 text-lg mr-2"></i>
                        <h3 class="text-sm font-medium text-gray-700">Percentage</h3>
                    </div>
                    <p class="text-3xl font-bold text-green-700">{{ $percentage }}%</p>
                </div>

                <!-- Questions Answered -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-lg border border-blue-200">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-question-circle text-blue-600 text-lg mr-2"></i>
                        <h3 class="text-sm font-medium text-gray-700">Questions</h3>
                    </div>
                    <p class="text-3xl font-bold text-blue-700">
                        {{ $answeredQuestions }}<span class="text-lg text-gray-600">/ {{ $totalQuestions }}</span>
                    </p>
                </div>

                <!-- Correct Answers -->
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-4 rounded-lg border border-purple-200">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-check-double text-purple-600 text-lg mr-2"></i>
                        <h3 class="text-sm font-medium text-gray-700">Correct</h3>
                    </div>
                    <p class="text-3xl font-bold text-purple-700">
                        {{ $correctAnswers }}<span class="text-lg text-gray-600">/ {{ $answeredQuestions }}</span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Questions & Answers Review -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">
                <i class="fas fa-file-alt text-indigo-600 mr-2"></i>Detailed Review
            </h2>

            <div class="space-y-6">
                @foreach($exam->items as $index => $item)
                    @php
                        $answer = $takenExam->answers->firstWhere('exam_item_id', $item->id);
                        $isManualGrading = in_array($item->type, ['essay', 'shortanswer']);

                        // Get points earned
                        $pointsEarned = $answer ? ($answer->points_earned ?? 0) : 0;
                        $isCorrect = $pointsEarned > 0;
                        $isPending = $isManualGrading && $answer && $answer->points_earned === null;
                    @endphp

                    <div class="border-2 rounded-lg p-6
                                {{ $isCorrect ? 'border-green-200 bg-green-50' : ($answer && !$isManualGrading ? 'border-red-200 bg-red-50' : 'border-gray-200 bg-white') }}">

                        <!-- Question Header -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-sm font-semibold rounded-full">
                                        Question {{ $index + 1 }}
                                    </span>
                                    <span class="px-3 py-1 bg-gray-100 text-gray-700 text-sm font-semibold rounded-full">
                                        {{ $item->points }} pts
                                    </span>
                                    @if($item->type === 'mcq')
                                        <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-full">MCQ</span>
                                    @elseif($item->type === 'truefalse')
                                        <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">True/False</span>
                                    @elseif($item->type === 'fillblank' || $item->type === 'fill_blank')
                                        <span class="px-2 py-1 bg-orange-100 text-orange-700 text-xs rounded-full">Fill Blank</span>
                                    @elseif($item->type === 'shortanswer')
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs rounded-full">Short Answer</span>
                                    @elseif($item->type === 'essay')
                                        <span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs rounded-full">Essay</span>
                                    @elseif($item->type === 'matching')
                                        <span class="px-2 py-1 bg-pink-100 text-pink-700 text-xs rounded-full">Matching</span>
                                    @endif
                                </div>
                                <h3 class="text-lg font-bold text-gray-900">{{ $item->question }}</h3>
                            </div>

                            <!-- Points Earned -->
                            <div class="ml-4 text-right">
                                @if($answer)
                                    <div class="px-4 py-2 rounded-lg font-bold
                                                {{ $isCorrect ? 'bg-green-600 text-white' : ($isPending ? 'bg-yellow-400 text-white' : 'bg-red-600 text-white') }}">
                                        @if($isPending)
                                            <span class="text-sm">Pending</span>
                                        @else
                                            <span class="text-xl">{{ $pointsEarned }}</span>
                                            <span class="text-sm">/ {{ $item->points }}</span>
                                        @endif
                                    </div>
                                    @if($isCorrect)
                                        <p class="text-xs text-green-700 mt-1 font-medium">
                                            <i class="fas fa-check-circle mr-1"></i>Correct
                                        </p>
                                    @elseif($isPending)
                                        <p class="text-xs text-gray-600 mt-1">
                                            <i class="fas fa-clock mr-1"></i>Manual Grading
                                        </p>
                                    @else
                                        <p class="text-xs text-red-700 mt-1 font-medium">
                                            <i class="fas fa-times-circle mr-1"></i>Incorrect
                                        </p>
                                    @endif
                                @else
                                    <div class="px-4 py-2 bg-gray-300 text-white rounded-lg font-bold">
                                        <span class="text-xl">0</span>
                                        <span class="text-sm">/ {{ $item->points }}</span>
                                    </div>
                                    <p class="text-xs text-gray-600 mt-1">
                                        <i class="fas fa-ban mr-1"></i>Not Answered
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Answer Display -->
                        <div class="mt-4">
                            @if($item->type === 'mcq')
                                <!-- Multiple Choice -->
                                <div class="space-y-2">
                                    @foreach($item->options as $optIndex => $option)
                                        @php
                                            $isStudentAnswer = $answer && $answer->answer == $optIndex;
                                            $isCorrectOption = isset($option['correct']) && $option['correct'];
                                        @endphp
                                        <div class="p-3 rounded-lg border-2
                                                    {{ $isCorrectOption ? 'bg-green-100 border-green-400' : ($isStudentAnswer ? 'bg-red-100 border-red-400' : 'bg-gray-50 border-gray-200') }}">
                                            <div class="flex items-center">
                                                @if($isCorrectOption)
                                                    <i class="fas fa-check-circle text-green-600 mr-2"></i>
                                                @elseif($isStudentAnswer)
                                                    <i class="fas fa-times-circle text-red-600 mr-2"></i>
                                                @else
                                                    <i class="fas fa-circle text-gray-400 mr-2 text-xs"></i>
                                                @endif
                                                <span class="font-semibold text-gray-700">{{ chr(65 + $optIndex) }}.</span>
                                                <span class="ml-2 text-gray-900">{{ is_array($option) ? $option['text'] : $option }}</span>
                                                @if($isStudentAnswer)
                                                    <span class="ml-auto px-2 py-1 bg-blue-600 text-white text-xs rounded-full font-semibold">Your Answer</span>
                                                @endif
                                                @if($isCorrectOption)
                                                    <span class="ml-auto px-2 py-1 bg-green-600 text-white text-xs rounded-full font-semibold">Correct Answer</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                            @elseif($item->type === 'truefalse')
                                <!-- True/False -->
                                <div class="space-y-2">
                                    @php
                                        $correctAnswer = strtolower($item->answer);
                                        $studentAnswer = $answer ? strtolower($answer->answer) : null;
                                    @endphp
                                    <div class="p-3 rounded-lg border-2 {{ $correctAnswer === 'true' ? 'bg-green-100 border-green-400' : ($studentAnswer === 'true' ? 'bg-red-100 border-red-400' : 'bg-gray-50 border-gray-200') }}">
                                        <div class="flex items-center">
                                            @if($correctAnswer === 'true')
                                                <i class="fas fa-check-circle text-green-600 mr-2"></i>
                                            @elseif($studentAnswer === 'true')
                                                <i class="fas fa-times-circle text-red-600 mr-2"></i>
                                            @else
                                                <i class="fas fa-circle text-gray-400 mr-2 text-xs"></i>
                                            @endif
                                            <span class="text-gray-900 font-medium">True</span>
                                            @if($studentAnswer === 'true')
                                                <span class="ml-auto px-2 py-1 bg-blue-600 text-white text-xs rounded-full font-semibold">Your Answer</span>
                                            @endif
                                            @if($correctAnswer === 'true')
                                                <span class="ml-auto px-2 py-1 bg-green-600 text-white text-xs rounded-full font-semibold">Correct Answer</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="p-3 rounded-lg border-2 {{ $correctAnswer === 'false' ? 'bg-green-100 border-green-400' : ($studentAnswer === 'false' ? 'bg-red-100 border-red-400' : 'bg-gray-50 border-gray-200') }}">
                                        <div class="flex items-center">
                                            @if($correctAnswer === 'false')
                                                <i class="fas fa-check-circle text-green-600 mr-2"></i>
                                            @elseif($studentAnswer === 'false')
                                                <i class="fas fa-times-circle text-red-600 mr-2"></i>
                                            @else
                                                <i class="fas fa-circle text-gray-400 mr-2 text-xs"></i>
                                            @endif
                                            <span class="text-gray-900 font-medium">False</span>
                                            @if($studentAnswer === 'false')
                                                <span class="ml-auto px-2 py-1 bg-blue-600 text-white text-xs rounded-full font-semibold">Your Answer</span>
                                            @endif
                                            @if($correctAnswer === 'false')
                                                <span class="ml-auto px-2 py-1 bg-green-600 text-white text-xs rounded-full font-semibold">Correct Answer</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            @elseif($item->type === 'matching')
                                <!-- Matching Type -->
                                @php
                                    $studentAnswer = $answer ? json_decode($answer->answer, true) : [];
                                    $pairs = $item->pairs ?? [];
                                @endphp
                                <div class="space-y-3">
                                    <p class="text-xs text-gray-600 font-semibold mb-3 uppercase">
                                        <i class="fas fa-link mr-1"></i>Match the items:
                                    </p>
                                    @foreach($pairs as $pairIndex => $pair)
                                        @php
                                            // Get student's answer for this pair (right-side index)
                                            $studentRightIndex = $studentAnswer[$pairIndex] ?? null;
                                            $studentRightText = isset($studentRightIndex) && isset($pairs[$studentRightIndex])
                                                ? $pairs[$studentRightIndex]['right']
                                                : null;

                                            // Correct answer is matching index (left index matches right index)
                                            $correctRightText = $pair['right'];
                                            $isCorrectMatch = $studentRightIndex !== null && $studentRightIndex == $pairIndex;
                                        @endphp

                                        <div class="p-4 rounded-lg border-2 {{ $isCorrectMatch ? 'bg-green-50 border-green-300' : 'bg-red-50 border-red-300' }}">
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 items-center">
                                                <!-- Left Item (Question) -->
                                                <div class="flex items-center">
                                                    <span class="font-bold text-indigo-600 mr-2">{{ $pairIndex + 1 }}.</span>
                                                    <span class="text-gray-900 font-medium">{{ $pair['left'] }}</span>
                                                </div>

                                                <!-- Arrow and Student Answer -->
                                                <div class="flex items-center justify-center space-x-2">
                                                    <i class="fas fa-arrow-right text-gray-400"></i>
                                                    @if($studentRightText)
                                                        <div class="px-3 py-1 {{ $isCorrectMatch ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }} rounded-lg text-sm font-medium">
                                                            {{ $studentRightText }}
                                                            @if($isCorrectMatch)
                                                                <i class="fas fa-check-circle ml-1"></i>
                                                            @else
                                                                <i class="fas fa-times-circle ml-1"></i>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <span class="text-gray-400 italic text-sm">Not answered</span>
                                                    @endif
                                                </div>

                                                <!-- Correct Answer -->
                                                <div class="flex items-center justify-end">
                                                    @if(!$isCorrectMatch)
                                                        <div class="text-sm">
                                                            <span class="text-gray-600">Correct: </span>
                                                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded font-medium">
                                                                {{ $correctRightText }}
                                                            </span>
                                                        </div>
                                                    @else
                                                        <span class="text-green-600 font-semibold text-sm">
                                                            <i class="fas fa-check-circle mr-1"></i>Correct!
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                            @elseif(in_array($item->type, ['fillblank', 'fill_blank', 'shortanswer', 'essay']))
                                <!-- Text-based Answers -->
                                <div class="space-y-3">
                                    <div class="bg-blue-50 p-4 rounded-lg border-2 border-blue-200">
                                        <p class="text-xs text-gray-600 font-semibold mb-2 uppercase">Your Answer:</p>
                                        <p class="text-gray-900 whitespace-pre-wrap">{{ $answer ? $answer->answer : 'Not answered' }}</p>
                                    </div>

                                    @if($item->expected_answer && !in_array($item->type, ['essay']))
                                        <div class="bg-green-50 p-4 rounded-lg border-2 border-green-200">
                                            <p class="text-xs text-gray-600 font-semibold mb-2 uppercase">Expected Answer:</p>
                                            <p class="text-gray-900">{{ $item->expected_answer }}</p>
                                        </div>
                                    @endif

                                    @if(in_array($item->type, ['essay', 'shortanswer']))
                                        @if($answer && $answer->points_earned !== null)
                                            <!-- Teacher Graded -->
                                            <div class="bg-indigo-50 p-4 rounded-lg border-2 border-indigo-200">
                                                <div class="flex items-center justify-between mb-2">
                                                    <p class="text-xs text-indigo-600 font-semibold uppercase">Teacher's Score:</p>
                                                    <span class="text-2xl font-bold text-indigo-700">
                                                        {{ $answer->points_earned }} / {{ $item->points }}
                                                    </span>
                                                </div>
                                                @if($answer->feedback)
                                                    <div class="mt-3 pt-3 border-t border-indigo-200">
                                                        <p class="text-xs text-indigo-600 font-semibold mb-2 uppercase">Teacher's Feedback:</p>
                                                        <p class="text-gray-900 whitespace-pre-wrap">{{ $answer->feedback }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <!-- Pending Grading -->
                                            <div class="bg-yellow-50 p-4 rounded-lg border-2 border-yellow-200">
                                                <p class="text-sm text-yellow-800">
                                                    <i class="fas fa-clock mr-2"></i>
                                                    This response is pending manual grading by your teacher.
                                                </p>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 flex justify-between">
            <a href="{{ route('student.exams.index') }}"
               class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition duration-200">
                <i class="fas fa-file-alt mr-2"></i>Browse More Exams
            </a>

            <a href="{{ route('student.taken-exams.index') }}"
               class="px-6 py-3 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition duration-200">
                <i class="fas fa-history mr-2"></i>View Exam History
            </a>
        </div>
    </div>
</div>

@endsection
