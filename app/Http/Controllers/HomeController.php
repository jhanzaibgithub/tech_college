<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $features = [
            ['icon' => 'book-open', 'title' => 'Practical Training', 'text' => 'Job-ready skills'],
            ['icon' => 'briefcase-business', 'title' => 'Placement Support', 'text' => 'Job opportunities'],
            ['icon' => 'monitor-cog', 'title' => 'Modern Labs', 'text' => 'Hands-on learning'],
            ['icon' => 'badge-check', 'title' => 'Recognized Certification', 'text' => 'Boost your career'],
            ['icon' => 'users-round', 'title' => 'Expert Instructors', 'text' => 'Industry professionals'],
        ];

        $courses = [
            ['number' => '01', 'title' => 'Technical Skills', 'text' => 'Mechanical, Electrical, IT and more', 'icon' => 'settings-2'],
            ['number' => '02', 'title' => 'IT & Digital Skills', 'text' => 'Computer, software and online tools', 'icon' => 'laptop'],
            ['number' => '03', 'title' => 'Vocational Training', 'text' => 'Practical trades and technical trades', 'icon' => 'hard-hat'],
            ['number' => '04', 'title' => 'Soft Skills', 'text' => 'Communication, productivity and growth', 'icon' => 'message-circle'],
            ['number' => '05', 'title' => 'Placement Preparation', 'text' => 'CV writing, interviews and job readiness', 'icon' => 'file-user'],
        ];

        $stats = [
            ['value' => '1500+', 'label' => 'Trained students', 'icon' => 'users'],
            ['value' => '1000+', 'label' => 'Successful placements', 'icon' => 'briefcase'],
            ['value' => '50+', 'label' => 'Industry partners', 'icon' => 'landmark'],
            ['value' => '4.8/5', 'label' => 'Student satisfaction', 'icon' => 'star'],
        ];

        $news = [
            ['date' => 'May 20, 2026', 'title' => 'Admissions open for July 2026 batch', 'text' => 'Start your journey towards a successful career.'],
            ['date' => 'May 15, 2026', 'title' => 'Free career counselling sessions', 'text' => 'Get expert guidance for your brighter future.'],
        ];

        return view('welcome', compact('features', 'courses', 'stats', 'news'));
    }
}
