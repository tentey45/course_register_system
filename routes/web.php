<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Student;
use App\Http\Controllers\Admin;
use App\Http\Middleware\AuthenticateSession;
use App\Http\Middleware\StudentMiddleware;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

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

// Google OAuth (students only)
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');

// New Google sign-ups finish here (pick department before account is created)
Route::get('/register/complete', [GoogleAuthController::class, 'showCompleteForm'])->name('register.complete');
Route::post('/register/complete', [GoogleAuthController::class, 'storeCompleteForm'])->name('register.complete.store');

/*
|--------------------------------------------------------------------------
| ABA PayWay Return URL (CSRF exempt — ABA POSTs from their servers)
|--------------------------------------------------------------------------
| Must be outside the auth middleware group so ABA's server-side callback
| can reach it without a Laravel session cookie.
| The browser GET redirect also lands here.
*/
Route::match(['get', 'post'], '/student/payment/return', [Student\PaymentController::class, 'handleReturn'])
    ->name('payment.return')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

// Authenticated Session Protected Routes
Route::middleware([AuthenticateSession::class])->group(function () {

    // Student Role Routes
    Route::middleware([StudentMiddleware::class])->prefix('student')->name('student.')->group(function () {
        Route::get('/dashboard', [Student\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/courses', [Student\CourseController::class, 'index'])->name('courses.index');
        Route::get('/courses/my-courses', [Student\CourseController::class, 'myCourses'])->name('courses.my-courses');
        Route::get('/courses/schedule', [Student\CourseController::class, 'schedule'])->name('courses.schedule');
        Route::get('/courses/{course}', [Student\CourseController::class, 'show'])->name('courses.show');
        Route::get('/profile', [Student\ProfileController::class, 'index'])->name('profile');

        // Payment flow (ABA PayWay Payment Link)
        Route::get('/courses/{course}/pay',  [Student\PaymentController::class, 'pay'])->name('payment.pay');
        Route::post('/courses/{course}/pay', [Student\PaymentController::class, 'processPay'])->name('payment.process');
        Route::post('/courses/{course}/pay/cancel', [Student\PaymentController::class, 'cancelPayment'])->name('payment.cancel');
        Route::post('/courses/{course}/pay/check', [Student\PaymentController::class, 'checkStatus'])->name('payment.check');
        Route::get('/payment/checkout/{registration}',[PaymentController::class,'checkout'])->name('student.payment.checkout');
        Route::post('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');

        // Drop a registered course (student)
        Route::post('/courses/{course}/drop', [Student\CourseController::class, 'drop'])->name('courses.drop');
        Route::get('/payment/{payment}/success', [Student\PaymentController::class, 'success'])->name('payment.success');
        Route::get('/payment/{payment}/failed',  [Student\PaymentController::class, 'failed'])->name('payment.failed');
        Route::get('/payment/{payment}/pending', [Student\PaymentController::class, 'pending'])->name('payment.pending');
    });

    // Admin Role Routes
    Route::middleware([AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::get('/departments', [Admin\DepartmentController::class, 'index'])->name('departments.index');
        Route::get('/departments/create', [Admin\DepartmentController::class, 'create'])->name('departments.create');
        Route::post('/departments', [Admin\DepartmentController::class, 'store'])->name('departments.store');
        Route::get('/departments/{department}/edit', [Admin\DepartmentController::class, 'edit'])->name('departments.edit');
        Route::put('/departments/{department}', [Admin\DepartmentController::class, 'update'])->name('departments.update');
        Route::get('/semesters', [Admin\SemesterController::class, 'index'])->name('semesters.index');
        Route::get('/semesters/create', [Admin\SemesterController::class, 'create'])->name('semesters.create');
        Route::post('/semesters', [Admin\SemesterController::class, 'store'])->name('semesters.store');
        Route::get('/semesters/{semester}/edit', [Admin\SemesterController::class, 'edit'])->name('semesters.edit');
        Route::put('/semesters/{semester}', [Admin\SemesterController::class, 'update'])->name('semesters.update');

        Route::get('/courses', [Admin\CourseController::class, 'index'])->name('courses.index');
        Route::get('/courses/create', [Admin\CourseController::class, 'create'])->name('courses.create');
        Route::post('/courses', [Admin\CourseController::class, 'store'])->name('courses.store');
        Route::get('/courses/{course}/edit', [Admin\CourseController::class, 'edit'])->name('courses.edit');
        Route::put('/courses/{course}', [Admin\CourseController::class, 'update'])->name('courses.update');
        Route::delete('/courses/{course}', [Admin\CourseController::class, 'destroy'])->name('courses.destroy');

        Route::get('/students', [Admin\StudentController::class, 'index'])->name('students.index');
        Route::get('/students/create', [Admin\StudentController::class, 'create'])->name('students.create');
        Route::post('/students', [Admin\StudentController::class, 'store'])->name('students.store');
        Route::get('/students/{student}', [Admin\StudentController::class, 'show'])->name('students.show');
        Route::get('/students/{student}/edit', [Admin\StudentController::class, 'edit'])->name('students.edit');
        Route::put('/students/{student}', [Admin\StudentController::class, 'update'])->name('students.update');
        Route::post('/students/{student}/deactivate', [Admin\StudentController::class, 'deactivate'])->name('students.deactivate');
        Route::post('/students/{student}/activate', [Admin\StudentController::class, 'activate'])->name('students.activate');
        Route::get('/registrations', [Admin\RegistrationController::class, 'index'])->name('registrations.index');

        // Payment monitoring & approval
        Route::get('/payments', [Admin\PaymentController::class, 'index'])->name('payments.index');
        Route::get('/payments/{payment}', [Admin\PaymentController::class, 'show'])->name('payments.show');
        Route::post('/payments/{payment}/confirm', [Admin\PaymentController::class, 'confirm'])->name('payments.confirm');
    });

});

require __DIR__.'/auth.php';
