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
                        <label for="year" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar mr-2 text-gray-500"></i>Academic Year *
                        </label>
                        <input
                            type="text"
                            id="year"
                            name="year"
                            required
                            value="{{ old('year', now()->year) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                            placeholder="e.g., 2025"
                        />
                        @error('year')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Sections -->
                    <div>
                        <label for="sections" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-users-class mr-2 text-gray-500"></i>Sections *
                        </label>
                        <input
                            type="text"
                            id="sections"
                            name="sections"
                            required
                            value="{{ old('sections') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                            placeholder="e.g., A, B, C or just A"
                        />
                        @error('sections')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                        <p class="mt-2 text-xs text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>Enter sections separated by commas (e.g., "A, B, C")
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
                            value="{{ old('starts_at') }}"
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
                            value="{{ old('ends_at') }}"
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

                <div id="tosContainer" class="space-y-4">
                    <!-- TOS Item Template -->
                    <div class="tos-item border border-gray-200 rounded-lg p-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Topic/Objective</label>
                                <input
                                    type="text"
                                    name="tos[0][topic]"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                                    placeholder="e.g., Arrays and Linked Lists"
                                />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Number of Items</label>
                                <input
                                    type="number"
                                    name="tos[0][items]"
                                    min="1"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                                    placeholder="5"
                                />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Points per Item</label>
                                <input
                                    type="number"
                                    name="tos[0][points]"
                                    min="1"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                                    placeholder="2"
                                />
                            </div>
                        </div>
                    </div>
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

            <!-- Settings Card -->
            <div class="bg-white rounded-xl shadow-md p-6" style="display: none;">
                <h2 class="text-xl font-bold text-gray-900 mb-6 border-b border-gray-200 pb-4">
                    <i class="fas fa-cog text-indigo-600 mr-2"></i>Exam Settings (Optional)
                </h2>

                <div class="space-y-4">
                    <!-- Total Points (Auto-calculated from TOS) -->
                    <input type="hidden" name="total_points" value="0">
                </div>
            </div>

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
    let tosIndex = 1;

    function addTosItem() {
        const container = document.getElementById('tosContainer');
        const newItem = document.createElement('div');
        newItem.className = 'tos-item border border-gray-200 rounded-lg p-4';
        newItem.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Topic/Objective</label>
                    <input
                        type="text"
                        name="tos[${tosIndex}][topic]"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                        placeholder="e.g., Stacks and Queues"
                    />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Number of Items</label>
                    <input
                        type="number"
                        name="tos[${tosIndex}][items]"
                        min="1"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                        placeholder="5"
                    />
                </div>
                <div class="flex items-end space-x-2">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Points per Item</label>
                        <input
                            type="number"
                            name="tos[${tosIndex}][points]"
                            min="1"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                            placeholder="2"
                        />
                    </div>
                    <button
                        type="button"
                        onclick="this.closest('.tos-item').remove()"
                        class="px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition duration-200"
                    >
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        container.appendChild(newItem);
        tosIndex++;
    }
</script>

@endsection
