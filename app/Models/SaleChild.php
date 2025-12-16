<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleChild extends Model
{
    //
    protected $table = 'sale_child';
    protected $fillable = [ 
        'sale_id','item_id','qty','price','subtotal','returned_quantity','variant' ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
