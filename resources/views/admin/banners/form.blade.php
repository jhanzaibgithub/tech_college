@extends('admin.layout')

@section('title', $banner->exists ? 'Edit Banner' : 'Add Banner')

@section('content')
    <form class="admin-form-grid" method="POST" action="{{ $banner->exists ? route('admin.banners.update', $banner) : route('admin.banners.store') }}" enctype="multipart/form-data">
        @csrf
        @if ($banner->exists) @method('PUT') @endif
        <section class="admin-card">
            <div class="admin-card-head"><div><h2>{{ $banner->exists ? 'Edit Banner' : 'Add Banner' }}</h2><p>Upload a banner for the homepage carousel.</p></div></div>
            @if ($errors->any())
                <div class="admin-error-list" role="alert">@foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>
            @endif
            <div class="admin-fields">
                <label>Banner title <input type="text" name="title" value="{{ old('title', $banner->title) }}" maxlength="255" required><small>Used to identify the banner and describe its image to screen readers.</small></label>
                <label>Display order <input type="number" name="sort_order" min="0" max="1000000" value="{{ old('sort_order', $banner->sort_order) }}" required></label>
            </div>
            <div class="admin-fields">
                <label>Banner image
                    <input type="file" name="image" accept="image/jpeg,image/png,image/webp" aria-describedby="banner-image-guidance" @required(!$banner->exists) data-banner-upload>
                    <small id="banner-image-guidance"><strong>Recommended ratio: 3:1 (width:height) &mdash; 1920 &times; 640 pixels.</strong><br>JPG, PNG or WebP, up to 8 MB. Images crop to fill the screen on mobile; keep important content near the center and leave room at the bottom for the buttons.</small>
                    @if ($banner->exists)<small>Leave empty to keep the current image.</small>@endif
                </label>
                <label class="admin-check admin-active"><input type="hidden" name="is_active" value="0"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $banner->is_active))> Show on homepage</label>
            </div>
            <div class="admin-current-image" style="width:100%;max-width:760px" data-banner-preview @if(!$banner->exists) hidden @endif>
                <img @if($banner->exists) src="{{ $banner->imageUrl() }}" @endif alt="Banner preview" style="height:auto;max-height:320px;object-fit:contain">
                <span>Banner preview</span>
            </div>
        </section>
        <div class="admin-form-actions">
            <a class="admin-secondary-button" href="{{ route('admin.banners.index') }}">Cancel</a>
            <button class="admin-button" type="submit"><i data-lucide="save"></i> Save Banner</button>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    const upload = document.querySelector('[data-banner-upload]');
    const preview = document.querySelector('[data-banner-preview]');
    const originalImage = preview.querySelector('img').getAttribute('src');
    let previewUrl;
    upload.addEventListener('change', () => {
        if (previewUrl) URL.revokeObjectURL(previewUrl);
        const file = upload.files[0];
        previewUrl = file ? URL.createObjectURL(file) : null;
        preview.hidden = !previewUrl && !originalImage;
        if (previewUrl || originalImage) preview.querySelector('img').src = previewUrl || originalImage;
        else preview.querySelector('img').removeAttribute('src');
    });
</script>
@endpush
