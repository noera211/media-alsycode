<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subject_info', function (Blueprint $table) {
            $table->id();
            $table->string('mata_pelajaran')->default('Informatika');
            $table->string('kelas')->default('X');
            $table->text('deskripsi')->nullable();
            $table->text('tujuan_pembelajaran')->nullable(); // satu tujuan per baris
            $table->timestamps();
        });

        // Seed default row
        DB::table('subject_info')->insert([
            'mata_pelajaran'     => 'Informatika',
            'kelas'              => 'X',
            'deskripsi'          => 'Mata pelajaran Informatika membekali siswa dengan kemampuan berpikir komputasional, pemrograman dasar, dan pemecahan masalah menggunakan teknologi.',
            'tujuan_pembelajaran'=> "Siswa mampu memahami konsep algoritma dan pemrograman\nSiswa mampu membuat pseudocode dan flowchart\nSiswa mampu menyelesaikan masalah menggunakan pendekatan komputasional\nSiswa mampu bekerja sama dalam proyek berbasis masalah (PBL)",
            'created_at'         => now(),
            'updated_at'         => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('subject_info');
    }
};
