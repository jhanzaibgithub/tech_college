<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PakistaniMobile implements ValidationRule
{
    private const PATTERN = '/^03[0-4]\d{8}$/';

    /** Returns the 11-digit number (03XXXXXXXXX), or null when it is not a valid Pakistani mobile number. */
    public static function normalize(?string $value): ?string
    {
        $compact = preg_replace('/[\s\-]/', '', (string) $value);

        return preg_match(self::PATTERN, $compact) ? $compact : null;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || self::normalize($value) === null) {
            $fail('Enter a valid Pakistani mobile number, e.g. 03001234567 (11 digits).');
        }
    }
}
