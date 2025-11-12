<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    //
    protected $table = 'complaints';
    protected $fillable = [
        'complaint_from_id','complaint_to_id','student_id','subject','description','solution','status','attachment'
    ];

     // 🧍‍♂️ The user who raised the complaint
    public function fromUser()
    {
        return $this->belongsTo(User::class, 'complaint_from_id');
    }

    // 🧍‍♀️ The user who is receiving / responsible for the complaint
    public function toUser()
    {
        return $this->belongsTo(User::class, 'complaint_to_id');
    }

    //  If the complaint is about a student
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
