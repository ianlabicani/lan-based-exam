@extends('teacher.shell')

@section('teacher-content')

<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="bg-white bg-opacity-20 w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold">Welcome back, {{ explode(' ', Auth::user()->name)[0] }}!</h1>
                        <p class="text-indigo-100 mt-1">
                            <i class="fas fa-envelope mr-2"></i>{{ Auth::user()->email }}
                        </p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm text-indigo-100">{{ now()->format('l, F j, Y') }}</p>
                    <p class="text-2xl font-semibold" id="current-time">{{ now()->format('g:i A') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Exams -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500 hover:shadow-lg transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Exams</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_exams'] }}</p>
                        <p class="text-sm text-gray-500 mt-1">
                            <span class="text-green-600 font-semibold">{{ $stats['active_exams'] }}</span> active
                            <span class="text-gray-400 mx-1">•</span>
                            <span class="text-gray-600">{{ $stats['draft_exams'] }}</span> draft
                        </p>
                    </div>
                    <div class="bg-blue-100 w-14 h-14 rounded-full flex items-center justify-center">
                        <i class="fas fa-file-alt text-2xl text-blue-600"></i>
                    </div>
                </div>
            </div>

            <!-- Total Students -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500 hover:shadow-lg transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Students</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_students'] }}</p>
                        <p class="text-sm text-gray-500 mt-1">
                            <span class="text-purple-600 font-semibold">{{ $stats['total_submissions'] }}</span> submissions
                        </p>
                    </div>
                    <div class="bg-purple-100 w-14 h-14 rounded-full flex items-center justify-center">
                        <i class="fas fa-user-graduate text-2xl text-purple-600"></i>
                    </div>
                </div>
            </div>

            <!-- Active Takers -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500 hover:shadow-lg transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Active Takers</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['active_takers'] }}</p>
                        <p class="text-sm text-gray-500 mt-1">
                            @if($stats['active_takers'] > 0)
                                <span class="text-green-600 font-semibold flex items-center">
                                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse mr-2"></span>
                                    Taking exams now
                                </span>
                            @else
                                <span class="text-gray-500">No active sessions</span>
                            @endif
                        </p>
                    </div>
                    <div class="bg-green-100 w-14 h-14 rounded-full flex items-center justify-center">
                        <i class="fas fa-users text-2xl text-green-600"></i>
                    </div>
                </div>
            </div>

            <!-- Pending Grading -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-orange-500 hover:shadow-lg transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Pending Grading</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['pending_grading'] }}</p>
                        <p class="text-sm text-gray-500 mt-1">
                            @if($stats['pending_grading'] > 0)
                                <span class="text-orange-600 font-semibold">Needs attention</span>
                            @else
                                <span class="text-green-600">All caught up!</span>
                            @endif
                        </p>
                    </div>
                    <div class="bg-orange-100 w-14 h-14 rounded-full flex items-center justify-center">
                        <i class="fas fa-clock text-2xl text-orange-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Recent Exams -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-md">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-bold text-gray-900">
                                <i class="fas fa-clipboard-list text-indigo-600 mr-2"></i>Recent Exams
                            </h2>
                            <a href="#" class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">
                                View All <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                    <div class="p-6">
                        @if(count($recentExams) > 0)
                            <div class="space-y-4">
                                @foreach($recentExams as $exam)
                                <div class="border border-gray-200 rounded-lg p-4 hover:border-indigo-300 hover:shadow-md transition duration-200">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-2 mb-2">
                                                <h3 class="font-semibold text-gray-900">{{ $exam['title'] }}</h3>
                                                @if($exam['status'] === 'published' || $exam['status'] === 'ongoing')
                                                    <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">
                                                        <i class="fas fa-check-circle"></i> {{ ucfirst($exam['status']) }}
                                                    </span>
                                                @elseif($exam['status'] === 'draft')
                                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs font-medium rounded-full">
                                                        <i class="fas fa-pencil-alt"></i> Draft
                                                    </span>
                                                @elseif($exam['status'] === 'closed')
                                                    <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-full">
                                                        <i class="fas fa-lock"></i> Closed
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="flex items-center space-x-4 text-sm text-gray-600">
                                                <span><i class="fas fa-calendar mr-1"></i>{{ $exam['schedule'] }}</span>
                                                @if($exam['duration'] > 0)
                                                    <span><i class="fas fa-clock mr-1"></i>{{ $exam['duration'] }} mins</span>
                                                @endif
                                                <span><i class="fas fa-question-circle mr-1"></i>{{ $exam['questions'] }} questions</span>
                                            </div>
                                        </div>
                                        <a href="{{ route('teacher.exams.show', $exam['id']) }}" class="text-indigo-600 hover:text-indigo-700" title="View Exam">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    </div>
                                    @if($exam['takers'] > 0)
                                        <div class="grid grid-cols-3 gap-4 mt-3 pt-3 border-t border-gray-100">
                                            <div class="text-center">
                                                <p class="text-xs text-gray-500">Takers</p>
                                                <p class="text-lg font-semibold text-gray-900">{{ $exam['takers'] }}</p>
                                            </div>
                                            <div class="text-center">
                                                <p class="text-xs text-gray-500">Completed</p>
                                                <p class="text-lg font-semibold text-green-600">{{ $exam['completed'] }}</p>
                                            </div>
                                            <div class="text-center">
                                                <p class="text-xs text-gray-500">Avg Score</p>
                                                <p class="text-lg font-semibold text-indigo-600">{{ $exam['average_score'] }}%</p>
                                            </div>
                                        </div>
                                    @else
                                        <div class="mt-3 pt-3 border-t border-gray-100 text-center">
                                            <p class="text-sm text-gray-500 italic">No submissions yet</p>
                                        </div>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <i class="fas fa-clipboard-list text-6xl text-gray-300 mb-4"></i>
                                <p class="text-gray-500 mb-4">No exams created yet</p>
                                <a href="{{ route('teacher.exams.create') }}" class="inline-block bg-indigo-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-indigo-700 transition duration-200">
                                    <i class="fas fa-plus-circle mr-2"></i>Create Your First Exam
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Active Takers Sidebar -->
            <div class="space-y-6">
                <!-- Active Takers Card -->
                <div class="bg-white rounded-xl shadow-md">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900">
                            <i class="fas fa-user-clock text-green-600 mr-2"></i>Active Takers
                        </h2>
                    </div>
                    <div class="p-6">
                        @if(count($activeTakers) > 0)
                            <div class="space-y-4">
                                @foreach($activeTakers as $taker)
                                <div class="border-l-4 {{ $taker['activity_flags'] > 3 ? 'border-red-500' : ($taker['activity_flags'] > 0 ? 'border-yellow-500' : 'border-green-500') }} bg-gray-50 rounded-r-lg p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="font-semibold text-gray-900 text-sm">{{ $taker['student'] }}</h4>
                                        @if($taker['activity_flags'] > 0)
                                            <span class="px-2 py-1 bg-red-100 text-red-700 text-xs font-medium rounded-full" title="Suspicious activity detected">
                                                <i class="fas fa-exclamation-triangle"></i> {{ $taker['activity_flags'] }}
                                            </span>
                                        @else
                                            <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">
                                                <i class="fas fa-check-circle"></i> OK
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-600 mb-2 truncate" title="{{ $taker['exam'] }}">{{ $taker['exam'] }}</p>
                                    <div class="space-y-2">
                                        <div class="flex justify-between text-xs text-gray-600">
                                            <span><i class="fas fa-clock mr-1"></i>{{ $taker['time_remaining'] }}</span>
                                            <span>{{ $taker['progress'] }}% time</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-indigo-600 h-2 rounded-full transition-all duration-300" style="width: {{ $taker['progress'] }}%"></div>
                                        </div>
                                        <p class="text-xs text-gray-500">
                                            <i class="fas fa-check-square mr-1"></i>{{ $taker['answered_count'] }} answers saved
                                        </p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-user-clock text-4xl text-gray-300 mb-3"></i>
                                <p class="text-gray-500 text-sm">No students taking exams right now</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">
                        <i class="fas fa-bolt text-yellow-500 mr-2"></i>Quick Actions
                    </h2>
                    <div class="space-y-3">
                        <a href="{{ route('teacher.exams.create') }}" class="block w-full bg-indigo-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-indigo-700 transition duration-200 text-center">
                            <i class="fas fa-plus-circle mr-2"></i>Create New Exam
                        </a>
                        <a href="{{ route('teacher.exams.index') }}" class="block w-full bg-white border-2 border-indigo-600 text-indigo-600 py-3 px-4 rounded-lg font-medium hover:bg-indigo-50 transition duration-200 text-center">
                            <i class="fas fa-list mr-2"></i>View All Exams
                        </a>
                        <a href="{{ route('teacher.analytics.index') }}" class="block w-full bg-white border-2 border-green-600 text-green-600 py-3 px-4 rounded-lg font-medium hover:bg-green-50 transition duration-200 text-center">
                            <i class="fas fa-chart-bar mr-2"></i>View Analytics
                        </a>
                        @if($stats['pending_grading'] > 0)
                            <a href="{{ route('teacher.grading.index') }}" class="block w-full bg-white border-2 border-orange-600 text-orange-600 py-3 px-4 rounded-lg font-medium hover:bg-orange-50 transition duration-200 text-center">
                                <i class="fas fa-tasks mr-2"></i>Grade Submissions ({{ $stats['pending_grading'] }})
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Grading Section -->
        <div class="bg-white rounded-xl shadow-md mb-8">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-900">
                        <i class="fas fa-tasks text-orange-600 mr-2"></i>Pending Grading
                    </h2>
                    @if(count($pendingGrading) > 0)
                        <span class="px-3 py-1 bg-orange-100 text-orange-700 text-sm font-medium rounded-full">
                            {{ count($pendingGrading) }} submission{{ count($pendingGrading) > 1 ? 's' : '' }}
                        </span>
                    @endif
                </div>
            </div>
            @if(count($pendingGrading) > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Exam</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pending Items</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Auto Score</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($pendingGrading as $submission)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="bg-indigo-100 w-10 h-10 rounded-full flex items-center justify-center text-indigo-600 font-semibold mr-3">
                                            {{ strtoupper(substr($submission['student'], 0, 1)) }}
                                        </div>
                                        <div class="font-medium text-gray-900">{{ $submission['student'] }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $submission['exam'] }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-600">
                                        <i class="fas fa-clock text-gray-400 mr-1"></i>{{ $submission['submitted'] }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        @if($submission['essay_count'] > 0)
                                            <span class="inline-flex items-center mr-2 px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs">
                                                <i class="fas fa-file-alt mr-1"></i> {{ $submission['essay_count'] }} Essay
                                            </span>
                                        @endif
                                        @if($submission['short_answer_count'] > 0)
                                            <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-700 rounded text-xs">
                                                <i class="fas fa-align-left mr-1"></i> {{ $submission['short_answer_count'] }} Short
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 bg-blue-100 text-blue-700 text-sm font-medium rounded-full">
                                        {{ $submission['auto_score'] }}%
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('teacher.grading.show', $submission['id']) }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-900 transition-colors duration-150">
                                        <i class="fas fa-edit mr-1"></i>Grade Now
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-4 bg-gray-50 border-t border-gray-200 text-center">
                    <a href="{{ route('teacher.grading.index') }}" class="text-indigo-600 hover:text-indigo-700 font-medium text-sm">
                        View All Pending Submissions <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            @else
                <div class="p-12 text-center">
                    <i class="fas fa-check-circle text-6xl text-green-300 mb-4"></i>
                    <p class="text-gray-600 text-lg font-medium">All caught up!</p>
                    <p class="text-gray-500 mt-2">No submissions are waiting for grading.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Real-time clock update -->
<script>
    function updateTime() {
        const now = new Date();
        const hours = now.getHours();
        const minutes = now.getMinutes();
        const ampm = hours >= 12 ? 'PM' : 'AM';
        const displayHours = hours % 12 || 12;
        const displayMinutes = minutes < 10 ? '0' + minutes : minutes;

        const timeElement = document.getElementById('current-time');
        if (timeElement) {
            timeElement.textContent = displayHours + ':' + displayMinutes + ' ' + ampm;
        }
    }

    // Update time every minute
    setInterval(updateTime, 60000);
</script>

@endsection
