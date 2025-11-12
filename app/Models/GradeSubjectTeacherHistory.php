<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeSubjectTeacherHistory extends Model
{
    //
    protected $table = 'grade_subject_teacher_history';
    protected $fillable = [
        'grade_id','subject_id','teacher_id', 'from_date','to_date'
    ];


//Relations

public function grade()
{
    return $this->belongsTo(Grade::class);
} 

public function subject()
{
    return $this->belongsTo(Subject::class);
}

public function teacher()
{
    return $this->belongsTo(Teacher::class);
}



}
