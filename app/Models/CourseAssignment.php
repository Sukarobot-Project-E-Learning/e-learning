<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseAssignment extends Model
{
    protected $table = 'lms_assignments';

    protected $fillable = [
        'program_id',
        'type',
        'title',
        'description',
        'allowed_extensions',
        'due_date',
        'passing_score',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'passing_score' => 'integer',
    ];

    /**
     * Relation to parent program.
     */
    public function program()
    {
        return $this->belongsTo(
            Program::class,
            'program_id'
        );
    }

    /**
     * Relation to submissions.
     */
    public function submissions()
    {
        return $this->hasMany(
            CourseSubmission::class,
            'assignment_id'
        );
    }

    /**
     * Check if a user has submitted this assignment.
     */
    public function hasUserSubmitted($userId)
    {
        return $this->submissions()
            ->where('user_id', $userId)
            ->exists();
    }

    /**
     * Get user's submission for this assignment.
     */
    public function getUserSubmission($userId)
    {
        return $this->submissions()
            ->where('user_id', $userId)
            ->latest()
            ->first();
    }

    /**
     * Scope: only post-test assignments.
     */
    public function scopePostTests($query)
    {
        return $query->where('type', 'post-test');
    }

    /**
     * Scope: only standard assignments.
     */
    public function scopeStandard($query)
    {
        return $query->where('type', 'standard');
    }

    /**
     * Check if this assignment is a post-test.
     */
    public function isPostTest(): bool
    {
        return $this->type === 'post-test';
    }
}
