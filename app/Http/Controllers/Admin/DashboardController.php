<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Course;
use App\Models\CourseImage;
use App\Models\Enrollment;
use App\Models\NewsEvent;
use App\Models\StudentTestimonial;
use App\Services\EnrollmentService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private readonly EnrollmentService $enrollments)
    {
    }

    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'courseCount' => Course::count(),
            'activeCourseCount' => Course::where('is_active', true)->count(),
            'imageCount' => CourseImage::count(),
            'adminCount' => Admin::count(),
            'enrollmentCount' => Enrollment::count(),
            'newEnrollmentCount' => Enrollment::where('status', Enrollment::STATUS_NEW)->count(),
            'confirmedEnrollmentCount' => Enrollment::where('status', Enrollment::STATUS_CONFIRMED)->count(),
            'completedEnrollmentCount' => Enrollment::where('status', Enrollment::STATUS_COMPLETED)->count(),
            'testimonialCount' => StudentTestimonial::count(),
            'newsEventCount' => NewsEvent::count(),
            'monthlyEnrollments' => $this->enrollments->monthlyCounts(),
            'latestCourses' => Course::with('images')->latest()->take(5)->get(),
            'latestEnrollments' => Enrollment::with('course')->latest()->take(5)->get(),
        ]);
    }
}
