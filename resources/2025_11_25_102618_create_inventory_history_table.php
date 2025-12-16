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
        Schema::create('inventory_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventory_id')->nullable(); // referring to inventory table
            $table->enum('change_type', ['added','sold','returned','adjusted']);
            $table->integer('quantity_changed')->nullable();
            $table->integer('previous_stock')->nullable();
            $table->integer('new_stock')->nullable();
            $table->string('reason')->nullable();
            $table->unsignedBigInteger('related_sale_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_history');
    }
};
