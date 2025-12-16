<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeStructureChild extends Model
{
    //
    protected $table = 'fee_structure_child';
    protected $fillable = [
        'parent_id','fee_component_id','amount'
    ];

    public function feeStructureParent()
    {
        $this->belongsTo(FeeStructure::class, 'parent_id');
    }

    public function feeComponent()
    {
        return $this->belongsTo(FeeComponent::class, 'fee_component_id');
    }
    
}
