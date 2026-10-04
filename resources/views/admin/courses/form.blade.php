@extends('admin.layout')

@section('title', $course->exists ? 'Edit Course' : 'Add Course')

@section('content')
    <form class="admin-form-grid" method="POST" action="{{ $action }}" enctype="multipart/form-data">
        @csrf
        @if ($method !== 'POST')
            @method($method)
        @endif
        <section class="admin-card admin-course-form-card">
            <div class="admin-card-head">
                <div><h2>{{ $course->exists ? 'Edit Course' : 'Add Course' }}</h2><p>Manage title, icon, descriptions and course images.</p></div>
            </div>
            @include('admin.settings._errors')

            <div class="admin-fields">
                <label>Course Title <input type="text" name="title" value="{{ old('title', $course->title) }}" required></label>
                <label>Slug <input type="text" name="slug" value="{{ old('slug', $course->slug) }}" placeholder="auto-generated if empty"></label>
                <label class="admin-check admin-active"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $course->is_active ?? true))> Active course</label>
            </div>

            <fieldset class="admin-full rating-field">
                <legend>Rating / Stars</legend>
                @php($currentRating = (int) old('rating', $course->rating))
                <div class="star-selector" data-star-selector>
                    <label class="star-none"><input type="radio" name="rating" value="" @checked($currentRating === 0)> <span>No rating</span></label>
                    @for ($star = 1; $star <= 5; $star++)
                        <label class="star-option" title="{{ $star }} {{ Str::plural('star', $star) }}">
                            <input type="radio" name="rating" value="{{ $star }}" @checked($currentRating === $star)>
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2.5l2.9 6.1 6.6.9-4.8 4.6 1.2 6.6L12 17.4 6.1 20.7l1.2-6.6L2.5 9.5l6.6-.9z"/></svg>
                            <span class="sr-only">{{ $star }} {{ Str::plural('star', $star) }}</span>
                        </label>
                    @endfor
                </div>
                <small class="field-hint">Shown on the homepage, course list and course detail page. Leave on "No rating" to hide stars.</small>
            </fieldset>
            <div class="admin-full">
                <label>Short Overview <input type="text" name="short_description" value="{{ old('short_description', $course->short_description) }}" maxlength="500" placeholder="2-3 words for course card, e.g. Job-Ready Skills" required></label>
            </div>

            <div class="admin-editor-wrap">
                <label>Course Detail Description</label>
                <textarea id="details-editor" name="details">{{ old('details', $course->details) }}</textarea>
            </div>
            <div class="admin-full icon-combobox">
                <label>Icon</label>
                <input type="hidden" name="icon" value="{{ old('icon', $course->icon ?? 'book-open') }}" data-icon-input>
                <input class="icon-search" type="search" value="{{ old('icon', $course->icon ?? 'book-open') }}" placeholder="Search icon, e.g. laptop, user, tool" data-icon-search autocomplete="off">
                <div class="icon-picker" data-icon-dropdown>
                    @foreach ($icons as $icon)
                        <button type="button" @class(['selected' => old('icon', $course->icon ?? 'book-open') === $icon]) data-icon="{{ $icon }}"><i data-lucide="{{ $icon }}"></i><span>{{ $icon }}</span></button>
                    @endforeach
                </div>
            </div>

            <h2>Course Images</h2>
            <label class="image-upload">
                <input type="file" name="images[]" accept="image/*" multiple data-image-input>
                <span><i data-lucide="upload-cloud"></i> Select multiple images</span>
            </label>
            <div class="image-preview-grid" data-preview-grid></div>

            @if ($course->exists && $course->images->isNotEmpty())
                <h3>Existing Images</h3>
                <div class="existing-images">
                    @foreach ($course->images as $image)
                        <label>
                            <img src="{{ $image->url() }}" alt="{{ $image->alt_text ?? $course->title }}">
                            <span><input type="checkbox" name="delete_images[]" value="{{ $image->id }}"> Delete</span>
                        </label>
                    @endforeach
                </div>
            @endif
        </section>
        <div class="admin-form-actions">
            <a class="admin-secondary-button" href="{{ route('admin.courses.index') }}">Cancel</a>
            <button class="admin-button" type="submit"><i data-lucide="save"></i> Save Course</button>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('[data-star-selector]').forEach((group) => {
        const options = Array.from(group.querySelectorAll('.star-option'));
        const paint = () => {
            const value = Number(group.querySelector('input:checked')?.value || 0);
            options.forEach((option, index) => option.classList.toggle('on', index < value));
        };
        group.addEventListener('change', paint);
        paint();
    });

    const iconInput = document.querySelector('[data-icon-input]');
    document.querySelectorAll('[data-icon]').forEach((button) => {
        button.addEventListener('click', () => {
            iconInput.value = button.dataset.icon;
            iconSearch.value = button.dataset.icon;
            iconDropdown.classList.remove('open');
            document.querySelectorAll('[data-icon]').forEach((item) => item.classList.remove('selected'));
            button.classList.add('selected');
        });
    });

    const iconSearch = document.querySelector('[data-icon-search]');
    const iconDropdown = document.querySelector('[data-icon-dropdown]');

    iconSearch?.addEventListener('focus', () => iconDropdown.classList.add('open'));
    iconSearch?.addEventListener('input', (event) => {
        const term = event.target.value.toLowerCase();
        iconInput.value = event.target.value;
        iconDropdown.classList.add('open');
        document.querySelectorAll('[data-icon]').forEach((button) => {
            button.hidden = term && !button.dataset.icon.toLowerCase().includes(term);
        });
    });

    document.addEventListener('click', (event) => {
        if (!event.target.closest('.icon-combobox')) {
            iconDropdown?.classList.remove('open');
        }
    });

    window.lucide?.createIcons();
</script>
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
    ClassicEditor.create(document.getElementById('details-editor'), {
        toolbar: [
            'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList',
            'blockQuote', 'insertTable', 'undo', 'redo'
        ],
        placeholder: 'Type or paste your course detail content here!',
    }).catch(error => console.error(error));
</script>
@endpush
