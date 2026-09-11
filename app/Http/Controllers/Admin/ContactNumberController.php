<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactNumber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * The phone numbers shown across the site.
 *
 * Two invariants are kept here rather than in the database, because both are
 * about what the *site* needs rather than what a row may hold:
 *
 *   - at most one row is primary, since every single-number slot reads it;
 *   - deleting the primary promotes the next row, so that slot is never empty.
 */
class ContactNumberController extends Controller
{
    public function index(): View
    {
        return view('admin.contact-numbers.index', [
            'numbers' => ContactNumber::orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        // New numbers go to the end of the list.
        $data['sort_order'] = (int) ContactNumber::max('sort_order') + 1;

        // The first number ever added is the primary whether or not the box
        // was ticked — otherwise the site would have numbers but no main one.
        $data['is_primary'] = $data['is_primary'] || ContactNumber::count() === 0;

        $number = ContactNumber::create($data);

        $this->keepOnePrimary($number);

        return redirect()->route('admin.contact-numbers.index')->with('status', 'Number added.');
    }

    public function update(Request $request, ContactNumber $contactNumber): RedirectResponse
    {
        $contactNumber->update($this->validated($request));

        $this->keepOnePrimary($contactNumber);

        return redirect()->route('admin.contact-numbers.index')->with('status', 'Saved.');
    }

    public function destroy(ContactNumber $contactNumber): RedirectResponse
    {
        $wasPrimary = $contactNumber->is_primary;

        $contactNumber->delete();

        // Something has to be the main number. Without this, deleting it would
        // leave every call button falling back to whichever row sorts first —
        // the same result, but arrived at silently.
        if ($wasPrimary) {
            ContactNumber::orderBy('sort_order')->orderBy('id')->first()?->update(['is_primary' => true]);
        }

        return redirect()->route('admin.contact-numbers.index')->with('status', 'Deleted.');
    }

    /**
     * Ticking "main number" on one row unticks it everywhere else; unticking
     * the last primary puts it back, since the site always needs one.
     */
    private function keepOnePrimary(ContactNumber $saved): void
    {
        if ($saved->is_primary) {
            ContactNumber::where('id', '!=', $saved->id)->update(['is_primary' => false]);

            return;
        }

        if (! ContactNumber::where('is_primary', true)->exists()) {
            $saved->update(['is_primary' => true]);
        }
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'label' => ['required', 'string', 'max:60'],
            'number' => ['required', 'string', 'max:50'],
            'key' => [
                'nullable',
                Rule::in(array_keys(ContactNumber::SLOTS)),
                // One desk, one number — forSlot() returns a single row.
                Rule::unique('contact_numbers', 'key')->ignore($request->route('contactNumber')),
            ],
            'sort_order' => ['nullable', 'integer', 'min:1'],
        ], [
            'key.unique' => 'Another number is already set as that desk.',
        ]);

        // Unchecked boxes send nothing, so absence has to mean false.
        $data['is_primary'] = $request->boolean('is_primary');
        $data['show_in_footer'] = $request->boolean('show_in_footer');
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
