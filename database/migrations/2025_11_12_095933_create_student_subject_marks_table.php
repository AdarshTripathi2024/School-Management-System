<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('student_subject_marks', function (Blueprint $table) {
            //
             $table->id();
            $table->unsignedBigInteger('result_id');
            $table->unsignedBigInteger('subject_id');
            $table->integer('theory_total');
            $table->decimal('obtained_theory');
            $table->integer('practical_total');
            $table->decimal('obtained_practical');
            $table->integer('total_marks');
            $table->decimal('obtained_total');
            $table->timestamps();
        });
    }

    public function down(): void
    {
         Schema::table('student_subject_marks', function (Blueprint $table) {
            //
        });
    }
};
