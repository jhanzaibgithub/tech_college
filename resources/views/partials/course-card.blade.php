@php($images = $item->imageUrls())
<article class="course-card">
    <div class="course-media" data-card-carousel @if(count($images) > 1) data-multi @endif>
        <div class="course-media-track" tabindex="-1">
            @foreach ($images as $url)
                <div class="course-media-slide" @if(count($images) > 1) role="group" aria-roledescription="slide" aria-label="Image {{ $loop->iteration }} of {{ count($images) }}" @endif>
                    <a href="{{ route('courses.show', $item->slug) }}" tabindex="-1" aria-hidden="true" draggable="false"><img src="{{ $url }}" alt="{{ $loop->first ? $item->title : $item->title . ' - image ' . $loop->iteration }}" loading="lazy" decoding="async" data-fallback="{{ asset(\App\Models\CourseImage::FALLBACK) }}"></a>
                </div>
            @endforeach
        </div>
        <span class="course-chip"><i data-lucide="{{ $item->icon }}"></i></span>
        @if (count($images) > 1)
            <button class="course-media-btn course-media-prev" type="button" aria-label="Previous image of {{ $item->title }}"><i data-lucide="chevron-left"></i></button>
            <button class="course-media-btn course-media-next" type="button" aria-label="Next image of {{ $item->title }}"><i data-lucide="chevron-right"></i></button>
            <div class="course-media-dots" aria-hidden="true">
                @foreach ($images as $url)<span @class(['on' => $loop->first])></span>@endforeach
            </div>
        @endif
    </div>
    <div class="course-card-body">
        @include('partials.stars', ['rating' => $item->rating])
        <h3><a href="{{ route('courses.show', $item->slug) }}">{{ $item->title }}</a></h3>
        <p>{{ $item->short_description }}</p>
        <div class="course-card-actions">
            <a class="button course-explore" href="{{ route('courses.show', $item->slug) }}" aria-label="Explore Course: {{ $item->title }}">Explore <i data-lucide="arrow-right"></i></a>
            <button class="button button-gold" type="button" data-open-enrollment data-course-id="{{ $item->id }}">Enrolled now</button>
        </div>
    </div>
</article>
