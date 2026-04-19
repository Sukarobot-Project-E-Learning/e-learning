<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseSection extends Model
{
    protected $table = 'lms_sections';

    protected $fillable = [
        'program_id',
        'title',
        'order',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    public function lessons()
    {
        return $this->hasMany(CourseLesson::class, 'section_id')->orderBy('order', 'asc');
    }
}
