<?php

namespace App\Http\Middleware;

use App\Models\Course;
use App\Models\Lesson;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckCourseAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Check for lesson parameter
        $lesson = $request->route('lesson');
        if ($lesson) {
            if ($lesson instanceof Lesson) {
                $course = $lesson->module->course;
            } else {
                $lesson = Lesson::findOrFail($lesson);
                $course = $lesson->module->course;
            }

            // Allow preview lessons
            if ($lesson->is_preview) {
                return $next($request);
            }

            if (!$user->hasAccessToCourse($course)) {
                abort(403, 'Vous n\'avez pas accès à cette formation.');
            }

            return $next($request);
        }

        // Check for course parameter
        $courseParam = $request->route('course') ?? $request->route('slug');
        if ($courseParam) {
            $course = $courseParam instanceof Course
                ? $courseParam
                : Course::where('slug', $courseParam)->firstOrFail();

            if (!$user->hasAccessToCourse($course)) {
                abort(403, 'Vous n\'avez pas accès à cette formation.');
            }
        }

        return $next($request);
    }
}
