@extends('teacher.shell')

@section('teacher-content')

@php
    $activeTab = request('tab', 'items'); // Default to items tab
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
                        <h1 class="text-3xl font-bold text-gray-900">{{ $exam->title }}</h1>
                        @if($exam->status === 'draft')
                            <span class="px-3 py-1 bg-gray-100 text-gray-700 text-sm font-semibold rounded-full">
                                <i class="fas fa-pencil-alt"></i> Draft
                            </span>
                        @elseif($exam->status === 'ready')
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 text-sm font-semibold rounded-full">
                                <i class="fas fa-clipboard-check"></i> Ready
                            </span>
                        @elseif($exam->status === 'published')
                            <span class="px-3 py-1 bg-green-100 text-green-700 text-sm font-semibold rounded-full">
                                <i class="fas fa-check-circle"></i> Published
                            </span>
                        @elseif($exam->status === 'ongoing')
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-sm font-semibold rounded-full">
                                <i class="fas fa-hourglass-half"></i> Ongoing
                            </span>
                        @elseif($exam->status === 'closed')
                            <span class="px-3 py-1 bg-orange-100 text-orange-700 text-sm font-semibold rounded-full">
                                <i class="fas fa-lock"></i> Closed
                            </span>
                        @elseif($exam->status === 'graded')
                            <span class="px-3 py-1 bg-purple-100 text-purple-700 text-sm font-semibold rounded-full">
                                <i class="fas fa-star"></i> Graded
                            </span>
                        @elseif($exam->status === 'archived')
                            <span class="px-3 py-1 bg-red-100 text-red-700 text-sm font-semibold rounded-full">
                                <i class="fas fa-archive"></i> Archived
                            </span>
                        @endif
                    </div>
                    <p class="text-gray-600 mt-2">{{ $exam->description }}</p>
                </div>
                <div class="flex space-x-2">
                    <button onclick="showStatusModal()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition duration-200">
                        <i class="fas fa-sync-alt mr-2"></i>Update Status
                    </button>
                    @if ($exam->status == 'draft')
                        <a href="{{ route('teacher.exams.edit', $exam->id) }}" class="px-4 py-2 bg-yellow-600 text-white rounded-lg font-medium hover:bg-yellow-700 transition duration-200">
                            <i class="fas fa-edit mr-2"></i>Edit
                        </a>
                        <form action="{{ route('teacher.exams.destroy', $exam->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this exam?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition duration-200">
                                <i class="fas fa-trash mr-2"></i>Delete
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Questions</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $exam->items->count() }}</p>
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
                        <p class="text-3xl font-bold text-gray-900 mt-2">0</p>
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
                        <p class="text-3xl font-bold text-gray-900 mt-2">0</p>
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
                        <p class="text-3xl font-bold text-gray-900 mt-2">--</p>
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
                        Questions ({{ $exam->items->count() }})
                    </a>
                    <a href="?tab=takers" class="group inline-flex items-center py-4 px-6 border-b-2 font-medium text-sm {{ $activeTab === 'takers' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        <i class="fas fa-users mr-2"></i>
                        Takers (0)
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

