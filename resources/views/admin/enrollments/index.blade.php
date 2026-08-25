@extends('admin.layout')

@section('title', 'Enrollments')

@section('content')
    <section class="admin-card">
        <div class="admin-card-head">
            <div><h2>Student Enrollments</h2><p>Review course requests and update student progress.</p></div>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead><tr><th>Student</th><th>Contact</th><th>Course</th><th>Message</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse ($enrollments as $enrollment)
                        <tr>
                            <td><strong>{{ $enrollment->name }}</strong></td>
                            <td>{{ $enrollment->phone }}<small>{{ $enrollment->email ?: 'No email' }}</small></td>
                            <td>{{ $enrollment->course?->title }}</td>
                            <td>{{ $enrollment->message ? \Illuminate\Support\Str::words($enrollment->message, 4, ' .....') : '-' }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.enrollments.update', $enrollment) }}">
                                    @csrf @method('PATCH')
                                    <select name="status" onchange="this.form.submit()">
                                        @foreach ($statuses as $key => $label)
                                            <option value="{{ $key }}" @selected($enrollment->status === $key)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                            <td>{{ $enrollment->created_at->format('M d, Y') }}</td>
                            <td class="admin-actions">
                                <button type="button" data-show-enrollment data-name="{{ $enrollment->name }}" data-phone="{{ $enrollment->phone }}" data-email="{{ $enrollment->email ?: 'No email' }}" data-course="{{ $enrollment->course?->title }}" data-status="{{ ucfirst($enrollment->status) }}" data-date="{{ $enrollment->created_at->format('M d, Y h:i A') }}" data-message="{{ $enrollment->message ?: 'No message provided.' }}"><i data-lucide="eye"></i></button>
                                <form method="POST" action="{{ route('admin.enrollments.destroy', $enrollment) }}" data-confirm="This enrollment request will be deleted.">@csrf @method('DELETE')<button type="submit"><i data-lucide="trash-2"></i></button></form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7">No enrollments found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('[data-show-enrollment]').forEach((button) => {
        button.addEventListener('click', () => {
            Swal.fire({
                icon: 'info',
                title: 'Enrollment Detail',
                html: `
                    <div class="swal-detail">
                        <p><strong>Student:</strong> ${button.dataset.name}</p>
                        <p><strong>Phone:</strong> ${button.dataset.phone}</p>
                        <p><strong>Email:</strong> ${button.dataset.email}</p>
                        <p><strong>Course:</strong> ${button.dataset.course}</p>
                        <p><strong>Status:</strong> ${button.dataset.status}</p>
                        <p><strong>Date:</strong> ${button.dataset.date}</p>
                        <p><strong>Message:</strong><br>${button.dataset.message}</p>
                    </div>
                `,
                confirmButtonColor: '#063d2b'
            });
        });
    });
</script>
@endpush
