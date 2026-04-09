# ALSYCODE – Laravel + MySQL Fullstack

Platform pembelajaran algoritma pemrograman berbasis **Problem-Based Learning (PBL)**.

---

## Persyaratan Sistem

| Komponen | Versi Minimum |
|---|---|
| PHP | 8.2+ |
| MySQL | 8.0+ |
| Composer | 2.x |
| Laravel | 11.x |

---

## Langkah Instalasi di Localhost

### 1. Buat Project Laravel Baru

```bash
composer create-project laravel/laravel alsycode
cd alsycode
```

### 2. Salin File dari Paket Ini

Salin seluruh folder dengan struktur berikut ke dalam project Laravel Anda:

```
alsycode/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── MateriController.php
│   │   │   ├── PblController.php
│   │   │   ├── NilaiController.php
│   │   │   └── AdminController.php
│   │   └── Middleware/
│   │       └── RoleMiddleware.php
│   └── Models/
│       ├── User.php
│       ├── Materi.php
│       ├── MateriProgress.php
│       ├── PblActivity.php
│       ├── PblSubmission.php
│       ├── LevelSetting.php
│       ├── TestQuestion.php
│       └── TestResult.php
├── bootstrap/
│   └── app.php
├── database/
│   ├── migrations/
│   │   ├── 2024_01_01_000001_create_users_table.php
│   │   ├── 2024_01_01_000002_create_materi_table.php
│   │   ├── 2024_01_01_000003_create_materi_progress_table.php
│   │   ├── 2024_01_01_000004_create_pbl_activities_table.php
│   │   ├── 2024_01_01_000005_create_pbl_submissions_table.php
│   │   ├── 2024_01_01_000006_create_level_settings_table.php
│   │   ├── 2024_01_01_000007_create_test_questions_table.php
│   │   └── 2024_01_01_000008_create_test_results_table.php
│   └── seeders/
│       └── DatabaseSeeder.php
├── resources/
│   └── views/
│       ├── layouts/app.blade.php
│       ├── landing.blade.php
│       ├── auth/login.blade.php
│       ├── dashboard/siswa.blade.php
│       ├── dashboard/guru.blade.php
│       ├── materi/index.blade.php
│       ├── materi/show.blade.php
│       ├── materi/_form.blade.php
│       ├── pbl/index.blade.php
│       ├── pbl/show.blade.php
│       ├── pbl/_form.blade.php
│       ├── nilai/guru.blade.php
│       ├── nilai/siswa.blade.php
│       ├── admin/dashboard.blade.php
│       ├── admin/users.blade.php
│       ├── admin/_user_form.blade.php
│       └── compiler/index.blade.php
└── routes/
    └── web.php
```

### 3. Konfigurasi Database

Edit file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=alsycode_db
DB_USERNAME=root
DB_PASSWORD=
```

Buat database di MySQL:

```sql
CREATE DATABASE alsycode_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 4. Hapus Migration Bawaan Laravel

```bash
# Hapus migration bawaan Laravel yang tidak dipakai
rm database/migrations/0001_01_01_000000_create_users_table.php
rm database/migrations/0001_01_01_000001_create_cache_table.php
rm database/migrations/0001_01_01_000002_create_jobs_table.php
```

### 5. Jalankan Migration & Seeder

```bash
php artisan key:generate
php artisan migrate
php artisan db:seed
```

### 6. Buat Symlink Storage (untuk upload file)

```bash
php artisan storage:link
```

### 7. Jalankan Server

```bash
php artisan serve
```

Buka browser: **http://localhost:8000**

---

## Akun Default

| Role | Email | Password |
|---|---|---|
| Admin | admin@alsycode.com | admin123 |
| Guru | guru@alsycode.com | guru123 |
| Siswa | siswa@alsycode.com | siswa123 |

---

## Fitur Per Role

### 👨‍🎓 Siswa
- Lihat dan pelajari materi (teks / video / PDF)
- Tandai materi sebagai sedang dipelajari / selesai
- Lihat progress materi dengan progress bar
- Unlock level PBL berdasarkan jumlah materi selesai
- Kerjakan dan kumpulkan aktivitas PBL (upload file)
- Lihat nilai dan feedback dari guru
- Kerjakan test mandiri pilihan ganda
- Mini compiler untuk latihan pseudocode / Python

### 👨‍🏫 Guru
- CRUD materi (teks, video YouTube, PDF embed)
- CRUD aktivitas PBL (3 level kesulitan)
- Review dan beri nilai + feedback pengumpulan siswa
- Kelola bank soal test
- Atur minimum materi untuk membuka setiap level
- Dashboard statistik kelas

### 🔧 Admin
- Manajemen user (tambah, edit, aktif/nonaktif, reset password)
- Dashboard statistik sistem

---

## Struktur Database

```
users ──┬── materi (created_by)
        ├── materi_progress (user_id)
        ├── pbl_activities (created_by)
        ├── pbl_submissions (student_id)
        ├── level_settings (updated_by)
        ├── test_questions (created_by)
        └── test_results (student_id)

materi ──── materi_progress (materi_id)
pbl_activities ──── pbl_submissions (activity_id)
```

---

## Troubleshooting

**Error: Class not found**
```bash
composer dump-autoload
```

**Error: SQLSTATE tabel tidak ada**
```bash
php artisan migrate:fresh --seed
```

**Upload file tidak berfungsi**
```bash
php artisan storage:link
# Pastikan folder storage/app/public writable
chmod -R 775 storage bootstrap/cache
```

**Session tidak tersimpan**
```bash
php artisan config:clear
php artisan cache:clear
```
