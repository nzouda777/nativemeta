<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\LessonProgress;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    public function update(Request $request, Lesson $lesson)
    {
        $request->validate([
            'watched_seconds' => ['sometimes', 'integer', 'min:0'],
            'is_completed' => ['sometimes', 'boolean'],
        ]);

        $progress = LessonProgress::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'lesson_id' => $lesson->id,
            ],
            array_filter([
                'watched_seconds' => $request->input('watched_seconds'),
                'is_completed' => $request->input('is_completed'),
                'completed_at' => $request->boolean('is_completed') ? now() : null,
            ], fn ($v) => $v !== null)
        );

        return response()->json([
            'progress' => [
                'is_completed' => $progress->is_completed,
                'watched_seconds' => $progress->watched_seconds,
            ],
        ]);
    }
}
