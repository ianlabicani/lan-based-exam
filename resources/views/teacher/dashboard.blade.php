@extends('teacher.shell')

@section('teacher-content')

@php
    // Mock data for teacher dashboard
    $teacher = [
        'name' => 'Dr. Sarah Johnson',
        'email' => 'sarah.johnson@school.edu',
        'department' => 'Computer Science',
        'avatar' => 'SJ'
    ];

    $stats = [
        'total_exams' => 24,
        'active_exams' => 5,
        'completed_exams' => 19,
        'total_students' => 156,
        'active_takers' => 12,
        'pending_grading' => 8
    ];

    $recentExams = [
        [
            'id' => 1,
            'title' => 'Midterm Examination - Data Structures',
            'status' => 'published',
            'schedule' => '2025-10-15 09:00 AM',
            'duration' => 120,
            'questions' => 50,
            'takers' => 45,
            'completed' => 38,
            'pending' => 7,
            'average_score' => 85.5
        ],
        [
            'id' => 2,
            'title' => 'Quiz 3 - Algorithms and Complexity',
            'status' => 'published',
            'schedule' => '2025-10-12 02:00 PM',
            'duration' => 60,
            'questions' => 30,
            'takers' => 42,
            'completed' => 42,
            'pending' => 0,
            'average_score' => 78.3
        ],
        [
            'id' => 3,
            'title' => 'Final Exam - Database Systems',
            'status' => 'draft',
            'schedule' => '2025-10-20 10:00 AM',
            'duration' => 180,
            'questions' => 75,
            'takers' => 0,
            'completed' => 0,
            'pending' => 0,
            'average_score' => 0
        ],
        [
            'id' => 4,
            'title' => 'Weekly Assessment - Web Development',
            'status' => 'published',
            'schedule' => '2025-10-10 01:00 PM',
            'duration' => 45,
            'questions' => 20,
            'takers' => 38,
            'completed' => 35,
            'pending' => 3,
            'average_score' => 92.1
        ]
    ];

    $activeTakers = [
        [
            'student' => 'John Martinez',
            'exam' => 'Midterm Examination - Data Structures',
            'started' => '09:05 AM',
            'time_remaining' => '01:45:23',
            'progress' => 60,
            'activity_flags' => 2
        ],
        [
            'student' => 'Emily Chen',
            'exam' => 'Midterm Examination - Data Structures',
            'started' => '09:02 AM',
            'time_remaining' => '01:48:15',
            'progress' => 75,
            'activity_flags' => 0
        ],
        [
            'student' => 'Michael Brown',
            'exam' => 'Weekly Assessment - Web Development',
            'started' => '01:10 PM',
            'time_remaining' => '00:25:40',
            'progress' => 45,
            'activity_flags' => 5
        ]
    ];

    $pendingGrading = [
        [
            'student' => 'Lisa Anderson',
            'exam' => 'Quiz 3 - Algorithms',
            'submitted' => '2 hours ago',
            'essay_count' => 3,
            'short_answer_count' => 5,
            'auto_score' => 65
        ],
        [
            'student' => 'David Kim',
            'exam' => 'Midterm Examination',
            'submitted' => '5 hours ago',
            'essay_count' => 2,
            'short_answer_count' => 8,
            'auto_score' => 72
        ],
        [
            'student' => 'Sarah Williams',
            'exam' => 'Weekly Assessment',
            'submitted' => '1 day ago',
            'essay_count' => 1,
            'short_answer_count' => 3,
            'auto_score' => 88
        ]
    ];

    $performanceData = [
        ['exam' => 'Quiz 1', 'average' => 82],
        ['exam' => 'Quiz 2', 'average' => 85],
        ['exam' => 'Quiz 3', 'average' => 78],
        ['exam' => 'Midterm', 'average' => 86],
    ];
@endphp

