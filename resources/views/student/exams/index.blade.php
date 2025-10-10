@extends('student.shell')

@section('student-content')

<div class="min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-md">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                    <p class="text-green-700 font-medium">{{ session('success') }}</p>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-green-500 hover:text-green-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-md">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-500 text-xl mr-3"></i>
                    <p class="text-red-700 font-medium">{{ session('error') }}</p>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-red-500 hover:text-red-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        @endif

        @if(session('info'))
            <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg shadow-md">
                <div class="flex items-center">
                    <i class="fas fa-info-circle text-blue-500 text-xl mr-3"></i>
                    <p class="text-blue-700 font-medium">{{ session('info') }}</p>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-blue-500 hover:text-blue-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        @endif

        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                <i class="fas fa-file-alt text-indigo-600 mr-3"></i>Available Exams
            </h1>
            <p class="text-gray-600">View and take your assigned exams</p>
        </div>

        <!-- Exams Grid -->
        @if($exams->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @foreach($exams as $exam)
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition duration-300 border border-gray-200">
                    <!-- Exam Header -->
                    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-xl font-bold mb-2">{{ $exam->title }}</h3>
                                <p class="text-indigo-100 text-sm">{{ $exam->description }}</p>

                                <!-- Year and Sections -->
                                <div class="flex flex-wrap items-center gap-2 mt-3">
                                    @if(is_array($exam->year) && count($exam->year) > 0)
                                        <div class="flex items-center gap-1">
                                            <span class="text-xs text-indigo-200">
                                                <i class="fas fa-graduation-cap"></i>
                                            </span>
                                            @foreach($exam->year as $year)
                                                <span class="px-2 py-0.5 bg-white bg-opacity-20 text-white text-xs font-medium rounded">
                                                    Yr{{ $year }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif

                                    @if(is_array($exam->sections) && count($exam->sections) > 0)
                                        <div class="flex items-center gap-1">
                                            <span class="text-xs text-indigo-200">
                                                <i class="fas fa-users-class"></i>
                                            </span>
                                            @foreach($exam->sections as $section)
                                                <span class="px-2 py-0.5 bg-white bg-opacity-20 text-white text-xs font-medium rounded uppercase">
                                                    {{ $section }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @if($exam->taken)
                                <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full flex-shrink-0 ml-2">
                                    <i class="fas fa-check-circle"></i> Taken
                                </span>
                            @elseif(!$exam->is_available)
                                <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs font-semibold rounded-full flex-shrink-0 ml-2">
                                    <i class="fas fa-clock"></i> Unavailable
                                </span>
                            @else
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded-full flex-shrink-0 ml-2">
                                    <i class="fas fa-hourglass-half"></i> Available
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Exam Details -->
                    <div class="p-6">
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="flex items-center space-x-2 text-sm text-gray-600">
                                <i class="fas fa-question-circle text-indigo-600"></i>
                                <span>{{ $exam->items->count() }} Questions</span>
                            </div>
                            <div class="flex items-center space-x-2 text-sm text-gray-600">
                                <i class="fas fa-star text-indigo-600"></i>
                                <span>{{ $exam->total_points }} Points</span>
                            </div>
                            <div class="flex items-center space-x-2 text-sm text-gray-600">
                                <i class="fas fa-calendar text-indigo-600"></i>
                                <span>{{ \Carbon\Carbon::parse($exam->starts_at)->format('M d, Y') }}</span>
                            </div>
                            <div class="flex items-center space-x-2 text-sm text-gray-600">
                                <i class="fas fa-clock text-indigo-600"></i>
                                <span>{{ \Carbon\Carbon::parse($exam->ends_at)->format('h:i A') }}</span>
                            </div>
                        </div>

                        <!-- Time Remaining -->
                        @if($exam->is_available && !$exam->taken)
                            @php
                                $endsAt = \Carbon\Carbon::parse($exam->ends_at);
                                $now = \Carbon\Carbon::now();
                                $hoursRemaining = $now->diffInHours($endsAt);
                                $minutesRemaining = $now->diffInMinutes($endsAt) % 60;
                            @endphp
                            <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <div class="flex items-center space-x-2 text-yellow-800 text-sm">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <span class="font-semibold">Time Remaining: {{ $hoursRemaining }}h {{ $minutesRemaining }}m</span>
                                </div>
                            </div>
                        @endif

                        <!-- Score Display (if taken) -->
                        @if($exam->taken && $exam->taken_exam)
                            <div class="mb-4 p-4 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">Your Score</p>
                                        <p class="text-2xl font-bold text-green-700">
                                            {{ $exam->taken_exam->total_points }}/{{ $exam->total_points }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-gray-600 mb-1">Percentage</p>
                                        <p class="text-2xl font-bold text-green-700">
                                            {{ $exam->total_points > 0 ? round(($exam->taken_exam->total_points / $exam->total_points) * 100, 2) : 0 }}%
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="flex space-x-3">
                            @if($exam->taken && $exam->taken_exam && $exam->taken_exam->submitted_at)
                                <a href="{{ route('student.taken-exams.show', $exam->taken_exam->id) }}"
                                   class="flex-1 px-4 py-3 bg-indigo-600 text-white text-center rounded-lg font-medium hover:bg-indigo-700 transition duration-200">
                                    <i class="fas fa-chart-bar mr-2"></i>View Results
                                </a>
                            @elseif($exam->taken && $exam->taken_exam && !$exam->taken_exam->submitted_at)
                                <a href="{{ route('student.taken-exams.continue', $exam->taken_exam->id) }}"
                                   class="flex-1 px-4 py-3 bg-yellow-600 text-white text-center rounded-lg font-medium hover:bg-yellow-700 transition duration-200">
                                    <i class="fas fa-play-circle mr-2"></i>Continue Exam
                                </a>
                            @elseif($exam->is_available)
                                <a href="{{ route('student.taken-exams.create', ['exam_id' => $exam->id]) }}"
                                   class="flex-1 px-4 py-3 bg-green-600 text-white text-center rounded-lg font-medium hover:bg-green-700 transition duration-200">
                                    <i class="fas fa-play-circle mr-2"></i>Start Exam
                                </a>
                            @else
                                <button disabled
                                        class="flex-1 px-4 py-3 bg-gray-300 text-gray-500 text-center rounded-lg font-medium cursor-not-allowed">
                                    <i class="fas fa-lock mr-2"></i>Not Available
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-xl shadow-md p-12 text-center">
                <div class="bg-gray-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-inbox text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">No Exams Available</h3>
                <p class="text-gray-600">There are currently no exams assigned to you.</p>
            </div>
        @endif
    </div>
</div>

@endsection
