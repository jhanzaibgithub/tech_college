<?php

namespace App\Providers;

use App\Services\SiteSettingsService;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(SiteSettingsService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // On the live site (APP_URL=https://...) never build http:// asset links: browsers block those images as mixed content.
        if (str_starts_with((string) config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }

        View::composer('layouts.public', function ($view) {
            $view->with('footerCourses', \App\Models\Course::query()
                ->where('is_active', true)
                ->orderByRaw('rating is null')
                ->orderByDesc('rating')
                ->orderBy('sort_order')
                ->take(5)
                ->get(['id', 'title', 'slug']));
        });

        View::composer(['layouts.public', 'welcome', 'about', 'contact', 'course-detail', 'courses'], function ($view) {
            $view->with('site', app(SiteSettingsService::class)->site());
        });
    }
}
