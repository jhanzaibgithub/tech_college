<?php

namespace App\Http\Controllers;

use App\Services\CourseService;
use App\Services\EnrollmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function __construct(
        private readonly CourseService $courses,
        private readonly EnrollmentService $enrollments
    ) {
    }

    public function store(Request $request, string $slug): RedirectResponse
    {
        $course = $this->courses->findPublicBySlug($slug);
        abort_unless($course, 404);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:40'],
            'message' => ['nullable', 'string', 'max:1000'],
        ]);

        $this->enrollments->create($course, $data);

        return redirect()
            ->route('home')
            ->with('status', 'Your enrollment request has been submitted.');
    }
}
