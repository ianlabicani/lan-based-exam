@extends('teacher.shell')

@section('teacher-content')

@php
    $activeTab = request('tab', 'items'); // Default to items tab
@endphp

<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-md">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-500 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-green-700 font-medium">{{ session('success') }}</p>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-green-500 hover:text-green-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        @endif

        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-4 mb-4">
                <a href="{{ route('teacher.exams.index') }}" class="text-gray-600 hover:text-gray-900">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div class="flex-1">
                    <div class="flex items-center space-x-3">
                        <h1 class="text-3xl font-bold text-gray-900">{{ $exam->title }}</h1>
                        @if($exam->status === 'published')
                            <span class="px-3 py-1 bg-green-100 text-green-700 text-sm font-semibold rounded-full">
                                <i class="fas fa-check-circle"></i> Published
                            </span>
                        @elseif($exam->status === 'draft')
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-sm font-semibold rounded-full">
                                <i class="fas fa-pencil-alt"></i> Draft
                            </span>
                        @endif
                    </div>
                    <p class="text-gray-600 mt-2">{{ $exam->description }}</p>
                </div>
                <div class="flex space-x-2">
                    <button onclick="showStatusModal()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition duration-200">
                        <i class="fas fa-sync-alt mr-2"></i>Update Status
                    </button>
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
                <p class="text-sm text-gray-500">{{ $exam->getLifecycleLabel() }}</p>
            </div>
        </div>

        <!-- Lifecycle Stages Info -->
        <div class="mb-6">
            <h4 class="text-sm font-semibold text-gray-700 mb-3">Exam Lifecycle:</h4>
            <div class="space-y-2">
                @php
                    $stages = ['draft', 'ready', 'published', 'ongoing', 'closed', 'graded', 'archived'];
                    $currentIndex = array_search($exam->status, $stages);
                @endphp

                @foreach($stages as $index => $stage)
                    <div class="flex items-center space-x-3">
                        @if($index < $currentIndex)
                            <i class="fas fa-check-circle text-green-500"></i>
                        @elseif($index === $currentIndex)
                            <i class="fas fa-dot-circle text-indigo-500"></i>
                        @else
                            <i class="far fa-circle text-gray-300"></i>
                        @endif
                        <span class="text-sm {{ $index === $currentIndex ? 'font-semibold text-indigo-600' : 'text-gray-600' }}">
                            {{ ucfirst($stage) }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Status Update Form -->
        <form action="{{ route('teacher.exams.updateStatus', $exam->id) }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="mb-6">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                    Select New Status:
                </label>
                <select name="status" id="status" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">-- Choose Status --</option>
                    @if($exam->status === 'draft')
                        <option value="ready">Ready for Review</option>
                    @elseif($exam->status === 'ready')
                        <option value="draft">Back to Draft</option>
                        <option value="published">Publish Exam</option>
                    @elseif($exam->status === 'published')
                        <option value="ready">Back to Ready</option>
                        <option value="ongoing">Start Exam (Make Ongoing)</option>
                    @elseif($exam->status === 'ongoing')
                        <option value="closed">Close Exam</option>
                    @elseif($exam->status === 'closed')
                        <option value="graded">Mark as Graded</option>
                    @elseif($exam->status === 'graded')
                        <option value="archived">Archive Exam</option>
                    @endif
                </select>
            </div>

            <!-- Warning Message -->
            <div class="mb-6 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                <div class="flex">
                    <i class="fas fa-exclamation-triangle text-yellow-400 mt-1"></i>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            <strong>Note:</strong> Status changes follow the exam lifecycle and cannot be reversed beyond certain stages.
                            @if($exam->status === 'draft' || $exam->status === 'ready')
                                <br>Questions can only be edited in Draft or Ready status.
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <button type="button" onclick="hideStatusModal()"
                    class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition duration-200">
                    Cancel
                </button>
                <button type="submit"
                    class="px-6 py-2 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition duration-200">
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

// Close modal when clicking outside
document.getElementById('statusModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        hideStatusModal();
    }
});
</script>

@endsection
