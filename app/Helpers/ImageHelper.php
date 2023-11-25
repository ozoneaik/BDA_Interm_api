<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ImageHelper
{
    public function getImage($path): StreamedResponse|string
    {
        $disk = config('filesystems.default');
        if (!Storage::disk($disk)->exists($path)) {
            return abort(404);
        }

        return Storage::disk($disk)->get($path);
    }
}
