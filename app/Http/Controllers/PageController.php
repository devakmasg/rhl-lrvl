<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
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

    /**
     * Mission, vision and the MD's writing all live on the "about" row, so
     * these two pages read it rather than keeping a second copy. Who the MD
     * *is* comes from the Director record — see Director::managingDirector().
     */
    public function missionVision()
    {
        $page = Page::where('slug', 'about')->firstOrFail();
        $md = Director::managingDirector();

        return view('pages.mission-vision', compact('page', 'md'));
    }

    public function mdMessage()
    {
        $page = Page::where('slug', 'about')->firstOrFail();
        $md = Director::managingDirector();

        return view('pages.md-message', compact('page', 'md'));
    }

    /** Same arrangement as mdMessage(), for the Chairman. */
    public function chairmanMessage()
    {
        $page = Page::where('slug', 'about')->firstOrFail();
        $chairman = Director::chairman();

        return view('pages.chairman-message', compact('page', 'chairman'));
    }

    public function directors()
    {
        $directors = Director::orderBy('order')->get();

        return view('pages.directors', compact('directors'));
    }

    public function management()
    {
        $teamMembers = TeamMember::department(TeamMember::MANAGEMENT)->orderBy('order')->get();

        return view('pages.management', compact('teamMembers'));
    }

    /**
     * Same listing, filtered to the sales department — see TeamMember, where
     * both pages read one table.
     */
    public function salesTeam()
    {
        $teamMembers = TeamMember::department(TeamMember::SALES)->orderBy('order')->get();
        // Only the nav and footer are given the settings row by a composer, so
        // the fallback message on an empty page needs it passed in.
        $setting = Setting::first();

        return view('pages.sales-team', compact('teamMembers', 'setting'));
    }

    public function achievements()
    {
        return view('pages.achievements', [
            'awards' => Achievement::live()->kind(Achievement::AWARD)->get(),
            'certifications' => Achievement::live()->kind(Achievement::CERTIFICATION)->get(),
        ]);
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

    /**
     * The landowner's own page. Its openers, quotes, FAQ and form copy come
     * from the "landowners" row; the qualifying pillars and the joint-venture
     * process are read from the partners row, where the Partners page tab
     * already shows them — one copy, edited in one place.
     */
    public function landowners()
    {
        $page = Page::where('slug', 'landowners')->firstOrFail();
        $partnersPage = Page::where('slug', 'partners')->first();
        $setting = Setting::first();

        return view('pages.landowners', compact('page', 'partnersPage', 'setting'));
    }

    public function testimonials()
    {
        $testimonials = Testimonial::all();

        return view('pages.testimonials', compact('testimonials'));
    }
}
