<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', function () {
        return view('teacher.dashboard');
    })->name('dashboard');

    // Exam Routes
    Route::prefix('exams')->name('exams.')->group(function () {
        Route::get('/', function () {
            return view('teacher.exams.index');
        })->name('index');

        Route::get('/create', function () {
            return view('teacher.exams.create');
        })->name('create');

        Route::post('/', function () {
            // Store exam logic
            return redirect()->route('teacher.exams.index');
        })->name('store');

        Route::get('/{id}', function ($id) {
            return view('teacher.exams.show', ['id' => $id]);
        })->name('show');

        Route::get('/{id}/edit', function ($id) {
            return view('teacher.exams.edit', ['id' => $id]);
        })->name('edit');

        Route::put('/{id}', function ($id) {
            // Update exam logic
            return redirect()->route('teacher.exams.show', $id);
        })->name('update');

        Route::delete('/{id}', function ($id) {
            // Delete exam logic
            return redirect()->route('teacher.exams.index');
        })->name('destroy');
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
