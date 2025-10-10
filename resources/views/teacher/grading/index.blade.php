@extends('teacher.shell')

@section('teacher-content')

<div class="min-h-screen bg-gray-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">
                <i class="fas fa-clipboard-check text-indigo-600 mr-3"></i>Pending Grading
            </h1>
            <p class="mt-2 text-gray-600">Review and grade student submissions requiring manual evaluation</p>
        </div>

        <!-- Pending Count Badge -->
        @if($pendingGrading->count() > 0)
        <div class="mb-6 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-r-lg">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-yellow-600 text-xl mr-3"></i>
                <div>
                    <p class="font-semibold text-yellow-800">{{ $pendingGrading->count() }} Submission(s) Awaiting Grading</p>
                    <p class="text-sm text-yellow-700">These exams contain essay or short-answer questions that need your review</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Submissions List -->
        @forelse($pendingGrading as $takenExam)
        <div class="bg-white rounded-xl shadow-md p-6 mb-4 hover:shadow-lg transition duration-200">
            <div class="flex items-start justify-between">
                <!-- Left Side: Student & Exam Info -->
                <div class="flex-1">
                    <div class="flex items-center mb-3">
                        <div class="h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center mr-4">
                            <i class="fas fa-user text-indigo-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">{{ $takenExam->user->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $takenExam->user->email }}</p>
                        </div>
                    </div>

                    <div class="ml-16">
                        <h4 class="font-semibold text-gray-800 mb-2">{{ $takenExam->exam->title }}</h4>

                        <div class="flex flex-wrap gap-3 text-sm text-gray-600">
                            <span class="flex items-center">
                                <i class="fas fa-calendar text-gray-400 mr-2"></i>
                                Submitted: {{ $takenExam->submitted_at->format('M d, Y h:i A') }}
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-clock text-gray-400 mr-2"></i>
                                {{ $takenExam->submitted_at->diffForHumans() }}
                            </span>
                        </div>

                        <!-- Items Needing Grading -->
                        @php
                            $needsGradingCount = $takenExam->answers->filter(function ($answer) {
                                return in_array($answer->item->type, ['essay', 'shortanswer']) &&
                                       $answer->teacher_score === null;
                            })->count();
                        @endphp

                        <div class="mt-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                                <i class="fas fa-pen-fancy mr-2"></i>
                                {{ $needsGradingCount }} item(s) need grading
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Right Side: Action Button -->
                <div class="ml-6">
                    <a href="{{ route('teacher.grading.show', $takenExam->id) }}"
                       class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-edit mr-2"></i>
                        Grade Now
                    </a>
                </div>
            </div>
        </div>
        @empty
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-md p-12 text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-green-100 mb-4">
                <i class="fas fa-check-circle text-green-600 text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">All Caught Up!</h3>
            <p class="text-gray-600">There are no submissions pending manual grading at this time.</p>
        </div>
        @endforelse

    </div>
</div>

@endsection
