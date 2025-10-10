@php
    // Parse TOS to get allocations
    $tosData = is_string($exam['tos']) ? json_decode($exam['tos'], true) : $exam['tos'];
    $allocations = [
        'easy' => 0,
        'moderate' => 0,
        'difficult' => 0
    ];

    if ($tosData && is_array($tosData)) {
        foreach ($tosData as $topic) {
            if (isset($topic['distribution'])) {
                $allocations['easy'] += $topic['distribution']['easy']['allocation'] ?? 0;
                // Check both 'average' (new) and 'moderate' (old) for backward compatibility
                $allocations['moderate'] += $topic['distribution']['average']['allocation'] ?? $topic['distribution']['moderate']['allocation'] ?? 0;
                $allocations['difficult'] += $topic['distribution']['difficult']['allocation'] ?? 0;
            }
        }
    }

    // Group items by level
    $groupedItems = [
        'easy' => [],
        'moderate' => [],
        'difficult' => []
    ];

    foreach ($examItems as $item) {
        $level = $item['level'] ?? 'moderate';
        if (isset($groupedItems[$level])) {
            $groupedItems[$level][] = $item;
        }
    }

    $isEditable = $exam->status === 'draft';
@endphp

<div class="space-y-6">
    <!-- Header -->
    <div>
        <h3 class="text-lg font-bold text-gray-900">Exam Questions</h3>
        <p class="text-sm text-gray-600 mt-1">Questions are organized by difficulty level based on your Table of Specifications</p>
    </div>

    <!-- Questions Grouped by Level -->
    <!-- Questions Grouped by Level -->
    @php
        $levelConfig = [
            'easy' => [
                'label' => 'Easy Questions',
                'icon' => 'fa-signal',
                'bgColor' => 'bg-emerald-50',
                'borderColor' => 'border-emerald-200',
                'textColor' => 'text-emerald-700',
                'badgeColor' => 'bg-emerald-100',
                'buttonColor' => 'bg-emerald-600 hover:bg-emerald-700'
            ],
            'moderate' => [
                'label' => 'Average Questions',
                'icon' => 'fa-signal',
                'bgColor' => 'bg-amber-50',
                'borderColor' => 'border-amber-200',
                'textColor' => 'text-amber-700',
                'badgeColor' => 'bg-amber-100',
                'buttonColor' => 'bg-amber-600 hover:bg-amber-700'
            ],
            'difficult' => [
                'label' => 'Difficult Questions',
                'icon' => 'fa-signal',
                'bgColor' => 'bg-red-50',
                'borderColor' => 'border-red-200',
                'textColor' => 'text-red-700',
                'badgeColor' => 'bg-red-100',
                'buttonColor' => 'bg-red-600 hover:bg-red-700'
            ]
        ];
    @endphp

    @foreach(['easy', 'moderate', 'difficult'] as $level)
        @php
            $config = $levelConfig[$level];
            $items = $groupedItems[$level];
            $currentCount = count($items);
            $maxAllocation = $allocations[$level];
            $canAddMore = $currentCount < $maxAllocation;
        @endphp

        <div class="border {{ $config['borderColor'] }} rounded-lg overflow-hidden">
            <!-- Level Header -->
            <div class="{{ $config['bgColor'] }} px-6 py-4 border-b {{ $config['borderColor'] }}">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <i class="fas {{ $config['icon'] }} {{ $config['textColor'] }} text-xl"></i>
                        <div>
                            <h4 class="text-lg font-bold {{ $config['textColor'] }}">{{ $config['label'] }}</h4>
                            <p class="text-sm {{ $config['textColor'] }} opacity-80">
                                {{ $currentCount }} of {{ $maxAllocation }} questions added
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <span class="px-3 py-1 {{ $config['badgeColor'] }} {{ $config['textColor'] }} text-xs font-semibold rounded-full">
                            {{ $currentCount }}/{{ $maxAllocation }}
                        </span>
                        @if($canAddMore)
                            <button onclick="showAddQuestionModalForLevel('{{ $level }}')"
                                class="px-4 py-2 {{ $config['buttonColor'] }} text-white rounded-lg font-medium transition duration-200 flex items-center space-x-2">
                                <i class="fas fa-plus-circle"></i>
                                <span>Add {{ ucfirst($level) }} Question</span>
                            </button>
                        @else
                            <button disabled
                                class="px-4 py-2 bg-gray-300 text-gray-500 rounded-lg font-medium cursor-not-allowed flex items-center space-x-2">
                                <i class="fas fa-check-circle"></i>
                                <span>Limit Reached</span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Items in this level -->
            <div class="bg-white">
                @if(count($items) > 0)
                    <div class="divide-y divide-gray-200">
                        @foreach($items as $index => $item)
                        <div class="p-6 hover:bg-gray-50 transition duration-200">
                            <div class="flex items-start justify-between">
                                <div class="flex items-start space-x-4 flex-1">
                                    <div class="{{ $config['badgeColor'] }} {{ $config['textColor'] }} w-10 h-10 rounded-full flex items-center justify-center font-bold flex-shrink-0">
                                        {{ $index + 1 }}
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2 mb-2">
                                            @if($item['type'] === 'mcq')
                                                <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">
                                                    <i class="fas fa-check-square mr-1"></i>Multiple Choice
                                                </span>
                                            @elseif($item['type'] === 'truefalse')
                                                <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">
                                                    <i class="fas fa-toggle-on mr-1"></i>True/False
                                                </span>
                                            @elseif($item['type'] === 'shortanswer')
                                                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded-full">
                                                    <i class="fas fa-pencil-alt mr-1"></i>Short Answer
                                                </span>
                                            @elseif($item['type'] === 'essay')
                                                <span class="px-3 py-1 bg-purple-100 text-purple-700 text-xs font-semibold rounded-full">
                                                    <i class="fas fa-file-alt mr-1"></i>Essay
                                                </span>
                                            @elseif($item['type'] === 'matching')
                                                <span class="px-3 py-1 bg-pink-100 text-pink-700 text-xs font-semibold rounded-full">
                                                    <i class="fas fa-link mr-1"></i>Matching
                                                </span>
                                            @elseif($item['type'] === 'fillblank' || $item['type'] === 'fill_blank')
                                                <span class="px-3 py-1 bg-orange-100 text-orange-700 text-xs font-semibold rounded-full">
                                                    <i class="fas fa-edit mr-1"></i>Fill in the Blank
                                                </span>
                                            @endif
                                            <span class="px-3 py-1 bg-gray-200 text-gray-700 text-xs font-semibold rounded-full">
                                                <i class="fas fa-star mr-1"></i>{{ $item['points'] }} pts
                                            </span>
                                        </div>
                                        <p class="text-gray-900 font-medium text-lg mb-3">{{ $item['question'] }}</p>

                                        <!-- Question Content -->
                                        @if($item['type'] === 'mcq')
                                            <div class="space-y-2">
                                                @foreach($item['options'] as $optIndex => $option)
                                                <div class="flex items-center space-x-3 p-2 rounded-lg {{ isset($option['correct']) && $option['correct'] ? 'bg-green-50 border border-green-200' : 'bg-gray-50' }}">
                                                    <div class="w-5 h-5 rounded-full border-2 {{ isset($option['correct']) && $option['correct'] ? 'border-green-500 bg-green-100' : 'border-gray-300' }} flex items-center justify-center">
                                                        @if(isset($option['correct']) && $option['correct'])
                                                            <i class="fas fa-check text-green-600 text-xs"></i>
                                                        @endif
                                                    </div>
                                                    <span class="text-sm text-gray-900 {{ isset($option['correct']) && $option['correct'] ? 'font-semibold' : '' }}">
                                                        {{ chr(65 + $optIndex) }}. {{ $option['text'] ?? $option }}
                                                        @if(isset($option['correct']) && $option['correct'])
                                                            <span class="text-green-600 text-xs ml-2">(Correct)</span>
                                                        @endif
                                                    </span>
                                                </div>
                                                @endforeach
                                            </div>
                                        @elseif($item['type'] === 'truefalse')
                                            <div class="flex items-center space-x-3">
                                                <span class="text-sm font-medium text-gray-700">Answer:</span>
                                                <span class="px-3 py-1 bg-green-100 text-green-700 font-semibold rounded text-sm">
                                                    <i class="fas fa-{{ ($item['answer'] === 'true') ? 'check' : 'times' }} mr-1"></i>
                                                    {{ ($item['answer'] === 'true') ? 'True' : 'False' }}
                                                </span>
                                            </div>
                                        @elseif($item['type'] === 'shortanswer')
                                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                                <p class="text-xs text-blue-800">
                                                    <i class="fas fa-info-circle mr-1"></i>Short answer - Manual grading required
                                                    @if(!empty($item['expected_answer']))
                                                        <br><strong>Expected:</strong> {{ $item['expected_answer'] }}
                                                    @endif
                                                </p>
                                            </div>
                                        @elseif($item['type'] === 'essay')
                                            <div class="bg-purple-50 border border-purple-200 rounded-lg p-3">
                                                <p class="text-xs text-purple-800">
                                                    <i class="fas fa-info-circle mr-1"></i>Essay - Manual grading required
                                                    @if(!empty($item['expected_answer']))
                                                        <br><strong>Rubric:</strong> {{ $item['expected_answer'] }}
                                                    @endif
                                                </p>
                                            </div>
                                        @elseif($item['type'] === 'matching')
                                            <div class="space-y-1">
                                                @foreach($item['pairs'] as $pair)
                                                <div class="flex items-center space-x-2 p-2 bg-gray-50 rounded text-sm">
                                                    <span class="text-gray-900 font-medium">{{ $pair['left'] ?? '' }}</span>
                                                    <i class="fas fa-arrow-right text-gray-400"></i>
                                                    <span class="text-indigo-600 font-semibold">{{ $pair['right'] ?? '' }}</span>
                                                </div>
                                                @endforeach
                                            </div>
                                        @elseif($item['type'] === 'fillblank' || $item['type'] === 'fill_blank')
                                            <div class="bg-orange-50 border border-orange-200 rounded-lg p-3">
                                                <p class="text-xs text-orange-800">
                                                    <i class="fas fa-info-circle mr-1"></i>Fill in the blank
                                                    @if(!empty($item['expected_answer']))
                                                        <br><strong>Answer:</strong> {{ $item['expected_answer'] }}
                                                    @endif
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @if ($isEditable)
                                <div class="flex items-center space-x-2 ml-4">
                                    <button onclick="editQuestion({{ $item->id }}, '{{ $item->type }}')" class="p-2 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition duration-200" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="showDeleteModal({{ $item->id }}, '{{ addslashes($item->question) }}')" class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition duration-200" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-8 text-center">
                        <div class="{{ $config['badgeColor'] }} w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-question {{ $config['textColor'] }} text-2xl"></i>
                        </div>
                        <p class="text-gray-600 mb-4">No {{ $level }} questions added yet</p>
                        @if($canAddMore)
                            <button onclick="showAddQuestionModalForLevel('{{ $level }}')"
                                class="px-4 py-2 {{ $config['buttonColor'] }} text-white rounded-lg font-medium transition duration-200 inline-flex items-center space-x-2">
                                <i class="fas fa-plus-circle"></i>
                                <span>Add First {{ ucfirst($level) }} Question</span>
                            </button>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    @endforeach
</div>

<!-- Include Modals -->
@include('teacher.exams.partials.modals.add-question-modal')
@include('teacher.exams.partials.modals.delete-question-modal')

<!-- Include Core Scripts -->
@include('teacher.exams.partials.items-scripts')

<!-- Include Question Type Forms -->
@include('teacher.exams.partials.forms.mcq')
@include('teacher.exams.partials.forms.truefalse')
@include('teacher.exams.partials.forms.shortanswer')
@include('teacher.exams.partials.forms.essay')
@include('teacher.exams.partials.forms.matching')
@include('teacher.exams.partials.forms.fillblank')

<!-- Include Edit Question Type Forms -->
@include('teacher.exams.partials.forms.edit-mcq')
@include('teacher.exams.partials.forms.edit-truefalse')
@include('teacher.exams.partials.forms.edit-shortanswer')
@include('teacher.exams.partials.forms.edit-essay')
@include('teacher.exams.partials.forms.edit-matching')
@include('teacher.exams.partials.forms.edit-fillblank')

