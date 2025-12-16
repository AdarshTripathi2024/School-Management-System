<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('returnbill_child', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('return_id');
            $table->unsignedBigInteger('item_id');
            $table->string('variant');
            $table->integer('qty');
            $table->decimal('unit_price',10,2);
            $table->integer('total');
            $table->timestamps();   
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('returnbill_child');
    }
};
