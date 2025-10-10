{{-- Takers Tab Content --}}
<div class="space-y-6" x-data="{
    showCloseModal: false,
    closeExam() {
        document.getElementById('closeExamForm').submit();
    }
}">
    @if($takers->isEmpty())
        <!-- Empty State -->
        <div class="flex flex-col items-center justify-center py-16 px-4">
            <div class="w-24 h-24 mb-6 text-gray-300">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">No Takers Yet</h3>
            <p class="text-gray-500 text-center max-w-md">
                No students have taken this exam yet. Share the exam code with your students to get started.
            </p>
        </div>
    @else
        <!-- Grading Status Banner and Close Exam Button -->
        @if($completedCount > 0)
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-indigo-200 rounded-xl p-6">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-12 h-12 bg-indigo-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-clipboard-check text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Grading Progress</h3>
                            <p class="text-sm text-gray-600">Track and manage student submissions</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Total Submitted</span>
                                <i class="fas fa-check-circle text-blue-500"></i>
                            </div>
                            <p class="text-2xl font-bold text-blue-600 mt-1">{{ $completedCount }}</p>
                        </div>

                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Graded</span>
                                <i class="fas fa-star text-green-500"></i>
                            </div>
                            <p class="text-2xl font-bold text-green-600 mt-1">{{ $gradedCount }}</p>
                        </div>

                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Pending Grading</span>
                                <i class="fas fa-clock text-orange-500"></i>
                            </div>
                            <p class="text-2xl font-bold text-orange-600 mt-1">{{ $pendingGradingCount }}</p>
                        </div>
                    </div>
                </div>

                <!-- Close Exam Button (shown when all grading is complete) -->
                @if($pendingGradingCount === 0 && $gradedCount > 0 && $exam->status !== 'closed')
                <div class="flex flex-col items-center gap-2">
                    <div class="bg-green-50 border-2 border-green-500 rounded-lg p-4 text-center">
                        <i class="fas fa-check-double text-green-600 text-2xl mb-2"></i>
                        <p class="text-sm font-semibold text-green-900 mb-1">All Graded!</p>
                        <p class="text-xs text-green-700 mb-3">Ready to release results</p>
                        <form id="closeExamForm" action="{{ route('teacher.exams.update', $exam->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="closed">
                        </form>
                        <button type="button"
                                @click="showCloseModal = true"
                                class="px-6 py-3 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition duration-200 shadow-lg hover:shadow-xl">
                            <i class="fas fa-lock mr-2"></i>Close Exam & Release Results
                        </button>
                    </div>
                </div>
                @elseif($exam->status === 'closed')
                <div class="bg-gray-50 border-2 border-gray-300 rounded-lg p-4 text-center">
                    <i class="fas fa-lock text-gray-600 text-2xl mb-2"></i>
                    <p class="text-sm font-semibold text-gray-700">Exam Closed</p>
                    <p class="text-xs text-gray-600 mt-1">Results visible to students</p>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Search and Filter Bar -->
        <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
            <div class="flex-1 max-w-md">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text"
                           id="searchTakers"
                           placeholder="Search by name or email..."
                           class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           onkeyup="filterTakers()">
                </div>
            </div>

            <div class="flex gap-2">
                <select id="filterStatus"
                        onchange="filterTakers()"
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="all">All Status</option>
                    <option value="ongoing">Ongoing</option>
                    <option value="submitted">Submitted</option>
                    <option value="graded">Graded</option>
                </select>

                <select id="sortBy"
                        onchange="sortTakers()"
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="recent">Most Recent</option>
                    <option value="name">Name (A-Z)</option>
                    <option value="score-high">Score (High to Low)</option>
                    <option value="score-low">Score (Low to High)</option>
                </select>
            </div>
        </div>

        <!-- Takers Grid -->
        <div id="takersGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($takers as $taker)
                <div class="taker-card bg-white rounded-lg border border-gray-200 hover:border-blue-400 hover:shadow-md transition-all duration-200 overflow-hidden"
                   data-name="{{ strtolower($taker['user']->name) }}"
                   data-email="{{ strtolower($taker['user']->email) }}"
                   data-status="{{ $taker['status'] }}"
                   data-score="{{ $taker['percentage'] ?? 0 }}"
                   data-submitted="{{ $taker['submitted_at'] ?? '' }}">

                    <!-- Card Header with Status Badge -->
                    <div class="p-4 pb-3 border-b border-gray-100">
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex-1 min-w-0">
                                <h4 class="font-semibold text-gray-900 truncate">{{ $taker['user']->name }}</h4>
                                <p class="text-sm text-gray-500 truncate">{{ $taker['user']->email }}</p>
                            </div>

                            @if($taker['status'] === 'ongoing')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 whitespace-nowrap">
                                    <i class="fas fa-circle text-yellow-400 mr-1.5" style="font-size: 6px;"></i>
                                    Ongoing
                                </span>
                            @elseif($taker['status'] === 'submitted')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 whitespace-nowrap">
                                    <i class="fas fa-circle text-orange-400 mr-1.5" style="font-size: 6px;"></i>
                                    Submitted
                                </span>
                            @elseif($taker['status'] === 'graded')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 whitespace-nowrap">
                                    <i class="fas fa-check-circle text-green-500 mr-1.5 text-xs"></i>
                                    Graded
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Card Body with Student Info -->
                    <div class="p-4 space-y-3">
                        <!-- Year & Section -->
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-graduation-cap w-5 text-gray-400"></i>
                            <span class="ml-2">
                                @if($taker['user']->year && $taker['user']->section)
                                    Year {{ $taker['user']->year }} - Section {{ $taker['user']->section }}
                                @else
                                    <span class="text-gray-400 italic">No year/section</span>
                                @endif
                            </span>
                        </div>

                        <!-- Progress -->
                        @if($taker['status'] !== 'ongoing')
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-check-double w-5 text-gray-400"></i>
                                <span class="ml-2">
                                    {{ $taker['answered_count'] }} / {{ $taker['total_questions'] }} answered
                                </span>
                            </div>
                        @endif

                        <!-- Score (if submitted/graded) -->
                        @if($taker['status'] !== 'ongoing' && $taker['total_points'] !== null)
                            <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                                <span class="text-sm font-medium text-gray-700">Score:</span>
                                <div class="flex items-center gap-2">
                                    <span class="text-lg font-bold text-blue-600">
                                        {{ number_format($taker['percentage'], 1) }}%
                                    </span>
                                    <span class="text-sm text-gray-500">
                                        ({{ number_format($taker['total_points'], 2) }} / {{ number_format($exam->total_points, 2) }})
                                    </span>
                                </div>
                            </div>
                        @endif

                        <!-- Submission Time -->
                        @if($taker['submitted_at'])
                            <div class="flex items-center text-xs text-gray-500 pt-2">
                                <i class="far fa-clock w-5 text-gray-400"></i>
                                <span class="ml-2">
                                    Submitted {{ \Carbon\Carbon::parse($taker['submitted_at'])->diffForHumans() }}
                                </span>
                            </div>
                        @else
                            <div class="flex items-center text-xs text-gray-500 pt-2">
                                <i class="far fa-clock w-5 text-gray-400"></i>
                                <span class="ml-2">
                                    Started {{ \Carbon\Carbon::parse($taker['started_at'])->diffForHumans() }}
                                </span>
                            </div>
                        @endif

                        <!-- Duration (if submitted) -->
                        @if($taker['duration'])
                            <div class="flex items-center text-xs text-gray-500">
                                <i class="fas fa-stopwatch w-5 text-gray-400"></i>
                                <span class="ml-2">
                                    Duration: {{ $taker['duration'] }} minutes
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Card Footer - Quick Action Hint -->
                    <div class="px-4 py-3 bg-gray-50 border-t border-gray-100">
                        <div class="flex items-center justify-between gap-3">
                            @if($taker['status'] === 'ongoing')
                                <a href="{{ route('teacher.grading.show', $taker['id']) }}"
                                   class="flex-1 text-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                                    <i class="fas fa-eye mr-1.5"></i>
                                    View Progress
                                </a>
                            @elseif($taker['status'] === 'submitted')
                                <a href="{{ route('teacher.grading.show', $taker['id']) }}"
                                   class="flex-1 text-center px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors font-medium">
                                    <i class="fas fa-pen mr-1.5"></i>
                                    Grade Now
                                </a>
                            @elseif($taker['status'] === 'graded')
                                <a href="{{ route('teacher.grading.show', $taker['id']) }}"
                                   class="flex-1 text-center px-3 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
                                    <i class="fas fa-edit mr-1.5"></i>
                                    Edit Grades
                                </a>
                                <a href="{{ route('teacher.exams.takenExams.show', [$exam->id, $taker['id']]) }}"
                                   class="flex-1 text-center px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium">
                                    <i class="fas fa-file-alt mr-1.5"></i>
                                    View Results
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- No Results Message (Hidden by default) -->
        <div id="noResults" class="hidden text-center py-12">
            <div class="text-gray-400 mb-3">
                <i class="fas fa-search text-4xl"></i>
            </div>
            <p class="text-gray-600 font-medium">No takers found</p>
            <p class="text-gray-500 text-sm mt-1">Try adjusting your search or filter criteria</p>
        </div>
    @endif

    <!-- Close Exam Confirmation Modal -->
    <div x-show="showCloseModal"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title"
         role="dialog"
         aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div x-show="showCloseModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                 @click="showCloseModal = false"
                 aria-hidden="true"></div>

            <!-- Center modal -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel -->
            <div x-show="showCloseModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Close Exam and Release Results?
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to close this exam? Once closed, all students with graded submissions will be able to view their results and feedback.
                                </p>
                                <div class="mt-3 bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                    <p class="text-xs text-yellow-800">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        <strong>Note:</strong> Students will immediately have access to their scores, answers, and any feedback you provided.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                    <button type="button"
                            @click="closeExam()"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                        <i class="fas fa-lock mr-2"></i>
                        Yes, Close Exam
                    </button>
                    <button type="button"
                            @click="showCloseModal = false"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@if(!$takers->isEmpty())
