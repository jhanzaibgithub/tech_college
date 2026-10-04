<div class="topbar">
    <div class="container topbar-inner">
        <a href="mailto:{{ $site['email'] }}"><i data-lucide="mail"></i> {{ $site['email'] }}</a>
        <a href="{{ $site['phone_href'] }}"><i data-lucide="phone"></i> {{ $site['phone'] }}</a>
        @if (! empty($site['social']))
            <span class="follow">Follow Us: @include('partials.social-icons', ['class' => 'social-links--light'])</span>
        @endif
    </div>
</div>
