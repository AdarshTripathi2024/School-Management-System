<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transport extends Model
{
    //
    protected $table = 'transports';
    protected $fillable = [
     'vehicle_type','driver_id','vehicle_number','route'
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
