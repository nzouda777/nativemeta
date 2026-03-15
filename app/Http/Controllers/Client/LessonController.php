<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\LessonProgress;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LessonController extends Controller
{
    public function show(Request $request, Lesson $lesson)
    {
        $user = $request->user();
        $course = $lesson->module->course;

        // Check access (unless preview)
        if (!$lesson->is_preview && !$user->hasAccessToCourse($course)) {
            abort(403, 'Vous n\'avez pas accès à cette leçon.');
        }

        $progress = $user->lessonProgress()
            ->where('lesson_id', $lesson->id)
            ->first();

        return response()->json([
            'lesson' => [
                'id' => $lesson->id,
                'title' => $lesson->title,
                'description' => $lesson->description,
                'type' => $lesson->type,
                'content_url' => $lesson->content_url,
                'content_text' => $lesson->content_text,
                'duration_seconds' => $lesson->duration_seconds,
                'is_preview' => $lesson->is_preview,
            ],
            'progress' => $progress ? [
                'is_completed' => $progress->is_completed,
                'watched_seconds' => $progress->watched_seconds,
            ] : null,
        ]);
    }
}
