<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $enrollments = $user->enrollments()
            ->with(['course' => fn ($q) => $q->with(['modules.lessons', 'category'])])
            ->active()
            ->get()
            ->map(function ($enrollment) use ($user) {
                $course = $enrollment->course;
                return [
                    'id' => $enrollment->id,
                    'course' => [
                        'id' => $course->id,
                        'title' => $course->title,
                        'slug' => $course->slug,
                        'thumbnail' => $course->thumbnail,
                        'category' => $course->category?->name,
                        'duration' => $course->getFormattedDuration(),
                        'lesson_count' => $course->lessons->count(),
                    ],
                    'progress' => $user->getCourseProgress($course),
                    'enrolled_at' => $enrollment->enrolled_at->format('d/m/Y'),
                ];
            });

        // Get last activity
        $lastProgress = $user->lessonProgress()
            ->with(['lesson.module.course'])
            ->orderBy('updated_at', 'desc')
            ->first();

        return Inertia::render('Client/Dashboard', [
            'enrollments' => $enrollments,
            'lastActivity' => $lastProgress ? [
                'lesson_title' => $lastProgress->lesson->title,
                'course_title' => $lastProgress->lesson->module->course->title,
                'course_slug' => $lastProgress->lesson->module->course->slug,
                'date' => $lastProgress->updated_at->diffForHumans(),
            ] : null,
        ]);
    }
}
