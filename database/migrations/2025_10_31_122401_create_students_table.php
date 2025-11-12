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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('parent_id')->nullable;
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('roll_number')->nullable;
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('phone');
            $table->date('dateofbirth');
            $table->string('current_address');
            $table->string('permanent_address');
            $table->boolean('requires_transport')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
