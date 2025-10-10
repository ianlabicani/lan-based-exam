@extends('teacher.shell')

@section('teacher-content')

<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-4 mb-4">
                <a href="{{ route('teacher.exams.index') }}" class="text-gray-600 hover:text-gray-900">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">
                        <i class="fas fa-plus-circle text-indigo-600 mr-3"></i>Create New Exam
                    </h1>
                    <p class="text-gray-600 mt-2">Fill in the details to create a new examination</p>
                </div>
            </div>
        </div>

        <!-- Create Exam Form -->
        <form action="{{ route('teacher.exams.store') }}" method="POST" class="space-y-6">
            @csrf

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
                            value="{{ old('title') }}"
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
                        >{{ old('description') }}</textarea>
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
                                        {{ in_array($yearOption, old('year', [])) ? 'checked' : '' }}
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
                                        {{ in_array($sectionOption, old('sections', [])) ? 'checked' : '' }}
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

                <div class="space-y-6">
                    <!-- Start Date & Time -->
                    <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-5">
                        <label class="block text-sm font-semibold text-emerald-800 mb-3">
                            <i class="fas fa-play-circle mr-2"></i>Exam Start *
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="start_date" class="block text-xs font-medium text-gray-600 mb-2">Date</label>
                                <input
                                    type="date"
                                    id="start_date"
                                    name="start_date"
                                    required
                                    value="{{ old('start_date') }}"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition duration-200 bg-white"
                                />
                            </div>
                            <div>
                                <label for="start_time" class="block text-xs font-medium text-gray-600 mb-2">Time</label>
                                <input
                                    type="time"
                                    id="start_time"
                                    name="start_time"
                                    required
                                    value="{{ old('start_time') }}"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition duration-200 bg-white"
                                />
                            </div>
                        </div>
                        @error('starts_at')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- End Date & Time -->
                    <div class="bg-red-50 border border-red-200 rounded-lg p-5">
                        <label class="block text-sm font-semibold text-red-800 mb-3">
                            <i class="fas fa-stop-circle mr-2"></i>Exam End *
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="end_date" class="block text-xs font-medium text-gray-600 mb-2">Date</label>
                                <input
                                    type="date"
                                    id="end_date"
                                    name="end_date"
                                    required
                                    value="{{ old('end_date') }}"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-200 bg-white"
                                />
                            </div>
                            <div>
                                <label for="end_time" class="block text-xs font-medium text-gray-600 mb-2">Time</label>
                                <input
                                    type="time"
                                    id="end_time"
                                    name="end_time"
                                    required
                                    value="{{ old('end_time') }}"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-200 bg-white"
                                />
                            </div>
                        </div>
                        @error('ends_at')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Duration Display (Optional Info) -->
                    <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4" x-data="scheduleCalculator()" x-init="init()">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-clock text-indigo-600"></i>
                                <span class="text-sm font-medium text-indigo-900">Exam Duration:</span>
                            </div>
                            <span class="text-lg font-bold text-indigo-700" x-text="duration"></span>
                        </div>
                    </div>
                </div>

                <!-- Hidden inputs for backward compatibility -->
                <input type="hidden" id="starts_at" name="starts_at" value="{{ old('starts_at') }}">
                <input type="hidden" id="ends_at" name="ends_at" value="{{ old('ends_at') }}">
            </div>

            <!-- Table of Specifications (TOS) Card -->
            <div class="bg-white rounded-xl shadow-md p-6" x-data="tosManager()">
                <h2 class="text-xl font-bold text-gray-900 mb-6 border-b border-gray-200 pb-4">
                    <i class="fas fa-table text-indigo-600 mr-2"></i>Table of Specifications (TOS)
                </h2>

                <!-- Total Items Input -->
                <div class="mb-6 bg-indigo-50 border border-indigo-200 rounded-lg p-5">
                    <label for="total_items_input" class="block text-sm font-semibold text-indigo-900 mb-3">
                        <i class="fas fa-hashtag mr-2"></i>Total Number of Exam Items *
                    </label>
                    <input
                        type="number"
                        id="total_items_input"
                        x-model.number="totalItems"
                        @input="recalculateAll()"
                        min="1"
                        required
                        class="w-full px-4 py-3 border border-indigo-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 text-lg font-semibold text-center"
                        placeholder="e.g., 50"
                    />
                    <p class="mt-2 text-xs text-indigo-700">
                        <i class="fas fa-info-circle mr-1"></i>This will be automatically distributed across topics based on their time allotment
                    </p>
                </div>

                <div id="tosContainer" class="space-y-6">
                    <!-- TOS Items will be rendered here by Alpine.js -->
                    <template x-for="(topic, index) in topics" :key="index">
                        <div class="tos-item border border-gray-200 rounded-lg p-6 bg-gray-50">
                            <div class="space-y-4">
                                <!-- Topic -->
                                <div class="flex items-center justify-between">
                                    <label class="block text-sm font-medium text-gray-700">Topic/Objective *</label>
                                    <button
                                        type="button"
                                        @click="removeTopic(index)"
                                        x-show="topics.length > 1"
                                        class="px-3 py-1 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition duration-200 text-sm"
                                    >
                                        <i class="fas fa-trash mr-1"></i>Remove
                                    </button>
                                </div>
                                <input
                                    type="text"
                                    :name="`tos[${index}][topic]`"
                                    x-model="topic.topic"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                                    placeholder="e.g., Arrays and Linked Lists"
                                />

                                <!-- Time Allotment and Total Items -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Time Allotment (hours) *
                                        </label>
                                        <input
                                            type="number"
                                            :name="`tos[${index}][time_allotment]`"
                                            x-model.number="topic.time_allotment"
                                            @input="recalculateAll()"
                                            min="0.5"
                                            step="0.5"
                                            required
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                                            placeholder="e.g., 6"
                                        />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Total Number of Items
                                            <span class="text-xs text-gray-500">(Auto-calculated)</span>
                                        </label>
                                        <input
                                            type="number"
                                            :name="`tos[${index}][no_of_items]`"
                                            :value="topic.no_of_items"
                                            readonly
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-center font-semibold text-lg"
                                        />
                                    </div>
                                </div>

                                <!-- Distribution by Difficulty -->
                                <div class="border-t pt-4 mt-4">
                                    <h4 class="text-sm font-semibold text-gray-700 mb-3">
                                        <i class="fas fa-chart-bar mr-2"></i>Distribution by Difficulty Level (Auto-calculated)
                                    </h4>

                                    <div class="grid grid-cols-3 gap-4">
                                        <!-- Easy (30%) -->
                                        <div class="bg-emerald-50 p-3 rounded-lg border border-emerald-200">
                                            <label class="block text-xs font-medium text-emerald-700 mb-2">
                                                <i class="fas fa-circle text-emerald-500 mr-1"></i>Easy (30%)
                                            </label>
                                            <input
                                                type="number"
                                                :name="`tos[${index}][distribution][easy][allocation]`"
                                                :value="topic.distribution.easy.allocation"
                                                readonly
                                                class="w-full px-3 py-2 border border-emerald-300 rounded-lg bg-emerald-100 text-center font-semibold"
                                            />
                                            <input type="hidden" :name="`tos[${index}][distribution][easy][percentage]`" value="30%" />
                                            <p class="text-xs text-emerald-600 mt-1 text-center">items</p>
                                        </div>

                                        <!-- Average (50%) -->
                                        <div class="bg-amber-50 p-3 rounded-lg border border-amber-200">
                                            <label class="block text-xs font-medium text-amber-700 mb-2">
                                                <i class="fas fa-circle text-amber-500 mr-1"></i>Average (50%)
                                            </label>
                                            <input
                                                type="number"
                                                :name="`tos[${index}][distribution][average][allocation]`"
                                                :value="topic.distribution.average.allocation"
                                                readonly
                                                class="w-full px-3 py-2 border border-amber-300 rounded-lg bg-amber-100 text-center font-semibold"
                                            />
                                            <input type="hidden" :name="`tos[${index}][distribution][average][percentage]`" value="50%" />
                                            <p class="text-xs text-amber-600 mt-1 text-center">items</p>
                                        </div>

                                        <!-- Difficult (20%) -->
                                        <div class="bg-red-50 p-3 rounded-lg border border-red-200">
                                            <label class="block text-xs font-medium text-red-700 mb-2">
                                                <i class="fas fa-circle text-red-500 mr-1"></i>Difficult (20%)
                                            </label>
                                            <input
                                                type="number"
                                                :name="`tos[${index}][distribution][difficult][allocation]`"
                                                :value="topic.distribution.difficult.allocation"
                                                readonly
                                                class="w-full px-3 py-2 border border-red-300 rounded-lg bg-red-100 text-center font-semibold"
                                            />
                                            <input type="hidden" :name="`tos[${index}][distribution][difficult][percentage]`" value="20%" />
                                            <p class="text-xs text-red-600 mt-1 text-center">items</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <button
                    type="button"
                    @click="addTopic()"
                    class="mt-4 px-4 py-2 bg-indigo-100 text-indigo-700 rounded-lg font-medium hover:bg-indigo-200 transition duration-200"
                >
                    <i class="fas fa-plus-circle mr-2"></i>Add Another Topic
                </button>

                <!-- Calculation Summary -->
                <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4" x-show="totalItems > 0">
                    <div class="flex items-center justify-between text-sm">
                        <span class="font-medium text-blue-900">
                            <i class="fas fa-calculator mr-2"></i>Computed Total Items:
                        </span>
                        <span class="text-lg font-bold" :class="computedTotal === totalItems ? 'text-green-600' : 'text-red-600'" x-text="computedTotal"></span>
                    </div>
                    <div class="mt-2 text-xs text-blue-700" x-show="computedTotal !== totalItems">
                        <i class="fas fa-info-circle mr-1"></i>Note: Minor rounding adjustments may occur to match the exact total
                    </div>
                </div>

                @error('tos')
                    <p class="mt-2 text-sm text-red-600 flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Hidden field for total_points -->
            <input type="hidden" name="total_points" x-ref="totalPointsField" />

            <!-- Form Actions -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between">
                    <a href="{{ route('teacher.exams.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition duration-200">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </a>
                    <div class="flex space-x-3">
                        <button
                            type="submit"
                            name="status"
                            value="draft"
                            class="px-6 py-3 bg-yellow-600 text-white rounded-lg font-medium hover:bg-yellow-700 transition duration-200 flex items-center space-x-2"
                        >
                            <i class="fas fa-save"></i>
                            <span>Save as Draft</span>
                        </button>
                        <button
                            type="submit"
                            name="status"
                            value="published"
                            class="px-6 py-3 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition duration-200 flex items-center space-x-2"
                        >
                            <i class="fas fa-check-circle"></i>
                            <span>Create & Add Questions</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // Schedule Calculator for Alpine.js
    function scheduleCalculator() {
        return {
            duration: 'Not set',

            init() {
                // Watch for changes in date/time inputs
                const inputs = ['start_date', 'start_time', 'end_date', 'end_time'];
                inputs.forEach(id => {
                    const element = document.getElementById(id);
                    if (element) {
                        element.addEventListener('change', () => this.calculate());
                    }
                });

                // Initial calculation
                this.calculate();
            },

            calculate() {
                const startDate = document.getElementById('start_date').value;
                const startTime = document.getElementById('start_time').value;
                const endDate = document.getElementById('end_date').value;
                const endTime = document.getElementById('end_time').value;

                // Combine date and time for hidden inputs
                if (startDate && startTime) {
                    document.getElementById('starts_at').value = `${startDate}T${startTime}`;
                }
                if (endDate && endTime) {
                    document.getElementById('ends_at').value = `${endDate}T${endTime}`;
                }

                // Calculate duration
                if (startDate && startTime && endDate && endTime) {
                    const start = new Date(`${startDate}T${startTime}`);
                    const end = new Date(`${endDate}T${endTime}`);

                    const diffMs = end - start;

                    if (diffMs <= 0) {
                        this.duration = 'Invalid (end before start)';
                        return;
                    }

                    const diffMins = Math.floor(diffMs / 60000);
                    const hours = Math.floor(diffMins / 60);
                    const minutes = diffMins % 60;
                    const days = Math.floor(hours / 24);
                    const remainingHours = hours % 24;

                    let parts = [];
                    if (days > 0) parts.push(`${days} day${days > 1 ? 's' : ''}`);
                    if (remainingHours > 0) parts.push(`${remainingHours} hour${remainingHours > 1 ? 's' : ''}`);
                    if (minutes > 0) parts.push(`${minutes} min${minutes > 1 ? 's' : ''}`);

                    this.duration = parts.length > 0 ? parts.join(', ') : 'Less than a minute';
                } else {
                    this.duration = 'Not set';
                }
            }
        };
    }

    // TOS Manager for Alpine.js
    function tosManager() {
        return {
            totalItems: 0,
            topics: [
                {
                    topic: '',
                    time_allotment: 0,
                    no_of_items: 0,
                    distribution: {
                        easy: { allocation: 0, percentage: '30%' },
                        average: { allocation: 0, percentage: '50%' },
                        difficult: { allocation: 0, percentage: '20%' }
                    }
                }
            ],

            init() {
                // Update total_points hidden field whenever totalItems changes
                this.$watch('totalItems', value => {
                    const field = document.querySelector('input[name="total_points"]');
                    if (field) {
                        field.value = value || 0;
                    }
                });
            },

            get computedTotal() {
                return this.topics.reduce((sum, topic) => sum + topic.no_of_items, 0);
            },

            addTopic() {
                this.topics.push({
                    topic: '',
                    time_allotment: 0,
                    no_of_items: 0,
                    distribution: {
                        easy: { allocation: 0, percentage: '30%' },
                        average: { allocation: 0, percentage: '50%' },
                        difficult: { allocation: 0, percentage: '20%' }
                    }
                });
                this.recalculateAll();
            },

            removeTopic(index) {
                if (this.topics.length > 1) {
                    this.topics.splice(index, 1);
                    this.recalculateAll();
                }
            },

            recalculateAll() {
                if (this.totalItems <= 0) {
                    // Reset all topics if no total items
                    this.topics.forEach(topic => {
                        topic.no_of_items = 0;
                        topic.distribution.easy.allocation = 0;
                        topic.distribution.average.allocation = 0;
                        topic.distribution.difficult.allocation = 0;
                    });
                    return;
                }

                // Step 1: Calculate total time allotment
                const totalTimeAllotment = this.topics.reduce((sum, topic) => sum + (parseFloat(topic.time_allotment) || 0), 0);

                if (totalTimeAllotment === 0) {
                    // Reset if no time allotment
                    this.topics.forEach(topic => {
                        topic.no_of_items = 0;
                        topic.distribution.easy.allocation = 0;
                        topic.distribution.average.allocation = 0;
                        topic.distribution.difficult.allocation = 0;
                    });
                    return;
                }

                // Step 2: Calculate items per topic based on time proportion
                let remainingItems = this.totalItems;
                let calculatedItems = [];

                this.topics.forEach((topic, index) => {
                    const timeAllotment = parseFloat(topic.time_allotment) || 0;
                    const proportion = timeAllotment / totalTimeAllotment;
                    const items = Math.round(proportion * this.totalItems);
                    calculatedItems.push(items);
                    remainingItems -= items;
                });

                // Step 3: Adjust last topic to ensure exact total (handle rounding)
                if (calculatedItems.length > 0) {
                    calculatedItems[calculatedItems.length - 1] += remainingItems;
                }

                // Step 4: Apply calculations and distribute by difficulty
                this.topics.forEach((topic, index) => {
                    const topicItems = Math.max(0, calculatedItems[index] || 0);
                    topic.no_of_items = topicItems;

                    // Calculate difficulty distribution (30% easy, 50% average, 20% difficult)
                    const easyCount = Math.round(topicItems * 0.3);
                    const difficultCount = Math.round(topicItems * 0.2);
                    const averageCount = topicItems - easyCount - difficultCount; // Remainder goes to average

                    topic.distribution.easy.allocation = easyCount;
                    topic.distribution.average.allocation = Math.max(0, averageCount);
                    topic.distribution.difficult.allocation = difficultCount;
                });
            }
        };
    }
</script>

@endsection
