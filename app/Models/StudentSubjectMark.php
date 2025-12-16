<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentSubjectMark extends Model
{
    protected $table= 'student_subject_marks';
    protected $fillable = ['result_id','subject_id','theory_total','obtained_theory','practical_total','obtained_practical','total_marks','obtained_total'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

}
