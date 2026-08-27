@extends('admin.layout')

@section('title', 'News & Events')

@section('content')
    <section class="admin-card">
        <div class="admin-card-head">
            <div><h2>News & Events</h2><p>Add, edit, delete and reorder home-page updates.</p></div>
            <a class="admin-button" href="{{ route('admin.news-events.create') }}"><i data-lucide="plus"></i> Add News</a>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead><tr><th>Order</th><th>Image</th><th>Title</th><th>Date</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody data-sortable-news-events data-reorder-url="{{ route('admin.news-events.reorder') }}">
                    @forelse ($newsEvents as $newsEvent)
                        <tr draggable="true" data-news-event-id="{{ $newsEvent->id }}">
                            <td class="drag-handle"><i data-lucide="grip-vertical"></i></td>
                            <td><img class="admin-thumb" src="{{ asset($newsEvent->image_path ?: 'data/campus-building.png') }}" alt="{{ $newsEvent->title }}"></td>
                            <td><strong>{{ $newsEvent->title }}</strong><small>{{ Str::limit($newsEvent->summary, 80) }}</small></td>
                            <td>{{ $newsEvent->event_date?->format('M d, Y') ?: 'No date' }}</td>
                            <td>{{ $newsEvent->is_active ? 'Active' : 'Hidden' }}</td>
                            <td class="admin-actions">
                                <a href="{{ route('admin.news-events.edit', $newsEvent) }}"><i data-lucide="pencil"></i></a>
                                <form method="POST" action="{{ route('admin.news-events.destroy', $newsEvent) }}" data-confirm="This news or event item will be deleted.">@csrf @method('DELETE')<button type="submit"><i data-lucide="trash-2"></i></button></form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6">No news or events found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    const newsEventBody = document.querySelector('[data-sortable-news-events]');
    let draggedNewsEvent = null;

    newsEventBody?.addEventListener('dragstart', (event) => {
        draggedNewsEvent = event.target.closest('tr');
        draggedNewsEvent.classList.add('dragging');
    });

    newsEventBody?.addEventListener('dragend', () => {
        draggedNewsEvent?.classList.remove('dragging');
        draggedNewsEvent = null;
        saveNewsEventOrder();
    });

    newsEventBody?.addEventListener('dragover', (event) => {
        event.preventDefault();
        const row = event.target.closest('tr');
        if (!row || row === draggedNewsEvent) return;
        const box = row.getBoundingClientRect();
        newsEventBody.insertBefore(draggedNewsEvent, event.clientY < box.top + box.height / 2 ? row : row.nextSibling);
    });

    function saveNewsEventOrder() {
        const news_events = Array.from(newsEventBody.querySelectorAll('[data-news-event-id]')).map((row) => row.dataset.newsEventId);
        fetch(newsEventBody.dataset.reorderUrl, {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            body: JSON.stringify({news_events})
        });
    }
</script>
@endpush
