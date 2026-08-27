<?php

namespace Database\Seeders;

use App\Models\NewsEvent;
use App\Models\StudentTestimonial;
use Illuminate\Database\Seeder;

class HomeContentSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'student_name' => 'Muhammad Ali',
                'designation' => 'Web Developer',
                'message' => 'TCSDP gave me the skills and confidence to start my career in Web Development. The placement support is excellent!',
                'image_path' => 'data/WhatsApp Image 2026-08-22 at 7.43.53 PM (1).jpeg',
            ],
            [
                'student_name' => 'Ayesha Khan',
                'designation' => 'IT Support Trainee',
                'message' => 'The practical classes helped me understand real workplace tasks and improved my confidence for interviews.',
                'image_path' => 'data/WhatsApp Image 2026-08-22 at 7.43.53 PM.jpeg',
            ],
            [
                'student_name' => 'Hassan Raza',
                'designation' => 'Graphic Design Student',
                'message' => 'I learned useful tools step by step, and the instructors guided me whenever I needed help.',
                'image_path' => 'data/WhatsApp Image 2026-08-23 at 3.36.55 PM.jpeg',
            ],
            [
                'student_name' => 'Sana Fatima',
                'designation' => 'Office Management Trainee',
                'message' => 'The career guidance and skill training made it easier for me to plan my next professional step.',
                'image_path' => 'data/campus-building.png',
            ],
        ];

        foreach ($testimonials as $index => $testimonial) {
            StudentTestimonial::updateOrCreate(
                ['student_name' => $testimonial['student_name']],
                $testimonial + [
                    'is_active' => true,
                    'sort_order' => $index + 1,
                ]
            );
        }

        $newsEvents = [
            [
                'title' => 'Admissions Open for July 2026 Batch',
                'summary' => 'Start your journey towards a successful career.',
                'event_date' => '2026-05-20',
                'image_path' => 'data/campus-building.png',
            ],
            [
                'title' => 'Free Career Counselling Sessions',
                'summary' => 'Get expert guidance for your bright future.',
                'event_date' => '2026-05-15',
                'image_path' => 'data/WhatsApp Image 2026-08-22 at 7.43.53 PM.jpeg',
            ],
            [
                'title' => 'New Digital Skills Classes Started',
                'summary' => 'Hands-on training for students interested in computer and online skills.',
                'event_date' => '2026-06-01',
                'image_path' => 'data/hero-students-placeholder.png',
            ],
            [
                'title' => 'Placement Preparation Workshop',
                'summary' => 'Students joined a focused session on CV writing and interview readiness.',
                'event_date' => '2026-06-10',
                'image_path' => 'data/WhatsApp Image 2026-08-22 at 7.43.53 PM (1).jpeg',
            ],
        ];

        foreach ($newsEvents as $index => $newsEvent) {
            NewsEvent::updateOrCreate(
                ['title' => $newsEvent['title']],
                $newsEvent + [
                    'is_active' => true,
                    'sort_order' => $index + 1,
                ]
            );
        }
    }
}
