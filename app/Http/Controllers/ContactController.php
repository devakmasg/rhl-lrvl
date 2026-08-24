<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Setting;

class ContactController extends Controller
{
    public function show()
    {
        $setting = Setting::first();

        $projects = Project::where('published', true)
            ->orderBy('name')
            ->get();

        return view('pages.contact', compact('setting', 'projects'));
    }
}
