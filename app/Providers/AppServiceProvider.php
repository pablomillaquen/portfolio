<?php

namespace App\Providers;

use App\Models\Course;
use App\Models\Post;
use App\Models\Project;
use App\Observers\ContentObserver;
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
    }
}
