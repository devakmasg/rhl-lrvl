<?php

namespace App\Http\Controllers;

use App\Models\Director;
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

        $featuredProjects = Project::where('published', true)
            ->where('featured', true)
            ->latest()
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

        $testimonials = Testimonial::all();

        $latestNews = News::where('published', true)
            ->orderByDesc('date')
            ->take(3)
            ->get();

        $leadership = Director::orderBy('order')->take(2)->get()
            ->concat(\App\Models\TeamMember::orderBy('order')->take(2)->get());

        $md = Director::where('role', 'Managing Director')->first();
        $services = Service::orderBy('order')->get();
        $setting = Setting::first();

        return view('pages.home', compact(
            'page', 'featuredProjects', 'ongoingProjects', 'completedProjects',
            'testimonials', 'latestNews', 'leadership', 'md', 'services', 'setting'
        ));
    }
}
