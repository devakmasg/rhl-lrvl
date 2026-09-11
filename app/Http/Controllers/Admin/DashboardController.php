<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use App\Models\Project;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // The dashboard is the one page that shows another section's data, so it
        // has to honour that section's permission itself — otherwise a role
        // denied Inquiries would still read customer names and phone numbers
        // here. Project counts stay visible to everyone: the public website
        // lists the same projects.
        $showInquiries = $user->canAccessSection('inquiries');

        $stats = [
            'total_projects' => Project::count(),
            'ongoing_projects' => Project::where('status', 'Ongoing')->count(),
            'completed_projects' => Project::where('status', 'Completed')->count(),
            'total_inquiries' => $showInquiries ? Inquiry::count() : null,
        ];

        $recentInquiries = $showInquiries
            ? Inquiry::with('project')->latest()->take(4)->get()
            : collect();

        return view('admin.dashboard', [
            'stats' => $stats,
            'recentInquiries' => $recentInquiries,
            'showInquiries' => $showInquiries,
            'canAddProject' => $user->canAccessSection('projects'),
        ]);
    }
}
