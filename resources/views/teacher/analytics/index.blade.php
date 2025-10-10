@extends('teacher.shell')

@section('teacher-content')

<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">
                <i class="fas fa-chart-line text-indigo-600 mr-3"></i>Analytics & Insights
            </h1>
            <p class="text-gray-600 mt-2">Track performance, analyze trends, and gain insights from your exams</p>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Exams -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Exams</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalExams }}</p>
                        <p class="text-sm text-gray-500 mt-1">
                            <span class="text-green-600">{{ $publishedExams }}</span> published
                        </p>
                    </div>
                    <div class="bg-blue-100 w-14 h-14 rounded-full flex items-center justify-center">
                        <i class="fas fa-file-alt text-2xl text-blue-600"></i>
                    </div>
                </div>
            </div>

            <!-- Total Students -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Students</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalStudents }}</p>
                        <p class="text-sm text-gray-500 mt-1">Unique takers</p>
                    </div>
                    <div class="bg-green-100 w-14 h-14 rounded-full flex items-center justify-center">
                        <i class="fas fa-users text-2xl text-green-600"></i>
                    </div>
                </div>
            </div>

            <!-- Total Submissions -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Submissions</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalSubmissions }}</p>
                        <p class="text-sm text-gray-500 mt-1">Completed exams</p>
                    </div>
                    <div class="bg-purple-100 w-14 h-14 rounded-full flex items-center justify-center">
                        <i class="fas fa-check-circle text-2xl text-purple-600"></i>
                    </div>
                </div>
            </div>

            <!-- Pending Grading -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-orange-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Pending Grading</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $pendingGradingCount }}</p>
                        <p class="text-sm text-gray-500 mt-1">Needs review</p>
                    </div>
                    <div class="bg-orange-100 w-14 h-14 rounded-full flex items-center justify-center">
                        <i class="fas fa-clock text-2xl text-orange-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Recent Exam Performance -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">
                    <i class="fas fa-chart-bar text-indigo-600 mr-2"></i>Recent Exam Performance
                </h2>
                <div style="height: 300px;">
                    <canvas id="performanceChart"></canvas>
                </div>
            </div>

            <!-- Question Type Distribution -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">
                    <i class="fas fa-chart-pie text-indigo-600 mr-2"></i>Question Type Distribution
                </h2>
                <div style="height: 300px;">
                    <canvas id="questionTypeChart"></canvas>
                </div>
            </div>

            <!-- Difficulty Distribution -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">
                    <i class="fas fa-layer-group text-indigo-600 mr-2"></i>Difficulty Distribution
                </h2>
                <div style="height: 300px;">
                    <canvas id="difficultyChart"></canvas>
                </div>
            </div>

            <!-- Top Performing Exams -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">
                    <i class="fas fa-trophy text-yellow-500 mr-2"></i>Top Performing Exams
                </h2>
                <div class="space-y-3">
                    @forelse($topPerformingExams as $exam)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div class="flex-1">
                                <a href="{{ route('teacher.exams.show', $exam['id']) }}" class="font-medium text-gray-900 hover:text-indigo-600">
                                    {{ $exam['title'] }}
                                </a>
                                <p class="text-sm text-gray-600">{{ $exam['submissions'] }} submissions</p>
                            </div>
                            <div class="ml-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                                    @if($exam['average_percentage'] >= 80) bg-green-100 text-green-700
                                    @elseif($exam['average_percentage'] >= 70) bg-blue-100 text-blue-700
                                    @elseif($exam['average_percentage'] >= 60) bg-yellow-100 text-yellow-700
                                    @else bg-red-100 text-red-700
                                    @endif">
                                    {{ $exam['average_percentage'] }}%
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-8">No data available yet</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-xl shadow-md">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900">
                    <i class="fas fa-history text-indigo-600 mr-2"></i>Recent Activity
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Exam</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($recentActivity as $activity)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-900">{{ $activity['student_name'] }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $activity['exam_title'] }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $activity['score'] }}/{{ $activity['total'] }}
                                        <span class="text-gray-500">({{ $activity['percentage'] }}%)</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($activity['status'] === 'graded')
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Graded
                                        </span>
                                    @else
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $activity['submitted_at']->diffForHumans() }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    No recent activity
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
// Performance Chart
const performanceCtx = document.getElementById('performanceChart').getContext('2d');
new Chart(performanceCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($recentExamPerformance->pluck('short_title')) !!},
        datasets: [{
            label: 'Average Score (%)',
            data: {!! json_encode($recentExamPerformance->pluck('average_percentage')) !!},
            backgroundColor: 'rgba(99, 102, 241, 0.5)',
            borderColor: 'rgba(99, 102, 241, 1)',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        aspectRatio: 2,
        scales: {
            y: {
                beginAtZero: true,
                max: 100,
                ticks: {
                    callback: function(value) {
                        return value + '%';
                    }
                }
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});

// Question Type Chart
const questionTypeCtx = document.getElementById('questionTypeChart').getContext('2d');
const questionTypeLabels = {
    'mcq': 'Multiple Choice',
    'truefalse': 'True/False',
    'fillblank': 'Fill in Blank',
    'shortanswer': 'Short Answer',
    'essay': 'Essay',
    'matching': 'Matching'
};
const questionTypes = {!! json_encode($questionTypeDistribution) !!};
new Chart(questionTypeCtx, {
    type: 'doughnut',
    data: {
        labels: Object.keys(questionTypes).map(type => questionTypeLabels[type] || type),
        datasets: [{
            data: Object.values(questionTypes),
            backgroundColor: [
                'rgba(59, 130, 246, 0.8)',
                'rgba(16, 185, 129, 0.8)',
                'rgba(245, 158, 11, 0.8)',
                'rgba(239, 68, 68, 0.8)',
                'rgba(139, 92, 246, 0.8)',
                'rgba(236, 72, 153, 0.8)'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        aspectRatio: 1.5,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Difficulty Chart
const difficultyCtx = document.getElementById('difficultyChart').getContext('2d');
const difficultyData = {!! json_encode($difficultyDistribution) !!};
new Chart(difficultyCtx, {
    type: 'pie',
    data: {
        labels: ['Easy', 'Moderate', 'Difficult'],
        datasets: [{
            data: [
                difficultyData['easy'] || 0,
                difficultyData['moderate'] || 0,
                difficultyData['difficult'] || 0
            ],
            backgroundColor: [
                'rgba(16, 185, 129, 0.8)',
                'rgba(245, 158, 11, 0.8)',
                'rgba(239, 68, 68, 0.8)'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        aspectRatio: 1.5,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>

@endsection
