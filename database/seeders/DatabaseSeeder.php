<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Users ───────────────────────────────────────────────────────
        $admin = DB::table('users')->insertGetId([
            'name'       => 'Administrator',
            'email'      => 'admin@alsycode.com',
            'password'   => Hash::make('admin123'),
            'role'       => 'admin',
            'is_active'  => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $guru = DB::table('users')->insertGetId([
            'name'       => 'Pak Budi',
            'email'      => 'guru@alsycode.com',
            'password'   => Hash::make('guru123'),
            'role'       => 'guru',
            'is_active'  => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $siswa1 = DB::table('users')->insertGetId([
            'name'       => 'Andi Pratama',
            'email'      => 'siswa@alsycode.com',
            'password'   => Hash::make('siswa123'),
            'role'       => 'siswa',
            'is_active'  => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            ['name' => 'Budi Santoso',  'email' => 'budi@siswa.com',  'password' => Hash::make('siswa123'), 'role' => 'siswa', 'is_active' => true,  'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Citra Dewi',    'email' => 'citra@siswa.com', 'password' => Hash::make('siswa123'), 'role' => 'siswa', 'is_active' => true,  'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Dian Permata',  'email' => 'dian@siswa.com',  'password' => Hash::make('siswa123'), 'role' => 'siswa', 'is_active' => false, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ibu Sari',      'email' => 'sari@guru.com',   'password' => Hash::make('guru123'),  'role' => 'guru',  'is_active' => true,  'created_at' => now(), 'updated_at' => now()],
        ]);

        // ── Materi ──────────────────────────────────────────────────────
        $materiIds = [];
        $materiData = [
            [
                'title'       => 'Pengenalan Algoritma',
                'description' => 'Konsep dasar algoritma dan langkah-langkah penyelesaian masalah',
                'type'        => 'teks',
                'duration'    => '15 menit',
                'content'     => "Algoritma adalah urutan langkah-langkah logis dan sistematis untuk menyelesaikan suatu masalah.\n\nCiri-ciri Algoritma yang Baik:\n- Finiteness: Memiliki akhir\n- Definiteness: Langkah jelas\n- Input: Memiliki masukan\n- Output: Menghasilkan keluaran\n- Effectiveness: Setiap langkah dapat dikerjakan",
                'video_url'   => null,
                'pdf_file'    => null,
            ],
            [
                'title'       => 'Variabel dan Tipe Data',
                'description' => 'Memahami variabel, konstanta, dan tipe data dalam pemrograman',
                'type'        => 'video',
                'duration'    => '20 menit',
                'content'     => 'Variabel adalah tempat penyimpanan data sementara dalam program.',
                'video_url'   => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'pdf_file'    => null,
            ],
            [
                'title'       => 'Percabangan (If-Else)',
                'description' => 'Struktur kontrol percabangan untuk pengambilan keputusan',
                'type'        => 'teks',
                'duration'    => '25 menit',
                'content'     => "Percabangan memungkinkan program mengambil keputusan berdasarkan kondisi tertentu.\n\nContoh:\nJIKA bilangan MOD 2 = 0 MAKA\n    CETAK \"Genap\"\nSELAINNYA\n    CETAK \"Ganjil\"",
                'video_url'   => null,
                'pdf_file'    => null,
            ],
            [
                'title'       => 'Perulangan (Loop)',
                'description' => 'Konsep for, while, dan do-while untuk proses berulang',
                'type'        => 'video',
                'duration'    => '30 menit',
                'content'     => null,
                'video_url'   => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'pdf_file'    => null,
            ],
            [
                'title'       => 'Array dan Struktur Data',
                'description' => 'Mengelola kumpulan data dengan array satu dan dua dimensi',
                'type'        => 'teks',
                'duration'    => '20 menit',
                'content'     => 'Array adalah struktur data yang menyimpan kumpulan elemen dengan tipe data yang sama.',
                'video_url'   => null,
                'pdf_file'    => null,
            ],
            [
                'title'       => 'Fungsi dan Prosedur',
                'description' => 'Membuat program modular dengan fungsi dan prosedur',
                'type'        => 'teks',
                'duration'    => '25 menit',
                'content'     => 'Fungsi adalah blok kode yang dapat dipanggil berulang kali untuk melakukan tugas tertentu.',
                'video_url'   => null,
                'pdf_file'    => null,
            ],
            [
                'title'       => 'Algoritma Pengurutan',
                'description' => 'Bubble sort, selection sort, dan insertion sort',
                'type'        => 'video',
                'duration'    => '35 menit',
                'content'     => null,
                'video_url'   => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'pdf_file'    => null,
            ],
            [
                'title'       => 'Algoritma Pencarian',
                'description' => 'Sequential search dan binary search',
                'type'        => 'teks',
                'duration'    => '20 menit',
                'content'     => 'Pencarian adalah proses menemukan data tertentu dalam kumpulan data.',
                'video_url'   => null,
                'pdf_file'    => null,
            ],
        ];

        foreach ($materiData as $m) {
            $materiIds[] = DB::table('materi')->insertGetId(array_merge($m, [
                'created_by' => $guru,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // ── Level Settings ───────────────────────────────────────────────
        DB::table('level_settings')->insert([
            ['difficulty' => 'Mudah', 'min_materi' => 1, 'updated_by' => $admin, 'created_at' => now(), 'updated_at' => now()],
            ['difficulty' => 'Sedang', 'min_materi' => 3, 'updated_by' => $admin, 'created_at' => now(), 'updated_at' => now()],
            ['difficulty' => 'Sulit', 'min_materi' => 5, 'updated_by' => $admin, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // ── PBL Activities ───────────────────────────────────────────────
        $pbl1 = DB::table('pbl_activities')->insertGetId([
            'title'          => 'Menentukan Bilangan Prima',
            'topic'          => 'Percabangan & Perulangan',
            'difficulty'     => 'Mudah',
            'problem'        => "Seorang guru matematika meminta Anda membuat program yang dapat memeriksa apakah suatu bilangan termasuk bilangan prima atau bukan.\n\n**Masalah:**\nBuatlah algoritma yang menerima input sebuah bilangan bulat positif, kemudian menentukan apakah bilangan tersebut prima atau bukan.\n\n**Contoh:**\n- Input: 7 → Output: \"7 adalah bilangan prima\"\n- Input: 12 → Output: \"12 bukan bilangan prima\"\n\n**Petunjuk:**\nBilangan prima adalah bilangan yang hanya habis dibagi 1 dan dirinya sendiri.",
            'related_materi' => 'Percabangan (If-Else)',
            'created_by'     => $guru,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        DB::table('pbl_activities')->insert([
            [
                'title' => 'Kasir Toko Sederhana', 'topic' => 'Variabel & Percabangan', 'difficulty' => 'Mudah',
                'problem' => "Sebuah toko kelontong ingin membuat sistem kasir sederhana.\n\n**Masalah:**\nBuatlah algoritma untuk menghitung total belanja. Jika total melebihi Rp100.000, berikan diskon 10%.",
                'related_materi' => 'Variabel dan Tipe Data', 'created_by' => $guru, 'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'title' => 'Pengurutan Nilai Siswa', 'topic' => 'Array & Sorting', 'difficulty' => 'Sedang',
                'problem' => "Guru ingin mengurutkan nilai siswa dari yang tertinggi.\n\n**Masalah:**\nDiberikan array berisi nilai ujian 10 siswa. Urutkan dari tertinggi ke terendah menggunakan bubble sort.",
                'related_materi' => 'Algoritma Pengurutan', 'created_by' => $guru, 'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'title' => 'Pencarian Data Siswa', 'topic' => 'Array & Searching', 'difficulty' => 'Sedang',
                'problem' => "Tata usaha sekolah ingin mencari data siswa berdasarkan NIS.\n\n**Masalah:**\nBuatlah algoritma pencarian sequential search untuk menemukan data siswa berdasarkan NIS.",
                'related_materi' => 'Algoritma Pencarian', 'created_by' => $guru, 'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'title' => 'Kalkulator BMI', 'topic' => 'Fungsi & Percabangan', 'difficulty' => 'Sulit',
                'problem' => "UKS sekolah ingin membuat program untuk menghitung BMI siswa.\n\n**Masalah:**\nBuatlah fungsi yang menerima berat badan (kg) dan tinggi badan (m), menghitung BMI, dan menampilkan kategori.",
                'related_materi' => 'Fungsi dan Prosedur', 'created_by' => $guru, 'created_at' => now(), 'updated_at' => now(),
            ],
        ]);

        // ── Test Questions ───────────────────────────────────────────────
        DB::table('test_questions')->insert([
            ['question' => 'Apa yang dimaksud dengan algoritma?', 'option_a' => 'Bahasa pemrograman', 'option_b' => 'Urutan langkah logis untuk menyelesaikan masalah', 'option_c' => 'Perangkat keras komputer', 'option_d' => 'Sistem operasi', 'correct_answer' => 'B', 'created_by' => $guru, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Manakah yang merupakan struktur perulangan?', 'option_a' => 'if-else', 'option_b' => 'switch-case', 'option_c' => 'for-while', 'option_d' => 'try-catch', 'correct_answer' => 'C', 'created_by' => $guru, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Apa output dari: for(i=0; i<3; i++) { print(i) }', 'option_a' => '1 2 3', 'option_b' => '0 1 2', 'option_c' => '0 1 2 3', 'option_d' => '1 2', 'correct_answer' => 'B', 'created_by' => $guru, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Tipe data yang menyimpan kumpulan elemen disebut?', 'option_a' => 'String', 'option_b' => 'Boolean', 'option_c' => 'Array', 'option_d' => 'Integer', 'correct_answer' => 'C', 'created_by' => $guru, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Binary Search bekerja pada data yang sudah?', 'option_a' => 'Acak', 'option_b' => 'Terurut', 'option_c' => 'Kosong', 'option_d' => 'Duplikat', 'correct_answer' => 'B', 'created_by' => $guru, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // ── Sample Submission ────────────────────────────────────────────
        DB::table('pbl_submissions')->insert([
            'activity_id'  => $pbl1,
            'student_id'   => $siswa1,
            'answer'       => 'Algoritma saya: loop dari 2 hingga n/2, cek modulo.',
            'file_path'    => null,
            'feedback'     => 'Algoritma sudah benar, perhatikan edge case bilangan 1.',
            'nilai'        => 85,
            'submitted_at' => now()->subDays(5),
            'graded_at'    => now()->subDays(3),
        ]);
    }
}
