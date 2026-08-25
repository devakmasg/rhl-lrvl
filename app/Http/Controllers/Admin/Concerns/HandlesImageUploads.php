<?php

namespace App\Http\Controllers\Admin\Concerns;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait HandlesImageUploads
{
    /**
     * Work out what should end up in an image column for a field that used to
     * be a pasted URL and is now an upload.
     *
     * Existing rows still hold seeded URLs, so an edit that doesn't touch the
     * file input has to leave the current value alone rather than blanking it.
     */
    protected function resolveImageInput(
        Request $request,
        string $field,
        string $folder,
        ?string $current = null
    ): ?string {
        if ($request->hasFile($field)) {
            $this->deleteUploadedFile($current);

            return $request->file($field)->store($folder, 'public');
        }

        if ($request->boolean($field.'_remove')) {
            $this->deleteUploadedFile($current);

            return null;
        }

        return $current;
    }

    /**
     * Delete a file we actually own. External URLs and the seeded files that
     * ship in public/assets are referenced, not managed, by this app — trying
     * to delete those would either no-op or remove a repo asset other records
     * still point at.
     */
    protected function deleteUploadedFile(?string $path): void
    {
        if (blank($path)) {
            return;
        }

        if (Str::startsWith($path, ['http://', 'https://', '//', 'data:', 'assets/', '/assets/'])) {
            return;
        }

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
