@extends('admin.layout')

@section('title', 'Courses')

@section('content')
    <section class="admin-card">
        <div class="admin-card-head">
            <div><h2>Courses</h2><p>Add, edit, delete and manage course images.</p></div>
            <a class="admin-button" href="{{ route('admin.courses.create') }}"><i data-lucide="plus"></i> Add Course</a>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead><tr><th>Order</th><th>Preview</th><th>Title</th><th>Icon</th><th>Status</th><th>Images</th><th>Actions</th></tr></thead>
                <tbody data-sortable-courses data-reorder-url="{{ route('admin.courses.reorder') }}">
                    @forelse ($courses as $course)
                        <tr draggable="true" data-course-id="{{ $course->id }}">
                            <td class="drag-handle"><i data-lucide="grip-vertical"></i></td>
                            <td><img class="admin-thumb" src="{{ asset($course->images->first()?->path ?? 'data/courses/technical-skills.png') }}" alt="{{ $course->title }}"></td>
                            <td><strong>{{ $course->title }}</strong><small>{{ $course->slug }}</small></td>
                            <td><i data-lucide="{{ $course->icon }}"></i></td>
                            <td>{{ $course->is_active ? 'Active' : 'Hidden' }}</td>
                            <td>{{ $course->images->count() }}</td>
                            <td class="admin-actions">
                                <a href="{{ route('admin.courses.edit', $course) }}"><i data-lucide="pencil"></i></a>
                                <form method="POST" action="{{ route('admin.courses.destroy', $course) }}" data-confirm="This course and its images will be deleted.">@csrf @method('DELETE')<button type="submit"><i data-lucide="trash-2"></i></button></form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7">No courses found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    const tbody = document.querySelector('[data-sortable-courses]');
    let draggedRow = null;

    tbody?.addEventListener('dragstart', (event) => {
        draggedRow = event.target.closest('tr');
        draggedRow.classList.add('dragging');
    });

    tbody?.addEventListener('dragend', () => {
        draggedRow?.classList.remove('dragging');
        draggedRow = null;
        saveCourseOrder();
    });

    tbody?.addEventListener('dragover', (event) => {
        event.preventDefault();
        const row = event.target.closest('tr');
        if (!row || row === draggedRow) return;
        const box = row.getBoundingClientRect();
        tbody.insertBefore(draggedRow, event.clientY < box.top + box.height / 2 ? row : row.nextSibling);
    });

    function saveCourseOrder() {
        const courses = Array.from(tbody.querySelectorAll('[data-course-id]')).map((row) => row.dataset.courseId);
        fetch(tbody.dataset.reorderUrl, {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            body: JSON.stringify({courses})
        });
    }
</script>
@endpush
