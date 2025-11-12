<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    //

    protected $fillable = [
        'name',
        'slug',
        'subject_code',
        'description'
    ];

    public function grades()
{
    return $this->belongsToMany(Grade::class, 'grade_subject_teacher', 'subject_id', 'grade_id')
                ->withPivot('teacher_id')
                ->withTimestamps();
}

public function teachers()
{
    return $this->belongsToMany(Teacher::class, 'grade_subject_teacher', 'subject_id', 'teacher_id')
                ->withPivot('grade_id')
                ->withTimestamps();
}

 
}
