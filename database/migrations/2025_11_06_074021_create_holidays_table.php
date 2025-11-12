<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
    Schema::create('holidays', function (Blueprint $table) {
    $table->id();
    $table->date('date');
    $table->string('occasion');
    $table->boolean('is_for_teacher')->default(true);
    $table->boolean('is_for_students')->default(false);
    $table->string('session')->default('2025-26');
    $table->text('remark')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('holidays');
    }
};
