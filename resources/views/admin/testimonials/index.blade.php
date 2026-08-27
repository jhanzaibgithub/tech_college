@extends('admin.layout')

@section('title', 'Testimonials')

@section('content')
    <section class="admin-card">
        <div class="admin-card-head">
            <div><h2>Testimonials</h2><p>Add, edit, delete and reorder student feedback.</p></div>
            <a class="admin-button" href="{{ route('admin.testimonials.create') }}"><i data-lucide="plus"></i> Add Testimonial</a>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead><tr><th>Order</th><th>Photo</th><th>Student</th><th>Message</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody data-sortable-testimonials data-reorder-url="{{ route('admin.testimonials.reorder') }}">
                    @forelse ($testimonials as $testimonial)
                        <tr draggable="true" data-testimonial-id="{{ $testimonial->id }}">
                            <td class="drag-handle"><i data-lucide="grip-vertical"></i></td>
                            <td><img class="admin-avatar-thumb" src="{{ asset($testimonial->image_path ?: 'data/WhatsApp Image 2026-08-23 at 3.36.55 PM.jpeg') }}" alt="{{ $testimonial->student_name }}"></td>
                            <td><strong>{{ $testimonial->student_name }}</strong><small>{{ $testimonial->designation }}</small></td>
                            <td>{{ Str::limit($testimonial->message, 90) }}</td>
                            <td>{{ $testimonial->is_active ? 'Active' : 'Hidden' }}</td>
                            <td class="admin-actions">
                                <a href="{{ route('admin.testimonials.edit', $testimonial) }}"><i data-lucide="pencil"></i></a>
                                <form method="POST" action="{{ route('admin.testimonials.destroy', $testimonial) }}" data-confirm="This testimonial will be deleted.">@csrf @method('DELETE')<button type="submit"><i data-lucide="trash-2"></i></button></form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6">No testimonials found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    const testimonialBody = document.querySelector('[data-sortable-testimonials]');
    let draggedTestimonial = null;

    testimonialBody?.addEventListener('dragstart', (event) => {
        draggedTestimonial = event.target.closest('tr');
        draggedTestimonial.classList.add('dragging');
    });

    testimonialBody?.addEventListener('dragend', () => {
        draggedTestimonial?.classList.remove('dragging');
        draggedTestimonial = null;
        saveTestimonialOrder();
    });

    testimonialBody?.addEventListener('dragover', (event) => {
        event.preventDefault();
        const row = event.target.closest('tr');
        if (!row || row === draggedTestimonial) return;
        const box = row.getBoundingClientRect();
        testimonialBody.insertBefore(draggedTestimonial, event.clientY < box.top + box.height / 2 ? row : row.nextSibling);
    });

    function saveTestimonialOrder() {
        const testimonials = Array.from(testimonialBody.querySelectorAll('[data-testimonial-id]')).map((row) => row.dataset.testimonialId);
        fetch(testimonialBody.dataset.reorderUrl, {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            body: JSON.stringify({testimonials})
        });
    }
</script>
@endpush
