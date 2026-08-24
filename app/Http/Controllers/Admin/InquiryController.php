<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Inquiry::query()->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('date')) {
            $days = (int) $request->input('date');
            if ($days > 0) {
                $query->where('created_at', '>=', now()->subDays($days));
            }
        }

        if ($request->filled('search')) {
            $term = trim((string) $request->input('search'));
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhere('phone', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%");
            });
        }

        $inquiries = $query->paginate(20)->withQueryString();

        return view('admin.inquiries.index', compact('inquiries'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Inquiry $inquiry)
    {
        $inquiry->load('notes');

        return view('admin.inquiries.show', compact('inquiry'));
    }

    /**
     * Update the status of the specified resource.
     */
    public function updateStatus(Request $request, Inquiry $inquiry)
    {
        $validated = $request->validate([
            'status' => 'required|in:new,contacted,follow-up,converted,closed',
        ]);

        $inquiry->update(['status' => $validated['status']]);

        return redirect()
            ->route('admin.inquiries.show', $inquiry)
            ->with('status', 'Status updated to ' . ucfirst(str_replace('-', ' ', $validated['status'])) . '.');
    }

    /**
     * Store a new note against the specified resource.
     */
    public function storeNote(Request $request, Inquiry $inquiry)
    {
        $validated = $request->validate([
            'text' => 'required|string',
        ]);

        $inquiry->notes()->create([
            'user_id' => auth()->id(),
            'author' => auth()->user()->name,
            'text' => $validated['text'],
        ]);

        return redirect()
            ->route('admin.inquiries.show', $inquiry)
            ->with('status', 'Note added.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inquiry $inquiry)
    {
        $inquiry->delete();

        return redirect()
            ->route('admin.inquiries.index')
            ->with('status', 'Inquiry deleted.');
    }
}
