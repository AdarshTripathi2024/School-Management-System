<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class holiday extends Model
{
    //

    protected $table = 'holidays';
    protected $fillable = [
        'date', 'occasion','is_for_teacher','is_for_students','session','remark',
    ];
}
