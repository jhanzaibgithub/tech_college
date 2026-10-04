<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

/**
 * Gives every course a starter rating and at least three images so the card carousel and
 * stars have something to show. Only fills gaps; anything an admin already set is kept.
 * Admins can replace all of it from Admin -> Courses.
 */
class CourseShowcaseSeeder extends Seeder
{
    private const POOL = [
        'data/banners/technical-training.png',
        'data/banners/digital-skills.png',
        'data/banners/student-learning.png',
        'data/banners/career-preparation.png',
    ];

    private const RATINGS = [5, 4, 5, 4, 5];

    public function run(): void
    {
        $pool = array_values(array_filter(self::POOL, fn ($path) => is_file(public_path($path))));

        Course::with('images')->orderBy('sort_order')->orderBy('id')->get()->each(function (Course $course, int $index) use ($pool) {
            if ($course->rating === null) {
                $course->update(['rating' => self::RATINGS[$index % count(self::RATINGS)]]);
            }

            $existing = $course->images->pluck('path')->all();
            $sort = (int) $course->images->max('sort_order');

            foreach ($pool as $offset => $path) {
                if (count($existing) >= 3) {
                    break;
                }
                // Rotate the pool so neighbouring courses do not show the same order.
                $candidate = $pool[($index + $offset) % count($pool)];
                if (in_array($candidate, $existing, true)) {
                    continue;
                }
                $course->images()->create(['path' => $candidate, 'alt_text' => $course->title, 'sort_order' => ++$sort]);
                $existing[] = $candidate;
            }
        });
    }
}
