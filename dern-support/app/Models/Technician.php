<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Technician extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'specialization',
        'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    /**
     * Get all support requests assigned to this technician
     */
    public function supportRequests(): HasMany
    {
        return $this->hasMany(SupportRequest::class);
    }

    /**
     * Get active support requests assigned to this technician
     */
    public function activeRequests(): HasMany
    {
        return $this->supportRequests()->where('status', 'open');
    }
}
