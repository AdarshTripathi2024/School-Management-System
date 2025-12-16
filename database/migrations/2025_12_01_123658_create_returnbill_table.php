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
        Schema::create('returnbill', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bill_no');
            $table->string('remark');
            $table->decimal('total_refund',10,2);
            $table->unsignedBigInteger('taken_by');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('returnbill');
    }
};
