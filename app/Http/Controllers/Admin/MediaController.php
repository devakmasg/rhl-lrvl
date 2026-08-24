<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    private const DIR = 'media';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $disk = Storage::disk('public');

        $files = collect($disk->files(self::DIR))
            ->sortByDesc(fn ($path) => $disk->lastModified($path))
            ->values()
            ->map(function ($path) use ($disk) {
                $name = basename($path);
                $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);

                return [
                    'path' => $path,
                    'name' => $name,
                    'url' => $disk->url($path),
                    'is_image' => $isImage,
                    'ext' => $ext,
                    'size' => $disk->size($path),
                ];
            });

        return view('admin.media.index', compact('files'));
    }

    /**
     * Store newly uploaded files.
     */
    public function store(Request $request)
    {
        $request->validate([
            'files' => ['required', 'array'],
            'files.*' => ['file', 'mimes:jpg,jpeg,png,gif,webp,svg,pdf', 'max:10240'],
        ]);

        $disk = Storage::disk('public');

        foreach ($request->file('files') as $file) {
            $file->store(self::DIR, 'public');
        }

        return redirect()->route('admin.media.index')->with('status', 'Files uploaded.');
    }

    /**
     * Remove the specified file from storage.
     */
    public function destroy(string $asset)
    {
        $path = self::DIR.'/'.basename($asset);

        Storage::disk('public')->delete($path);

        return redirect()->route('admin.media.index')->with('status', 'File deleted.');
    }
}
