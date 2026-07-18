<?php

namespace App\Providers;

use App\Models\Course;
use App\Models\Post;
use App\Models\Project;
use App\Observers\ContentObserver;
use Cache\RateLimiting\Limit;
use Dedoc\Scramble\Scramble;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Project::observe(ContentObserver::class);
        Post::observe(ContentObserver::class);
        Course::observe(ContentObserver::class);

        $this->configureRateLimiting();
        $this->configureScramble();
    }

    private function configureRateLimiting(): void
    {
        RateLimiter::for('api-anonymous', function (Request $request) {
            return Limit::perMinute(60)->by($request->ip());
        });

        RateLimiter::for('api-authenticated', function (Request $request) {
            return Limit::perMinute(120)->by($request->user()?->id ?: $request->ip());
        });
    }

    private function configureScramble(): void
    {
        Scramble::configure()
            ->apiPath('api/v1');
    }
}
