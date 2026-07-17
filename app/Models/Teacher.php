<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'subject',
        'salary',
    ];

    /**
     * User Relationship
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Subjects this teacher teaches (many-to-many)
     */
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'subject_teacher');
    }
}