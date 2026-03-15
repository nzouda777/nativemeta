<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EnrollmentController extends Controller
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

        return Inertia::render('Client/MyFormations', [
            'enrollments' => $enrollments,
        ]);
    }

    public function show(Request $request, string $slug)
    {
        $user = $request->user();
        $course = Course::where('slug', $slug)
            ->with([
                'modules' => fn ($q) => $q->orderBy('order'),
                'modules.lessons' => fn ($q) => $q->orderBy('order'),
            ])
            ->firstOrFail();

        if (!$user->hasAccessToCourse($course)) {
            abort(403, 'Vous n\'avez pas accès à cette formation.');
        }

        // Get user progress for all lessons
        $progressMap = $user->lessonProgress()
            ->whereIn('lesson_id', $course->lessons->pluck('id'))
            ->get()
            ->keyBy('lesson_id');

        $modules = $course->modules->map(fn ($module) => [
            'id' => $module->id,
            'title' => $module->title,
            'description' => $module->description,
            'lessons' => $module->lessons->map(fn ($lesson) => [
                'id' => $lesson->id,
                'title' => $lesson->title,
                'type' => $lesson->type,
                'duration' => $lesson->getFormattedDuration(),
                'is_completed' => $progressMap->has($lesson->id) && $progressMap[$lesson->id]->is_completed,
                'watched_seconds' => $progressMap->has($lesson->id) ? $progressMap[$lesson->id]->watched_seconds : 0,
            ]),
        ]);

        // Find current lesson (first incomplete)
        $currentLesson = null;
        foreach ($course->modules as $module) {
            foreach ($module->lessons as $lesson) {
                if (!$progressMap->has($lesson->id) || !$progressMap[$lesson->id]->is_completed) {
                    $currentLesson = $lesson->id;
                    break 2;
                }
            }
        }

        return Inertia::render('Client/CoursePlayer', [
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
                'slug' => $course->slug,
                'thumbnail' => $course->thumbnail,
            ],
            'modules' => $modules,
            'currentLessonId' => $currentLesson ?? $course->lessons->first()?->id,
            'progress' => $user->getCourseProgress($course),
        ]);
    }
}
