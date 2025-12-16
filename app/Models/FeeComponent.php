<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeComponent extends Model
{
    //
    protected $table = 'fee_component';
    protected $fillable = [
        'name'
    ];
}
