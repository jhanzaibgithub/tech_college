@extends('admin.layout')

@section('title', 'Edit News Bar Item')

@section('content')
    <form class="admin-form-grid" method="POST" action="{{ route('admin.ticker.update', $item) }}">
        @csrf @method('PUT')
        <section class="admin-card">
            <div class="admin-card-head"><div><h2>Edit news bar item</h2><p>Only the title and date are shown in the bar.</p></div></div>
            @include('admin.settings._errors')
            <div class="admin-fields">
                <label>Title <input type="text" name="title" value="{{ old('title', $item->title) }}" maxlength="200" required></label>
                <label>Date <input type="date" name="item_date" value="{{ old('item_date', $item->item_date?->toDateString()) }}" required></label>
            </div>
            <label class="admin-check admin-active"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $item->is_active))> Show this item in the news bar</label>
        </section>
        <div class="admin-form-actions">
            <a class="admin-secondary-button" href="{{ route('admin.ticker.index') }}">Cancel</a>
            <button class="admin-button" type="submit"><i data-lucide="save"></i> Save</button>
        </div>
    </form>
@endsection
