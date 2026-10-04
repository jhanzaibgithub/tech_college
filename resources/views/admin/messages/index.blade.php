@extends('admin.layout')

@section('title', 'Messages')

@section('content')
    <section class="admin-card">
        <div class="admin-card-head">
            <div><h2>Contact Messages</h2><p>Messages sent from the public Contact Us form.</p></div>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead><tr><th>From</th><th>Subject</th><th>Message</th><th>Date</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse ($messages as $message)
                        <tr @class(['unread' => ! $message->is_read])>
                            <td><strong>{{ $message->name }}</strong><small>{{ $message->email }}</small>@if($message->phone)<small>{{ $message->phone }}</small>@endif</td>
                            <td>{{ $message->subject ?: '-' }}</td>
                            <td class="message-cell">{{ $message->message }}</td>
                            <td>{{ $message->created_at?->format('M d, Y h:i A') ?? '-' }}</td>
                            <td class="admin-actions">
                                <form method="POST" action="{{ route('admin.messages.update', $message) }}">@csrf @method('PATCH')<button type="submit" aria-label="{{ $message->is_read ? 'Mark as unread' : 'Mark as read' }}" title="{{ $message->is_read ? 'Mark as unread' : 'Mark as read' }}"><i data-lucide="{{ $message->is_read ? 'mail' : 'mail-open' }}"></i></button></form>
                                <a href="mailto:{{ $message->email }}?subject={{ rawurlencode('Re: ' . ($message->subject ?: 'Your message to Tech College')) }}" aria-label="Reply by email"><i data-lucide="reply"></i></a>
                                <form method="POST" action="{{ route('admin.messages.destroy', $message) }}" data-confirm="This message will be deleted.">@csrf @method('DELETE')<button type="submit" aria-label="Delete message"><i data-lucide="trash-2"></i></button></form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="empty-state">No messages yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $messages->links('admin.pagination') }}
    </section>
@endsection
