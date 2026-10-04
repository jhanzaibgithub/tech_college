<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseImage extends Model
{
    use HasFactory;

    public const FALLBACK = 'data/campus-building.png';

    protected $fillable = [
        'course_id',
        'path',
        'alt_text',
        'sort_order',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function existsOnDisk(): bool
    {
        return is_file(public_path($this->path));
    }

    /** Public URL of the image, or of the fallback when the stored file is missing. */
    public function url(): string
    {
        return asset($this->existsOnDisk() ? $this->path : self::FALLBACK);
    }
}
