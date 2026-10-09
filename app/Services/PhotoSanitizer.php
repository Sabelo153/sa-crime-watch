<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PhotoSanitizer
{
    public function store(UploadedFile $file): string
    {
        if (! extension_loaded('gd')) {
            throw ValidationException::withMessages(['photos' => 'Photo uploads require the PHP GD extension.']);
        }
        $info = @getimagesize($file->getRealPath());
        if (! $info || $info[0] * $info[1] > 12000000) {
            throw ValidationException::withMessages(['photos' => 'Use a valid image with at most 12 megapixels.']);
        }
        $image = @imagecreatefromstring(file_get_contents($file->getRealPath()));
        if (! $image) {
            throw ValidationException::withMessages(['photos' => 'The photo could not be decoded.']);
        }
        // Re-encoding removes EXIF, GPS, original filenames and other source metadata.
        ob_start();
        try {
            imagejpeg($image, null, 85);
            $bytes = ob_get_contents();
        } finally {
            ob_end_clean();
            imagedestroy($image);
        }
        $path = 'reports/'.Str::uuid().'.jpg';
        if (! Storage::disk('local')->put($path, $bytes)) {
            throw new \RuntimeException('Photo storage failed');
        }

        return $path;
    }
}
