<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    protected $fillable = [
        'name',
        'fee_amount',
    ];

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    /**
     * Students belonging to this class (matched by class name,
     * since students.class stores the class name string).
     */
    public function students()
    {
        return $this->hasMany(Student::class, 'class', 'name');
    }
}