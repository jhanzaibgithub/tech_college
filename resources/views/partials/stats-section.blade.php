<section class="stats-band" aria-label="Tech College in numbers" data-reveal>
    <div class="container stats-grid" data-stagger>
        @foreach ($site['stats'] as $stat)
            <article class="stat-card">
                <span class="stat-icon"><i data-lucide="{{ $stat['icon'] }}"></i></span>
                <strong data-count-to="{{ $stat['value'] }}">{{ number_format($stat['value']) }}</strong>
                <span class="stat-label">{{ $stat['label'] }}</span>
            </article>
        @endforeach
    </div>
</section>
