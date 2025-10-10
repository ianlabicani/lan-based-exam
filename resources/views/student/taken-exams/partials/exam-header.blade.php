<!-- Exam Header -->
<div class="bg-white rounded-xl shadow-md p-6 mb-6">
    <div class="flex items-center justify-between flex-wrap gap-4">
        <!-- Title & Info -->
        <div class="flex-1">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $exam->title }}</h1>
            <p class="text-gray-600 text-sm">{{ $exam->description }}</p>
        </div>

        <!-- Timer -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-4 rounded-lg shadow-lg">
            <div class="text-center">
                <p class="text-xs text-indigo-100 mb-1">Time Remaining</p>
                <div class="text-3xl font-bold font-mono" x-text="formatTime(timeRemaining)">00:00:00</div>
                <div class="mt-2 bg-white bg-opacity-20 rounded-full h-1">
                    <div class="bg-white h-1 rounded-full transition-all duration-1000"
                         :style="`width: ${timePercentage}%`"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="mt-6">
        <div class="flex items-center justify-between text-sm mb-2">
            <span class="text-gray-600 font-medium">Progress</span>
            <span class="text-gray-900 font-semibold" x-text="`${answeredCount} / ${totalQuestions} Answered`">0 / {{ $exam->items->count() }} Answered</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-3">
            <div class="bg-green-500 h-3 rounded-full transition-all duration-300"
                 :style="`width: ${progressPercentage}%`"></div>
        </div>
    </div>
</div>
