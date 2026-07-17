<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mark extends Model
{
    protected $fillable = [
        'student_id',
        'subject_id',
        'marks',
        'total_marks',
        'grade',
    ];

    // Student Relationship
    public function student()
    {
        return $this->belongsTo(Student::class)->withTrashed();
    }

    // Subject Relationship
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}