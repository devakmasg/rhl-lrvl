<?php

namespace App\Http\Controllers;

use App\Mail\NewInquiryReceived;
use App\Models\Inquiry;
use App\Models\Project;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class InquiryController extends Controller
{
    /**
     * Bangladeshi mobile number check, ported from assets/js/forms.js
     * (isValidBdPhone): strip everything but digits, drop a leading 880 or 0
     * country/trunk prefix, and require an 11-digit local number starting
     * 1[3-9] — the same rule the client-side script enforces, so the two
     * validation layers agree rather than contradict each other.
     */
    private function isValidBdPhone(string $raw): bool
    {
        $digits = preg_replace('/\D/', '', $raw) ?? '';

        if (str_starts_with($digits, '880')) {
            $local = substr($digits, 3);
        } elseif (str_starts_with($digits, '0')) {
            $local = substr($digits, 1);
        } else {
            $local = $digits;
        }

        return (bool) preg_match('/^1[3-9]\d{8}$/', $local);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => [
                'required',
                'string',
                'max:30',
                function ($attribute, $value, $fail) {
                    if (! $this->isValidBdPhone((string) $value)) {
                        $fail('Enter a valid Bangladeshi mobile number, e.g. +880 1XXX-XXXXXX.');
                    }
                },
            ],
            'project_id' => ['nullable', 'integer', 'exists:projects,id'],
            'project_name' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ]);

        $projectName = $validated['project_name'] ?? null;

        if (! empty($validated['project_id'])) {
            $project = Project::find($validated['project_id']);
            $projectName = $project?->name ?? $projectName;
        }

        $inquiry = Inquiry::create([
            'reference' => $this->nextReference(),
            'type' => 'general',
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'project_id' => $validated['project_id'] ?? null,
            'project_name' => $projectName,
            'message' => $validated['message'],
            'status' => 'new',
        ]);

        $this->notifyAdmin($inquiry);

        return redirect()
            ->route('thank-you')
            ->with('inquiry_name', $inquiry->name)
            ->with('inquiry_project', $inquiry->project_name);
    }

    /**
     * Landowner / investor submissions from the partners page. Same inbox as
     * a project enquiry, but the extra qualifying fields are kept in their own
     * columns rather than flattened into the message.
     */
    public function storePartner(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'in:landowner,investor,both'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => [
                'required',
                'string',
                'max:30',
                function ($attribute, $value, $fail) {
                    if (! $this->isValidBdPhone((string) $value)) {
                        $fail('Enter a valid Bangladeshi mobile number, e.g. +880 1XXX-XXXXXX.');
                    }
                },
            ],
            'area' => ['nullable', 'string', 'max:100'],
            'size' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'min:20'],
        ]);

        $inquiry = Inquiry::create([
            'reference' => $this->nextReference(),
            'type' => 'partner',
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'partner_role' => $validated['role'],
            'area' => $validated['area'] ?? null,
            'budget' => $validated['size'] ?? null,
            'message' => $validated['message'],
            'status' => 'new',
        ]);

        $this->notifyAdmin($inquiry);

        return redirect()
            ->route('thank-you')
            ->with('inquiry_name', $inquiry->name);
    }

    /**
     * Sequential, human-readable reference. Derived from the highest id rather
     * than a row count — a count goes back down when an inquiry is deleted,
     * which would collide with an existing reference on the unique index.
     */
    private function nextReference(): string
    {
        return 'INQ-'.(1051 + (int) Inquiry::max('id'));
    }

    private function notifyAdmin(Inquiry $inquiry): void
    {
        $adminEmail = Setting::first()?->email ?? 'admin@rhlproperties.com.bd';
        Mail::to($adminEmail)->send(new NewInquiryReceived($inquiry));
    }

    public function thankYou()
    {
        return view('pages.thank-you');
    }
}
