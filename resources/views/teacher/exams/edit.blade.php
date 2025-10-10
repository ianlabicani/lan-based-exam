@extends('teacher.shell')

@section('teacher-content')

<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-4 mb-4">
                <a href="{{ route('teacher.exams.show', $exam->id) }}" class="text-gray-600 hover:text-gray-900">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">
                        <i class="fas fa-edit text-yellow-600 mr-3"></i>Edit Exam
                    </h1>
                    <p class="text-gray-600 mt-2">Update the exam details</p>
                </div>
            </div>

            <!-- Warning if exam cannot be edited -->
            @if(!$exam->canBeEdited())
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
                    <div class="flex">
                        <i class="fas fa-exclamation-triangle text-yellow-400 mt-1"></i>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                <strong>Warning:</strong> This exam is in <strong>{{ ucfirst($exam->status) }}</strong> status.
                                Some fields may be restricted from editing. Only exams in Draft or Ready status can be fully edited.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Edit Exam Form -->
        <form action="{{ route('teacher.exams.update', $exam->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Basic Information Card -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 border-b border-gray-200 pb-4">
                    <i class="fas fa-info-circle text-indigo-600 mr-2"></i>Basic Information
                </h2>

                <div class="space-y-6">
                    <!-- Exam Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-heading mr-2 text-gray-500"></i>Exam Title *
                        </label>
                        <input
                            type="text"
                            id="title"
                            name="title"
                            required
                            value="{{ old('title', $exam->title) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                            placeholder="e.g., Midterm Examination - Data Structures"
                        />
                        @error('title')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-align-left mr-2 text-gray-500"></i>Description
                        </label>
                        <textarea
                            id="description"
                            name="description"
                            rows="4"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                            placeholder="Provide a brief description of the exam content and objectives..."
                        >{{ old('description', $exam->description) }}</textarea>
                        @error('description')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Year -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar mr-2 text-gray-500"></i>Year Level(s) *
                        </label>
                        <div class="grid grid-cols-4 gap-3">
                            @foreach(['1', '2', '3', '4'] as $yearOption)
                                <label class="flex items-center space-x-2 p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-indigo-50 transition">
                                    <input
                                        type="checkbox"
                                        name="year[]"
                                        value="{{ $yearOption }}"
                                        {{ in_array($yearOption, old('year', $exam->year ?? [])) ? 'checked' : '' }}
                                        class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                    />
                                    <span class="text-sm font-medium text-gray-700">Year {{ $yearOption }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('year')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Sections -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            <i class="fas fa-users-class mr-2 text-gray-500"></i>Section(s) *
                        </label>
                        <div class="grid grid-cols-7 gap-2">
                            @foreach(['a', 'b', 'c', 'd', 'e', 'f', 'g'] as $sectionOption)
                                <label class="flex items-center justify-center space-x-2 p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-indigo-50 transition">
                                    <input
                                        type="checkbox"
                                        name="sections[]"
                                        value="{{ $sectionOption }}"
                                        {{ in_array($sectionOption, old('sections', $exam->sections ?? [])) ? 'checked' : '' }}
                                        class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                    />
                                    <span class="text-sm font-medium text-gray-700 uppercase">{{ $sectionOption }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('sections')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                        <p class="mt-2 text-xs text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>Select one or more sections that can take this exam
                        </p>
                    </div>
                </div>
            </div>

            <!-- Schedule Card -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 border-b border-gray-200 pb-4">
                    <i class="fas fa-calendar-alt text-indigo-600 mr-2"></i>Schedule
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Start Time -->
                    <div>
                        <label for="starts_at" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-play-circle mr-2 text-gray-500"></i>Start Date & Time *
                        </label>
                        <input
                            type="datetime-local"
                            id="starts_at"
                            name="starts_at"
                            required
                            value="{{ old('starts_at', $exam->starts_at ? $exam->starts_at->format('Y-m-d\TH:i') : '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                        />
                        @error('starts_at')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- End Time -->
                    <div>
                        <label for="ends_at" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-stop-circle mr-2 text-gray-500"></i>End Date & Time *
                        </label>
                        <input
                            type="datetime-local"
                            id="ends_at"
                            name="ends_at"
                            required
                            value="{{ old('ends_at', $exam->ends_at ? $exam->ends_at->format('Y-m-d\TH:i') : '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                        />
                        @error('ends_at')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Table of Specifications (TOS) Card -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 border-b border-gray-200 pb-4">
                    <i class="fas fa-table text-indigo-600 mr-2"></i>Table of Specifications (TOS)
                </h2>

                <div id="tosContainer" class="space-y-6">
                    @php
                        $oldTos = old('tos', $exam->tos ?? []);
                    @endphp

                    @forelse($oldTos as $index => $tosItem)
                        <!-- TOS Item -->
                        <div class="tos-item border border-gray-200 rounded-lg p-6 bg-gray-50">
                            <div class="space-y-4">
                                <!-- Topic -->
                                <div class="flex items-center justify-between">
                                    <label class="block text-sm font-medium text-gray-700">Topic/Objective *</label>
                                    @if($index > 0)
                                        <button
                                            type="button"
                                            onclick="this.closest('.tos-item').remove()"
                                            class="px-3 py-1 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition duration-200 text-sm"
                                        >
                                            <i class="fas fa-trash mr-1"></i>Remove
                                        </button>
                                    @endif
                                </div>
                                <input
                                    type="text"
                                    name="tos[{{ $index }}][topic]"
                                    required
                                    value="{{ $tosItem['topic'] ?? '' }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                                    placeholder="e.g., Arrays and Linked Lists"
                                />

                                <!-- Time Allotment and Total Items -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Time Allotment (minutes)</label>
                                        <input
                                            type="number"
                                            name="tos[{{ $index }}][time_allotment]"
                                            min="1"
                                            value="{{ $tosItem['time_allotment'] ?? '' }}"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                                            placeholder="30"
                                        />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Total Number of Items *
                                            <span class="text-xs text-gray-500">(Auto-calculated)</span>
                                        </label>
                                        <input
                                            type="number"
                                            id="totalItems_{{ $index }}"
                                            name="tos[{{ $index }}][no_of_items]"
                                            min="0"
                                            required
                                            readonly
                                            value="{{ $tosItem['no_of_items'] ?? 0 }}"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-center font-semibold text-lg"
                                            placeholder="0"
                                        />
                                    </div>
                                </div>

                                <!-- Distribution by Difficulty -->
                                <div class="border-t pt-4 mt-4">
                                    <h4 class="text-sm font-semibold text-gray-700 mb-3">
                                        <i class="fas fa-chart-bar mr-2"></i>Distribution by Difficulty Level
                                    </h4>

                                    <div class="grid grid-cols-3 gap-4">
                                        <!-- Easy -->
                                        <div class="bg-emerald-50 p-3 rounded-lg border border-emerald-200">
                                            <label class="block text-xs font-medium text-emerald-700 mb-2">
                                                <i class="fas fa-circle text-emerald-500 mr-1"></i>Easy
                                            </label>
                                            <input
                                                type="number"
                                                name="tos[{{ $index }}][distribution][easy][allocation]"
                                                min="0"
                                                value="{{ $tosItem['distribution']['easy']['allocation'] ?? 0 }}"
                                                class="w-full px-3 py-2 border border-emerald-300 rounded-lg focus:ring-2 focus:ring-emerald-500 text-center"
                                                placeholder="0"
                                                oninput="updateTotalItems({{ $index }})"
                                            />
                                            <p class="text-xs text-emerald-600 mt-1 text-center">items</p>
                                        </div>

                                        <!-- Moderate -->
                                        <div class="bg-amber-50 p-3 rounded-lg border border-amber-200">
                                            <label class="block text-xs font-medium text-amber-700 mb-2">
                                                <i class="fas fa-circle text-amber-500 mr-1"></i>Moderate
                                            </label>
                                            <input
                                                type="number"
                                                name="tos[{{ $index }}][distribution][moderate][allocation]"
                                                min="0"
                                                value="{{ $tosItem['distribution']['moderate']['allocation'] ?? 0 }}"
                                                class="w-full px-3 py-2 border border-amber-300 rounded-lg focus:ring-2 focus:ring-amber-500 text-center"
                                                placeholder="0"
                                                oninput="updateTotalItems({{ $index }})"
                                            />
                                            <p class="text-xs text-amber-600 mt-1 text-center">items</p>
                                        </div>

                                        <!-- Difficult -->
                                        <div class="bg-red-50 p-3 rounded-lg border border-red-200">
                                            <label class="block text-xs font-medium text-red-700 mb-2">
                                                <i class="fas fa-circle text-red-500 mr-1"></i>Difficult
                                            </label>
                                            <input
                                                type="number"
                                                name="tos[{{ $index }}][distribution][difficult][allocation]"
                                                min="0"
                                                value="{{ $tosItem['distribution']['difficult']['allocation'] ?? 0 }}"
                                                class="w-full px-3 py-2 border border-red-300 rounded-lg focus:ring-2 focus:ring-red-500 text-center"
                                                placeholder="0"
                                                oninput="updateTotalItems({{ $index }})"
                                            />
                                            <p class="text-xs text-red-600 mt-1 text-center">items</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <!-- Default TOS Item if none exist -->
                        <div class="tos-item border border-gray-200 rounded-lg p-6 bg-gray-50">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Topic/Objective *</label>
                                    <input
                                        type="text"
                                        name="tos[0][topic]"
                                        required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                                        placeholder="e.g., Arrays and Linked Lists"
                                    />
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Time Allotment (minutes)</label>
                                        <input
                                            type="number"
                                            name="tos[0][time_allotment]"
                                            min="1"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                                            placeholder="30"
                                        />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Total Number of Items *</label>
                                        <input
                                            type="number"
                                            name="tos[0][no_of_items]"
                                            min="1"
                                            required
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                                            placeholder="10"
                                        />
                                    </div>
                                </div>
                                <div class="grid grid-cols-3 gap-4">
                                    <div class="bg-emerald-50 p-3 rounded-lg border border-emerald-200">
                                        <label class="block text-xs font-medium text-emerald-700 mb-2">Easy</label>
                                        <input type="number" name="tos[0][distribution][easy][allocation]" min="0" value="0" class="w-full px-3 py-2 border border-emerald-300 rounded-lg text-center" />
                                    </div>
                                    <div class="bg-amber-50 p-3 rounded-lg border border-amber-200">
                                        <label class="block text-xs font-medium text-amber-700 mb-2">Moderate</label>
                                        <input type="number" name="tos[0][distribution][moderate][allocation]" min="0" value="0" class="w-full px-3 py-2 border border-amber-300 rounded-lg text-center" />
                                    </div>
                                    <div class="bg-red-50 p-3 rounded-lg border border-red-200">
                                        <label class="block text-xs font-medium text-red-700 mb-2">Difficult</label>
                                        <input type="number" name="tos[0][distribution][difficult][allocation]" min="0" value="0" class="w-full px-3 py-2 border border-red-300 rounded-lg text-center" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>

                <button
                    type="button"
                    onclick="addTosItem()"
                    class="mt-4 px-4 py-2 bg-indigo-100 text-indigo-700 rounded-lg font-medium hover:bg-indigo-200 transition duration-200"
                >
                    <i class="fas fa-plus-circle mr-2"></i>Add Another Topic
                </button>

                @error('tos')
                    <p class="mt-2 text-sm text-red-600 flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between">
                    <a href="{{ route('teacher.exams.show', $exam->id) }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition duration-200">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </a>
                    <button
                        type="submit"
                        class="px-6 py-3 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition duration-200 flex items-center space-x-2"
                    >
                        <i class="fas fa-save"></i>
                        <span>Update Exam</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    let tosIndex = {{ count($exam->tos ?? []) }};

    function updateTotalItems(index) {
        const easy = parseInt(document.querySelector(`input[name="tos[${index}][distribution][easy][allocation]"]`).value) || 0;
        const moderate = parseInt(document.querySelector(`input[name="tos[${index}][distribution][moderate][allocation]"]`).value) || 0;
        const difficult = parseInt(document.querySelector(`input[name="tos[${index}][distribution][difficult][allocation]"]`).value) || 0;

        const total = easy + moderate + difficult;
        const totalField = document.getElementById(`totalItems_${index}`) || document.querySelector(`input[name="tos[${index}][no_of_items]"]`);

        if (totalField) {
            totalField.value = total;
        }
    }

    function addTosItem() {
        const container = document.getElementById('tosContainer');
        const newItem = document.createElement('div');
        newItem.className = 'tos-item border border-gray-200 rounded-lg p-6 bg-gray-50';
        newItem.innerHTML = `
            <div class="space-y-4">
                <!-- Topic -->
                <div class="flex items-center justify-between">
                    <label class="block text-sm font-medium text-gray-700">Topic/Objective *</label>
                    <button
                        type="button"
                        onclick="this.closest('.tos-item').remove()"
                        class="px-3 py-1 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition duration-200 text-sm"
                    >
                        <i class="fas fa-trash mr-1"></i>Remove
                    </button>
                </div>
                <input
                    type="text"
                    name="tos[${tosIndex}][topic]"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                    placeholder="e.g., Stacks and Queues"
                />

                <!-- Time Allotment and Total Items -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Time Allotment (minutes)</label>
                        <input
                            type="number"
                            name="tos[${tosIndex}][time_allotment]"
                            min="1"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                            placeholder="30"
                        />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Total Number of Items *
                            <span class="text-xs text-gray-500">(Auto-calculated)</span>
                        </label>
                        <input
                            type="number"
                            id="totalItems_${tosIndex}"
                            name="tos[${tosIndex}][no_of_items]"
                            min="0"
                            required
                            readonly
                            value="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-center font-semibold text-lg"
                            placeholder="0"
                        />
                    </div>
                </div>

                <!-- Distribution by Difficulty -->
                <div class="border-t pt-4 mt-4">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3">
                        <i class="fas fa-chart-bar mr-2"></i>Distribution by Difficulty Level
                    </h4>

                    <div class="grid grid-cols-3 gap-4">
                        <!-- Easy -->
                        <div class="bg-emerald-50 p-3 rounded-lg border border-emerald-200">
                            <label class="block text-xs font-medium text-emerald-700 mb-2">
                                <i class="fas fa-circle text-emerald-500 mr-1"></i>Easy
                            </label>
                            <input
                                type="number"
                                name="tos[${tosIndex}][distribution][easy][allocation]"
                                min="0"
                                value="0"
                                class="w-full px-3 py-2 border border-emerald-300 rounded-lg focus:ring-2 focus:ring-emerald-500 text-center"
                                placeholder="0"
                                oninput="updateTotalItems(${tosIndex})"
                            />
                            <p class="text-xs text-emerald-600 mt-1 text-center">items</p>
                        </div>

                        <!-- Moderate -->
                        <div class="bg-amber-50 p-3 rounded-lg border border-amber-200">
                            <label class="block text-xs font-medium text-amber-700 mb-2">
                                <i class="fas fa-circle text-amber-500 mr-1"></i>Moderate
                            </label>
                            <input
                                type="number"
                                name="tos[${tosIndex}][distribution][moderate][allocation]"
                                min="0"
                                value="0"
                                class="w-full px-3 py-2 border border-amber-300 rounded-lg focus:ring-2 focus:ring-amber-500 text-center"
                                placeholder="0"
                                oninput="updateTotalItems(${tosIndex})"
                            />
                            <p class="text-xs text-amber-600 mt-1 text-center">items</p>
                        </div>

                        <!-- Difficult -->
                        <div class="bg-red-50 p-3 rounded-lg border border-red-200">
                            <label class="block text-xs font-medium text-red-700 mb-2">
                                <i class="fas fa-circle text-red-500 mr-1"></i>Difficult
                            </label>
                            <input
                                type="number"
                                name="tos[${tosIndex}][distribution][difficult][allocation]"
                                min="0"
                                value="0"
                                class="w-full px-3 py-2 border border-red-300 rounded-lg focus:ring-2 focus:ring-red-500 text-center"
                                placeholder="0"
                                oninput="updateTotalItems(${tosIndex})"
                            />
                            <p class="text-xs text-red-600 mt-1 text-center">items</p>
                        </div>
                    </div>
                </div>
            </div>
        `;
        container.appendChild(newItem);
        tosIndex++;
    }
</script>

@endsection
