<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            ['technical-skills', 'Technical Skills', 'Mechanical, Electrical, IT and more', 'settings-2', 'data/courses/technical-skills.png'],
            ['it-digital-skills', 'IT & Digital Skills', 'Computer, Software and Online Tools', 'monitor', 'data/courses/it-digital-skills.png'],
            ['vocational-training', 'Vocational Training', 'Practical Trades & Technical Trades', 'hard-hat', 'data/courses/vocational-training.png'],
            ['soft-skills', 'Soft Skills', 'Communication, Productivity & Career Growth', 'users-round', 'data/courses/soft-skills.png'],
            ['placement-preparation', 'Placement Preparation', 'CV Writing, Interview Skills & Job Readiness', 'briefcase-business', 'data/courses/placement-preparation.png'],
        ];

        foreach ($courses as $index => [$slug, $title, $description, $icon, $image]) {
            $course = Course::updateOrCreate(
                ['slug' => $slug],
                [
                    'title' => $title,
                    'icon' => $icon,
                    'short_description' => $description,
                    'overview' => $description . '. Build practical skills through guided training, hands-on assignments and career-focused support.',
                    'details' => '<p>This program is designed for students who want practical, job-ready training with clear guidance, real practice and placement preparation.</p>',
                    'is_active' => true,
                    'sort_order' => $index + 1,
                ]
            );

            $course->images()->updateOrCreate(
                ['path' => $image],
                [
                    'alt_text' => $title,
                    'sort_order' => 1,
                ]
            );
        }
    }
}
