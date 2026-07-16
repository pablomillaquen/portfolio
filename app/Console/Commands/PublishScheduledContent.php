<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Models\Project;
use Illuminate\Console\Command;

class PublishScheduledContent extends Command
{
    protected $signature = 'content:publish-scheduled';

    protected $description = 'Publish projects and posts whose scheduled publication date has passed';

    public function handle(): int
    {
        $projectsCount = Project::query()
            ->where('status', 'draft')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->update(['status' => 'published']);

        $postsCount = Post::query()
            ->where('status', 'draft')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->update(['status' => 'published']);

        $total = $projectsCount + $postsCount;

        if ($total > 0) {
            $this->info("Published {$projectsCount} project(s) and {$postsCount} post(s).");
        } else {
            $this->line('No scheduled content to publish.');
        }

        return self::SUCCESS;
    }
}
