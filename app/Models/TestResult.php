<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestResult extends Model
{
    public $timestamps = false;

    protected $fillable = ['student_id', 'score', 'total_questions', 'answers', 'taken_at'];

    protected $casts = [
        'answers'  => 'array',
        'taken_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function getPersentaseAttribute(): int
    {
        if ($this->total_questions === 0) return 0;
        return (int) round(($this->score / $this->total_questions) * 100);
    }
}
