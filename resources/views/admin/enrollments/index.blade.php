@extends('admin.layout')

@section('title', 'Enrollments')

@php
    $activeStatus = $filters['status'] ?? 'all';
    $activePeriod = $filters['period'] ?? '';
    $hasFilters = collect($filters)->filter(fn ($value, $key) => $value !== null && $value !== '' && ! ($key === 'status' && $value === 'all'))->isNotEmpty();
    $chipQuery = fn ($status) => array_filter(array_merge($filters, ['status' => $status, 'page' => null]), fn ($v) => $v !== null && $v !== '' && $v !== 'all');
@endphp

@section('content')
    <section class="admin-card">
        <div class="admin-card-head">
            <div><h2>Student Enrollments</h2><p>Review course requests and update student progress.</p></div>
        </div>

        <div class="status-chips" aria-label="Filter by status">
            <a href="{{ route('admin.enrollments.index', $chipQuery('all')) }}" @class(['active' => $activeStatus === 'all'])>All <b>{{ array_sum($statusCounts) }}</b></a>
            @foreach ($statuses as $key => $label)
                <a href="{{ route('admin.enrollments.index', $chipQuery($key)) }}" @class(['active' => $activeStatus === $key])>{{ $label }} <b>{{ $statusCounts[$key] ?? 0 }}</b></a>
            @endforeach
        </div>

        <form class="enrollment-filters" method="GET" action="{{ route('admin.enrollments.index') }}" data-filter-form>
            <label class="filter-search">Search
                <span class="search-box"><i data-lucide="search"></i><input type="search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Student name, course or mobile number" maxlength="100" autocomplete="off"></span>
            </label>
            <label>Course
                <select name="course_id">
                    <option value="">All courses</option>
                    @foreach ($courses as $course)
                        <option value="{{ $course->id }}" @selected((string) ($filters['course_id'] ?? '') === (string) $course->id)>{{ $course->title }}</option>
                    @endforeach
                </select>
            </label>
            <label>Status
                <select name="status">
                    <option value="all">All statuses</option>
                    @foreach ($statuses as $key => $label)
                        <option value="{{ $key }}" @selected($activeStatus === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </label>
            <label>Date
                <select name="period" data-period>
                    <option value="">Any time</option>
                    @foreach ($periods as $key => $label)
                        <option value="{{ $key }}" @selected($activePeriod === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </label>
            <label data-custom-date @if($activePeriod !== 'custom') hidden @endif>From <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}"></label>
            <label data-custom-date @if($activePeriod !== 'custom') hidden @endif>To <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}"></label>
            <div class="filter-actions">
                <button class="admin-button" type="submit"><i data-lucide="filter"></i> Apply</button>
                @if ($hasFilters)
                    <a class="admin-secondary-button" href="{{ route('admin.enrollments.index') }}"><i data-lucide="x"></i> Clear filters</a>
                @endif
            </div>
        </form>
        @if ($errors->any())
            <div class="admin-error-list">@foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>
        @endif

        <p class="result-count">{{ $enrollments->total() }} {{ \Illuminate\Support\Str::plural('enrollment', $enrollments->total()) }} found</p>
        <div class="admin-table-wrap table-scroll" data-table-wrap tabindex="0" role="region" aria-label="Enrollments table, scrolls sideways on small screens">
            <div class="table-loading" aria-hidden="true"><span></span></div>
            <table class="admin-table enrollment-table">
                <thead><tr><th class="col-student">Student</th><th class="col-contact">Contact</th><th class="col-course">Course</th><th class="col-message">Message</th><th class="col-status">Status</th><th class="col-date">Applied</th><th class="col-actions">Actions</th></tr></thead>
                <tbody>
                    @forelse ($enrollments as $enrollment)
                        @php($studentName = trim((string) $enrollment->name) !== '' ? $enrollment->name : 'Unnamed student')
                        <tr>
                            <td class="col-student">
                                <div class="student-cell">
                                    <span class="student-avatar" aria-hidden="true">{{ mb_strtoupper(mb_substr($studentName, 0, 1)) }}</span>
                                    <strong title="{{ $studentName }}">{{ $studentName }}</strong>
                                </div>
                            </td>
                            <td class="col-contact">
                                <div class="contact-cell">
                                    @if ($enrollment->phone)
                                        <a class="contact-line" href="tel:{{ $enrollment->phone }}"><i data-lucide="phone"></i><span>{{ $enrollment->formattedPhone() }}</span></a>
                                    @else
                                        <span class="contact-line is-empty"><i data-lucide="phone-off"></i><span>No phone</span></span>
                                    @endif
                                    @if ($enrollment->email)
                                        <a class="contact-line is-email" href="mailto:{{ $enrollment->email }}" title="{{ $enrollment->email }}"><i data-lucide="mail"></i><span>{{ $enrollment->email }}</span></a>
                                    @else
                                        <span class="contact-line is-empty"><i data-lucide="mail-x"></i><span>No email</span></span>
                                    @endif
                                </div>
                            </td>
                            <td class="col-course">
                                @if ($enrollment->course)
                                    <span class="course-chip-admin" title="{{ $enrollment->course->title }}">{{ $enrollment->course->title }}</span>
                                @else
                                    <span class="course-chip-admin is-missing">Course removed</span>
                                @endif
                            </td>
                            <td class="col-message">
                                @if (filled($enrollment->message))
                                    <span class="message-clip" title="{{ $enrollment->message }}">{{ $enrollment->message }}</span>
                                @else
                                    <span class="muted-dash">&mdash;</span>
                                @endif
                            </td>
                            <td class="col-status">
                                <form method="POST" action="{{ route('admin.enrollments.update', $enrollment) }}" class="status-form">
                                    @csrf @method('PATCH')
                                    <span class="status-pill status-{{ $enrollment->status }}">
                                        <i aria-hidden="true"></i>
                                        <select name="status" aria-label="Status for {{ $studentName }}" onchange="this.form.submit()">
                                            @foreach ($statuses as $key => $label)
                                                <option value="{{ $key }}" @selected($enrollment->status === $key)>{{ \Illuminate\Support\Str::before($label, ' ') }}</option>
                                            @endforeach
                                        </select>
                                    </span>
                                </form>
                            </td>
                            <td class="col-date">
                                @if ($enrollment->created_at)
                                    <span class="date-main">{{ $enrollment->created_at->format('M d, Y') }}</span>
                                    <span class="date-time">{{ $enrollment->created_at->format('h:i A') }}</span>
                                @else
                                    <span class="muted-dash">&mdash;</span>
                                @endif
                            </td>
                            <td class="col-actions">
                                <div class="admin-actions">
                                    <button type="button" aria-label="View enrollment" title="View details" data-show-enrollment data-name="{{ $studentName }}" data-phone="{{ $enrollment->phone ? $enrollment->formattedPhone() : 'No phone' }}" data-email="{{ $enrollment->email ?: 'No email' }}" data-course="{{ $enrollment->course?->title ?? 'Course removed' }}" data-status="{{ $statuses[$enrollment->status] ?? ucfirst((string) $enrollment->status) }}" data-date="{{ $enrollment->created_at?->format('M d, Y h:i A') ?? '-' }}" data-message="{{ filled($enrollment->message) ? $enrollment->message : 'No message provided.' }}"><i data-lucide="eye"></i></button>
                                    <form method="POST" action="{{ route('admin.enrollments.destroy', $enrollment) }}" data-confirm="This enrollment request will be deleted.">@csrf @method('DELETE')<button type="submit" aria-label="Delete enrollment" title="Delete"><i data-lucide="trash-2"></i></button></form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="empty-state">
                            @if ($hasFilters)
                                No enrollments match these filters. <a href="{{ route('admin.enrollments.index') }}">Clear filters</a>
                            @else
                                No enrollment requests yet. They will appear here when students apply.
                            @endif
                        </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $enrollments->links('admin.pagination') }}
    </section>
