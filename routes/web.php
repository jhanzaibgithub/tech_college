<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\EnrollmentController as PublicEnrollmentController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\MaintenanceController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\TickerItemController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EnrollmentController;
use App\Http\Controllers\Admin\NewsEventController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\StudentTestimonialController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/courses', [HomeController::class, 'courses'])->name('courses.index');
Route::get('/courses/{slug}', [HomeController::class, 'course'])->name('courses.show');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::middleware('throttle:10,1')->group(function () {
    Route::post('/enroll', [PublicEnrollmentController::class, 'apply'])->name('enroll.store');
    Route::post('/courses/{slug}/enroll', [PublicEnrollmentController::class, 'store'])->name('courses.enroll');
    Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    });

    Route::middleware('auth:admin')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', DashboardController::class)->name('dashboard');
        Route::resource('banners', BannerController::class)->except(['show']);
        Route::get('/courses/slug-check', [CourseController::class, 'slugCheck'])->middleware('throttle:60,1')->name('courses.slug-check');
        Route::post('/courses/reorder', [CourseController::class, 'reorder'])->name('courses.reorder');
        Route::resource('courses', CourseController::class)->except(['show']);
        Route::post('/testimonials/reorder', [StudentTestimonialController::class, 'reorder'])->name('testimonials.reorder');
        Route::resource('testimonials', StudentTestimonialController::class)->except(['show']);
        Route::post('/news-events/reorder', [NewsEventController::class, 'reorder'])->name('news-events.reorder');
        Route::resource('news-events', NewsEventController::class)->except(['show']);
        Route::patch('/ticker/{ticker}/toggle', [TickerItemController::class, 'toggle'])->name('ticker.toggle');
        Route::resource('ticker', TickerItemController::class)->only(['index', 'store', 'edit', 'update', 'destroy']);
        Route::get('/enrollments', [EnrollmentController::class, 'index'])->name('enrollments.index');
        Route::patch('/enrollments/{enrollment}', [EnrollmentController::class, 'update'])->name('enrollments.update');
        Route::delete('/enrollments/{enrollment}', [EnrollmentController::class, 'destroy'])->name('enrollments.destroy');
        Route::get('/messages', [ContactMessageController::class, 'index'])->name('messages.index');
        Route::patch('/messages/{message}', [ContactMessageController::class, 'update'])->name('messages.update');
        Route::delete('/messages/{message}', [ContactMessageController::class, 'destroy'])->name('messages.destroy');
        Route::get('/settings/{section}', [SiteSettingController::class, 'edit'])->name('settings.edit');
        Route::put('/settings/{section}', [SiteSettingController::class, 'update'])->name('settings.update');
        Route::get('/maintenance', [MaintenanceController::class, 'index'])->name('maintenance.index');
        Route::middleware('throttle:10,1')->group(function () {
            Route::post('/maintenance/clear-cache', [MaintenanceController::class, 'clearCache'])->name('maintenance.clear-cache');
            Route::post('/maintenance/storage-link', [MaintenanceController::class, 'storageLink'])->name('maintenance.storage-link');
        });
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    });
});
