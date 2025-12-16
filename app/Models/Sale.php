<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    //
    protected $table = 'sale_parent';
    protected $fillable = [
        'bill_no','parent_id','total','is_return','payment_mode','discount','created_by','return_taken_by'
    ];

      public function parent()
    {
        return $this->belongsTo(Parents::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(SaleChild::class, 'sale_id');
    }

    public function sale_done_by()
    {
        return $this->belongsTo(User::class,'created_by');
    }
    
}
