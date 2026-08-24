<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use App\Models\Project;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_projects' => Project::count(),
            'ongoing_projects' => Project::where('status', 'Ongoing')->count(),
            'completed_projects' => Project::where('status', 'Completed')->count(),
            'total_inquiries' => Inquiry::count(),
        ];

        $recentInquiries = Inquiry::with('project')->latest()->take(4)->get();

        return view('admin.dashboard', compact('stats', 'recentInquiries'));
    }
}
