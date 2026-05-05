<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Kumpulan soal (pretest / posttest / ulangan, dll)
        Schema::create('question_sets', function (Blueprint $table) {
            $table->id();
            $table->string('name');                         // judul kumpulan soal
            $table->enum('type', ['pretest', 'posttest', 'ulangan', 'latihan'])->default('latihan');
            $table->text('description')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        // Pivot: soal mana saja yang masuk ke kumpulan ini
        Schema::create('question_set_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_set_id')->constrained('question_sets')->onDelete('cascade');
            $table->foreignId('test_question_id')->constrained('test_questions')->onDelete('cascade');
            $table->unsignedSmallInteger('order')->default(0);
            $table->unique(['question_set_id', 'test_question_id']);
        });

        // Riwayat pengerjaan kumpulan soal oleh siswa
        Schema::create('question_set_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_set_id')->constrained('question_sets')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->integer('score');
            $table->integer('total_questions');
            $table->json('answers')->nullable();   // {question_id: 'A', ...}
            $table->timestamp('taken_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('question_set_results');
        Schema::dropIfExists('question_set_items');
        Schema::dropIfExists('question_sets');
    }
};
