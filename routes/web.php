<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Client\DashboardController;
use App\Http\Controllers\Client\EnrollmentController;
use App\Http\Controllers\Client\LessonController;
use App\Http\Controllers\Client\ProfileController;
use App\Http\Controllers\Client\ProgressController;
use App\Http\Controllers\Payment\CheckoutController;
use App\Http\Controllers\Public\AboutController;
use App\Http\Controllers\Public\CourseController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\LegalController;
use App\Http\Controllers\Webhook\StripeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::domain('playbook.nativescale.com')->group(function () {
    Route::get('/', [CourseController::class, 'show'])->name('playbook.home');
    Route::get('/{slug}', [CourseController::class, 'show'])->name('playbook.course');
});

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/formations', [CourseController::class, 'index'])->name('courses.index');
Route::get('/formations/{slug}', [CourseController::class, 'show'])->name('courses.show');
Route::get('/a-propos', [AboutController::class, 'index'])->name('about');
Route::get('/mentions-legales', [LegalController::class, 'mentions'])->name('legal.mentions');
Route::get('/cgv', [LegalController::class, 'cgv'])->name('legal.cgv');
Route::get('/politique-confidentialite', [LegalController::class, 'privacy'])->name('legal.privacy');
Route::get('/temoignages', function () {
    return Inertia\Inertia::render('Public/Testimonials');
})->name('testimonials');

/*
|--------------------------------------------------------------------------
| Payment Routes
|--------------------------------------------------------------------------
*/
Route::post('/checkout/{course}', [CheckoutController::class, 'create'])->name('checkout.create');
Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/checkout/cancel', [CheckoutController::class, 'cancel'])->name('checkout.cancel');

/*
|--------------------------------------------------------------------------
| Stripe Webhook (unprotected - verified by signature)
|--------------------------------------------------------------------------
*/
Route::post('/stripe/webhook', [StripeController::class, 'handle'])
    ->withoutMiddleware([\App\Http\Middleware\XssProtection::class])
    ->name('stripe.webhook');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Client Routes (Authenticated)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/mes-formations', [EnrollmentController::class, 'index'])->name('enrollments.index');
    Route::get('/mes-formations/{slug}', [EnrollmentController::class, 'show'])->name('enrollments.show');
    Route::get('/lecon/{lesson}', [LessonController::class, 'show'])->name('lesson.show');
    Route::post('/lecon/{lesson}/progress', [ProgressController::class, 'update'])->name('lesson.progress');
    Route::get('/profil', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
});

