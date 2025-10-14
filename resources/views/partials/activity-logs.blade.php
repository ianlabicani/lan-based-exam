{{--
    Activity Logs Partial
    Displays exam activity logs (tab switches, window blur events)

    Required variables:
    - $activityLogs: Collection of ExamActivityLog models
    - $takenExam: TakenExam model (for context)

    Optional variables:
    - $showStudentInfo: boolean (default: false) - Show student name
    - $title: string (default: "Activity Log") - Section title
--}}

@php
    $title = $title ?? 'Activity Log';
    $showStudentInfo = $showStudentInfo ?? false;
    $activityCount = $activityLogs->count();

    // Count by event type
    $visibilityChanges = $activityLogs->where('event_type', 'visibility_change')->count();
    $windowBlurs = $activityLogs->where('event_type', 'window_blur')->count();
    $tabSwitches = $activityLogs->where('event_type', 'tab_switch')->count();

    // Determine alert level based on activity count
    $alertLevel = 'info';
    $alertBg = 'bg-blue-50 border-blue-200';
    $alertIcon = 'fa-info-circle text-blue-600';
    $alertText = 'text-blue-800';

    if ($activityCount > 10) {
        $alertLevel = 'danger';
        $alertBg = 'bg-red-50 border-red-200';
        $alertIcon = 'fa-exclamation-triangle text-red-600';
        $alertText = 'text-red-800';
    } elseif ($activityCount > 5) {
        $alertLevel = 'warning';
        $alertBg = 'bg-yellow-50 border-yellow-200';
        $alertIcon = 'fa-exclamation-circle text-yellow-600';
        $alertText = 'text-yellow-800';
    } elseif ($activityCount === 0) {
        $alertBg = 'bg-green-50 border-green-200';
        $alertIcon = 'fa-check-circle text-green-600';
        $alertText = 'text-green-800';
    }
@endphp

