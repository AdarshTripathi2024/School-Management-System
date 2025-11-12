<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('complaint_from_id');
            $table->unsignedBigInteger('complaint_to_id')->nullable();
            $table->unsignedBigInteger('student_id')->nullable();
            $table->string('subject');
            $table->text('description');
            $table->text('solution')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'resolved'])->default('pending');
            $table->string('attachment')->nullable();
            $table->timestamps();
        });
    }   

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
