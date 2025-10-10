@extends('student.shell')

@section('student-content')

<div class="min-h-screen bg-gray-100 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header Section -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <a href="{{ route('student.taken-exams.index') }}"
               class="inline-flex items-center text-indigo-600 hover:text-indigo-800 mb-3 font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Back to Exam History
            </a>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $exam->title }}</h1>
            <p class="text-gray-600">{{ $exam->description }}</p>
        </div>

        <!-- Status Card -->
        <div class="bg-white rounded-xl shadow-md p-8 text-center">
            <!-- Success Icon -->
            <div class="mb-6">
                <div class="mx-auto w-24 h-24 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-5xl"></i>
                </div>
            </div>

            <!-- Main Message -->
            <h2 class="text-2xl font-bold text-gray-900 mb-3">Exam Submitted Successfully!</h2>
            <p class="text-lg text-gray-600 mb-6">
                Your exam has been successfully submitted on {{ $takenExam->submitted_at->format('M d, Y h:i A') }}
            </p>

            <!-- Pending Message -->
            <div class="max-w-2xl mx-auto bg-yellow-50 border-2 border-yellow-200 rounded-lg p-6 mb-6">
                <div class="flex items-start">
                    <i class="fas fa-clock text-yellow-600 text-3xl mr-4 mt-1"></i>
                    <div class="text-left flex-1">
                        <h3 class="text-lg font-bold text-yellow-900 mb-2">
                            @if($takenExam->status === 'graded')
                                Graded - Awaiting Release
                            @else
                                Results Pending Grading
                            @endif
                        </h3>
                        <p class="text-yellow-800 mb-3">
                            @if($takenExam->status === 'graded')
                                Your exam has been graded by your teacher! Results and detailed feedback will be visible once the teacher closes the exam for all students.
                            @else
                                Your exam is currently being reviewed by your teacher. Results and detailed feedback will be available once grading is complete and the exam is closed.
                            @endif
                        </p>
                        <div class="bg-yellow-100 rounded-lg p-3">
                            <p class="text-sm font-semibold text-yellow-900 mb-1">What happens next:</p>
                            <ul class="text-sm text-yellow-800 space-y-1">
                                @if($takenExam->status === 'graded')
                                    <li><i class="fas fa-check text-green-600 mr-2"></i>Your responses have been graded</li>
                                    <li><i class="fas fa-check text-green-600 mr-2"></i>Scores and feedback are ready</li>
                                    <li><i class="fas fa-arrow-right text-yellow-600 mr-2"></i>Teacher will close the exam when all students are graded</li>
                                    <li><i class="fas fa-arrow-right text-yellow-600 mr-2"></i>You'll be able to view your complete results after closure</li>
                                @else
                                    <li><i class="fas fa-arrow-right text-yellow-600 mr-2"></i>Your teacher will review your responses</li>
                                    <li><i class="fas fa-arrow-right text-yellow-600 mr-2"></i>Manual grading items (essays, short answers) will receive scores and feedback</li>
                                    <li><i class="fas fa-arrow-right text-yellow-600 mr-2"></i>Once grading is complete, teacher will close the exam</li>
                                    <li><i class="fas fa-arrow-right text-yellow-600 mr-2"></i>You'll be able to view your results and feedback after closure</li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Exam Status Badge -->
            <div class="inline-flex items-center px-6 py-3 bg-blue-50 rounded-lg border-2 border-blue-200 mb-6">
                <i class="fas fa-info-circle text-blue-600 text-xl mr-3"></i>
                <div class="text-left">
                    <p class="text-sm text-blue-600 font-semibold">Current Exam Status:</p>
                    <p class="text-lg font-bold text-blue-900">
                        @if($exam->status === 'ongoing')
                            <span class="text-orange-600">Ongoing</span> - Accepting submissions
                        @elseif($exam->status === 'finished')
                            <span class="text-purple-600">Finished</span> - Being graded
                        @else
                            {{ ucfirst($exam->status) }}
                        @endif
                    </p>
                </div>
            </div>

            <!-- Submission Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-2xl mx-auto mb-6">
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <p class="text-sm text-gray-600 mb-1">Submitted On</p>
                    <p class="text-lg font-bold text-gray-900">
                        {{ $takenExam->submitted_at->format('M d, Y') }}
                    </p>
                    <p class="text-sm text-gray-600">
                        {{ $takenExam->submitted_at->format('h:i A') }}
                    </p>
                </div>

                @if($takenExam->status === 'graded')
                <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                    <p class="text-sm text-green-600 mb-1">Grading Status</p>
                    <p class="text-lg font-bold text-green-900">
                        <i class="fas fa-check-circle mr-1"></i>Graded
                    </p>
                    <p class="text-sm text-green-700">
                        Waiting for exam to close
                    </p>
                </div>
                @else
                <div class="bg-orange-50 p-4 rounded-lg border border-orange-200">
                    <p class="text-sm text-orange-600 mb-1">Grading Status</p>
                    <p class="text-lg font-bold text-orange-900">
                        <i class="fas fa-hourglass-half mr-1"></i>Pending
                    </p>
                    <p class="text-sm text-orange-700">
                        Awaiting teacher review
                    </p>
                </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('student.exams.index') }}"
                   class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition duration-200">
                    <i class="fas fa-file-alt mr-2"></i>Browse More Exams
                </a>

                <a href="{{ route('student.taken-exams.index') }}"
                   class="px-6 py-3 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition duration-200">
                    <i class="fas fa-history mr-2"></i>View Exam History
                </a>
            </div>

            <!-- Help Text -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <p class="text-sm text-gray-500">
                    <i class="fas fa-question-circle mr-1"></i>
                    Check back later to view your results. You'll receive your score and detailed feedback once the exam is closed by your teacher.
                </p>
            </div>
        </div>

    </div>
</div>

@endsection
