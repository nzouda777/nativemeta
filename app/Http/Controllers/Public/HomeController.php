<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Category;
use App\Models\Setting;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        $courses = Course::published()
            ->with(['category', 'modules.lessons'])
            ->withCount('enrollments')
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get()
            ->map(fn ($course) => [
                'id' => $course->id,
                'title' => $course->title,
                'slug' => $course->slug,
                'description' => $course->description,
                'thumbnail' => $course->thumbnail_url,
                'price' => $course->price,
                'sale_price' => $course->sale_price,
                'effective_price' => $course->getEffectivePrice(),
                'is_on_sale' => $course->isOnSale(),
                'is_featured' => $course->is_featured,
                'category' => $course->category?->name,
                'student_count' => $course->enrollments_count,
                'duration' => $course->getFormattedDuration(),
                'lesson_count' => $course->lessons->count(),
            ]);

        $categories = Category::withCount('publishedCourses')
            ->orderBy('order')
            ->get();

        return Inertia::render('Public/Home', [
            'courses' => $courses,
            'categories' => $categories,
            'stats' => [
                'students' => Setting::get('stats.students', 500),
                'revenue' => Setting::get('stats.revenue', '2M€'),
                'satisfaction' => Setting::get('stats.satisfaction', '4.9/5'),
            ],
        ]);
    }
}
