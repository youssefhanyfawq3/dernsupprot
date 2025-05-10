<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SatisfactionRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'rating',
        'comment',
        'support_request_id',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    /**
     * Get the support request that owns the rating
     */
    public function supportRequest(): BelongsTo
    {
        return $this->belongsTo(SupportRequest::class);
    }

    /**
     * The rating must be between 1 and 5
     */
    protected static function booted()
    {
        static::saving(function ($rating) {
            if ($rating->rating < 1) $rating->rating = 1;
            if ($rating->rating > 5) $rating->rating = 5;
        });
    }
}
