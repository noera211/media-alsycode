<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subject_info', function (Blueprint $table) {
            $table->foreignId('current_evaluation_set_id')
                ->nullable()
                ->after('tujuan_pembelajaran')
                ->constrained('question_sets')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('subject_info', function (Blueprint $table) {
            $table->dropForeign(['current_evaluation_set_id']);
            $table->dropColumn('current_evaluation_set_id');
        });
    }
};
