<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    //
    protected $table = 'drivers';
    protected $fillable = [
        'name','license_number','email','phone','address','date_of_joining'
    ];
    
    public function transport(){
        return $this->hasOne(Transport::class);
    }
}
