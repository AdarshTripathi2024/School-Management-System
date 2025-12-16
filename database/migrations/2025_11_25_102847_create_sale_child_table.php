<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('sale_child', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sale_id');
            $table->unsignedBigInteger('inventory_id'); //changed to item_id     
            $table->integer('quantity');
            $table->decimal('selling_price',10,2); // price at time of sale
            $table->decimal('total_price',10,2);
            $table->integer('returned_quantity')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_child');
    }

};
