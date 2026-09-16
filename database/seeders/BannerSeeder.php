<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $banners = [
            'student-learning.png' => 'Students learning practical skills',
            'digital-skills.png' => 'Digital skills and computer training',
            'technical-training.png' => 'Hands-on technical training',
            'career-preparation.png' => 'Career guidance and interview preparation',
        ];

        $order = 1;

        foreach ($banners as $image => $title) {
            Banner::firstOrCreate(
                ['image_path' => $image],
                ['title' => $title, 'sort_order' => $order++, 'is_active' => true]
            );
        }
    }
}
