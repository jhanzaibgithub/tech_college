@extends('admin.layout')

@section('title', $section['title'])

@section('content')
    <form class="admin-form-grid" method="POST" action="{{ route('admin.settings.update', 'pages') }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <section class="admin-card">
            <div class="admin-card-head"><div><h2>{{ $section['title'] }}</h2><p>{{ $section['blurb'] }}</p></div></div>
            @include('admin.settings._errors')
            @foreach (['about' => 'About page', 'courses' => 'Courses page', 'contact' => 'Contact page'] as $page => $label)
                <h3>{{ $label }}</h3>
                <div class="admin-fields">
                    <label>Title <input type="text" name="page_{{ $page }}_title" value="{{ old('page_' . $page . '_title', $values['page_' . $page . '_title']) }}" maxlength="160" required></label>
                    <label>Text <input type="text" name="page_{{ $page }}_text" value="{{ old('page_' . $page . '_text', $values['page_' . $page . '_text']) }}" maxlength="300"></label>
                </div>
                <div class="about-image-row">
                    <img class="about-image-current" data-preview="{{ $page }}" src="{{ asset(is_file(public_path($values['page_' . $page . '_image'])) ? $values['page_' . $page . '_image'] : 'data/campus-building.png') }}" alt="Current {{ $label }} banner image">
                    <label class="image-upload"><input type="file" name="page_{{ $page }}_image" accept="image/*" data-page-image="{{ $page }}"><span><i data-lucide="upload-cloud"></i> Replace banner image (max 4 MB, wide image works best)</span></label>
                </div>
            @endforeach
        </section>
        <div class="admin-form-actions"><button class="admin-button" type="submit"><i data-lucide="save"></i> Save changes</button></div>
    </form>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('[data-page-image]').forEach((input) => {
        input.addEventListener('change', () => {
            const file = input.files[0];
            const preview = document.querySelector('[data-preview="' + input.dataset.pageImage + '"]');
            if (file && preview) preview.src = URL.createObjectURL(file);
        });
    });
</script>
@endpush
