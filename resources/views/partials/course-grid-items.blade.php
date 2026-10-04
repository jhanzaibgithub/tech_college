@foreach ($courses as $item)
    @include('partials.course-card', ['item' => $item])
@endforeach
