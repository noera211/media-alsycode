<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentGrade extends Model
{
    protected $fillable = ['student_id', 'nilai_pbl', 'catatan_pbl'];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}