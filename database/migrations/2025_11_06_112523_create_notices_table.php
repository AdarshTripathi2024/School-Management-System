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
         Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->string('title');                            
            $table->text('content');                            
            $table->enum('audience', ['all', 'students', 'teachers', 'parents'])->default('all');
            $table->unsignedBigInteger('class_id')->nullable(); 
            $table->date('notice_date')->default(now());        
            $table->date('expiry_date')->nullable();            
            $table->string('attachment')->nullable();         
            // $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notices');
    }
};
