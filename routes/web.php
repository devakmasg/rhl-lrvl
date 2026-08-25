<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/mission-vision', [PageController::class, 'missionVision'])->name('mission-vision');
Route::get('/md-message', [PageController::class, 'mdMessage'])->name('md-message');
Route::get('/directors', [PageController::class, 'directors'])->name('directors');
Route::get('/management', [PageController::class, 'management'])->name('management');
Route::get('/achievements', [PageController::class, 'achievements'])->name('achievements');

Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/{project:slug}', [ProjectController::class, 'show'])->name('projects.show');

Route::get('/services', [PageController::class, 'services'])->name('services');
Route::get('/partners', [PageController::class, 'partners'])->name('partners');
Route::get('/testimonials', [PageController::class, 'testimonials'])->name('testimonials');

Route::get('/contact', [ContactController::class, 'show'])->name('contact');

Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{news:slug}', [NewsController::class, 'show'])->name('news.show');

Route::post('/inquiries', [InquiryController::class, 'store'])->name('inquiries.store');
Route::post('/partner-inquiries', [InquiryController::class, 'storePartner'])->name('inquiries.partner.store');
Route::get('/thank-you', [InquiryController::class, 'thankYou'])->name('thank-you');
