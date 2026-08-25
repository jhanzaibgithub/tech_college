<?php

namespace App\Http\Controllers;

use App\Services\CourseService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(private readonly CourseService $courses)
    {
    }

    public function index(): View
    {
        $features = [
            ['icon' => 'book-open', 'title' => 'Practical Training', 'text' => 'Job-ready skills'],
            ['icon' => 'briefcase-business', 'title' => 'Placement Support', 'text' => 'Job opportunities'],
            ['icon' => 'monitor-cog', 'title' => 'Modern Labs', 'text' => 'Hands-on learning'],
            ['icon' => 'badge-check', 'title' => 'Recognized Certification', 'text' => 'Boost your career'],
            ['icon' => 'users-round', 'title' => 'Expert Instructors', 'text' => 'Industry professionals'],
        ];

        $courses = $this->courses->publicCourses();

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

    public function course(string $slug): View
    {
        $courses = $this->courses->publicCourses();
        $course = $this->courses->findPublicBySlug($slug);

        abort_unless($course, 404);

        $gallery = $this->courses->gallery($course);

        return view('course-detail', compact('course', 'courses', 'gallery'));
    }
}
