<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeSubjectTeacher extends Model
{
    protected $table = 'grade_subject_teacher';
    protected $fillable = ['grade_id', 'subject_id', 'teacher_id'];

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
