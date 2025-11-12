<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassTeacherHistory extends Model
{
    //

     protected $table = 'class_teacher_history';

    // ✅ Allow mass assignment for these columns
    protected $fillable = [
        'grade_id',
        'teacher_id',
        'from_date',
        'to_date',
    ];

    // ✅ Relationships

    /**
     * A history record belongs to a grade (class)
     */
    public function grade()
    {
        return $this->belongsTo(Grade::class, 'grade_id');
    }

    /**
     * A history record belongs to a teacher
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    // Helper method (optional): check if this record is current
    public function isCurrent()
    {
        return is_null($this->to_date);
    }
}
