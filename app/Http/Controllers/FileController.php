<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class FileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('serve');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:20480',
            'type' => 'required|in:material,curso',
        ]);

        $path = $request->file('file')->store($request->input('type'), 'public');

        return response()->json([
            'success' => true,
            'path' => $path,
            'url' => storage_url($path),
        ]);
    }

    public function delete(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
        ]);

        if (Storage::disk('public')->exists($request->input('path'))) {
            Storage::disk('public')->delete($request->input('path'));
        }

        return response()->json(['success' => true]);
    }

    /**
     * Serve files from storage/app/public without requiring symlink
     * Route: GET /storage/{path}
     */
    public function serve(Request $request, string $path)
    {
        $disk = Storage::disk('public');

        // Security: prevent directory traversal
        $path = ltrim($path, '/');
        if (strpos($path, '..') !== false) {
            abort(403, 'Invalid path');
        }

        if (!$disk->exists($path)) {
            abort(404, 'File not found');
        }

        $mime = $disk->mimeType($path);
        $size = $disk->size($path);
        $lastModified = $disk->lastModified($path);

        // Handle conditional requests (caching)
        $etag = md5($path . $lastModified . $size);
        $noneMatch = $request->header('If-None-Match');
        if ($noneMatch && $noneMatch === $etag) {
            return response('', 304);
        }

        $modifiedSince = $request->header('If-Modified-Since');
        if ($modifiedSince && strtotime($modifiedSince) >= $lastModified) {
            return response('', 304);
        }

        $content = $disk->get($path);

        return response($content, 200, [
            'Content-Type' => $mime,
            'Content-Length' => $size,
            'ETag' => $etag,
            'Last-Modified' => gmdate('D, d M Y H:i:s', $lastModified) . ' GMT',
            'Cache-Control' => 'public, max-age=31536000, immutable', // 1 year
            'Content-Disposition' => ResponseHeaderBag::DISPOSITION_INLINE,
        ]);
    }
}
