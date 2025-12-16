<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnBill extends Model
{
    protected $table = 'returnbill';
    protected $fillable = [
        'bill_no','remark','total_refund','taken_by'
    ];


       public function returnChildren()
    {
        return $this->hasMany(ReturnBillChild::class, 'return_id');
    }

    public function return_by()
    {
        return $this->belongsTo(User::class,'taken_by');
    }

    public function saleBill()
    {
        return $this->belongsTo(Sale::class,'bill_no','bill_no'); 
        // third parameter is the column_name of Sale class where it is the ownerkey.
    }

}
