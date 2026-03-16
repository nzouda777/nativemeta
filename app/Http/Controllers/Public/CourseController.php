<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Category;
use Inertia\Inertia;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::published()
            ->with(['category', 'creator'])
            ->withCount('enrollments')
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(12)
            ->through(fn ($course) => [
                'id' => $course->id,
                'title' => $course->title,
                'slug' => $course->slug,
                'description' => $course->description,
                'thumbnail' => $course->thumbnail_url,
                'price' => $course->price,
                'sale_price' => $course->sale_price,
                'effective_price' => $course->getEffectivePrice(),
                'is_on_sale' => $course->isOnSale(),
                'category' => $course->category?->name,
                'duration' => $course->getFormattedDuration(),
                'student_count' => $course->enrollments_count,
            ]);

        $categories = Category::withCount('publishedCourses')
            ->orderBy('order')
            ->get();

        return Inertia::render('Public/Courses', [
            'courses' => $courses,
            'categories' => $categories,
        ]);
    }

    public function show(string $slug = 'native-ads-playbook-eu')
    {
        // If accessing via playbook subdomain, default to the playbook course
        if (request()->getHost() === 'playbook.nativescale.com') {
            $slug = 'native-ads-playbook-eu';
        }

        $course = Course::where('slug', $slug)
            ->published()
            ->with([
                'category',
                'creator',
                'modules' => fn ($q) => $q->orderBy('order'),
                'modules.lessons' => fn ($q) => $q->orderBy('order'),
            ])
            ->withCount('enrollments')
            ->firstOrFail();

        return Inertia::render('Public/CourseDetail', [
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
                'slug' => $course->slug,
                'description' => $course->description,
                'long_description' => $course->long_description,
                'thumbnail' => $course->thumbnail_url,
                'trailer_url' => $course->trailer_url,
                'price' => $course->price,
                'sale_price' => $course->sale_price,
                'effective_price' => $course->getEffectivePrice(),
                'is_on_sale' => $course->isOnSale(),
                'currency' => $course->currency,
                'category' => $course->category?->name,
                'creator' => $course->creator ? [
                    'name' => $course->creator->name,
                    'avatar' => $course->creator->avatar,
                ] : null,
                'student_count' => $course->enrollments_count,
                'duration' => $course->getFormattedDuration(),
                'meta_title' => $course->meta_title ?? $course->title,
                'meta_description' => $course->meta_description ?? $course->description,
                'modules' => $course->modules->map(fn ($module) => [
                    'id' => $module->id,
                    'title' => $module->title,
                    'description' => $module->description,
                    'lessons' => $module->lessons->map(fn ($lesson) => [
                        'id' => $lesson->id,
                        'title' => $lesson->title,
                        'type' => $lesson->type,
                        'duration' => $lesson->getFormattedDuration(),
                        'is_preview' => $lesson->is_preview,
                    ]),
                ]),
            ],
            'userHasAccess' => auth()->check() && auth()->user()->hasAccessToCourse($course),
        ]);
    }
}
