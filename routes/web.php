<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentQuizController;
use App\Http\Controllers\StudentTaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // React + TypeScript learning dashboard
    Route::view('/learning', 'react.app')->name('learning.react');

    // Student quick pages
    Route::view('/student/subjects', 'student.subjects')->name('student.subjects');
    Route::get('/student/tasks', [StudentTaskController::class, 'index'])->name('student.tasks');
    Route::get('/student/quizzes', [StudentQuizController::class, 'index'])->name('student.quizzes');
    Route::get('/student/quizzes/{quiz}', [StudentQuizController::class, 'show'])->name('student.quizzes.show');
    Route::post('/student/quizzes/{quiz}', [StudentQuizController::class, 'submit'])->name('student.quizzes.submit');
    Route::get('/student/quiz-attempts/{attempt}', [StudentQuizController::class, 'result'])->name('student.quizzes.result');
    Route::view('/student/achievements', 'student.achievements')->name('student.achievements');

    // Teacher: create subjects & tasks
    Route::get('/teacher/subjects/create', [TeacherController::class, 'createSubject'])->name('teacher.subjects.create');
    Route::post('/teacher/subjects', [TeacherController::class, 'storeSubject'])->name('teacher.subjects.store');

    Route::get('/teacher/tasks/create', [TeacherController::class, 'createTask'])->name('teacher.tasks.create');
    Route::post('/teacher/tasks', [TeacherController::class, 'storeTask'])->name('teacher.tasks.store');

    // Teacher: create quizzes (multiple choice)
    Route::get('/teacher/quizzes/create', [TeacherController::class, 'createQuiz'])->name('teacher.quizzes.create');
    Route::post('/teacher/quizzes', [TeacherController::class, 'storeQuiz'])->name('teacher.quizzes.store');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
