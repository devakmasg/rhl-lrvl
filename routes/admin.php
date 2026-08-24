<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DirectorController;
use App\Http\Controllers\Admin\InquiryController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TeamMemberController;
use App\Http\Controllers\Admin\TestimonialController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('admin.auth')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('projects', ProjectController::class);
        Route::post('projects/{project}/images', [ProjectController::class, 'storeImage'])->name('projects.images.store');
        Route::delete('projects/{project}/images/{image}', [ProjectController::class, 'destroyImage'])->name('projects.images.destroy');
        Route::post('projects/{project}/images/{image}/feature', [ProjectController::class, 'featureImage'])->name('projects.images.feature');
        Route::post('projects/{project}/images/reorder', [ProjectController::class, 'reorderImages'])->name('projects.images.reorder');
        Route::post('projects/{project}/floor-plans', [ProjectController::class, 'storeFloorPlan'])->name('projects.floor-plans.store');
        Route::delete('projects/{project}/floor-plans/{floorPlan}', [ProjectController::class, 'destroyFloorPlan'])->name('projects.floor-plans.destroy');

        Route::get('inquiries', [InquiryController::class, 'index'])->name('inquiries.index');
        Route::get('inquiries/{inquiry}', [InquiryController::class, 'show'])->name('inquiries.show');
        Route::patch('inquiries/{inquiry}/status', [InquiryController::class, 'updateStatus'])->name('inquiries.status');
        Route::post('inquiries/{inquiry}/notes', [InquiryController::class, 'storeNote'])->name('inquiries.notes.store');
        Route::delete('inquiries/{inquiry}', [InquiryController::class, 'destroy'])->name('inquiries.destroy');

        Route::resource('news', NewsController::class);

        Route::get('media', [MediaController::class, 'index'])->name('media.index');
        Route::post('media', [MediaController::class, 'store'])->name('media.store');
        Route::delete('media/{asset}', [MediaController::class, 'destroy'])->name('media.destroy');

        Route::get('content/home', [ContentController::class, 'editHome'])->name('content.home');
        Route::put('content/home', [ContentController::class, 'updateHome'])->name('content.home.update');
        Route::get('content/about', [ContentController::class, 'editAbout'])->name('content.about');
        Route::put('content/about', [ContentController::class, 'updateAbout'])->name('content.about.update');

        Route::resource('directors', DirectorController::class)->except(['show']);
        Route::resource('team', TeamMemberController::class)->except(['show']);
        Route::resource('services', ServiceController::class)->except(['show']);
        Route::resource('testimonials', TestimonialController::class)->except(['show']);

        Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

        Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    });
});