@endsection

@push('scripts')
<script>
    const escapeHtml = (value) => String(value).replace(/[&<>"']/g, (char) => ({'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'}[char]));

    document.querySelectorAll('[data-show-enrollment]').forEach((button) => {
        button.addEventListener('click', () => {
            const d = button.dataset;
            Swal.fire({
                icon: 'info',
                title: 'Enrollment Detail',
                html: `
                    <div class="swal-detail">
                        <p><strong>Student:</strong> ${escapeHtml(d.name)}</p>
                        <p><strong>Phone:</strong> ${escapeHtml(d.phone)}</p>
                        <p><strong>Email:</strong> ${escapeHtml(d.email)}</p>
                        <p><strong>Course:</strong> ${escapeHtml(d.course)}</p>
                        <p><strong>Status:</strong> ${escapeHtml(d.status)}</p>
                        <p><strong>Date:</strong> ${escapeHtml(d.date)}</p>
                        <p><strong>Message:</strong><br>${escapeHtml(d.message)}</p>
                    </div>
                `,
                confirmButtonColor: '#063d2b'
            });
        });
    });

    const filterForm = document.querySelector('[data-filter-form]');
    const tableWrap = document.querySelector('[data-table-wrap]');
    const startLoading = () => tableWrap?.classList.add('is-loading');
    filterForm?.addEventListener('submit', startLoading);
    document.querySelectorAll('.status-chips a, .admin-pagination a').forEach((link) => link.addEventListener('click', startLoading));
    window.addEventListener('pageshow', () => tableWrap?.classList.remove('is-loading'));

    const period = filterForm?.querySelector('[data-period]');
    const customDates = filterForm?.querySelectorAll('[data-custom-date]');
    period?.addEventListener('change', () => {
        const custom = period.value === 'custom';
        customDates.forEach((field) => { field.hidden = !custom; });
        if (!custom) filterForm.requestSubmit();
    });
    // Selecting a course or status applies immediately; date fields use the Apply button.
    filterForm?.querySelectorAll('select[name="course_id"], select[name="status"]').forEach((select) => select.addEventListener('change', () => filterForm.requestSubmit()));
</script>
@endpush
