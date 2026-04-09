<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('level_settings', function (Blueprint $table) {
            $table->id();
            $table->enum('difficulty', ['Mudah', 'Sedang', 'Sulit'])->unique();
            $table->integer('min_materi')->default(1);
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('level_settings');
    }
};
