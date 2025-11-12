<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
        public function up(): void
        {
                Schema::create('grades', function (Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('class_numeric')->nullable();
                    $table->string('class_name');
                    $table->string('class_description')->nullable();
                    $table->unsignedBigInteger('class_teacher')->nullable();
                    $table->timestamps();
                });
            }

   
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
