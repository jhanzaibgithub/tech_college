@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
    <section class="admin-stats">
        <article><i data-lucide="book-open"></i><strong>{{ $courseCount }}</strong><span>Total Courses</span></article>
        <article><i data-lucide="user-plus"></i><strong>{{ $enrollmentCount }}</strong><span>Total Enrollments</span></article>
        <article><i data-lucide="bell"></i><strong>{{ $newEnrollmentCount }}</strong><span>New Requests</span></article>
        <article><i data-lucide="circle-check"></i><strong>{{ $confirmedEnrollmentCount }}</strong><span>Confirmed Students</span></article>
    </section>
    <section class="admin-dashboard-grid">
    <section class="admin-card">
        <div class="admin-card-head">
            <div><h2>Monthly Student Requests</h2><p>Enrollment requests received by month.</p></div>
        </div>
        @php $max = max(array_column($monthlyEnrollments, 'total')) ?: 1; @endphp
        <div class="admin-chart">
            @foreach ($monthlyEnrollments as $month)
                <div><span style="height: {{ max(8, ($month['total'] / $max) * 100) }}%"></span><strong>{{ $month['total'] }}</strong><em>{{ $month['label'] }}</em></div>
            @endforeach
        </div>
    </section>
    <section class="admin-card">
        <div class="admin-card-head">
            <div><h2>Latest Enrollments</h2><p>Newest student course requests.</p></div>
            <a class="admin-button" href="{{ route('admin.enrollments.index') }}"><i data-lucide="users"></i> View All</a>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead><tr><th>Student</th><th>Course</th><th>Status</th><th>Date</th></tr></thead>
                <tbody>
                    @forelse ($latestEnrollments as $enrollment)
                        <tr><td>{{ $enrollment->name }}<small>{{ $enrollment->phone }}</small></td><td>{{ $enrollment->course?->title }}</td><td>{{ ucfirst($enrollment->status) }}</td><td>{{ $enrollment->created_at->format('M d, Y') }}</td></tr>
                    @empty
                        <tr><td colspan="4">No enrollments yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
    </section>
    <section class="admin-card">
        <div class="admin-card-head">
            <div><h2>Latest Courses</h2><p>Recently added or updated programs.</p></div>
            <a class="admin-button" href="{{ route('admin.courses.create') }}"><i data-lucide="plus"></i> Add Course</a>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead><tr><th>Course</th><th>Icon</th><th>Status</th><th>Images</th><th>Updated</th></tr></thead>
                <tbody>
                    @forelse ($latestCourses as $course)
                        <tr><td>{{ $course->title }}</td><td><i data-lucide="{{ $course->icon }}"></i></td><td>{{ $course->is_active ? 'Active' : 'Hidden' }}</td><td>{{ $course->images->count() }}</td><td>{{ $course->updated_at->diffForHumans() }}</td></tr>
                    @empty
                        <tr><td colspan="5">No courses yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
