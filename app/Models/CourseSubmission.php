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
        'feedback',
        'submitted_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function assignment()
    {
        return $this->belongsTo(CourseAssignment::class, 'assignment_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