<div class="bg-white rounded-xl shadow-md p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-900">
            <i class="fas fa-clipboard-check text-indigo-600 mr-2"></i>{{ $title }}
        </h2>

        @if($activityCount > 0)
            <span class="px-4 py-2 rounded-full text-sm font-semibold {{ $alertBg }} {{ $alertText }}">
                <i class="fas {{ $alertIcon }} mr-1"></i>
                {{ $activityCount }} {{ Str::plural('Event', $activityCount) }}
            </span>
        @endif
    </div>

    @if($showStudentInfo && isset($takenExam->user))
        <!-- Student Info -->
        <div class="mb-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
            <div class="flex items-center">
                <i class="fas fa-user-circle text-gray-600 text-2xl mr-3"></i>
                <div>
                    <p class="font-semibold text-gray-900">{{ $takenExam->user->name }}</p>
                    <p class="text-sm text-gray-600">{{ $takenExam->user->email }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Summary Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <!-- Tab Visibility Changes -->
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-4 rounded-lg border border-purple-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-700 mb-1">Tab Changes</p>
                    <p class="text-3xl font-bold text-purple-700">{{ $visibilityChanges + $tabSwitches }}</p>
                </div>
                <i class="fas fa-eye-slash text-purple-400 text-3xl"></i>
            </div>
            <p class="text-xs text-gray-600 mt-2">Student switched or hid tab</p>
        </div>

        <!-- Window Blur Events -->
        <div class="bg-gradient-to-br from-orange-50 to-orange-100 p-4 rounded-lg border border-orange-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-700 mb-1">Focus Loss</p>
                    <p class="text-3xl font-bold text-orange-700">{{ $windowBlurs }}</p>
                </div>
                <i class="fas fa-window-minimize text-orange-400 text-3xl"></i>
            </div>
            <p class="text-xs text-gray-600 mt-2">Clicked outside browser</p>
        </div>

        <!-- Total Events -->
        <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 p-4 rounded-lg border border-indigo-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-700 mb-1">Total Events</p>
                    <p class="text-3xl font-bold text-indigo-700">{{ $activityCount }}</p>
                </div>
                <i class="fas fa-list text-indigo-400 text-3xl"></i>
            </div>
            <p class="text-xs text-gray-600 mt-2">All tracked activities</p>
        </div>
    </div>

    @if($activityCount > 0)
        <!-- Alert Message based on activity level -->
        <div class="mb-6 p-4 rounded-lg border-2 {{ $alertBg }}">
            <div class="flex items-start">
                <i class="fas {{ $alertIcon }} text-2xl mr-3 mt-1"></i>
                <div>
                    @if($alertLevel === 'danger')
                        <h3 class="font-bold {{ $alertText }} mb-1">High Activity Detected</h3>
                        <p class="text-sm {{ $alertText }}">
                            This exam session shows significant activity ({{ $activityCount }} events).
                            Frequent tab switching or focus loss may indicate potential integrity concerns.
                        </p>
                    @elseif($alertLevel === 'warning')
                        <h3 class="font-bold {{ $alertText }} mb-1">Moderate Activity Detected</h3>
                        <p class="text-sm {{ $alertText }}">
                            This exam session shows some activity ({{ $activityCount }} events).
                            Review the timeline below for details.
                        </p>
                    @else
                        <h3 class="font-bold {{ $alertText }} mb-1">Low Activity</h3>
                        <p class="text-sm {{ $alertText }}">
                            Minimal suspicious activity detected during this exam session.
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Activity Timeline -->
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-clock text-gray-600 mr-2"></i>Activity Timeline
            </h3>

            <div class="space-y-3 max-h-96 overflow-y-auto pr-2">
                @foreach($activityLogs as $log)
                    @php
                        $details = is_array($log->details) ? $log->details : json_decode($log->details, true);
                        $timestamp = isset($details['timestamp']) ? \Carbon\Carbon::parse($details['timestamp']) : $log->created_at;

                        // Icon and color based on event type
                        $iconClass = 'fa-eye-slash';
                        $colorClass = 'text-purple-600';
                        $bgClass = 'bg-purple-50';
                        $borderClass = 'border-purple-200';
                        $eventLabel = 'Tab Hidden';

                        if ($log->event_type === 'window_blur') {
                            $iconClass = 'fa-window-minimize';
                            $colorClass = 'text-orange-600';
                            $bgClass = 'bg-orange-50';
                            $borderClass = 'border-orange-200';
                            $eventLabel = 'Focus Lost';
                        } elseif ($log->event_type === 'tab_switch') {
                            $iconClass = 'fa-exchange-alt';
                            $colorClass = 'text-blue-600';
                            $bgClass = 'bg-blue-50';
                            $borderClass = 'border-blue-200';
                            $eventLabel = 'Tab Switched';
                        }
                    @endphp

                    <div class="flex items-start p-3 rounded-lg border {{ $borderClass }} {{ $bgClass }}">
                        <div class="flex-shrink-0 mt-1">
                            <i class="fas {{ $iconClass }} {{ $colorClass }} text-lg"></i>
                        </div>
                        <div class="ml-3 flex-1">
                            <div class="flex items-center justify-between">
                                <p class="font-semibold text-gray-900">{{ $eventLabel }}</p>
                                <span class="text-xs text-gray-500">
                                    {{ $timestamp->format('h:i:s A') }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mt-1">
                                {{ ucfirst(str_replace('_', ' ', $log->event_type)) }}
                            </p>
                            @if(isset($details['ip_address']))
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-network-wired mr-1"></i>IP: {{ $details['ip_address'] }}
                                </p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Export/Download Option (Optional) -->
        @if($showStudentInfo)
            <div class="mt-6 pt-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-info-circle mr-1"></i>
                        Activity logs help maintain exam integrity and identify potential issues.
                    </p>
                    {{-- Future: Add export button here
                    <button class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm">
                        <i class="fas fa-download mr-2"></i>Export Log
                    </button>
                    --}}
                </div>
            </div>
        @endif

    @else
        <!-- No Activity -->
        <div class="text-center py-12">
            <div class="{{ $alertBg }} rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                <i class="fas {{ $alertIcon }} text-4xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No Activity Detected</h3>
            <p class="text-gray-600 max-w-md mx-auto">
                No suspicious activity was recorded during this exam session.
                The student maintained focus throughout the exam.
            </p>
        </div>
    @endif
</div>
