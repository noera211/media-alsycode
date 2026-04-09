<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pbl_activities', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('topic');
            $table->enum('difficulty', ['Mudah', 'Sedang', 'Sulit']);
            $table->text('problem');
            $table->string('related_materi');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pbl_activities');
    }
};
