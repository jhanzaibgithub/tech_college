<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrollment extends Model
{
    use HasFactory;

    public const STATUS_NEW = 'new';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_COMPLETED = 'completed';

    protected $fillable = [
        'course_id',
        'name',
        'email',
        'phone',
        'status',
        'message',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
