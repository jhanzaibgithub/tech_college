@extends('admin.layout')

@section('title', 'Maintenance')

@section('content')
    <section class="admin-card">
        <div class="admin-card-head">
            <div><h2>Maintenance</h2><p>One-click server tools for hosting without a command line. Safe to run at any time; your content is not touched.</p></div>
        </div>

        @if (session('maintenance_error'))
            <div class="admin-error-list" role="alert"><p>{{ session('maintenance_error') }}</p></div>
        @endif

        @if (session('maintenance_log'))
            <div class="maint-log" role="status">
                <strong>{{ session('maintenance_title') }}: result</strong>
                <ul>
                    @foreach (session('maintenance_log') as $line)
                        <li @class(['is-ok' => $line['ok'], 'is-fail' => ! $line['ok']])>{{ $line['text'] }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="maint-grid">
            <article class="maint-card">
                <span class="maint-icon"><i data-lucide="eraser"></i></span>
                <h3>Clear all caches</h3>
                <p>Runs <code>optimize:clear</code>, <code>view:clear</code>, <code>cache:clear</code>, <code>route:clear</code> and <code>config:clear</code>. Use it after uploading new code or changing the <code>.env</code> file, or when the site shows old content.</p>
                <form method="POST" action="{{ route('admin.maintenance.clear-cache') }}" data-confirm="Clear all caches now? The first page loads afterwards may be a little slower.">
                    @csrf
                    <button class="admin-button" type="submit"><i data-lucide="trash-2"></i> Clear all caches</button>
                </form>
            </article>

            <article class="maint-card">
                <span class="maint-icon"><i data-lucide="link"></i></span>
                <h3>Connect storage link</h3>
                <p>Runs <code>storage:link</code> so files in <code>storage/app/public</code> are reachable from the website as <code>/storage/...</code>. Use it if uploaded files or pictures do not show on the live site.</p>
                <p class="maint-state is-{{ $storage['state'] }}"><i></i>
                    @switch($storage['state'])
                        @case('connected') Connected @break
                        @case('missing') Not connected yet @break
                        @case('broken') Link is pointing to the wrong place @break
                        @default Cannot create here
                    @endswitch
                    <small>{{ $storage['message'] }}</small>
                </p>
                <form method="POST" action="{{ route('admin.maintenance.storage-link') }}">
                    @csrf
                    <button class="admin-button" type="submit" @disabled($storage['state'] === 'connected')><i data-lucide="link-2"></i> {{ $storage['state'] === 'connected' ? 'Already connected' : 'Connect storage link' }}</button>
                </form>
            </article>
        </div>
    </section>
@endsection
