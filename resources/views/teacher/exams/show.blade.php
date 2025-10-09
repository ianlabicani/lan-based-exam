@extends('teacher.shell')

@section('teacher-content')

@php
    // Mock exam data
    $exam = [
        'id' => 1,
        'title' => 'Midterm Examination - Data Structures',
        'description' => 'Comprehensive midterm covering arrays, linked lists, stacks, queues, and trees',
        'instructions' => 'Please read all questions carefully. Answer all questions to the best of your ability. Tab switching during the exam will be monitored.',
        'status' => 'published',
        'start_time' => '2025-10-15 09:00:00',
        'end_time' => '2025-10-15 12:00:00',
        'duration' => 120,
        'total_items' => 10,
        'total_points' => 100,
        'shuffle_questions' => true,
        'show_results' => false,
        'monitor_activity' => true,
        'takers_count' => 45,
        'completed_count' => 38,
        'average_score' => 85.5,
        'created_at' => '2025-10-01'
    ];

    $examItems = [
        [
            'id' => 1,
            'type' => 'multiple_choice',
            'question' => 'What is the time complexity of accessing an element in an array by index?',
            'points' => 5,
            'order' => 1,
            'options' => ['O(1)', 'O(n)', 'O(log n)', 'O(n²)'],
            'correct_answer' => 'O(1)'
        ],
        [
            'id' => 2,
            'type' => 'true_false',
            'question' => 'A stack follows the LIFO (Last In First Out) principle.',
            'points' => 3,
            'order' => 2,
            'correct_answer' => true
        ],
        [
            'id' => 3,
            'type' => 'short_answer',
            'question' => 'Explain the difference between a stack and a queue in 2-3 sentences.',
            'points' => 10,
            'order' => 3,
            'max_length' => 500
        ],
        [
            'id' => 4,
            'type' => 'essay',
            'question' => 'Discuss the advantages and disadvantages of using linked lists compared to arrays. Provide examples of scenarios where each data structure would be most appropriate.',
            'points' => 20,
            'order' => 4,
            'min_words' => 200
        ],
        [
            'id' => 5,
            'type' => 'matching',
            'question' => 'Match the data structure operation with its time complexity:',
            'points' => 8,
            'order' => 5,
            'pairs' => [
                ['Array access by index', 'O(1)'],
                ['Linear search', 'O(n)'],
                ['Binary search', 'O(log n)'],
                ['Bubble sort', 'O(n²)']
            ]
        ]
    ];

    $activeTab = request('tab', 'details');
@endphp

<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-4 mb-4">
                <a href="{{ route('teacher.exams.index') }}" class="text-gray-600 hover:text-gray-900">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div class="flex-1">
                    <div class="flex items-center space-x-3">
                        <h1 class="text-3xl font-bold text-gray-900">{{ $exam['title'] }}</h1>
                        @if($exam['status'] === 'published')
                            <span class="px-3 py-1 bg-green-100 text-green-700 text-sm font-semibold rounded-full">
                                <i class="fas fa-check-circle"></i> Published
                            </span>
                        @elseif($exam['status'] === 'draft')
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-sm font-semibold rounded-full">
                                <i class="fas fa-pencil-alt"></i> Draft
                            </span>
                        @endif
                    </div>
                    <p class="text-gray-600 mt-2">{{ $exam['description'] }}</p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('teacher.exams.edit', $exam['id']) }}" class="px-4 py-2 bg-yellow-600 text-white rounded-lg font-medium hover:bg-yellow-700 transition duration-200">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                    <button class="px-4 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition duration-200">
                        <i class="fas fa-trash mr-2"></i>Delete
                    </button>
                </div>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Questions</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $exam['total_items'] }}</p>
                    </div>
                    <div class="bg-blue-100 w-12 h-12 rounded-full flex items-center justify-center">
                        <i class="fas fa-question-circle text-xl text-blue-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Takers</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $exam['takers_count'] }}</p>
                    </div>
                    <div class="bg-green-100 w-12 h-12 rounded-full flex items-center justify-center">
                        <i class="fas fa-users text-xl text-green-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Completed</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $exam['completed_count'] }}</p>
                    </div>
                    <div class="bg-purple-100 w-12 h-12 rounded-full flex items-center justify-center">
                        <i class="fas fa-check-circle text-xl text-purple-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-indigo-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Average Score</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $exam['average_score'] }}%</p>
                    </div>
                    <div class="bg-indigo-100 w-12 h-12 rounded-full flex items-center justify-center">
                        <i class="fas fa-chart-line text-xl text-indigo-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="bg-white rounded-t-xl shadow-md overflow-hidden">
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px">
                    <a href="?tab=details" class="group inline-flex items-center py-4 px-6 border-b-2 font-medium text-sm {{ $activeTab === 'details' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        <i class="fas fa-info-circle mr-2"></i>
                        Details
                    </a>
                    <a href="?tab=items" class="group inline-flex items-center py-4 px-6 border-b-2 font-medium text-sm {{ $activeTab === 'items' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        <i class="fas fa-list mr-2"></i>
                        Questions ({{ $exam['total_items'] }})
                    </a>
                    <a href="?tab=takers" class="group inline-flex items-center py-4 px-6 border-b-2 font-medium text-sm {{ $activeTab === 'takers' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        <i class="fas fa-users mr-2"></i>
                        Takers ({{ $exam['takers_count'] }})
                    </a>
                    <a href="?tab=results" class="group inline-flex items-center py-4 px-6 border-b-2 font-medium text-sm {{ $activeTab === 'results' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        <i class="fas fa-chart-bar mr-2"></i>
                        Results & Analytics
                    </a>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                @if($activeTab === 'details')
                    @include('teacher.exams.partials.details', ['exam' => $exam])
                @elseif($activeTab === 'items')
                    @include('teacher.exams.partials.items', ['exam' => $exam, 'examItems' => $examItems])
                @elseif($activeTab === 'takers')
                    @include('teacher.exams.partials.takers', ['exam' => $exam])
                @elseif($activeTab === 'results')
                    @include('teacher.exams.partials.results', ['exam' => $exam])
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
