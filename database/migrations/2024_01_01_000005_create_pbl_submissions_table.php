<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pbl_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained('pbl_activities')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->text('answer')->nullable();
            $table->string('file_path')->nullable();
            $table->text('feedback')->nullable();
            $table->integer('nilai')->nullable();
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamp('graded_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pbl_submissions');
    }
};
