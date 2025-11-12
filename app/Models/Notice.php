<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    //
    protected $table = 'notices';
    protected $fillable = [
        'title',
        'content',
        'audience',
        'class_id',
        'notice_date',
        'expiry_date',
        'is_important',
        'attachment',
    ];
}
