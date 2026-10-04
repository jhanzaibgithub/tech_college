@extends('admin.layout')

@section('title', $section['title'])

@section('content')
    <form class="admin-form-grid" method="POST" action="{{ route('admin.settings.update', 'about') }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <section class="admin-card">
            <div class="admin-card-head"><div><h2>{{ $section['title'] }}</h2><p>{{ $section['blurb'] }}</p></div></div>
            @include('admin.settings._errors')
            <div class="admin-fields">
                <label>About title <input type="text" name="about_title" value="{{ old('about_title', $values['about_title']) }}" required></label>
                <label>Tagline <input type="text" name="about_tagline" value="{{ old('about_tagline', $values['about_tagline']) }}"></label>
            </div>
            <div class="admin-full"><label>Description <textarea name="about_description" rows="4" required>{{ old('about_description', $values['about_description']) }}</textarea></label></div>
            <div class="admin-full"><label>Highlights (one per line) <textarea name="about_points" rows="5">{{ old('about_points', $values['about_points']) }}</textarea></label></div>
            <div class="admin-fields">
                <label>Mission <textarea name="about_mission" rows="4">{{ old('about_mission', $values['about_mission']) }}</textarea></label>
                <label>Vision <textarea name="about_vision" rows="4">{{ old('about_vision', $values['about_vision']) }}</textarea></label>
            </div>
            <div class="admin-full"><label>Institution information <textarea name="about_institution" rows="4" placeholder="History, affiliations, facilities...">{{ old('about_institution', $values['about_institution']) }}</textarea></label></div>
            <div class="admin-full"><label>Quote <input type="text" name="about_quote" value="{{ old('about_quote', $values['about_quote']) }}"></label></div>
            <h3>About image</h3>
            <div class="about-image-row">
                <img class="about-image-current" src="{{ asset(is_file(public_path($values['about_image'])) ? $values['about_image'] : 'data/campus-building.png') }}" alt="Current About image">
                <label class="image-upload"><input type="file" name="about_image" accept="image/*" data-about-image><span><i data-lucide="upload-cloud"></i> Replace image (max 4 MB)</span></label>
            </div>
        </section>
        <div class="admin-form-actions"><button class="admin-button" type="submit"><i data-lucide="save"></i> Save changes</button></div>
    </form>
@endsection

@push('scripts')
<script>
    document.querySelector('[data-about-image]')?.addEventListener('change', (event) => {
        const file = event.target.files[0];
        if (file) document.querySelector('.about-image-current').src = URL.createObjectURL(file);
    });
</script>
@endpush
