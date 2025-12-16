<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddInventory extends Model
{
    //
    protected $table = 'add_inventory';
    protected $fillable = [
        'invoice_no','vendor_id','total','added_by','payment_mode'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function inv_added_by()
    {
        return $this->belongsTo(User::class,'added_by');
    }

    public function children()
    {
        return $this->hasMany(AddInventoryChild::class,'inventory_id');
    }
}
    