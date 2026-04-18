<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Hapus kolom nilai_evaluasi yang salah (dari migration sebelumnya)
        if (Schema::hasColumn('test_results', 'nilai_evaluasi')) {
            Schema::table('test_results', function (Blueprint $table) {
                $table->dropColumn('nilai_evaluasi');
            });
        }

        // Tabel nilai akhir per siswa
        Schema::create('student_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->integer('nilai_pbl')->nullable();       // diinput manual guru
            $table->string('catatan_pbl')->nullable();      // opsional: keterangan guru
            $table->timestamps();

            $table->unique('student_id'); // 1 record per siswa
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_grades');
    }
};