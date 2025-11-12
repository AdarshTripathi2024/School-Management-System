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
        Schema::create('class_teacher_history', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('grade_id');
        $table->unsignedBigInteger('teacher_id');
        $table->date('from_date');
        $table->date('to_date')->nullable();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_teacher_history');
    }
};
