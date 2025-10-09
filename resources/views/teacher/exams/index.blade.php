@extends('teacher.shell')

@section('teacher-content')

@php
    // Mock data for exams list
    $exams = [
        [
            'id' => 1,
            'title' => 'Midterm Examination - Data Structures',
            'description' => 'Comprehensive midterm covering arrays, linked lists, stacks, queues, and trees',
            'status' => 'published',
            'start_time' => '2025-10-15 09:00:00',
            'end_time' => '2025-10-15 12:00:00',
            'duration' => 120,
            'total_items' => 50,
            'total_points' => 100,
            'takers_count' => 45,
            'completed_count' => 38,
            'average_score' => 85.5,
            'created_at' => '2025-10-01'
        ],
        [
            'id' => 2,
            'title' => 'Quiz 3 - Algorithms and Complexity',
            'description' => 'Weekly quiz on sorting algorithms and Big-O notation',
            'status' => 'published',
            'start_time' => '2025-10-12 14:00:00',
            'end_time' => '2025-10-12 15:00:00',
            'duration' => 60,
            'total_items' => 30,
            'total_points' => 50,
            'takers_count' => 42,
            'completed_count' => 42,
            'average_score' => 78.3,
            'created_at' => '2025-10-05'
        ],
        [
            'id' => 3,
            'title' => 'Final Exam - Database Systems',
            'description' => 'Comprehensive final exam covering SQL, normalization, and transactions',
            'status' => 'draft',
            'start_time' => '2025-10-20 10:00:00',
            'end_time' => '2025-10-20 13:00:00',
            'duration' => 180,
            'total_items' => 75,
            'total_points' => 150,
            'takers_count' => 0,
            'completed_count' => 0,
            'average_score' => 0,
            'created_at' => '2025-10-08'
        ],
        [
            'id' => 4,
            'title' => 'Weekly Assessment - Web Development',
            'description' => 'Assessment covering HTML, CSS, and JavaScript fundamentals',
            'status' => 'published',
            'start_time' => '2025-10-10 13:00:00',
            'end_time' => '2025-10-10 14:00:00',
            'duration' => 45,
            'total_items' => 20,
            'total_points' => 30,
            'takers_count' => 38,
            'completed_count' => 35,
            'average_score' => 92.1,
            'created_at' => '2025-10-03'
        ],
        [
            'id' => 5,
            'title' => 'Pop Quiz - Object-Oriented Programming',
            'description' => 'Quick assessment on OOP principles and design patterns',
            'status' => 'archived',
            'start_time' => '2025-09-28 11:00:00',
            'end_time' => '2025-09-28 11:30:00',
            'duration' => 30,
            'total_items' => 15,
            'total_points' => 20,
            'takers_count' => 40,
            'completed_count' => 40,
            'average_score' => 88.7,
            'created_at' => '2025-09-25'
        ]
    ];

    $statusFilter = request('status', 'all');
@endphp

