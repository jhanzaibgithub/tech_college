@extends('admin.layout')

@section('title', $section['title'])

@section('content')
    <form class="admin-form-grid" method="POST" action="{{ route('admin.settings.update', 'hero') }}">
        @csrf @method('PUT')
        <section class="admin-card">
            <div class="admin-card-head"><div><h2>{{ $section['title'] }}</h2><p>{{ $section['blurb'] }} Banner images are managed under Banners.</p></div></div>
            @include('admin.settings._errors')
            <div class="admin-full"><label>Small heading <input type="text" name="hero_kicker" value="{{ old('hero_kicker', $values['hero_kicker']) }}" maxlength="120"></label></div>
            <div class="admin-full"><label>Headline <input type="text" name="hero_title" value="{{ old('hero_title', $values['hero_title']) }}" maxlength="160" required></label></div>
            <div class="admin-full"><label>Supporting text <textarea name="hero_text" rows="3" maxlength="400">{{ old('hero_text', $values['hero_text']) }}</textarea></label></div>
        </section>
        <div class="admin-form-actions"><button class="admin-button" type="submit"><i data-lucide="save"></i> Save changes</button></div>
    </form>
@endsection
