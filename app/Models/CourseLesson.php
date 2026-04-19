<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseLesson extends Model
{
    protected $table = 'lms_lessons';

    protected $fillable = [
        'section_id',
        'title',
        'type',
        'content',
        'video_url',
        'order',
    ];

    public function section()
    {
        return $this->belongsTo(CourseSection::class, 'section_id');
    }

    public function progresses()
    {
        return $this->hasMany(CourseProgress::class, 'lesson_id');
    }

    public function isCompletedByUser($userId)
    {
        return $this->progresses()->where('user_id', $userId)->where('is_completed', true)->exists();
    }
}