<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="bg-white bg-opacity-20 w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold">
                        {{ $teacher['avatar'] }}
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold">Welcome back, {{ explode(' ', $teacher['name'])[1] }}!</h1>
                        <p class="text-indigo-100 mt-1">
                            <i class="fas fa-building mr-2"></i>{{ $teacher['department'] }}
                        </p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm text-indigo-100">{{ date('l, F j, Y') }}</p>
                    <p class="text-2xl font-semibold">{{ date('g:i A') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Total Exams -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Exams</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_exams'] }}</p>
                        <p class="text-sm text-gray-500 mt-1">
                            <span class="text-green-600">{{ $stats['active_exams'] }}</span> active
                        </p>
                    </div>
                    <div class="bg-blue-100 w-14 h-14 rounded-full flex items-center justify-center">
                        <i class="fas fa-file-alt text-2xl text-blue-600"></i>
                    </div>
                </div>
            </div>

            <!-- Active Takers -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Active Takers</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['active_takers'] }}</p>
                        <p class="text-sm text-gray-500 mt-1">Taking exams now</p>
                    </div>
                    <div class="bg-green-100 w-14 h-14 rounded-full flex items-center justify-center">
                        <i class="fas fa-users text-2xl text-green-600"></i>
                    </div>
                </div>
            </div>

            <!-- Pending Grading -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-orange-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Pending Grading</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['pending_grading'] }}</p>
                        <p class="text-sm text-gray-500 mt-1">Submissions to review</p>
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
                        <div class="space-y-4">
                            @foreach($recentExams as $exam)
                            <div class="border border-gray-200 rounded-lg p-4 hover:border-indigo-300 hover:shadow-md transition duration-200">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2 mb-2">
                                            <h3 class="font-semibold text-gray-900">{{ $exam['title'] }}</h3>
                                            @if($exam['status'] === 'published')
                                                <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">
                                                    <i class="fas fa-check-circle"></i> Published
                                                </span>
                                            @else
                                                <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs font-medium rounded-full">
                                                    <i class="fas fa-pencil-alt"></i> Draft
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex items-center space-x-4 text-sm text-gray-600">
                                            <span><i class="fas fa-calendar mr-1"></i>{{ $exam['schedule'] }}</span>
                                            <span><i class="fas fa-clock mr-1"></i>{{ $exam['duration'] }} mins</span>
                                            <span><i class="fas fa-question-circle mr-1"></i>{{ $exam['questions'] }} questions</span>
                                        </div>
                                    </div>
                                    <a href="#" class="text-indigo-600 hover:text-indigo-700">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                </div>
                                @if($exam['status'] === 'published')
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
                                @endif
                            </div>
                            @endforeach
                        </div>
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
                        <div class="space-y-4">
                            @foreach($activeTakers as $taker)
                            <div class="border-l-4 @if($taker['activity_flags'] > 3) border-red-500 @elseif($taker['activity_flags'] > 0) border-yellow-500 @else border-green-500 @endif bg-gray-50 rounded-r-lg p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="font-semibold text-gray-900 text-sm">{{ $taker['student'] }}</h4>
                                    @if($taker['activity_flags'] > 0)
                                        <span class="px-2 py-1 bg-red-100 text-red-700 text-xs font-medium rounded-full">
                                            <i class="fas fa-exclamation-triangle"></i> {{ $taker['activity_flags'] }}
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-600 mb-2">{{ $taker['exam'] }}</p>
                                <div class="space-y-2">
                                    <div class="flex justify-between text-xs text-gray-600">
                                        <span><i class="fas fa-clock mr-1"></i>{{ $taker['time_remaining'] }}</span>
                                        <span>{{ $taker['progress'] }}% complete</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $taker['progress'] }}%"></div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <a href="#" class="block text-center mt-4 text-indigo-600 hover:text-indigo-700 text-sm font-medium">
                            View All Active Takers <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">
                        <i class="fas fa-bolt text-yellow-500 mr-2"></i>Quick Actions
                    </h2>
                    <div class="space-y-3">
                        <a href="#" class="block w-full bg-indigo-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-indigo-700 transition duration-200 text-center">
                            <i class="fas fa-plus-circle mr-2"></i>Create New Exam
                        </a>
                        <a href="#" class="block w-full bg-white border-2 border-indigo-600 text-indigo-600 py-3 px-4 rounded-lg font-medium hover:bg-indigo-50 transition duration-200 text-center">
                            <i class="fas fa-list mr-2"></i>View All Exams
                        </a>
                        <a href="#" class="block w-full bg-white border-2 border-green-600 text-green-600 py-3 px-4 rounded-lg font-medium hover:bg-green-50 transition duration-200 text-center">
                            <i class="fas fa-chart-bar mr-2"></i>View Analytics
                        </a>
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
                    <span class="px-3 py-1 bg-orange-100 text-orange-700 text-sm font-medium rounded-full">
                        {{ count($pendingGrading) }} submissions
                    </span>
                </div>
            </div>
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
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="bg-indigo-100 w-10 h-10 rounded-full flex items-center justify-center text-indigo-600 font-semibold mr-3">
                                        {{ substr($submission['student'], 0, 1) }}
                                    </div>
                                    <div class="font-medium text-gray-900">{{ $submission['student'] }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $submission['exam'] }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-600">{{ $submission['submitted'] }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    @if($submission['essay_count'] > 0)
                                        <span class="mr-2"><i class="fas fa-file-alt text-blue-600"></i> {{ $submission['essay_count'] }} Essay</span>
                                    @endif
                                    @if($submission['short_answer_count'] > 0)
                                        <span><i class="fas fa-align-left text-green-600"></i> {{ $submission['short_answer_count'] }} Short</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 text-sm font-medium rounded-full">
                                    {{ $submission['auto_score'] }}%
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="#" class="text-indigo-600 hover:text-indigo-900">
                                    <i class="fas fa-edit mr-1"></i>Grade Now
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
