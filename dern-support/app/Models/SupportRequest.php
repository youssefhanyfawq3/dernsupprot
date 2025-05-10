<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SupportRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'user_id',
        'technician_id',
        'service_type',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    /**
     * Get the user who created the support request
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the technician assigned to the support request
     */
    public function technician(): BelongsTo
    {
        return $this->belongsTo(Technician::class);
    }

    /**
     * Get the service schedule for this support request
     */
    public function serviceSchedule(): HasOne
    {
        return $this->hasOne(ServiceSchedule::class);
    }

    /**
     * Get the comments for this support request
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the satisfaction rating for this support request
     */
    public function satisfactionRating(): HasOne
    {
        return $this->hasOne(SatisfactionRating::class);
    }

    /**
     * Scope a query to only include open requests
     */
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    /**
     * Scope a query to only include resolved requests
     */
    public function scopeResolved($query)
    {
        return $query->whereNotNull('resolved_at');
    }
}
