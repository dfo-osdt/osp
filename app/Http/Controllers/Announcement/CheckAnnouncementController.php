<?php

namespace App\Http\Controllers\Announcement;

use App\Http\Controllers\Controller;
use App\Http\Resources\AnnouncementResource;
use App\Models\Announcement;
use App\Traits\LocaleTrait;
use Illuminate\Http\Request;

class CheckAnnouncementController extends Controller
{
    use LocaleTrait;

    /**
     * Display active announcements.
     */
    public function __invoke(Request $request)
    {

        $announcement = Announcement::active()->orderBy('start_at', 'desc')->get();

        if ($announcement->isEmpty()) {
            return response()->json([], 204);
        }

        return AnnouncementResource::collection($announcement);

    }
}
