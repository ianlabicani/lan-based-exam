<div class="space-y-6">
    <!-- Exam Information -->
    <div class="bg-gray-50 rounded-lg p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">
            <i class="fas fa-info-circle text-indigo-600 mr-2"></i>Exam Information
        </h3>
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <dt class="text-sm font-medium text-gray-500 mb-1">
                    <i class="fas fa-graduation-cap mr-1"></i>Year Level(s)
                </dt>
                <dd class="text-lg font-semibold text-gray-900">
                    @if(is_array($exam->year) && count($exam->year) > 0)
                        <div class="flex flex-wrap gap-2">
                            @foreach($exam->year as $year)
                                <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-sm font-semibold rounded-full">
                                    Year {{ $year }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <span class="text-gray-400">Not specified</span>
                    @endif
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500 mb-1">
                    <i class="fas fa-users-class mr-1"></i>Section(s)
                </dt>
                <dd class="text-lg font-semibold text-gray-900">
                    @if(is_array($exam->sections) && count($exam->sections) > 0)
                        <div class="flex flex-wrap gap-2">
                            @foreach($exam->sections as $section)
                                <span class="px-3 py-1 bg-green-100 text-green-700 text-sm font-semibold rounded-full uppercase">
                                    {{ $section }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <span class="text-gray-400">Not specified</span>
                    @endif
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500 mb-1">
                    <i class="fas fa-calendar mr-1"></i>Start Date & Time
                </dt>
                <dd class="text-lg font-semibold text-gray-900">
                    {{ $exam->starts_at ? $exam->starts_at->format('l, F j, Y - g:i A') : 'Not set' }}
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500 mb-1">
                    <i class="fas fa-calendar-times mr-1"></i>End Date & Time
                </dt>
                <dd class="text-lg font-semibold text-gray-900">
                    {{ $exam->ends_at ? $exam->ends_at->format('l, F j, Y - g:i A') : 'Not set' }}
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500 mb-1">
                    <i class="fas fa-clock mr-1"></i>Duration
                </dt>
                <dd class="text-lg font-semibold text-gray-900">
                    @if($exam->starts_at && $exam->ends_at)
                        {{ $exam->starts_at->diffInMinutes($exam->ends_at) }} minutes
                    @else
                        Not calculated
                    @endif
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500 mb-1">
                    <i class="fas fa-star mr-1"></i>Total Points
                </dt>
                <dd class="text-lg font-semibold text-gray-900">
                    {{ $exam->total_points }} points
                </dd>
            </div>
        </dl>
    </div>

    <!-- Table of Specifications -->
    @if(!empty($exam['tos']))
    @php
        $tosData = is_string($exam['tos']) ? json_decode($exam['tos'], true) : $exam['tos'];
    @endphp
    @if($tosData && is_array($tosData))
    <div class="bg-gray-50 rounded-lg p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">
            <i class="fas fa-table text-indigo-600 mr-2"></i>Table of Specifications
        </h3>
        <div class="space-y-4">
            @foreach($tosData as $index => $topic)
            <div class="bg-white rounded-lg p-4 border border-gray-200">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <h4 class="text-md font-bold text-gray-900 mb-2">
                            <i class="fas fa-book-open text-indigo-500 mr-2"></i>{{ $topic['topic'] ?? 'Topic ' . ($index + 1) }}
                        </h4>
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div>
                                <span class="text-gray-500">Time Allotment:</span>
                                <span class="font-semibold text-gray-900">{{ $topic['time_allotment'] ?? 0 }} hours</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Number of Items:</span>
                                <span class="font-semibold text-gray-900">{{ $topic['no_of_items'] ?? 0 }} questions</span>
                            </div>
                        </div>
                    </div>
                </div>

                @if(isset($topic['distribution']))
                <div class="mt-3 pt-3 border-t border-gray-200">
                    <p class="text-xs font-medium text-gray-500 uppercase mb-2">Difficulty Distribution</p>
                    <div class="flex items-center space-x-3">
                        @if(isset($topic['distribution']['easy']))
                        <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-full">
                            <i class="fas fa-signal mr-1"></i>Easy (30%): {{ $topic['distribution']['easy']['allocation'] ?? 0 }}
                        </span>
                        @endif
                        @if(isset($topic['distribution']['average']))
                        <span class="px-3 py-1 bg-amber-100 text-amber-700 text-xs font-semibold rounded-full">
                            <i class="fas fa-signal mr-1"></i>Average (50%): {{ $topic['distribution']['average']['allocation'] ?? 0 }}
                        </span>
                        @endif
                        @if(isset($topic['distribution']['moderate']))
                        <span class="px-3 py-1 bg-amber-100 text-amber-700 text-xs font-semibold rounded-full">
                            <i class="fas fa-signal mr-1"></i>Average (50%): {{ $topic['distribution']['moderate']['allocation'] ?? 0 }}
                        </span>
                        @endif
                        @if(isset($topic['distribution']['difficult']))
                        <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-full">
                            <i class="fas fa-signal mr-1"></i>Difficult (20%): {{ $topic['distribution']['difficult']['allocation'] ?? 0 }}
                        </span>
                        @endif
                    </div>
                </div>
                @endif

                @if(!empty($topic['outcomes']))
                <div class="mt-3 pt-3 border-t border-gray-200">
                    <p class="text-xs font-medium text-gray-500 uppercase mb-2">Learning Outcomes</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($topic['outcomes'] as $outcome)
                        <li class="text-sm text-gray-700">{{ $outcome }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif
    @endif

    <!-- Instructions -->
    <div class="bg-gray-50 rounded-lg p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">
            <i class="fas fa-list-ol text-indigo-600 mr-2"></i>Instructions for Students
        </h3>
        <p class="text-gray-700 whitespace-pre-line">{{ $exam['instructions'] }}</p>
    </div>

    <!-- Settings -->
    <div class="bg-gray-50 rounded-lg p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">
            <i class="fas fa-cog text-indigo-600 mr-2"></i>Exam Settings
        </h3>
        <div class="space-y-3">
            <div class="flex items-center justify-between p-3 bg-white rounded-lg">
                <span class="text-gray-700 font-medium">Shuffle Questions</span>
                @if($exam['shuffle_questions'])
                    <span class="px-3 py-1 bg-green-100 text-green-700 text-sm font-medium rounded-full">
                        <i class="fas fa-check mr-1"></i>Enabled
                    </span>
                @else
                    <span class="px-3 py-1 bg-gray-100 text-gray-700 text-sm font-medium rounded-full">
                        <i class="fas fa-times mr-1"></i>Disabled
                    </span>
                @endif
            </div>
            <div class="flex items-center justify-between p-3 bg-white rounded-lg">
                <span class="text-gray-700 font-medium">Show Results Immediately</span>
                @if($exam['show_results'])
                    <span class="px-3 py-1 bg-green-100 text-green-700 text-sm font-medium rounded-full">
                        <i class="fas fa-check mr-1"></i>Enabled
                    </span>
                @else
                    <span class="px-3 py-1 bg-gray-100 text-gray-700 text-sm font-medium rounded-full">
                        <i class="fas fa-times mr-1"></i>Disabled
                    </span>
                @endif
            </div>
            <div class="flex items-center justify-between p-3 bg-white rounded-lg">
                <span class="text-gray-700 font-medium">Activity Monitoring</span>
                @if($exam['monitor_activity'])
                    <span class="px-3 py-1 bg-green-100 text-green-700 text-sm font-medium rounded-full">
                        <i class="fas fa-check mr-1"></i>Enabled
                    </span>
                @else
                    <span class="px-3 py-1 bg-gray-100 text-gray-700 text-sm font-medium rounded-full">
                        <i class="fas fa-times mr-1"></i>Disabled
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Metadata -->
    <div class="bg-gray-50 rounded-lg p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">
            <i class="fas fa-database text-indigo-600 mr-2"></i>Metadata
        </h3>
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <dt class="text-sm font-medium text-gray-500">Created On</dt>
                <dd class="text-sm text-gray-900 mt-1">{{ date('F j, Y g:i A', strtotime($exam['created_at'])) }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Exam ID</dt>
                <dd class="text-sm text-gray-900 mt-1">#{{ $exam['id'] }}</dd>
            </div>
        </dl>
    </div>
</div>
