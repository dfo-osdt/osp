<?php

namespace App\Observers;

use App\Models\HelpfulLink;
use Illuminate\Support\Facades\Cache;

class HelpfulLinkObserver
{
    public function created(HelpfulLink $helpfulLink): void
    {
        Cache::forget('public-stats');
    }

    public function updated(HelpfulLink $helpfulLink): void
    {
        Cache::forget('public-stats');
    }

    public function deleted(HelpfulLink $helpfulLink): void
    {
        Cache::forget('public-stats');
    }
}
