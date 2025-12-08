<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    protected $table = 'data_trainers';

    protected $fillable = [
        'nama',
        'email',
        'telephone',
        'keahlian',
        'status_trainer',
        'pengalaman',
        'bio',
        'foto',
        'created_at',
        'updated_at',
    ];
}
