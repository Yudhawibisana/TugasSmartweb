<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Back\UserController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Back\CategoryController;
use App\Http\Controllers\Back\DashboardController;
use App\Http\Controllers\Back\PostinganController;
use App\Http\Controllers\Front\CategoryController as FrontCategoryController;
use App\Http\Controllers\Front\PostinganController as FrontPostinganController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Front\ProfileController;
use App\Http\Controllers\Back\ApprovalController;

// Halaman utama
Route::get('/', [HomeController::class, 'index']);
Route::post('/postingan/search', [HomeController::class, 'index'])->name('search');

// Halaman postingan publik
Route::get('/p/{slug}', [FrontPostinganController::class, 'show'])->name('postingan.show');
Route::get('category/{slug}', [FrontCategoryController::class, 'index']);

// Login Google
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// Update password (autentikasi diperlukan)
Route::put('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');

// Route dengan autentikasi
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Profil
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/categories', [ProfileController::class, 'updateCategories'])->name('profile.updateCategories');
    Route::delete('/profile/categories/{category}', [ProfileController::class, 'removeCategory'])->name('profile.remove-category');
    Route::put('/profile/update-biodata', [ProfileController::class, 'updateBiodata'])->name('profile.updateBiodata');
    Route::post('/profile/upload-photo', [ProfileController::class, 'uploadPhoto'])->name('profile.uploadPhoto');
    Route::post('/profile/certificate', [ProfileController::class, 'uploadCertificate'])->name('profile.uploadCertificate');

    // Postingan
    Route::resource('/postingan', PostinganController::class);

    // Hanya untuk admin (UserAccess:1)
    Route::resource('categories', CategoryController::class)->only(['index', 'store', 'update', 'destroy'])->middleware([\App\Http\Middleware\UserAccess::class . ':1']);
    Route::resource('/users', UserController::class);

    // Fitur join grup
    Route::post('postingan/{id}/request-join', [FrontPostinganController::class, 'requestJoin'])->name('postingan.requestJoin');
    Route::get('postingan/{id}/requests', [PostinganController::class, 'joinRequests'])->name('postingan.joinRequests');
    Route::post('postingan/{postingan_id}/approve/{user_id}', [PostinganController::class, 'approveJoin'])->name('postingan.approveJoin');
    Route::post('postingan/{postingan_id}/reject/{user_id}', [PostinganController::class, 'rejectJoin'])->name('postingan.rejectJoin');

    // Laravel File Manager (harus di dalam middleware web + auth)
    Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
        \UniSharp\LaravelFilemanager\Lfm::routes();
    });
});

// Hanya untuk admin (Approval sistem)
Route::middleware(['auth', \App\Http\Middleware\UserAccess::class . ':1'])->group(function () {
    Route::get('/approval', [ApprovalController::class, 'index'])->name('approval.index');
    Route::post('/approval/{id}/approve', [ApprovalController::class, 'approve'])->name('approval.approve');
    Route::post('/approval/{id}/reject', [ApprovalController::class, 'reject'])->name('approval.reject');
});

// Auth bawaan Laravel
Auth::routes();

// Redirect setelah login
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
