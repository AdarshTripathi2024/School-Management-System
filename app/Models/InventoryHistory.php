<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryHistory extends Model
{
    //
    protected $table = 'inventory_history';
    protected $fillable = [
        'inventory_id','change_type','quantity_changed','previous_stock','new_stock','reason','related_sale_id'
    ];
}
