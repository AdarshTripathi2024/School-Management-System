<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('add_inventory_child', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventory_id'); //foreign key refering to add_inventory table
            $table->unsignedBigInteger('item_id');
            $table->string('variant');
            $table->integer('qty');
            $table->decimal('cost_price',10,2);
            $table->decimal('selling_price',10,2);
            $table->decimal('subtotal',10,2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('add_inventory_child');
    }
};
