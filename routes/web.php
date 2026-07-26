<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Student;
use App\Http\Controllers\Admin;
use App\Http\Middleware\AuthenticateSession;
use App\Http\Middleware\StudentMiddleware;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Smart Course Registration System (SCRS)
|--------------------------------------------------------------------------
*/

// Entry Point Redirect
Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

// Guest Authentication Routes
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

// Authenticated Session Protected Routes
Route::middleware([AuthenticateSession::class])->group(function () {

    // Student Role Routes
    Route::middleware([StudentMiddleware::class])->prefix('student')->name('student.')->group(function () {
        Route::get('/dashboard', [Student\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/courses', [Student\CourseController::class, 'index'])->name('courses.index');
        Route::get('/courses/my-courses', [Student\CourseController::class, 'myCourses'])->name('courses.my-courses');
        Route::get('/courses/schedule', [Student\CourseController::class, 'schedule'])->name('courses.schedule');
        Route::get('/courses/{course}', [Student\CourseController::class, 'show'])->name('courses.show');
        Route::post('/courses/{course}/register', [Student\CourseController::class, 'register'])->name('courses.register');
        Route::get('/profile', [Student\ProfileController::class, 'index'])->name('profile');
    });

    // Admin Role Routes
    Route::middleware([AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/courses', [Admin\CourseController::class, 'index'])->name('courses.index');
        Route::get('/students', [Admin\StudentController::class, 'index'])->name('students.index');
        Route::get('/registrations', [Admin\RegistrationController::class, 'index'])->name('registrations.index');
    });

});

require __DIR__.'/auth.php';