<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">
                        <i class="fas fa-file-alt text-indigo-600 mr-3"></i>My Exams
                    </h1>
                    <p class="text-gray-600 mt-2">Create, manage, and monitor your examinations</p>
                </div>
                <a href="{{ route('teacher.exams.create') }}" class="px-6 py-3 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition duration-200 flex items-center space-x-2 shadow-md">
                    <i class="fas fa-plus-circle"></i>
                    <span>Create New Exam</span>
                </a>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                <!-- Status Filter -->
                <div class="flex items-center space-x-2">
                    <label class="text-sm font-medium text-gray-700">Filter by Status:</label>
                    <div class="flex space-x-2">
                        <a href="?status=all" class="px-4 py-2 rounded-lg text-sm font-medium transition duration-200 {{ $statusFilter === 'all' ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            All
                        </a>
                        <a href="?status=published" class="px-4 py-2 rounded-lg text-sm font-medium transition duration-200 {{ $statusFilter === 'published' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            Published
                        </a>
                        <a href="?status=draft" class="px-4 py-2 rounded-lg text-sm font-medium transition duration-200 {{ $statusFilter === 'draft' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            Draft
                        </a>
                        <a href="?status=archived" class="px-4 py-2 rounded-lg text-sm font-medium transition duration-200 {{ $statusFilter === 'archived' ? 'bg-gray-100 text-gray-700' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            Archived
                        </a>
                    </div>
                </div>

                <!-- Search -->
                <div class="relative">
                    <input
                        type="text"
                        placeholder="Search exams..."
                        class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 w-64"
                    />
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>
        </div>

        <!-- Exams Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach($exams as $exam)
                @if($statusFilter === 'all' || $exam['status'] === $statusFilter)
                <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition duration-300 overflow-hidden">
                    <!-- Exam Header -->
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-2">
                                    <h3 class="text-xl font-bold text-gray-900">{{ $exam['title'] }}</h3>
                                    @if($exam['status'] === 'published')
                                        <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">
                                            <i class="fas fa-check-circle"></i> Published
                                        </span>
                                    @elseif($exam['status'] === 'draft')
                                        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded-full">
                                            <i class="fas fa-pencil-alt"></i> Draft
                                        </span>
                                    @else
                                        <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs font-semibold rounded-full">
                                            <i class="fas fa-archive"></i> Archived
                                        </span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-600">{{ $exam['description'] }}</p>
                            </div>
                        </div>

                        <!-- Exam Details -->
                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-calendar text-indigo-600 mr-2"></i>
                                <span>{{ date('M d, Y g:i A', strtotime($exam['start_time'])) }}</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-clock text-indigo-600 mr-2"></i>
                                <span>{{ $exam['duration'] }} minutes</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-question-circle text-indigo-600 mr-2"></i>
                                <span>{{ $exam['total_items'] }} questions</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-star text-indigo-600 mr-2"></i>
                                <span>{{ $exam['total_points'] }} points</span>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics -->
                    @if($exam['status'] === 'published' || $exam['status'] === 'archived')
                    <div class="bg-gray-50 px-6 py-4">
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-center">
                                <p class="text-xs text-gray-500 mb-1">Takers</p>
                                <p class="text-lg font-bold text-gray-900">{{ $exam['takers_count'] }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-xs text-gray-500 mb-1">Completed</p>
                                <p class="text-lg font-bold text-green-600">{{ $exam['completed_count'] }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-xs text-gray-500 mb-1">Avg Score</p>
                                <p class="text-lg font-bold text-indigo-600">{{ $exam['average_score'] }}%</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Actions -->
                    <div class="px-6 py-4 bg-white border-t border-gray-200">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-500">
                                <i class="fas fa-calendar-plus mr-1"></i>Created {{ date('M d, Y', strtotime($exam['created_at'])) }}
                            </span>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('teacher.exams.show', $exam['id']) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition duration-200">
                                    <i class="fas fa-eye mr-1"></i>View
                                </a>
                                @if($exam['status'] === 'draft')
                                <a href="{{ route('teacher.exams.edit', $exam['id']) }}" class="px-4 py-2 bg-yellow-600 text-white rounded-lg text-sm font-medium hover:bg-yellow-700 transition duration-200">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </a>
                                @endif
                                <button class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 transition duration-200">
                                    <i class="fas fa-trash mr-1"></i>Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            @endforeach
        </div>

        @if(count(array_filter($exams, fn($e) => $statusFilter === 'all' || $e['status'] === $statusFilter)) === 0)
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-md p-12 text-center">
            <div class="bg-gray-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-folder-open text-4xl text-gray-400"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">No Exams Found</h3>
            <p class="text-gray-600 mb-6">You haven't created any exams yet. Get started by creating your first exam.</p>
            <a href="{{ route('teacher.exams.create') }}" class="inline-flex items-center space-x-2 px-6 py-3 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition duration-200">
                <i class="fas fa-plus-circle"></i>
                <span>Create Your First Exam</span>
            </a>
        </div>
        @endif
    </div>
</div>

@endsection
