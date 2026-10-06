<?php

namespace App\Services;

use App\Models\Course;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CourseService
{
    public function publicCourses(): Collection
    {
        return Course::query()
            ->with('images')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    public const SORTS = [
        'rating' => 'Top rated',
        'newest' => 'Newest first',
        'title' => 'Title A-Z',
    ];

    public function paginatePublic(string $sort = 'rating', int $perPage = 6): LengthAwarePaginator
    {
        $query = Course::query()->with('images')->where('is_active', true);

        match ($sort) {
            'newest' => $query->orderByDesc('id'),
            'title' => $query->orderBy('title'),
            default => $query->orderByRaw('rating is null')->orderByDesc('rating')->orderBy('sort_order')->orderBy('id'),
        };

        return $query->paginate($perPage)->withQueryString();
    }

    /** The courses shown on the homepage: highest star rating first, unrated last, admin drag order breaks ties. */
    public function homeCourses(int $limit = 9): Collection
    {
        return Course::query()
            ->with('images')
            ->where('is_active', true)
            ->orderByRaw('rating is null')
            ->orderByDesc('rating')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->take($limit)
            ->get();
    }

    public function publicCourseCount(): int
    {
        return Course::where('is_active', true)->count();
    }

    public function adminCourses(): Collection
    {
        return Course::query()
            ->with('images')
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get();
    }

    public function findPublicBySlug(string $slug): ?Course
    {
        return Course::query()
            ->with('images')
            ->where('is_active', true)
            ->where('slug', $slug)
            ->first();
    }

    public function create(array $data, array $images = []): Course
    {
        $course = Course::create($this->payload($data));
        $this->storeImages($course, $images);

        return $course->load('images');
    }

    public function update(Course $course, array $data, array $images = [], array $deleteImageIds = []): Course
    {
        $course->update($this->payload($data, $course));
        $this->deleteImages($course, $deleteImageIds);
        $this->storeImages($course, $images);

        return $course->load('images');
    }

    public function delete(Course $course): void
    {
        foreach ($course->images as $image) {
            $this->deletePublicFile($image->path);
        }

        $course->delete();
    }

    public function gallery(Course $course): array
    {
        return $course->imageUrls();
    }

    private function payload(array $data, ?Course $course = null): array
    {
        $title = trim($data['title']);
        $slug = $data['slug'] ?? null;
        $summary = trim($data['short_description'] ?? '');
        $overview = Str::words(strip_tags($data['details'] ?? ''), 18, '');

        return [
            'title' => $title,
            'slug' => $slug ? Str::slug($slug) : $this->uniqueSlug($title, $course),
            'icon' => $data['icon'] ?? 'book-open',
            'rating' => ! empty($data['rating']) ? (int) $data['rating'] : null,
            'short_description' => $summary ?: $title,
            'overview' => $overview ?: $summary ?: $title,
            'details' => $data['details'] ?? null,
            'is_active' => isset($data['is_active']),
            'sort_order' => $course?->sort_order ?? ((int) Course::max('sort_order') + 1),
        ];
    }

    private function storeImages(Course $course, array $images): void
    {
        $nextSort = (int) $course->images()->max('sort_order') + 1;

        foreach ($images as $image) {
            if (! $image instanceof UploadedFile) {
                continue;
            }

            $filename = $course->slug . '-' . Str::random(8) . '.' . $image->getClientOriginalExtension();
            $directory = public_path('data/courses');

            File::ensureDirectoryExists($directory);
            $image->move($directory, $filename);

            $course->images()->create([
                'path' => 'data/courses/' . $filename,
                'alt_text' => $course->title,
                'sort_order' => $nextSort++,
            ]);
        }
    }

    private function deleteImages(Course $course, array $imageIds): void
    {
        if ($imageIds === []) {
            return;
        }

        $images = $course->images()->whereIn('id', $imageIds)->get();

        foreach ($images as $image) {
            $this->deletePublicFile($image->path);
            $image->delete();
        }
    }

    private function deletePublicFile(string $path): void
    {
        $fullPath = public_path($path);

        if (File::exists($fullPath)) {
            File::delete($fullPath);
        }
    }

    private function uniqueSlug(string $title, ?Course $course = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $counter = 2;

        while (Course::query()
            ->where('slug', $slug)
            ->when($course, fn ($query) => $query->whereKeyNot($course->id))
            ->exists()) {
            $slug = $base . '-' . $counter++;
        }

        return $slug;
    }
}
