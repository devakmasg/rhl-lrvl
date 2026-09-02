<?php

use App\Http\Controllers\Admin\AchievementController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\CtaBlockController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DirectorController;
use App\Http\Controllers\Admin\ExploreSlideController;
use App\Http\Controllers\Admin\InquiryController;
use App\Http\Controllers\Admin\JourneyChapterController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\PageBannerController;
use App\Http\Controllers\Admin\PageSectionController;
use App\Http\Controllers\Admin\PartnerController;
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
        Route::post('content/home/hero-slides', [ContentController::class, 'storeHeroSlide'])->name('content.home.hero-slides.store');
        Route::put('content/home/hero-slides/{heroSlide}', [ContentController::class, 'updateHeroSlide'])->name('content.home.hero-slides.update');
        Route::post('content/home/hero-slides/{heroSlide}/toggle', [ContentController::class, 'toggleHeroSlide'])->name('content.home.hero-slides.toggle');
        Route::delete('content/home/hero-slides/{heroSlide}', [ContentController::class, 'destroyHeroSlide'])->name('content.home.hero-slides.destroy');
        Route::post('content/home/hero-slides/reorder', [ContentController::class, 'reorderHeroSlides'])->name('content.home.hero-slides.reorder');
        Route::get('content/about', [ContentController::class, 'editAbout'])->name('content.about');
        Route::put('content/about', [ContentController::class, 'updateAbout'])->name('content.about.update');
        Route::get('content/landowners', [ContentController::class, 'editLandowners'])->name('content.landowners');
        Route::put('content/landowners', [ContentController::class, 'updateLandowners'])->name('content.landowners.update');
        Route::get('content/partners', [ContentController::class, 'editPartners'])->name('content.partners');
        Route::put('content/partners', [ContentController::class, 'updatePartners'])->name('content.partners.update');

        Route::post('content/home/journey', [JourneyChapterController::class, 'store'])->name('content.home.journey.store');
        Route::put('content/home/journey/{journeyChapter}', [JourneyChapterController::class, 'update'])->name('content.home.journey.update');
        Route::delete('content/home/journey/{journeyChapter}', [JourneyChapterController::class, 'destroy'])->name('content.home.journey.destroy');
        Route::post('content/home/journey/reorder', [JourneyChapterController::class, 'reorder'])->name('content.home.journey.reorder');

        Route::post('content/home/explore', [ExploreSlideController::class, 'store'])->name('content.home.explore.store');
        Route::put('content/home/explore/{exploreSlide}', [ExploreSlideController::class, 'update'])->name('content.home.explore.update');
        Route::delete('content/home/explore/{exploreSlide}', [ExploreSlideController::class, 'destroy'])->name('content.home.explore.destroy');
        Route::post('content/home/explore/reorder', [ExploreSlideController::class, 'reorder'])->name('content.home.explore.reorder');

        Route::get('page-headers', [PageBannerController::class, 'index'])->name('page-banners.index');
        Route::put('page-headers/{pageBanner}', [PageBannerController::class, 'update'])->name('page-banners.update');

        Route::get('page-sections', [PageSectionController::class, 'index'])->name('page-sections.index');
        Route::put('page-sections/{pageKey}', [PageSectionController::class, 'update'])->name('page-sections.update');

        Route::get('achievements', [AchievementController::class, 'index'])->name('achievements.index');
        Route::post('achievements', [AchievementController::class, 'store'])->name('achievements.store');
        Route::put('achievements/{achievement}', [AchievementController::class, 'update'])->name('achievements.update');
        Route::delete('achievements/{achievement}', [AchievementController::class, 'destroy'])->name('achievements.destroy');

        Route::get('trusted-partners', [PartnerController::class, 'index'])->name('partners.index');
        Route::put('trusted-partners/section', [PartnerController::class, 'updateStrip'])->name('partners.strip.update');
        Route::post('trusted-partners', [PartnerController::class, 'store'])->name('partners.store');
        Route::put('trusted-partners/{partner}', [PartnerController::class, 'update'])->name('partners.update');
        Route::delete('trusted-partners/{partner}', [PartnerController::class, 'destroy'])->name('partners.destroy');

        Route::get('menus', [MenuController::class, 'index'])->name('menus.index');
        Route::put('menus/{menu}', [MenuController::class, 'update'])->name('menus.update');

        Route::get('page-ctas', [CtaBlockController::class, 'index'])->name('cta-blocks.index');
        Route::put('page-ctas/{ctaBlock}', [CtaBlockController::class, 'update'])->name('cta-blocks.update');

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
