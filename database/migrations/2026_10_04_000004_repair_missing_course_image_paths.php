<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Early seed data stored course image paths such as data/courses/technical-skills.png
 * that were never shipped, so the admin list rendered broken thumbnails. Point those
 * rows at the images that do exist in public/data.
 */
return new class extends Migration
{
    private const REPLACEMENTS = [
        'data/courses/technical-skills.png' => 'data/banners/technical-training.png',
        'data/courses/it-digital-skills.png' => 'data/banners/digital-skills.png',
        'data/courses/vocational-training.png' => 'data/banners/student-learning.png',
        'data/courses/soft-skills.png' => 'data/courses/soft-skill-8G6LpKiU.png',
        'data/courses/placement-preparation.png' => 'data/banners/career-preparation.png',
    ];

    public function up(): void
    {
        foreach (self::REPLACEMENTS as $missing => $existing) {
            if (is_file(public_path($missing)) || ! is_file(public_path($existing))) {
                continue;
            }

            DB::table('course_images')->where('path', $missing)->update(['path' => $existing]);
        }
    }

    public function down(): void
    {
        // Data repair only; nothing to undo.
    }
};
