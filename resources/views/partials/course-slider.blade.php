@php($manyCourses = $sliderCourses->count() > 1)
<div class="course-slider-wrap" data-reveal>
    <div class="course-slider-head">
        <div class="course-list-heading">
            <p class="section-kicker">{{ $sliderKicker ?? 'OUR PROGRAMS' }}</p>
            <h2 id="{{ $sliderId }}-title">{{ $sliderHeading }}</h2>
        </div>
        @if ($manyCourses)
            <div class="slider-controls" aria-label="Course slider controls">
                <button type="button" data-course-direction="-1" aria-label="Previous courses" aria-controls="{{ $sliderId }}"><i data-lucide="arrow-left"></i></button>
                <button type="button" data-course-direction="1" aria-label="Next courses" aria-controls="{{ $sliderId }}"><i data-lucide="arrow-right"></i></button>
            </div>
        @endif
    </div>
    <div class="popular-course-slider" id="{{ $sliderId }}" tabindex="0" role="region" aria-labelledby="{{ $sliderId }}-title">
        @forelse ($sliderCourses as $item)
            @include('partials.course-card', ['item' => $item])
        @empty
            <p class="empty-panel-text">New courses are on the way. Contact our team for upcoming programs.</p>
        @endforelse
    </div>
</div>
