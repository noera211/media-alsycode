<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('test_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->integer('score');
            $table->integer('total_questions');
            $table->json('answers')->nullable(); // detail jawaban per soal
            $table->timestamp('taken_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('test_results');
    }
};
