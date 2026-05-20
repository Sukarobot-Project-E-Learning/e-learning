<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseSubmission extends Model
{
    protected $table = 'lms_submissions';

    protected $fillable = [
        'assignment_id',
        'user_id',
        'file_path',
        'file_name',
        'grade',
        'score',
        'feedback',
        'submitted_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'score' => 'integer',
    ];

    /**
     * Relation to parent assignment.
     */
    public function assignment()
    {
        return $this->belongsTo(
            CourseAssignment::class,
            'assignment_id'
        );
    }

    /**
     * Relation to submitting user.
     */
    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }

    /**
     * Check if this submission passed the assignment.
     */
    public function hasPassed(): bool
    {
        if ($this->score === null) {
            return false;
        }

        $passingScore = $this->assignment
            ->passing_score ?? 70;

        return $this->score >= $passingScore;
    }
}
