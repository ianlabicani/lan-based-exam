<?php

use App\Http\Controllers\Student\ExamController;
use App\Http\Controllers\Student\TakenExamController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('student.exams.index');
    })->name('dashboard');

    // Exam Routes for Students
    Route::prefix('exams')->name('exams.')->group(function () {
        // List available exams
        Route::get('/', [ExamController::class, 'index'])->name('index');

        // Take exam
        Route::get('/{id}/take', [ExamController::class, 'take'])->name('take');
        Route::post('/{id}/start', [ExamController::class, 'start'])->name('start');

        // Save answer (AJAX)
        Route::post('/{id}/save-answer', [ExamController::class, 'saveAnswer'])->name('saveAnswer');

        // Submit exam
        Route::post('/{id}/submit', [ExamController::class, 'submit'])->name('submit');

        // View results
        Route::get('/{id}/results', [ExamController::class, 'results'])->name('results');
    });

    // My Exam History
    Route::get('/my-exams', [TakenExamController::class, 'myExams'])->name('myExams');
});
