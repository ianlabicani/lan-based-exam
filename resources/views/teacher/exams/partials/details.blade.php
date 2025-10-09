<div class="space-y-6">
    <!-- Exam Information -->
    <div class="bg-gray-50 rounded-lg p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">
            <i class="fas fa-info-circle text-indigo-600 mr-2"></i>Exam Information
        </h3>
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <dt class="text-sm font-medium text-gray-500 mb-1">
                    <i class="fas fa-calendar mr-1"></i>Start Date & Time
                </dt>
                <dd class="text-lg font-semibold text-gray-900">
                    {{ date('l, F j, Y - g:i A', strtotime($exam['start_time'])) }}
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500 mb-1">
                    <i class="fas fa-calendar-times mr-1"></i>End Date & Time
                </dt>
                <dd class="text-lg font-semibold text-gray-900">
                    {{ date('l, F j, Y - g:i A', strtotime($exam['end_time'])) }}
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500 mb-1">
                    <i class="fas fa-clock mr-1"></i>Duration
                </dt>
                <dd class="text-lg font-semibold text-gray-900">
                    {{ $exam['duration'] }} minutes
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500 mb-1">
                    <i class="fas fa-star mr-1"></i>Total Points
                </dt>
                <dd class="text-lg font-semibold text-gray-900">
                    {{ $exam['total_points'] }} points
                </dd>
            </div>
        </dl>
    </div>

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
