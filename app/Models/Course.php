<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'icon',
        'rating',
        'short_description',
        'overview',
        'details',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'rating' => 'integer',
    ];

    public function images(): HasMany
    {
        return $this->hasMany(CourseImage::class)->orderBy('sort_order')->orderBy('id');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function primaryImage(): ?CourseImage
    {
        return $this->images->first();
    }

    /** URLs of every usable image (missing files are skipped); the fallback when there are none. */
    public function imageUrls(): array
    {
        $urls = $this->images->filter->existsOnDisk()->map->url()->values()->all();

        return $urls ?: [asset(CourseImage::FALLBACK)];
    }

    public function primaryImageUrl(): string
    {
        return $this->imageUrls()[0];
    }
}
