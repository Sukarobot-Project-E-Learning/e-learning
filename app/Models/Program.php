<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $table = 'data_programs';

    protected $fillable = [
        'program',
        'slug',
        'description',
        'price',
        'category',
        'type',
        'image',
        'zoom_link',
        'status',
        'instructor_id',
        'quota',
        'enrolled_count',
        'rating',
        'total_reviews',
        'province',
        'city',
        'district',
        'village',
        'full_address',
        'start_date',
        'start_time',
        'end_date',
        'end_time',
        'tools',
        'learning_materials',
        'benefits',
    ];

    protected $casts = [
        'tools' => 'array',
        'learning_materials' => 'array',
        'benefits' => 'array',
        'price' => 'decimal:2',
        'rating' => 'decimal:1',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Relation to Instructor (User)
     */
    public function instructor()
    {
        return $this->belongsTo(\App\Models\User::class, 'instructor_id');
    }

    /**
     * Relation to Transactions
     */
    public function transactions()
    {
        return $this->hasMany(\App\Models\Transaction::class, 'program_id');
    }
}
