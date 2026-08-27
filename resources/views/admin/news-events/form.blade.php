@extends('admin.layout')

@section('title', $newsEvent->exists ? 'Edit News & Event' : 'Add News & Event')

@section('content')
    <form class="admin-form-grid" method="POST" action="{{ $action }}" enctype="multipart/form-data">
        @csrf
        @if ($method !== 'POST')
            @method($method)
        @endif
        <section class="admin-card">
            <div class="admin-card-head">
                <div><h2>{{ $newsEvent->exists ? 'Edit News & Event' : 'Add News & Event' }}</h2><p>Manage the latest updates shown on the home page.</p></div>
            </div>

            <div class="admin-fields">
                <label>Title <input type="text" name="title" value="{{ old('title', $newsEvent->title) }}" required></label>
                <label>Date <input type="date" name="event_date" value="{{ old('event_date', $newsEvent->event_date?->format('Y-m-d')) }}"></label>
            </div>

            <div class="admin-full">
                <label>Summary <textarea name="summary" rows="5" maxlength="1000" required>{{ old('summary', $newsEvent->summary) }}</textarea></label>
            </div>

            <div class="admin-fields">
                <label>Image <input type="file" name="image" accept="image/*"></label>
                <label class="admin-check admin-active"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $newsEvent->is_active ?? true))> Active item</label>
            </div>

            @if ($newsEvent->image_path)
                <div class="admin-current-image">
                    <img src="{{ asset($newsEvent->image_path) }}" alt="{{ $newsEvent->title }}">
                    <span>Current image</span>
                </div>
            @endif
        </section>
        <div class="admin-form-actions">
            <a class="admin-secondary-button" href="{{ route('admin.news-events.index') }}">Cancel</a>
            <button class="admin-button" type="submit"><i data-lucide="save"></i> Save News & Event</button>
        </div>
    </form>
@endsection
