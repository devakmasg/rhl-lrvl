<?php

namespace App\Http\Controllers;

use App\Models\Director;
use App\Models\ExploreSlide;
use App\Models\HeroSlide;
use App\Models\JourneyChapter;
use App\Models\News;
use App\Models\Page;
use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Testimonial;

class HomeController extends Controller
{
    public function index()
    {
        $page = Page::where('slug', 'home')->firstOrFail();

        // The mission/vision teaser and the MD pull-quote on this page show the
        // same copy as the About page, so they read that row rather than
        // keeping a second copy that can drift out of sync.
        $aboutPage = Page::where('slug', 'about')->first();

        $heroSlides = HeroSlide::where('is_active', true)->orderBy('sort_order')->get();

        $journeyChapters = JourneyChapter::where('is_active', true)->orderBy('sort_order')->get();

        $exploreSlides = ExploreSlide::with('project')->where('is_active', true)->orderBy('sort_order')->get();

        // Capped like the lists below it. Nothing in the admin limits how many
        // projects can be flagged featured, and every slide costs an image.
        $featuredProjects = Project::where('published', true)
            ->where('featured', true)
            ->latest()
            ->take(8)
            ->get();

        $ongoingProjects = Project::where('published', true)
            ->where('status', 'Ongoing')
            ->latest()
            ->take(3)
            ->get();

        $completedProjects = Project::where('published', true)
            ->where('status', 'Completed')
            ->latest()
            ->take(3)
            ->get();

        // The dedicated testimonials page shows every one; the homepage slider
        // only needs enough to be worth swiping.
        $testimonials = Testimonial::take(10)->get();

        $latestNews = News::where('published', true)
            ->orderByDesc('date')
            ->take(3)
            ->get();

        $leadership = Director::orderBy('order')->take(2)->get()
            ->concat(\App\Models\TeamMember::orderBy('order')->take(2)->get());

        $md = Director::managingDirector();
        $services = Service::orderBy('order')->get();
        $setting = Setting::first();

        return view('pages.home', compact(
            'page', 'aboutPage', 'heroSlides', 'journeyChapters', 'exploreSlides', 'featuredProjects', 'ongoingProjects',
            'completedProjects', 'testimonials', 'latestNews', 'leadership', 'md',
            'services', 'setting'
        ));
    }
}
