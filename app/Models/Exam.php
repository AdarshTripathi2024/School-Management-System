<?php

namespace App\Models;
use App\Models\Result;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    //
    protected $table = "exams";
    protected $fillable = [
        'exam_name','start_date','end_date','description','timetable','status'
    ];

    public function results()
    {
        return $this->hasMany(Result::class);
    }
}
