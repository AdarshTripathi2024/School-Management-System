<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fee_structure', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('class_id');      
            $table->decimal('total_fee',10,2);      
            $table->string('monthly_installment');      
            $table->string('quarterly_installment');      
            $table->string('halfyealy_installment');
            $table->string('academic_year');            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_structure_parent');
    }
};
