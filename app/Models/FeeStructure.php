<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeStructure extends Model
{
    protected $table = 'fee_structure';
    protected $fillable = [
        'class_id','total_fee','monthly_installment','quarterly_installment','halfyearly_installment','academic_year'
    ];

    public function children()
    {
        return $this->hasMany(FeeStructureChild::class,'parent_id');
    }

    public function grade()
    {
       return $this->belongsTo(Grade::class,'class_id');
    }  
}
