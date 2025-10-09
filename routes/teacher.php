<?php

use App\Http\Controllers\Teacher\ExamController;
use App\Http\Controllers\Teacher\ExamItemController;
use App\Http\Controllers\Teacher\TakenExamController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', function () {
        return view('teacher.dashboard');
    })->name('dashboard');

    // Exam Routes
    Route::prefix('exams')->name('exams.')->group(function () {
        Route::get('/', [ExamController::class, 'index'])->name('index');
        Route::get('/create', [ExamController::class, 'create'])->name('create');
        Route::post('/', [ExamController::class, 'store'])->name('store');
        Route::get('/{id}', [ExamController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ExamController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ExamController::class, 'update'])->name('update');
        Route::patch('/{id}/status', [ExamController::class, 'updateStatus'])->name('updateStatus');
        Route::delete('/{id}', [ExamController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/takers', [ExamController::class, 'getExamTakers'])->name('takers');

        // Exam Items Routes
        Route::post('/{examId}/items', [ExamItemController::class, 'store'])->name('items.store');
        Route::put('/{examId}/items/{itemId}', [ExamItemController::class, 'update'])->name('items.update');
        Route::delete('/{examId}/items/{itemId}', [ExamItemController::class, 'destroy'])->name('items.destroy');

        // Taken Exams Routes
        Route::get('/{examId}/taken-exams', [TakenExamController::class, 'index'])->name('takenExams.index');
        Route::get('/{examId}/taken-exams/{takenExamId}', [TakenExamController::class, 'show'])->name('takenExams.show');
        Route::patch('/{examId}/taken-exams/{takenExamId}/answers/{answerId}/points', [TakenExamController::class, 'updatePoints'])->name('takenExams.updatePoints');
    });

    // Analytics Route
    Route::get('/analytics', function () {
        return view('teacher.analytics');
    })->name('analytics');

    // Grading Route
    Route::get('/grading', function () {
        return view('teacher.grading');
    })->name('grading');
});
