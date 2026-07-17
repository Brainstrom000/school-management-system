<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'phone',
        'address',
        'class',
        'profile_image',
    ];

    /**
     * User Relationship
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Attendance Relationship
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Marks Relationship
     */
    public function marks()
    {
        return $this->hasMany(Mark::class);
    }

    /**
     * Fees Relationship
     */
    public function fees()
    {
        return $this->hasMany(Fee::class);
    }

    /**
     * Subjects this student is enrolled in (many-to-many)
     */
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'student_subject');
    }
}