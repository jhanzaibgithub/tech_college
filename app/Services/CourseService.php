<?php

namespace App\Services;

use App\Models\Course;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
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

    public function cardImage(Course $course): string
    {
        return $course->images->first()?->path ?? 'data/courses/technical-skills.png';
    }

    public function gallery(Course $course): array
    {
        $paths = $course->images->pluck('path')->all();

        return $paths ?: [$this->cardImage($course)];
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
