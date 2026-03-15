<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Order extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'user_id',
        'email',
        'stripe_session_id',
        'stripe_payment_intent_id',
        'amount',
        'currency',
        'status',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'metadata' => 'json',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'amount'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "La commande #{$this->id} a été {$eventName}");
    }

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'order_items')
            ->withPivot('amount')
            ->withTimestamps();
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function invitationToken()
    {
        return $this->hasOne(InvitationToken::class);
    }

    // Scopes
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Helpers
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isRefunded(): bool
    {
        return $this->status === 'refunded';
    }

    public function getFormattedAmount(): string
    {
        return number_format($this->amount, 2, ',', ' ') . ' ' . strtoupper($this->currency);
    }
}
