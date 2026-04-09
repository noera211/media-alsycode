<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materi_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('materi_id')->constrained('materi')->onDelete('cascade');
            $table->enum('status', ['belum', 'sedang', 'selesai'])->default('belum');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'materi_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materi_progress');
    }
};
