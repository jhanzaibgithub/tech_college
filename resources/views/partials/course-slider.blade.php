<div class="course-slider-wrap">
    <div class="popular-course-slider" id="{{ $sliderId }}" tabindex="0" role="region" aria-label="{{ $sliderLabel }}">
        @forelse ($sliderCourses as $item)
            <article class="popular-course-card">
                <a class="popular-course-link" href="{{ route('courses.show', $item->slug) }}">
                    <div class="popular-course-image"><img src="{{ asset($item->primaryImage()?->path ?? 'data/campus-building.png') }}" data-fallback="{{ asset('data/campus-building.png') }}" alt="" loading="lazy"></div>
                    <h3>{{ $item->title }}</h3>
                </a>
                <a class="button course-explore" href="{{ route('courses.show', $item->slug) }}" aria-label="Explore Course: {{ $item->title }}">Explore Course <i data-lucide="arrow-right"></i></a>
            </article>
        @empty
            <p class="empty-panel-text">New courses are on the way. Contact our team for upcoming programs.</p>
        @endforelse
    </div>
    <div class="slider-controls" aria-label="Course slider controls">
        <button type="button" data-course-direction="-1" aria-label="Previous courses" aria-controls="{{ $sliderId }}"><i data-lucide="arrow-left"></i></button>
        <button type="button" data-course-direction="1" aria-label="Next courses" aria-controls="{{ $sliderId }}"><i data-lucide="arrow-right"></i></button>
    </div>
</div>
