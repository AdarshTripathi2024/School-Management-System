<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'inventory'; 
    protected $fillable = [
        'item_id','selling_price','cost_price','created_by','variant','stock'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class,'item_id');
    }

    public function inv_created_by()
    {
        return $this->belongsTo(User::class,'created_by');
    }
}
