<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Services\EnrollmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EnrollmentController extends Controller
{
    public function __construct(private readonly EnrollmentService $enrollments)
    {
    }

    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'course_id' => ['nullable', 'integer'],
            'status' => ['nullable', 'in:all,new,confirmed,completed'],
            'period' => ['nullable', 'in:today,week,month,custom'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
        ]);

        return view('admin.enrollments.index', [
            'enrollments' => $this->enrollments->filtered($filters),
            'statuses' => $this->enrollments->statuses(),
            'statusCounts' => $this->enrollments->statusCounts(),
            'periods' => $this->enrollments->periods(),
            'courses' => Course::orderBy('title')->get(['id', 'title']),
            'filters' => $filters,
        ]);
    }

    public function update(Request $request, Enrollment $enrollment): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:new,confirmed,completed'],
        ]);

        $enrollment->update($data);

        return back()->with('status', 'Enrollment status updated.');
    }

    public function destroy(Enrollment $enrollment): RedirectResponse
    {
        $enrollment->delete();

        return back()->with('status', 'Enrollment deleted.');
    }
}
