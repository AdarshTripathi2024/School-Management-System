<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnBillChild extends Model
{
    protected $table = 'returnbill_child';
    protected $fillable = [
        'return_id','item_id','variant','taken_by','qty','unit_price','total'
    ];

     public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
    
}

