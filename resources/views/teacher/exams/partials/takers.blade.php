{{-- Takers Tab Content --}}
<div class="space-y-6">
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
