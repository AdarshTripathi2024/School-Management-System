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
        Schema::create('sale_parent', function (Blueprint $table) {
            $table->id();
            $table->string('bill_no')->unique();
            // $table->unsignedBigInteger('student_id');
            // $table->unsignedBigInteger('class_id')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->decimal('total',10,2);
            $table->enum('is_return',[0,1,2])->default(0); // 0=No return, 1=Full return, 2=Partial return
            $table->string('payment_mode')->nullable();
            $table->decimal('discount',10,2)->default(0);
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('return_taken_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_parent');
    }
};
