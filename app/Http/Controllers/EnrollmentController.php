<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Rules\PakistaniMobile;
use App\Services\CourseService;
use App\Services\EnrollmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EnrollmentController extends Controller
{
    public function __construct(
        private readonly CourseService $courses,
        private readonly EnrollmentService $enrollments
    ) {
    }

    /** Generic "Enrolled now" form: the student picks the course. */
    public function apply(Request $request): RedirectResponse
    {
        $data = $this->validated($request, [
            'course_id' => ['required', 'integer', Rule::exists('courses', 'id')->where('is_active', true)],
        ]);

        return $this->submit(Course::findOrFail($data['course_id']), $data);
    }

    /** Course detail page form (kept for existing links). */
    public function store(Request $request, string $slug): RedirectResponse
    {
        $course = $this->courses->findPublicBySlug($slug);
        abort_unless($course, 404);

        return $this->submit($course, $this->validated($request));
    }

    private function validated(Request $request, array $extra = []): array
    {
        return $request->validateWithBag('enroll', $extra + [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:20', new PakistaniMobile()],
            'message' => ['nullable', 'string', 'max:1000'],
        ]);
    }

    private function submit(Course $course, array $data): RedirectResponse
    {
        $this->enrollments->create($course, $data);

        return back()->with('status', 'Your enrollment request has been submitted. Our team will contact you soon.');
    }
}
