<?php

use App\Http\Controllers\Admin\BirthdayMessageController;
use App\Http\Controllers\Admin\BirthdayProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\LoveLetterController;
use App\Http\Controllers\Admin\MemoryController;
use App\Http\Controllers\Admin\MusicPlaylistController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SurpriseController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
Route::get('/surprise/admin', [AuthController::class, 'showAdminLogin'])->name('admin.login');
Route::post('/surprise/admin', [AuthController::class, 'adminLogin'])->name('admin.login.attempt');
Route::get('/admin/login', fn () => redirect()->route('admin.login'));
Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.legacy.attempt');

Route::middleware('auth')->group(function () {
    Route::get('/surprise', [SurpriseController::class, 'index'])->name('surprise');
    Route::get('/memories', fn () => redirect(route('surprise').'#timeline'))->name('memories');
    Route::get('/gallery', fn () => redirect(route('surprise').'#gallery'))->name('gallery');
    Route::get('/music', fn () => redirect(route('surprise').'#music'))->name('music');
    Route::get('/letter', fn () => redirect(route('surprise').'#letter'))->name('letter');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [BirthdayProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [BirthdayProfileController::class, 'update'])->name('profile.update');
    Route::resource('memories', MemoryController::class)->except(['show']);
    Route::resource('gallery', GalleryController::class)->parameters(['gallery' => 'gallery'])->except(['show']);
    Route::resource('messages', BirthdayMessageController::class)->except(['show']);
    Route::resource('music', MusicPlaylistController::class)->parameters(['music' => 'music'])->except(['show']);
    Route::get('/love-letter', [LoveLetterController::class, 'edit'])->name('letter.edit');
    Route::put('/love-letter', [LoveLetterController::class, 'update'])->name('letter.update');
    Route::get('/settings', [SiteSettingController::class, 'edit'])->name('settings.edit');
    Route::put('/settings', [SiteSettingController::class, 'update'])->name('settings.update');
    Route::get('/user', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/user', [UserController::class, 'update'])->name('user.update');
});
