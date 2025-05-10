<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceSchedule extends Model
{
    use HasFactory;
    protected $fillable = [
        'support_request_id',
        'technician_id',
        'service_type',
        'scheduled_at',
        'location',
        'status'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime'
    ];

    public function supportRequest(): BelongsTo
    {
        return $this->belongsTo(SupportRequest::class);
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(Technician::class);
    }

    public function isUpcoming(): bool
    {
        return $this->scheduled_at->isFuture() && $this->status === 'scheduled';
    }
}
