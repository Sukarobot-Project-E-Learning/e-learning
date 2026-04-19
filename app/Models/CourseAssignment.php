<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseAssignment extends Model
{
    protected $table = 'lms_assignments';

    protected $fillable = [
        'program_id',
        'title',
        'description',
        'allowed_extensions',
        'due_date',
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    public function submissions()
    {
        return $this->hasMany(CourseSubmission::class, 'assignment_id');
    }

    public function hasUserSubmitted($userId)
    {
        return $this->submissions()->where('user_id', $userId)->exists();
    }
}
