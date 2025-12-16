<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    //
    protected $table = 'results';
    protected $fillable = ['student_id','exam_id','total','grandtotal','percentage','class_id'];

    public function subjectMarks()
    {
        return $this->hasMany(StudentSubjectMark::class);
    }

     public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // Each result belongs to one class
    public function class()
    {
        return $this->belongsTo(Grade::class, 'class_id');
    }

    // Each result belongs to one exam
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }


}
