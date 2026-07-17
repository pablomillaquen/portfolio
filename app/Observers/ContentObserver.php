<?php

namespace App\Observers;

use App\Jobs\RegenerateSitemap;
use Illuminate\Database\Eloquent\Model;

class ContentObserver
{
    public function created(Model $model): void
    {
        $this->dispatchJob();
    }

    public function updated(Model $model): void
    {
        $this->dispatchJob();
    }

    public function deleted(Model $model): void
    {
        $this->dispatchJob();
    }

    private function dispatchJob(): void
    {
        RegenerateSitemap::dispatch();
    }
}