<!-- Status Update Modal -->
<div id="statusModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-lg bg-white">
        <div class="flex items-center justify-between mb-4 pb-4 border-b">
            <h3 class="text-2xl font-bold text-gray-900">
                <i class="fas fa-sync-alt text-indigo-600 mr-2"></i>
                Update Exam Status
            </h3>
            <button onclick="hideStatusModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <!-- Current Status -->
        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
            <p class="text-sm text-gray-600 mb-2">Current Status:</p>
            <div class="flex items-center space-x-2">
                <span class="px-4 py-2 rounded-full font-semibold
                    @if($exam->status === 'draft') bg-gray-100 text-gray-700
                    @elseif($exam->status === 'ready') bg-blue-100 text-blue-700
                    @elseif($exam->status === 'published') bg-green-100 text-green-700
                    @elseif($exam->status === 'ongoing') bg-yellow-100 text-yellow-700
                    @elseif($exam->status === 'closed') bg-orange-100 text-orange-700
                    @elseif($exam->status === 'graded') bg-purple-100 text-purple-700
                    @elseif($exam->status === 'archived') bg-red-100 text-red-700
                    @endif">
                    {{ ucfirst($exam->status) }}
                </span>
            </div>
        </div>

        @php
            // Parse TOS to get allocations
            $tosData = is_string($exam->tos) ? json_decode($exam->tos, true) : $exam->tos;
            $requiredAllocations = [
                'easy' => 0,
                'moderate' => 0,
                'difficult' => 0
            ];

            if ($tosData && is_array($tosData)) {
                foreach ($tosData as $topic) {
                    if (isset($topic['distribution'])) {
                        $requiredAllocations['easy'] += $topic['distribution']['easy']['allocation'] ?? 0;
                        $requiredAllocations['moderate'] += $topic['distribution']['moderate']['allocation'] ?? 0;
                        $requiredAllocations['difficult'] += $topic['distribution']['difficult']['allocation'] ?? 0;
                    }
                }
            }

            // Count current items by level
            $currentCounts = [
                'easy' => $exam->items->where('level', 'easy')->count(),
                'moderate' => $exam->items->where('level', 'moderate')->count(),
                'difficult' => $exam->items->where('level', 'difficult')->count()
            ];

            // Check if requirements are met
            $requirementsMet =
                $currentCounts['easy'] >= $requiredAllocations['easy'] &&
                $currentCounts['moderate'] >= $requiredAllocations['moderate'] &&
                $currentCounts['difficult'] >= $requiredAllocations['difficult'] &&
                $exam->items->count() > 0;

            // Define lifecycle
            $lifecycle = ['draft', 'ready', 'published', 'ongoing', 'closed', 'graded', 'archived'];
            $currentIndex = array_search($exam->status, $lifecycle);
            $canGoBack = $currentIndex > 0;
            $canGoForward = $currentIndex < count($lifecycle) - 1;
            $previousStatus = $canGoBack ? $lifecycle[$currentIndex - 1] : null;
            $nextStatus = $canGoForward ? $lifecycle[$currentIndex + 1] : null;
        @endphp

        <!-- Requirements Check (only show if trying to move forward) -->
        @if(!$requirementsMet && ($exam->status === 'draft' || $exam->status === 'ready'))
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-400 rounded">
            <div class="flex">
                <i class="fas fa-exclamation-circle text-red-400 mt-1"></i>
                <div class="ml-3">
                    <h4 class="text-sm font-semibold text-red-800 mb-2">Requirements Not Met</h4>
                    <p class="text-sm text-red-700 mb-3">
                        The exam does not meet the Table of Specifications requirements. Please add the required questions before proceeding.
                    </p>
                    <div class="space-y-2 text-sm">
                        @if($currentCounts['easy'] < $requiredAllocations['easy'])
                            <div class="flex items-center text-red-700">
                                <i class="fas fa-times-circle mr-2"></i>
                                <span>Easy: {{ $currentCounts['easy'] }}/{{ $requiredAllocations['easy'] }} questions
                                    <span class="font-semibold">({{ $requiredAllocations['easy'] - $currentCounts['easy'] }} more needed)</span>
                                </span>
                            </div>
                        @else
                            <div class="flex items-center text-green-700">
                                <i class="fas fa-check-circle mr-2"></i>
                                <span>Easy: {{ $currentCounts['easy'] }}/{{ $requiredAllocations['easy'] }} questions ✓</span>
                            </div>
                        @endif

                        @if($currentCounts['moderate'] < $requiredAllocations['moderate'])
                            <div class="flex items-center text-red-700">
                                <i class="fas fa-times-circle mr-2"></i>
                                <span>Moderate: {{ $currentCounts['moderate'] }}/{{ $requiredAllocations['moderate'] }} questions
                                    <span class="font-semibold">({{ $requiredAllocations['moderate'] - $currentCounts['moderate'] }} more needed)</span>
                                </span>
                            </div>
                        @else
                            <div class="flex items-center text-green-700">
                                <i class="fas fa-check-circle mr-2"></i>
                                <span>Moderate: {{ $currentCounts['moderate'] }}/{{ $requiredAllocations['moderate'] }} questions ✓</span>
                            </div>
                        @endif

                        @if($currentCounts['difficult'] < $requiredAllocations['difficult'])
                            <div class="flex items-center text-red-700">
                                <i class="fas fa-times-circle mr-2"></i>
                                <span>Difficult: {{ $currentCounts['difficult'] }}/{{ $requiredAllocations['difficult'] }} questions
                                    <span class="font-semibold">({{ $requiredAllocations['difficult'] - $currentCounts['difficult'] }} more needed)</span>
                                </span>
                            </div>
                        @else
                            <div class="flex items-center text-green-700">
                                <i class="fas fa-check-circle mr-2"></i>
                                <span>Difficult: {{ $currentCounts['difficult'] }}/{{ $requiredAllocations['difficult'] }} questions ✓</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @elseif($requirementsMet && ($exam->status === 'draft' || $exam->status === 'ready'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-400 rounded">
            <div class="flex">
                <i class="fas fa-check-circle text-green-400 mt-1"></i>
                <div class="ml-3">
                    <h4 class="text-sm font-semibold text-green-800 mb-2">All Requirements Met ✓</h4>
                    <p class="text-sm text-green-700 mb-3">
                        The exam meets all Table of Specifications requirements.
                    </p>
                    <div class="space-y-2 text-sm text-green-700">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span>Easy: {{ $currentCounts['easy'] }}/{{ $requiredAllocations['easy'] }} questions ✓</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span>Moderate: {{ $currentCounts['moderate'] }}/{{ $requiredAllocations['moderate'] }} questions ✓</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span>Difficult: {{ $currentCounts['difficult'] }}/{{ $requiredAllocations['difficult'] }} questions ✓</span>
                        </div>
                        <div class="flex items-center font-semibold">
                            <i class="fas fa-check-double mr-2"></i>
                            <span>Total: {{ $exam->items->count() }} questions</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Status Update Form -->
        <form action="{{ route('teacher.exams.updateStatus', $exam->id) }}" method="POST" id="statusUpdateForm">
            @csrf
            @method('PATCH')

            <div class="mb-6">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-3">
                    Select Status Transition:
                </label>

                <div class="space-y-3">
                    @if($canGoBack)
                    <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-indigo-300 hover:bg-indigo-50 transition duration-200">
                        <input type="radio" name="status" value="{{ $previousStatus }}" class="w-5 h-5 text-indigo-600">
                        <div class="ml-3 flex-1">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-arrow-left text-gray-600"></i>
                                <span class="font-semibold text-gray-900">Go Back to {{ ucfirst($previousStatus) }}</span>
                            </div>
                            <p class="text-sm text-gray-600 mt-1">Return to the previous stage</p>
                        </div>
                    </label>
                    @endif

                    @if($canGoForward)
                    <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-green-300 hover:bg-green-50 transition duration-200 {{ !$requirementsMet && ($exam->status === 'draft' || $exam->status === 'ready') ? 'opacity-50 cursor-not-allowed' : '' }}">
                        <input type="radio" name="status" value="{{ $nextStatus }}" class="w-5 h-5 text-green-600"
                            {{ !$requirementsMet && ($exam->status === 'draft' || $exam->status === 'ready') ? 'disabled' : '' }}>
                        <div class="ml-3 flex-1">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-arrow-right text-green-600"></i>
                                <span class="font-semibold text-gray-900">Proceed to {{ ucfirst($nextStatus) }}</span>
                                @if(!$requirementsMet && ($exam->status === 'draft' || $exam->status === 'ready'))
                                    <span class="px-2 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded">Locked</span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-600 mt-1">
                                @if($nextStatus === 'ready')
                                    Mark as ready for review
                                @elseif($nextStatus === 'published')
                                    Publish exam to students
                                @elseif($nextStatus === 'ongoing')
                                    Start the exam period
                                @elseif($nextStatus === 'closed')
                                    Close exam submissions
                                @elseif($nextStatus === 'graded')
                                    Mark grading as complete
                                @elseif($nextStatus === 'archived')
                                    Archive this exam
                                @else
                                    Move to the next stage
                                @endif
                            </p>
                        </div>
                    </label>
                    @endif

                    @if(!$canGoBack && !$canGoForward)
                    <div class="p-4 bg-gray-50 border-2 border-gray-200 rounded-lg text-center">
                        <p class="text-gray-600">This exam is at the final stage of its lifecycle.</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Warning Message -->
            @if($exam->status === 'draft' || $exam->status === 'ready')
            <div class="mb-6 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                <div class="flex">
                    <i class="fas fa-exclamation-triangle text-yellow-400 mt-1"></i>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            <strong>Note:</strong> Questions can only be edited in Draft or Ready status. Once published, the exam structure is locked.
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <div class="flex justify-end space-x-3">
                <button type="button" onclick="hideStatusModal()"
                    class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition duration-200">
                    Cancel
                </button>
                <button type="submit" id="updateStatusBtn"
                    class="px-6 py-2 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition duration-200 disabled:bg-gray-300 disabled:cursor-not-allowed"
                    disabled>
                    <i class="fas fa-check mr-2"></i>Update Status
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showStatusModal() {
    document.getElementById('statusModal').classList.remove('hidden');
}

function hideStatusModal() {
    document.getElementById('statusModal').classList.add('hidden');
}

// Enable submit button when a status is selected
document.querySelectorAll('input[name="status"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.getElementById('updateStatusBtn').disabled = false;
    });
});

// Close modal when clicking outside
document.getElementById('statusModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        hideStatusModal();
    }
});
</script>

@endsection
