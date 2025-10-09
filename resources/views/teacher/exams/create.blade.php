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

                    <!-- Instructions -->
                    <div>
                        <label for="instructions" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-list-ol mr-2 text-gray-500"></i>Instructions for Students
                        </label>
                        <textarea
                            id="instructions"
                            name="instructions"
                            rows="6"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                            placeholder="Enter detailed instructions for students taking this exam..."
                        >{{ old('instructions') }}</textarea>
                        @error('instructions')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                        <p class="mt-2 text-xs text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>Provide clear instructions about exam rules, time limits, and any special requirements.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Schedule Card -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 border-b border-gray-200 pb-4">
                    <i class="fas fa-calendar-alt text-indigo-600 mr-2"></i>Schedule & Duration
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Start Time -->
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-play-circle mr-2 text-gray-500"></i>Start Date & Time *
                        </label>
                        <input
                            type="datetime-local"
                            id="start_time"
                            name="start_time"
                            required
                            value="{{ old('start_time') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                        />
                        @error('start_time')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- End Time -->
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-stop-circle mr-2 text-gray-500"></i>End Date & Time *
                        </label>
                        <input
                            type="datetime-local"
                            id="end_time"
                            name="end_time"
                            required
                            value="{{ old('end_time') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                        />
                        @error('end_time')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Duration -->
                    <div>
                        <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-clock mr-2 text-gray-500"></i>Duration (minutes) *
                        </label>
                        <input
                            type="number"
                            id="duration"
                            name="duration"
                            required
                            min="1"
                            value="{{ old('duration', 60) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                            placeholder="60"
                        />
                        @error('duration')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                        <p class="mt-2 text-xs text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>Time allowed for students to complete the exam
                        </p>
                    </div>

                    <!-- Total Points -->
                    <div>
                        <label for="total_points" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-star mr-2 text-gray-500"></i>Total Points
                        </label>
                        <input
                            type="number"
                            id="total_points"
                            name="total_points"
                            min="0"
                            value="{{ old('total_points', 100) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                            placeholder="100"
                        />
                        @error('total_points')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Settings Card -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 border-b border-gray-200 pb-4">
                    <i class="fas fa-cog text-indigo-600 mr-2"></i>Exam Settings
                </h2>

                <div class="space-y-4">
                    <!-- Shuffle Questions -->
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-start space-x-3">
                            <input
                                type="checkbox"
                                id="shuffle_questions"
                                name="shuffle_questions"
                                value="1"
                                {{ old('shuffle_questions') ? 'checked' : '' }}
                                class="mt-1 w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                            />
                            <div>
                                <label for="shuffle_questions" class="font-medium text-gray-900 cursor-pointer">
                                    Shuffle Questions
                                </label>
                                <p class="text-sm text-gray-600">Randomize the order of questions for each student</p>
                            </div>
                        </div>
                    </div>

                    <!-- Show Results Immediately -->
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-start space-x-3">
                            <input
                                type="checkbox"
                                id="show_results"
                                name="show_results"
                                value="1"
                                {{ old('show_results') ? 'checked' : '' }}
                                class="mt-1 w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                            />
                            <div>
                                <label for="show_results" class="font-medium text-gray-900 cursor-pointer">
                                    Show Results Immediately
                                </label>
                                <p class="text-sm text-gray-600">Display scores to students right after submission</p>
                            </div>
                        </div>
                    </div>

                    <!-- Enable Activity Monitoring -->
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-start space-x-3">
                            <input
                                type="checkbox"
                                id="monitor_activity"
                                name="monitor_activity"
                                value="1"
                                checked
                                class="mt-1 w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                            />
                            <div>
                                <label for="monitor_activity" class="font-medium text-gray-900 cursor-pointer">
                                    Enable Activity Monitoring
                                </label>
                                <p class="text-sm text-gray-600">Track tab switches and window focus changes during exam</p>
                            </div>
                        </div>
                    </div>
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
                            <span>Create & Publish</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
