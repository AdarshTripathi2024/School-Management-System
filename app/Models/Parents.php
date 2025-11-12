<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parents extends Model
{
    //
    protected $table = 'parents';

    protected $fillable = [
        'user_id',
        'mother_name',
        'mqualification',
        'fqualification',
        'foccupation',
        'moccupation',
        'phone',
        'current_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function children()
    {
        return $this->hasMany(Student::class, 'parent_id');
    }
}
