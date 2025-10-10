@extends('teacher.shell')

@section('teacher-content')

<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <a href="{{ route('teacher.analytics.index') }}" class="text-indigo-600 hover:text-indigo-800 mb-2 inline-flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Analytics
                    </a>
                    <h1 class="text-3xl font-bold text-gray-900 mt-2">
                        <i class="fas fa-chart-line text-indigo-600 mr-3"></i>{{ $exam->title }}
                    </h1>
                    <p class="text-gray-600 mt-2">Detailed analytics and performance insights</p>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold
                        @if($exam->status === 'ongoing') bg-green-100 text-green-800
                        @elseif($exam->status === 'published') bg-blue-100 text-blue-800
                        @elseif($exam->status === 'closed') bg-gray-100 text-gray-800
                        @else bg-yellow-100 text-yellow-800
                        @endif">
                        {{ ucfirst($exam->status) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Submissions -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Submissions</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalSubmissions }}</p>
                    </div>
                    <div class="bg-blue-100 w-14 h-14 rounded-full flex items-center justify-center">
                        <i class="fas fa-users text-2xl text-blue-600"></i>
                    </div>
                </div>
            </div>

            <!-- Average Score -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Average Score</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($averagePercentage, 1) }}%</p>
                        <p class="text-sm text-gray-500 mt-1">{{ number_format($averageScore, 1) }}/{{ $exam->total_points }} pts</p>
                    </div>
                    <div class="bg-purple-100 w-14 h-14 rounded-full flex items-center justify-center">
                        <i class="fas fa-chart-bar text-2xl text-purple-600"></i>
                    </div>
                </div>
            </div>

            <!-- Highest Score -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Highest Score</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($highestScore, 1) }}</p>
                        <p class="text-sm text-gray-500 mt-1">{{ number_format(($highestScore / $exam->total_points) * 100, 1) }}%</p>
                    </div>
                    <div class="bg-green-100 w-14 h-14 rounded-full flex items-center justify-center">
                        <i class="fas fa-trophy text-2xl text-green-600"></i>
                    </div>
                </div>
            </div>

            <!-- Lowest Score -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-orange-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Lowest Score</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($lowestScore, 1) }}</p>
                        <p class="text-sm text-gray-500 mt-1">{{ number_format(($lowestScore / $exam->total_points) * 100, 1) }}%</p>
                    </div>
                    <div class="bg-orange-100 w-14 h-14 rounded-full flex items-center justify-center">
                        <i class="fas fa-arrow-down text-2xl text-orange-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Score Distribution -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">
                    <i class="fas fa-chart-pie text-indigo-600 mr-2"></i>Score Distribution
                </h2>
                <div style="height: 300px;">
                    <canvas id="scoreDistributionChart"></canvas>
                </div>
                <div class="mt-4 space-y-2">
                    @foreach(['90-100%' => 'green', '80-89%' => 'blue', '70-79%' => 'yellow', '60-69%' => 'orange', 'Below 60%' => 'red'] as $range => $color)
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-700">{{ $range }}</span>
                            <span class="font-semibold text-{{ $color }}-600">
                                {{ $scoreDistribution[$range] ?? 0 }} student(s)
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Question Success Rate -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">
                    <i class="fas fa-percentage text-indigo-600 mr-2"></i>Question Success Rates
                </h2>
                <div style="height: 300px;">
                    <canvas id="questionSuccessChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Question Analysis Table -->
        <div class="bg-white rounded-xl shadow-md">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900">
                    <i class="fas fa-table text-indigo-600 mr-2"></i>Detailed Question Analysis
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Question</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Difficulty</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Answers</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Correct</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Success Rate</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg Points</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($questionAnalysis as $index => $question)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 max-w-md">
                                        {{ Str::limit(strip_tags($question['question']), 80) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($question['type'] === 'mcq') bg-blue-100 text-blue-800
                                        @elseif($question['type'] === 'truefalse') bg-green-100 text-green-800
                                        @elseif($question['type'] === 'essay') bg-purple-100 text-purple-800
                                        @elseif($question['type'] === 'shortanswer') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ strtoupper($question['type']) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if(($question['level'] ?? '') === 'easy') bg-green-100 text-green-800
                                        @elseif(($question['level'] ?? '') === 'moderate') bg-yellow-100 text-yellow-800
                                        @elseif(($question['level'] ?? '') === 'difficult') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($question['level'] ?? 'N/A') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $question['total_answers'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $question['correct_count'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-full bg-gray-200 rounded-full h-2 mr-2" style="min-width: 60px;">
                                            <div class="h-2 rounded-full
                                                @if($question['success_rate'] >= 80) bg-green-500
                                                @elseif($question['success_rate'] >= 60) bg-blue-500
                                                @elseif($question['success_rate'] >= 40) bg-yellow-500
                                                @else bg-red-500
                                                @endif"
                                                style="width: {{ $question['success_rate'] }}%"></div>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-900 whitespace-nowrap">
                                            {{ number_format($question['success_rate'], 1) }}%
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($question['average_points'], 1) }}/{{ $question['max_points'] }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                    No question data available
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 flex justify-center space-x-4">
            <a href="{{ route('teacher.exams.show', $exam->id) }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-eye mr-2"></i> View Exam
            </a>
            <a href="{{ route('teacher.taken-exams.index', $exam->id) }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-users mr-2"></i> View Submissions
            </a>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
// Score Distribution Chart
const scoreDistCtx = document.getElementById('scoreDistributionChart').getContext('2d');
const scoreDistData = @json($scoreDistribution);
new Chart(scoreDistCtx, {
    type: 'doughnut',
    data: {
        labels: ['90-100%', '80-89%', '70-79%', '60-69%', 'Below 60%'],
        datasets: [{
            data: [
                scoreDistData['90-100%'] || 0,
                scoreDistData['80-89%'] || 0,
                scoreDistData['70-79%'] || 0,
                scoreDistData['60-69%'] || 0,
                scoreDistData['Below 60%'] || 0
            ],
            backgroundColor: [
                'rgba(16, 185, 129, 0.8)',   // Green
                'rgba(59, 130, 246, 0.8)',   // Blue
                'rgba(245, 158, 11, 0.8)',   // Yellow
                'rgba(249, 115, 22, 0.8)',   // Orange
                'rgba(239, 68, 68, 0.8)'     // Red
            ],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        aspectRatio: 1.5,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 15,
                    font: {
                        size: 12
                    }
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const label = context.label || '';
                        const value = context.parsed || 0;
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                        return `${label}: ${value} student(s) (${percentage}%)`;
                    }
                }
            }
        }
    }
});

// Question Success Rate Chart
const questionSuccessCtx = document.getElementById('questionSuccessChart').getContext('2d');
const questionData = @json($questionAnalysis);
new Chart(questionSuccessCtx, {
    type: 'bar',
    data: {
        labels: questionData.map((q, i) => `Q${i + 1}`),
        datasets: [{
            label: 'Success Rate (%)',
            data: questionData.map(q => q.success_rate),
            backgroundColor: questionData.map(q => {
                if (q.success_rate >= 80) return 'rgba(16, 185, 129, 0.6)';
                if (q.success_rate >= 60) return 'rgba(59, 130, 246, 0.6)';
                if (q.success_rate >= 40) return 'rgba(245, 158, 11, 0.6)';
                return 'rgba(239, 68, 68, 0.6)';
            }),
            borderColor: questionData.map(q => {
                if (q.success_rate >= 80) return 'rgba(16, 185, 129, 1)';
                if (q.success_rate >= 60) return 'rgba(59, 130, 246, 1)';
                if (q.success_rate >= 40) return 'rgba(245, 158, 11, 1)';
                return 'rgba(239, 68, 68, 1)';
            }),
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        aspectRatio: 1.5,
        scales: {
            y: {
                beginAtZero: true,
                max: 100,
                ticks: {
                    callback: function(value) {
                        return value + '%';
                    }
                }
            },
            x: {
                ticks: {
                    maxRotation: 45,
                    minRotation: 45
                }
            }
        },
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    title: function(context) {
                        const index = context[0].dataIndex;
                        const question = questionData[index];
                        return `Question ${index + 1}`;
                    },
                    label: function(context) {
                        return `Success Rate: ${context.parsed.y.toFixed(1)}%`;
                    },
                    afterLabel: function(context) {
                        const index = context.dataIndex;
                        const question = questionData[index];
                        return [
                            `Type: ${question.type.toUpperCase()}`,
                            `Correct: ${question.correct_count}/${question.total_answers}`,
                            `Avg Points: ${question.average_points.toFixed(1)}/${question.max_points}`
                        ];
                    }
                }
            }
        }
    }
});
</script>

@endsection
