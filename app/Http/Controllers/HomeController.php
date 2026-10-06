<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\NewsEvent;
use App\Models\StudentTestimonial;
use App\Models\TickerItem;
use App\Services\CourseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

        $courses = $this->courses->homeCourses(9);
        $courseTotal = $this->courses->publicCourseCount();
        $banners = Banner::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get();

        $testimonials = StudentTestimonial::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get();

        $newsEvents = NewsEvent::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->take(4)
            ->get();

        $tickerItems = TickerItem::shown()
            ->orderByRaw('COALESCE(item_date, DATE(created_at)) DESC')
            ->orderByDesc('id')
            ->take(20)
            ->get();

        return view('welcome', compact('features', 'courses', 'courseTotal', 'banners', 'testimonials', 'newsEvents', 'tickerItems'));
    }

    public function courses(Request $request): View|JsonResponse
    {
        $sort = $request->query('sort');
        $sort = array_key_exists($sort, CourseService::SORTS) ? $sort : 'rating';
        $courses = $this->courses->paginatePublic($sort);

        // "Load more" and sorting fetch just the cards for a page.
        if ($request->ajax()) {
            return response()->json([
                'html' => view('partials.course-grid-items', ['courses' => $courses])->render(),
                'hasMore' => $courses->hasMorePages(),
                'nextUrl' => $courses->nextPageUrl(),
                'shown' => $courses->lastItem() ?? 0,
                'total' => $courses->total(),
            ]);
        }

        return view('courses', ['courses' => $courses, 'sort' => $sort, 'sorts' => CourseService::SORTS]);
    }

    public function about(): View
    {
        return view('about');
    }

    public function course(string $slug): View
    {
        $course = $this->courses->findPublicBySlug($slug);

        abort_unless($course, 404);

        return view('course-detail', [
            'course' => $course,
            'courses' => $this->courses->publicCourses()->where('id', '!=', $course->id)->values(),
            'gallery' => $this->courses->gallery($course),
        ]);
    }
}
