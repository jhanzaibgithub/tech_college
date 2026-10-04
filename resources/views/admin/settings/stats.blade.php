@extends('admin.layout')

@section('title', $section['title'])

@section('content')
    <form class="admin-form-grid" method="POST" action="{{ route('admin.settings.update', 'stats') }}">
        @csrf @method('PUT')
        <section class="admin-card">
            <div class="admin-card-head"><div><h2>{{ $section['title'] }}</h2><p>{{ $section['blurb'] }}</p></div></div>
            @include('admin.settings._errors')
            <div class="admin-fields">
                <label>Students enrolled <input type="number" min="0" name="stat_students" value="{{ old('stat_students', $values['stat_students']) }}" required></label>
                <label>Completed students <input type="number" min="0" name="stat_completed" value="{{ old('stat_completed', $values['stat_completed']) }}" required></label>
                <label>Courses <input type="number" min="0" name="stat_courses" value="{{ old('stat_courses', $values['stat_courses']) }}" placeholder="Empty = count active courses automatically"></label>
                <label>Years of experience <input type="number" min="0" max="999" name="stat_years" value="{{ old('stat_years', $values['stat_years']) }}" required></label>
            </div>
            <p class="field-hint">The homepage counters animate from 0 up to these numbers when visitors scroll to them.</p>
        </section>
        <div class="admin-form-actions"><button class="admin-button" type="submit"><i data-lucide="save"></i> Save changes</button></div>
    </form>
@endsection
