<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'name',
        'school_class_id',
    ];

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class);
    }
    public function marks()
{
    return $this->hasMany(Mark::class);
}

    /**
     * Teachers who teach this subject (many-to-many)
     */
    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'subject_teacher');
    }

    /**
     * Students enrolled in this subject (many-to-many)
     */
    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_subject');
    }
}
