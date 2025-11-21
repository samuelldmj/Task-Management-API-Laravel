<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\TaskSummaryResource;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SummaryController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $tasks = $request->user()->taskSummary($request->period);

        return $tasks->mapToGroups(function ($item, $key) {
            return [$item->is_completed ? 'completed' : 'incomplete' => TaskSummaryResource::make($item)];
        });
    }
}
