<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'is_active'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function materiProgress()
    {
        return $this->hasMany(MateriProgress::class);
    }

    public function submissions()
    {
        return $this->hasMany(PblSubmission::class, 'student_id');
    }

    public function testResults()
    {
        return $this->hasMany(TestResult::class, 'student_id');
    }

    public function completedMateriCount(): int
    {
        return $this->materiProgress()->where('status', 'selesai')->count();
    }

    public function isAdmin(): bool   { return $this->role === 'admin'; }
    public function isGuru(): bool    { return $this->role === 'guru'; }
    public function isSiswa(): bool   { return $this->role === 'siswa'; }
}
