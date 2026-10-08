@extends('admin.layout')

@section('title', $section['title'])

@section('content')
    <form class="admin-form-grid" method="POST" action="{{ route('admin.settings.update', 'features') }}">
        @csrf @method('PUT')
        <section class="admin-card">
            <div class="admin-card-head"><div><h2>{{ $section['title'] }}</h2><p>{{ $section['blurb'] }} Leave a title empty to hide that card.</p></div></div>
            @include('admin.settings._errors')
            @php($icons = \App\Services\SiteSettingsService::FEATURE_ICONS)
            @foreach (range(1, 5) as $i)
                <h3 class="feature-row-title"><i data-lucide="{{ $icons[$i - 1] }}"></i> Card {{ $i }}</h3>
                <div class="admin-fields">
                    <label>Title <input type="text" name="feature_{{ $i }}_title" value="{{ old('feature_' . $i . '_title', $values['feature_' . $i . '_title']) }}" maxlength="40"></label>
                    <label>Short line under the title <input type="text" name="feature_{{ $i }}_text" value="{{ old('feature_' . $i . '_text', $values['feature_' . $i . '_text']) }}" maxlength="60"></label>
                </div>
            @endforeach
        </section>
        <div class="admin-form-actions"><button class="admin-button" type="submit"><i data-lucide="save"></i> Save changes</button></div>
    </form>
@endsection