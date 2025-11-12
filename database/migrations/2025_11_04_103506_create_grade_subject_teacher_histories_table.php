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
        Schema::create('grade_subject_teacher_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('grade_id');
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('teacher_id');
            $table->date('from_date');
            $table->date('to_date')->nullable(); // null means currently assigned
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grade_subject_teacher_history');
    }
};