<script>
    // Search and Filter functionality
    function filterTakers() {
        const searchTerm = document.getElementById('searchTakers').value.toLowerCase();
        const statusFilter = document.getElementById('filterStatus').value;
        const cards = document.querySelectorAll('.taker-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const name = card.dataset.name;
            const email = card.dataset.email;
            const status = card.dataset.status;

            const matchesSearch = name.includes(searchTerm) || email.includes(searchTerm);
            const matchesStatus = statusFilter === 'all' || status === statusFilter;

            if (matchesSearch && matchesStatus) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Show/hide no results message
        document.getElementById('noResults').classList.toggle('hidden', visibleCount > 0);
    }

    // Sort functionality
    function sortTakers() {
        const sortBy = document.getElementById('sortBy').value;
        const grid = document.getElementById('takersGrid');
        const cards = Array.from(document.querySelectorAll('.taker-card'));

        cards.sort((a, b) => {
            switch(sortBy) {
                case 'name':
                    return a.dataset.name.localeCompare(b.dataset.name);

                case 'score-high':
                    return parseFloat(b.dataset.score) - parseFloat(a.dataset.score);

                case 'score-low':
                    return parseFloat(a.dataset.score) - parseFloat(b.dataset.score);

                case 'recent':
                default:
                    const dateA = a.dataset.submitted || a.dataset.started || '';
                    const dateB = b.dataset.submitted || b.dataset.started || '';
                    return dateB.localeCompare(dateA);
            }
        });

        // Re-append sorted cards
        cards.forEach(card => grid.appendChild(card));
    }
</script>
@endif
