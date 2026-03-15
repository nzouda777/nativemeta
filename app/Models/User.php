<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles, LogsActivity;

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isAdmin() && $this->is_active;
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'stripe_customer_id',
        'last_login_at',
        'is_active',
    ];

    protected $appends = ['avatar_url'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'is_active'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "L'utilisateur {$this->name} a été {$eventName}");
    }

    // Relations
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'enrollments')
            ->withPivot('enrolled_at', 'expires_at')
            ->withTimestamps();
    }

    public function lessonProgress()
    {
        return $this->hasMany(LessonProgress::class);
    }

    public function createdCourses()
    {
        return $this->hasMany(Course::class, 'created_by');
    }

    // Helpers
    public function hasAccessToCourse(Course $course): bool
    {
        return $this->enrollments()
            ->where('course_id', $course->id)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->exists();
    }

    public function getCourseProgress(Course $course): float
    {
        $totalLessons = $course->lessons()->count();
        if ($totalLessons === 0) return 0;

        $completedLessons = $this->lessonProgress()
            ->whereIn('lesson_id', $course->lessons()->pluck('lessons.id'))
            ->where('is_completed', true)
            ->count();

        return round(($completedLessons / $totalLessons) * 100, 1);
    }

    public function isAdmin(): bool
    {
        return $this->hasAnyRole(['super_admin', 'admin']);
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin');
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->avatar)) {
            return \Illuminate\Support\Facades\Storage::url($this->avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=f59e0b&background=050508';
    }
}
