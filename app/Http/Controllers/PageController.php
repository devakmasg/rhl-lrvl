<?php

namespace App\Http\Controllers;

use App\Models\Director;
use App\Models\Page;
use App\Models\Service;
use App\Models\Setting;
use App\Models\TeamMember;
use App\Models\Testimonial;

class PageController extends Controller
{
    public function about()
    {
        $page = Page::where('slug', 'about')->firstOrFail();

        return view('pages.about', compact('page'));
    }

    public function missionVision()
    {
        $page = Page::where('slug', 'about')->firstOrFail();

        return view('pages.mission-vision', compact('page'));
    }

    public function mdMessage()
    {
        $page = Page::where('slug', 'about')->firstOrFail();

        return view('pages.md-message', compact('page'));
    }

    public function directors()
    {
        $directors = Director::orderBy('order')->get();

        return view('pages.directors', compact('directors'));
    }

    public function management()
    {
        $teamMembers = TeamMember::orderBy('order')->get();

        return view('pages.management', compact('teamMembers'));
    }

    public function achievements()
    {
        return view('pages.achievements');
    }

    public function services()
    {
        $services = Service::orderBy('order')->get();

        return view('pages.services', compact('services'));
    }

    public function partners()
    {
        $page = Page::where('slug', 'partners')->firstOrFail();
        $setting = Setting::first();

        return view('pages.partners', compact('page', 'setting'));
    }

    public function testimonials()
    {
        $testimonials = Testimonial::all();

        return view('pages.testimonials', compact('testimonials'));
    }
}
