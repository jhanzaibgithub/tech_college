<?php

namespace App\Support;

class Media
{
    public const FALLBACK = 'data/campus-building.png';

    public const AVATAR_FALLBACK = 'data/WhatsApp Image 2026-08-23 at 3.36.55 PM.jpeg';

    /** Public URL of a stored file, or of the fallback when the path is empty or the file is missing. */
    public static function url(?string $path, string $fallback = self::FALLBACK): string
    {
        return asset($path && is_file(public_path($path)) ? $path : $fallback);
    }
}
