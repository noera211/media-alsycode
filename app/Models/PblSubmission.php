<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PblSubmission extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'activity_id', 'student_id', 'answer', 'file_path',
        'feedback', 'nilai', 'submitted_at', 'graded_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'graded_at'    => 'datetime',
    ];

    public function activity()
    {
        return $this->belongsTo(PblActivity::class, 'activity_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
