<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        $status = $request->query('status', 'all');

        return view('admin.enrollments.index', [
            'enrollments' => $this->enrollments->filtered($status),
            'statuses' => $this->enrollments->statuses(),
            'status' => $status,
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
