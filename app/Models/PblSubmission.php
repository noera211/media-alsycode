<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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

    protected static function boot()
    {
        parent::boot();
        
        static::deleting(function ($submission) {
            if ($submission->file_path && Storage::disk('local')->exists($submission->file_path)) {
                Storage::disk('local')->delete($submission->file_path);
            }
        });
    }

    public function activity()
    {
        return $this->belongsTo(PblActivity::class, 'activity_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function auditLogs()
    {
        return $this->hasMany(SubmissionAuditLog::class);
    }

    public function getFileSizeAttribute()
    {
        if (!$this->file_path || !Storage::disk('local')->exists($this->file_path)) {
            return 0;
        }
        return Storage::disk('local')->size($this->file_path);
    }
}
