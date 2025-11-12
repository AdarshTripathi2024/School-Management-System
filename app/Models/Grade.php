<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    //
        protected $fillable = [
        'class_name',
        'class_numeric',
        'class_description',
        'class_teacher',
    ];

   public function subjects()
{
    return $this->belongsToMany(Subject::class, 'grade_subject_teacher', 'grade_id', 'subject_id')
                ->withPivot('teacher_id')
                ->withTimestamps();
}

public function teachers()
{
    return $this->belongsToMany(Teacher::class, 'grade_subject_teacher', 'grade_id', 'teacher_id')
                ->withPivot('subject_id')
                ->withTimestamps();
}

public function teacherHistory()
{
    return $this->hasMany(ClassTeacherHistory::class, 'grade_id');
}

public function teacher()
{
    return $this->belongsTo(Teacher::class, 'class_teacher');
}

public function students()
{
    return $this->hasMany(Student::class, 'class_id');
}

}
