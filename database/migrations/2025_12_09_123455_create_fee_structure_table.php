<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('fee_structure_child', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id');            // FK → grades or classes             // e.g., 2024-25
            $table->unsignedBigInteger('fee_component_id');     // FK → fee_components
            $table->decimal('amount', 10, 2);                   // fee amount
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_structure_child');
    }
};
