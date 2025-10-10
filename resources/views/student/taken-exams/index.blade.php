@extends('student.shell')

@section('student-content')

<div class="min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                <i class="fas fa-history text-indigo-600 mr-3"></i>My Exam History
            </h1>
            <p class="text-gray-600">View all your taken exams and results</p>
        </div>

        <!-- Filter Tabs (Alpine.js) -->
        <div x-data="{ activeTab: 'all' }" class="mb-6">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8">
                    <button @click="activeTab = 'all'"
                            :class="activeTab === 'all' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        All Exams
                    </button>
                    <button @click="activeTab = 'ongoing'"
                            :class="activeTab === 'ongoing' ? 'border-yellow-500 text-yellow-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Ongoing
                    </button>
                    <button @click="activeTab = 'completed'"
                            :class="activeTab === 'completed' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Completed
                    </button>
                </nav>
            </div>

            <!-- Exams List -->
            @if($takenExams->count() > 0)
                <div class="mt-6 space-y-4">
                    @foreach($takenExams as $takenExam)
                        <div x-show="activeTab === 'all' || (activeTab === 'ongoing' && {{ $takenExam->is_ongoing ? 'true' : 'false' }}) || (activeTab === 'completed' && {{ $takenExam->is_completed ? 'true' : 'false' }})"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition duration-300 border border-gray-200">

                            <div class="flex flex-col md:flex-row">
                                <!-- Left Side: Exam Info -->
                                <div class="flex-1 p-6">
                                    <div class="flex items-start justify-between mb-4">
                                        <div>
                                            <h3 class="text-xl font-bold text-gray-900 mb-1">
                                                {{ $takenExam->exam->title ?? 'Untitled Exam' }}
                                            </h3>
                                            <p class="text-sm text-gray-600">
                                                {{ $takenExam->exam->description ?? 'No description available' }}
                                            </p>
                                        </div>

                                        <!-- Status Badge -->
                                        @if($takenExam->is_ongoing)
                                            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded-full flex-shrink-0 ml-2">
                                                <i class="fas fa-hourglass-half"></i> Ongoing
                                            </span>
                                        @else
                                            <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full flex-shrink-0 ml-2">
                                                <i class="fas fa-check-circle"></i> Submitted
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Exam Stats -->
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                                        <div class="flex items-center space-x-2 text-sm text-gray-600">
                                            <i class="fas fa-question-circle text-indigo-600"></i>
                                            <span>{{ $takenExam->exam->items->count() ?? 0 }} Questions</span>
                                        </div>
                                        <div class="flex items-center space-x-2 text-sm text-gray-600">
                                            <i class="fas fa-calendar text-indigo-600"></i>
                                            <span>{{ \Carbon\Carbon::parse($takenExam->started_at)->format('M d, Y') }}</span>
                                        </div>
                                        <div class="flex items-center space-x-2 text-sm text-gray-600">
                                            <i class="fas fa-clock text-indigo-600"></i>
                                            <span>{{ \Carbon\Carbon::parse($takenExam->started_at)->format('h:i A') }}</span>
                                        </div>
                                        @if($takenExam->is_completed)
                                            <div class="flex items-center space-x-2 text-sm text-gray-600">
                                                <i class="fas fa-check-double text-green-600"></i>
                                                <span>{{ \Carbon\Carbon::parse($takenExam->submitted_at)->diffForHumans() }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Right Side: Score & Actions -->
                                <div class="bg-gray-50 p-6 md:w-64 flex flex-col justify-between border-t md:border-t-0 md:border-l border-gray-200">
                                    @if($takenExam->is_completed)
                                        @if($takenExam->status === 'graded' && $takenExam->exam->status === 'closed')
                                            <!-- Score Display (only show when graded AND exam is closed) -->
                                            <div class="mb-4">
                                                <p class="text-sm text-gray-600 mb-2">Your Score</p>
                                                <div class="flex items-baseline">
                                                    <span class="text-3xl font-bold text-indigo-600">{{ $takenExam->total_points }}</span>
                                                    <span class="text-lg text-gray-500 ml-1">/{{ $takenExam->exam->total_points ?? 0 }}</span>
                                                </div>
                                                <div class="mt-2">
                                                    <div class="flex items-center justify-between text-sm mb-1">
                                                        <span class="text-gray-600">Percentage</span>
                                                        <span class="font-semibold text-gray-900">{{ $takenExam->percentage ?? 0 }}%</span>
                                                    </div>
                                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                                        <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $takenExam->percentage ?? 0 }}%"></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- View Results Button -->
                                            <a href="{{ route('student.taken-exams.show', $takenExam->id) }}"
                                               class="w-full px-4 py-3 bg-indigo-600 text-white text-center rounded-lg font-medium hover:bg-indigo-700 transition duration-200">
                                                <i class="fas fa-chart-bar mr-2"></i>View Results
                                            </a>
                                        @else
                                            <!-- Results Pending (not graded yet or exam not closed) -->
                                            <div class="mb-4">
                                                <p class="text-sm text-gray-600 mb-2">Status</p>
                                                <div class="flex items-center">
                                                    @if($takenExam->status === 'graded')
                                                        <i class="fas fa-check-circle text-green-600 mr-2"></i>
                                                        <p class="text-sm font-semibold text-green-700">Graded</p>
                                                    @else
                                                        <i class="fas fa-clock text-yellow-600 mr-2"></i>
                                                        <p class="text-sm font-semibold text-yellow-700">Awaiting Grading</p>
                                                    @endif
                                                </div>
                                                <p class="text-xs text-gray-500 mt-2">
                                                    @if($takenExam->status === 'graded')
                                                        Results will be visible when exam is closed
                                                    @else
                                                        Teacher is reviewing your submission
                                                    @endif
                                                </p>
                                            </div>

                                            <!-- View Status Button -->
                                            <a href="{{ route('student.taken-exams.show', $takenExam->id) }}"
                                               class="w-full px-4 py-3 bg-gray-200 text-gray-700 text-center rounded-lg font-medium hover:bg-gray-300 transition duration-200">
                                                <i class="fas fa-info-circle mr-2"></i>View Status
                                            </a>
                                        @endif
                                    @else
                                        <!-- Ongoing Exam -->
                                        <div class="mb-4">
                                            <p class="text-sm text-gray-600 mb-2">Status</p>
                                            <p class="text-lg font-semibold text-yellow-700">In Progress</p>
                                        </div>

                                        <!-- Continue Button -->
                                        <a href="{{ route('student.taken-exams.continue', $takenExam->id) }}"
                                           class="w-full px-4 py-3 bg-yellow-600 text-white text-center rounded-lg font-medium hover:bg-yellow-700 transition duration-200">
                                            <i class="fas fa-play-circle mr-2"></i>Continue Exam
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="mt-6 bg-white rounded-xl shadow-md p-12 text-center">
                    <div class="bg-gray-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-inbox text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">No Exam History</h3>
                    <p class="text-gray-600 mb-6">You haven't taken any exams yet.</p>
                    <a href="{{ route('student.exams.index') }}"
                       class="inline-block px-6 py-3 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition duration-200">
                        <i class="fas fa-search mr-2"></i>Browse Available Exams
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
