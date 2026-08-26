<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Services\CourseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function __construct(private readonly CourseService $courses)
    {
    }

    public function index(): View
    {
        return view('admin.courses.index', [
            'courses' => $this->courses->adminCourses(),
        ]);
    }

    public function create(): View
    {
        return view('admin.courses.form', [
            'course' => new Course(['is_active' => true]),
            'icons' => $this->icons(),
            'action' => route('admin.courses.store'),
            'method' => 'POST',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->courses->create($this->validated($request), $request->file('images', []));

        return redirect()->route('admin.courses.index')->with('status', 'Course created successfully.');
    }

    public function edit(Course $course): View
    {
        $course->load('images');

        return view('admin.courses.form', [
            'course' => $course,
            'icons' => $this->icons(),
            'action' => route('admin.courses.update', $course),
            'method' => 'PUT',
        ]);
    }

    public function update(Request $request, Course $course): RedirectResponse
    {
        $this->courses->update(
            $course->load('images'),
            $this->validated($request, $course),
            $request->file('images', []),
            $request->input('delete_images', [])
        );

        return redirect()->route('admin.courses.index')->with('status', 'Course updated successfully.');
    }

    public function destroy(Course $course): RedirectResponse
    {
        $this->courses->delete($course->load('images'));

        return redirect()->route('admin.courses.index')->with('status', 'Course deleted successfully.');
    }

    public function reorder(Request $request): Response
    {
        $data = $request->validate([
            'courses' => ['required', 'array'],
            'courses.*' => ['integer', 'exists:courses,id'],
        ]);

        foreach ($data['courses'] as $index => $courseId) {
            Course::whereKey($courseId)->update(['sort_order' => $index + 1]);
        }

        return response()->noContent();
    }

    private function validated(Request $request, ?Course $course = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('courses', 'slug')->ignore($course?->id)],
            'icon' => ['required', 'string', 'max:80'],
            'short_description' => ['required', 'string', 'max:500'],
            'details' => ['required', 'string'],
            'is_active' => ['nullable'],
            'images.*' => ['nullable', 'image', 'max:4096'],
            'delete_images' => ['nullable', 'array'],
            'delete_images.*' => ['integer', 'exists:course_images,id'],
        ]);
    }

    private function icons(): array
    {
        return [
            'settings-2', 'monitor', 'hard-hat', 'users-round', 'briefcase-business', 'book-open',
            'badge-check', 'graduation-cap', 'laptop', 'file-user', 'wrench', 'code-2',
            'building-2', 'chart-bar', 'clipboard-check', 'cpu', 'database', 'factory',
            'file-text', 'globe', 'hammer', 'headphones', 'landmark', 'layers',
            'lightbulb', 'mail-check', 'message-circle', 'microscope', 'network',
            'pen-tool', 'phone-call', 'presentation', 'rocket', 'school', 'shield-check',
            'sparkles', 'target', 'tool-case', 'truck', 'user-check', 'workflow',
        ];
    }
}
