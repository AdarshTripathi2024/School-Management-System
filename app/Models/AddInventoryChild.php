<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddInventoryChild extends Model
{
    protected $table = 'add_inventory_child';
    protected $fillable = [
        'inventory_id','item_id','qty','cost_price','selling_price','subtotal','variant'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class,'item_id');
    }

    public function add_inventory()
    {
        return $this->belongsTo(AddInventory::class,'inventory_id');
    }
}


    