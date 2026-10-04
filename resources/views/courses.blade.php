@extends('layouts.public')

@section('title', 'Courses | Tech College')
@section('description', 'Browse all skills development and vocational courses offered at Tech College.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/course-slider.css') }}?v={{ filemtime(public_path('css/course-slider.css')) }}">
@endpush

@section('content')
    @include('partials.page-hero', ['page' => 'courses', 'kicker' => 'Courses'])
    <section class="section courses-section courses-page" id="courses" data-courses-page data-url="{{ route('courses.index') }}">
        <div class="container">
            @if ($courses->total() === 0)
                <p class="empty-panel-text">New courses are on the way. Contact our team for upcoming programs.</p>
            @else
                @php($icons = ['rating' => 'star', 'newest' => 'sparkles', 'title' => 'arrow-down-a-z'])
                <div class="courses-toolbar" data-reveal>
                    <p class="courses-count" role="status" aria-live="polite">Showing <b data-shown>{{ $courses->lastItem() }}</b> of <b data-total>{{ $courses->total() }}</b> courses</p>
                    <div class="sort-dd" data-sort-dd>
                        <span class="sort-dd-label" id="sort-label">Sort by</span>
                        <button class="sort-dd-btn" type="button" aria-haspopup="listbox" aria-expanded="false" aria-labelledby="sort-label sort-current">
                            <i data-lucide="{{ $icons[$sort] }}" data-sort-icon></i><span id="sort-current">{{ $sorts[$sort] }}</span><i class="sort-dd-chevron" data-lucide="chevron-down"></i>
                        </button>
                        <ul class="sort-dd-menu" role="listbox" tabindex="-1" aria-labelledby="sort-label">
                            @foreach ($sorts as $value => $label)
                                <li role="option" tabindex="-1" data-value="{{ $value }}" data-icon="{{ $icons[$value] }}" aria-selected="{{ $value === $sort ? 'true' : 'false' }}"><i data-lucide="{{ $icons[$value] }}"></i><span>{{ $label }}</span><i class="sort-dd-check" data-lucide="check"></i></li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="course-grid-list" id="course-grid" data-reveal data-stagger data-sort="{{ $sort }}">
                    @include('partials.course-grid-items', ['courses' => $courses])
                </div>

                <div class="load-more-wrap" data-load-more-wrap @if(! $courses->hasMorePages()) hidden @endif>
                    <div class="load-more-progress" aria-hidden="true"><span data-progress style="width: {{ round($courses->lastItem() / $courses->total() * 100) }}%"></span></div>
                    <a class="load-more" href="{{ $courses->nextPageUrl() }}" data-load-more><span class="load-more-text">Load more courses</span><span class="load-more-spinner" aria-hidden="true"></span><i data-lucide="arrow-down"></i></a>
                </div>
                <p class="all-loaded" data-all-loaded @if($courses->hasMorePages() || $courses->total() <= $courses->perPage()) hidden @endif>You have seen all {{ $courses->total() }} courses.</p>
            @endif
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/course-slider.js') }}?v={{ filemtime(public_path('js/course-slider.js')) }}"></script>
    <script src="{{ asset('js/courses-page.js') }}?v={{ filemtime(public_path('js/courses-page.js')) }}"></script>
@endpush
