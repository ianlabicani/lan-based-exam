{{-- Results & Analytics Tab Content --}}
<div class="space-y-6">
    @if($exam->takenExams->where('submitted_at', '!=', null)->isEmpty())
        <!-- Empty State -->
        <div class="flex flex-col items-center justify-center py-16 px-4">
            <div class="w-24 h-24 mb-6 text-gray-300">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">No Results Available</h3>
            <p class="text-gray-500 text-center max-w-md">
                No students have completed this exam yet. Results and analytics will appear here once submissions are received.
            </p>
        </div>
    @else
        @php
            // Calculate analytics for the results view
            $submittedExams = $exam->takenExams->where('submitted_at', '!=', null);
            $totalSubmitted = $submittedExams->count();
            $averageScore = $submittedExams->avg('total_points');
            $averagePercentage = $exam->total_points > 0 ? round(($averageScore / $exam->total_points) * 100, 1) : 0;
            $highestScore = $submittedExams->max('total_points');
            $lowestScore = $submittedExams->min('total_points');

            // Pass rate (75% or higher)
            $passCount = $submittedExams->filter(function($takenExam) use ($exam) {
                $percentage = $exam->total_points > 0 ? ($takenExam->total_points / $exam->total_points) * 100 : 0;
                return $percentage >= 75;
            })->count();
            $passPercentage = $totalSubmitted > 0 ? round(($passCount / $totalSubmitted) * 100, 1) : 0;

            // Score distribution
            $scoreRanges = [
                '90-100' => 0,
                '80-89' => 0,
                '70-79' => 0,
                '60-69' => 0,
                'Below 60' => 0,
            ];

            foreach ($submittedExams as $takenExam) {
                $percentage = $exam->total_points > 0 ? ($takenExam->total_points / $exam->total_points) * 100 : 0;

                if ($percentage >= 90) {
                    $scoreRanges['90-100']++;
                } elseif ($percentage >= 80) {
                    $scoreRanges['80-89']++;
                } elseif ($percentage >= 70) {
                    $scoreRanges['70-79']++;
                } elseif ($percentage >= 60) {
                    $scoreRanges['60-69']++;
                } else {
                    $scoreRanges['Below 60']++;
                }
            }

            // Top 10 performers
            $topPerformers = $submittedExams
                ->sortByDesc('total_points')
                ->take(10)
                ->map(function($takenExam) use ($exam) {
                    $percentage = $exam->total_points > 0 ? round(($takenExam->total_points / $exam->total_points) * 100, 1) : 0;
                    return [
                        'id' => $takenExam->id,
                        'name' => $takenExam->user->name,
                        'email' => $takenExam->user->email,
                        'year' => $takenExam->user->year,
                        'section' => $takenExam->user->section,
                        'score' => $takenExam->total_points,
                        'percentage' => $percentage,
                        'submitted_at' => $takenExam->submitted_at,
                        'status' => $takenExam->status,
                    ];
                })
                ->values();
        @endphp

        <!-- Overall Statistics Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Overall Statistics Card -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 border-2 border-blue-200">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-chart-line text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Overall Statistics</h3>
                        <p class="text-sm text-gray-600">Exam performance overview</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="bg-white rounded-lg p-4 border border-blue-100">
                        <p class="text-xs text-gray-600 mb-1">Total Submitted</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $totalSubmitted }}</p>
                    </div>

                    <div class="bg-white rounded-lg p-4 border border-blue-100">
                        <p class="text-xs text-gray-600 mb-1">Average Score</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $averagePercentage }}%</p>
                        <p class="text-xs text-gray-500 mt-1">{{ number_format($averageScore, 1) }} / {{ number_format($exam->total_points, 1) }}</p>
                    </div>

                    <div class="bg-white rounded-lg p-4 border border-blue-100">
                        <p class="text-xs text-gray-600 mb-1">Highest Score</p>
                        <p class="text-2xl font-bold text-green-600">{{ number_format($highestScore, 1) }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $exam->total_points > 0 ? round(($highestScore / $exam->total_points) * 100, 1) : 0 }}%</p>
                    </div>

                    <div class="bg-white rounded-lg p-4 border border-blue-100">
                        <p class="text-xs text-gray-600 mb-1">Lowest Score</p>
                        <p class="text-2xl font-bold text-orange-600">{{ number_format($lowestScore, 1) }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $exam->total_points > 0 ? round(($lowestScore / $exam->total_points) * 100, 1) : 0 }}%</p>
                    </div>
                </div>

                <!-- Pass Rate -->
                <div class="bg-white rounded-lg p-4 border border-blue-100">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm font-semibold text-gray-700">Pass Rate (≥75%)</p>
                        <p class="text-2xl font-bold text-green-600">{{ $passPercentage }}%</p>
                    </div>
                    <p class="text-xs text-gray-500">{{ $passCount }} out of {{ $totalSubmitted }} students passed</p>
                    <div class="mt-2 bg-gray-200 rounded-full h-3 overflow-hidden">
                        <div class="bg-gradient-to-r from-green-500 to-green-600 h-full transition-all duration-300"
                             style="width: {{ $passPercentage }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Score Distribution Card -->
            <div class="bg-white rounded-xl p-6 border-2 border-gray-200">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-chart-bar text-purple-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Score Distribution</h3>
                        <p class="text-sm text-gray-600">Student performance breakdown</p>
                    </div>
                </div>

                <div class="space-y-3">
                    @foreach($scoreRanges as $range => $count)
                        @php
                            $percentage = $totalSubmitted > 0 ? round(($count / $totalSubmitted) * 100) : 0;
                            $colorClass = match($range) {
                                '90-100' => 'bg-green-500',
                                '80-89' => 'bg-blue-500',
                                '70-79' => 'bg-yellow-500',
                                '60-69' => 'bg-orange-500',
                                default => 'bg-red-500',
                            };
                            $textColorClass = match($range) {
                                '90-100' => 'text-green-700',
                                '80-89' => 'text-blue-700',
                                '70-79' => 'text-yellow-700',
                                '60-69' => 'text-orange-700',
                                default => 'text-red-700',
                            };
                        @endphp
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm font-medium {{ $textColorClass }}">{{ $range }}%</span>
                                <span class="text-sm font-bold text-gray-600">{{ $count }} students ({{ $percentage }}%)</span>
                            </div>
                            <div class="bg-gray-200 rounded-full h-6 overflow-hidden">
                                <div class="{{ $colorClass }} h-full flex items-center justify-end pr-2 transition-all duration-300"
                                     style="width: {{ $percentage }}%">
                                    @if($count > 0)
                                        <span class="text-xs font-semibold text-white">{{ $count }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Top Performers Section -->
        <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-6 border-2 border-green-200">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-green-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-trophy text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Top 10 Performers</h3>
                        <p class="text-sm text-gray-600">Highest scoring students</p>
                    </div>
                </div>
            </div>

            @if($topPerformers->count() > 0)
                <!-- Top 3 Podium -->
                @if($topPerformers->count() >= 3)
                <div class="grid grid-cols-3 gap-4 mb-6">
                    @foreach($topPerformers->take(3) as $index => $performer)
                        <div class="text-center {{ $index === 0 ? 'order-2' : ($index === 1 ? 'order-1' : 'order-3') }}">
                            <div class="mb-3">
                                @if($index === 0)
                                    <div class="w-20 h-20 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-full flex items-center justify-center mx-auto shadow-lg mb-2">
                                        <i class="fas fa-crown text-white text-2xl"></i>
                                    </div>
                                    <div class="inline-block px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-bold">
                                        1st Place
                                    </div>
                                @elseif($index === 1)
                                    <div class="w-16 h-16 bg-gradient-to-br from-gray-300 to-gray-500 rounded-full flex items-center justify-center mx-auto shadow-lg mb-2 mt-4">
                                        <span class="text-white font-bold text-xl">2</span>
                                    </div>
                                    <div class="inline-block px-3 py-1 bg-gray-200 text-gray-800 rounded-full text-xs font-bold">
                                        2nd Place
                                    </div>
                                @else
                                    <div class="w-16 h-16 bg-gradient-to-br from-orange-400 to-orange-600 rounded-full flex items-center justify-center mx-auto shadow-lg mb-2 mt-4">
                                        <span class="text-white font-bold text-xl">3</span>
                                    </div>
                                    <div class="inline-block px-3 py-1 bg-orange-100 text-orange-800 rounded-full text-xs font-bold">
                                        3rd Place
                                    </div>
                                @endif
                            </div>
                            <div class="bg-white rounded-lg p-4 shadow-md border-2 {{ $index === 0 ? 'border-yellow-300' : ($index === 1 ? 'border-gray-300' : 'border-orange-300') }}">
                                <p class="font-bold text-gray-900 truncate">{{ $performer['name'] }}</p>
                                <p class="text-xs text-gray-500 truncate mb-2">{{ $performer['email'] }}</p>
                                <p class="text-2xl font-bold {{ $index === 0 ? 'text-yellow-600' : ($index === 1 ? 'text-gray-600' : 'text-orange-600') }}">
                                    {{ $performer['percentage'] }}%
                                </p>
                                <p class="text-xs text-gray-600 mt-1">{{ number_format($performer['score'], 1) }} pts</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                @endif

                <!-- Remaining Top Performers (4-10) -->
                @if($topPerformers->count() > 3)
                <div class="bg-white rounded-lg border border-green-100 overflow-hidden">
                    <div class="divide-y divide-gray-100">
                        @foreach($topPerformers->slice(3) as $index => $performer)
                            <div class="p-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div class="flex-shrink-0 w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                                        <span class="text-gray-600 font-bold">{{ $index + 4 }}</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-gray-900 truncate">{{ $performer['name'] }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ $performer['email'] }}</p>
                                        @if($performer['year'] && $performer['section'])
                                            <p class="text-xs text-gray-500 mt-0.5">
                                                Year {{ $performer['year'] }} - Section {{ strtoupper($performer['section']) }}
                                            </p>
                                        @endif
                                    </div>
                                    <div class="text-right flex-shrink-0">
                                        <p class="text-xl font-bold text-green-600">{{ $performer['percentage'] }}%</p>
                                        <p class="text-xs text-gray-500">{{ number_format($performer['score'], 1) }} pts</p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <a href="{{ route('teacher.exams.takenExams.show', [$exam->id, $performer['id']]) }}"
                                           class="px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            @else
                <p class="text-gray-500 text-center py-8 italic">No submissions yet</p>
            @endif
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl p-6 border-2 border-gray-200">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-tasks text-indigo-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Quick Actions</h3>
                    <p class="text-sm text-gray-600">Manage exam results</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="?tab=takers" class="block p-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg border-2 border-blue-200 hover:border-blue-400 transition-all">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-users text-white"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">View All Takers</p>
                            <p class="text-xs text-gray-600">Manage submissions</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('teacher.exams.index') }}" class="block p-4 bg-gradient-to-br from-purple-50 to-pink-50 rounded-lg border-2 border-purple-200 hover:border-purple-400 transition-all">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-purple-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-clipboard-list text-white"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">All Exams</p>
                            <p class="text-xs text-gray-600">Back to exam list</p>
                        </div>
                    </div>
                </a>

                <button onclick="window.print()" class="block p-4 bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg border-2 border-green-200 hover:border-green-400 transition-all text-left">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-print text-white"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Print Results</p>
                            <p class="text-xs text-gray-600">Print this report</p>
                        </div>
                    </div>
                </button>
            </div>
        </div>
    @endif
</div>
