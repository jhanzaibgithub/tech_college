@extends('admin.layout')

@section('title', 'News Bar')

@section('content')
    <section class="admin-card">
        <div class="admin-card-head">
            <div><h2>News Bar</h2><p>Add as many announcements as you like, then switch on the ones that should scroll in the "Latest News" bar on the homepage.</p></div>
        </div>
        @include('admin.settings._errors')
        <form class="ticker-add" method="POST" action="{{ route('admin.ticker.store') }}">
            @csrf
            <label>Title <input type="text" name="title" value="{{ old('title') }}" maxlength="200" placeholder="e.g. Admissions open for the new batch" required></label>
            <label>Date <input type="date" name="item_date" value="{{ old('item_date', now()->toDateString()) }}" required></label>
            <button class="admin-button" type="submit"><i data-lucide="plus"></i> Add</button>
            <label class="admin-check ticker-add-check"><input type="checkbox" name="is_active" value="1" @checked(old('title') === null || old('is_active'))> Show in the news bar</label>
        </form>

        <div class="ticker-summary" role="status">
            <span class="ticker-pill is-on"><i></i>{{ $shownCount }} showing in the news bar</span>
            <span class="ticker-pill"><i></i>{{ $hiddenCount }} hidden</span>
        </div>

        <div class="admin-table-wrap table-scroll" tabindex="0" role="region" aria-label="News bar items">
            <table class="admin-table ticker-table">
                <thead><tr><th class="col-tswitch">Show In News bar</th><th>Title</th><th class="col-tdate">Date</th><th class="col-tactions">Actions</th></tr></thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr @class(['is-hidden-item' => ! $item->is_active])>
                            <td class="col-tswitch">
                                <form method="POST" action="{{ route('admin.ticker.toggle', $item) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="switch" role="switch" aria-checked="{{ $item->is_active ? 'true' : 'false' }}" aria-label="Show &quot;{{ $item->title }}&quot; in the news bar" title="{{ $item->is_active ? 'Showing: click to hide' : 'Hidden: click to show' }}"><span></span></button>
                                </form>
                            </td>
                            <td class="ticker-title"><span class="ticker-title-text" title="{{ $item->title }}">{{ $item->title }}</span></td>
                            <td class="col-tdate">{{ $item->item_date?->format('d M Y') ?? '-' }}</td>
                            <td class="col-tactions">
                                <div class="admin-actions">
                                    <a href="{{ route('admin.ticker.edit', $item) }}" aria-label="Edit {{ $item->title }}" title="Edit"><i data-lucide="pencil"></i></a>
                                    <form method="POST" action="{{ route('admin.ticker.destroy', $item) }}" data-confirm="This item will be deleted.">@csrf @method('DELETE')<button type="submit" aria-label="Delete {{ $item->title }}" title="Delete"><i data-lucide="trash-2"></i></button></form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="empty-state">No news bar items yet. Add one above; until then the bar shows a default welcome message.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $items->links('admin.pagination') }}
    </section>
@endsection
