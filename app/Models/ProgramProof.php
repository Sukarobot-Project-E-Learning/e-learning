<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramProof extends Model
{
    protected $fillable = [
        'student_id',
        'program_id',
        'schedule_id',
        'documentation',
        'status',
        'rating',
        'review'
    ];

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }
}
