<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    //
     protected $fillable = [
        'user_id',
        'gender',
        'phone',
        'dateofbirth',
        'current_address',
        'qualification',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function students() 
    {
        return $this->classes()->withCount('students');
    }

    public function grades()
{
    return $this->belongsToMany(Grade::class, 'grade_subject_teacher', 'teacher_id', 'grade_id')
                ->withPivot('subject_id')
                ->withTimestamps();
}      

public function classTeacherOf()
{
    return $this->hasMany(Grade::class, 'class_teacher');
}

public function subjects()
{
    return $this->belongsToMany(Subject::class, 'grade_subject_teacher', 'teacher_id', 'subject_id')
                ->withPivot('grade_id')
                ->withTimestamps();
}

public function classHistory()
{
    return $this->hasMany(ClassTeacherHistory::class, 'teacher_id');
}


}


// Get all subjects of a class
//$subjects = Grade::find(1)->subjects;

// Get the teacher of a subject in a class
//$teacher = GradeSubjectTeacher::where('grade_id', 1)
                           //   ->where('subject_id', 3)
                           //   ->first()
                            //  ->teacher;

// Get all classes a teacher teaches
//$classes = Teacher::find(2)->grades;
