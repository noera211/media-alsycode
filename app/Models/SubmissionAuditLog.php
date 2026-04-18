<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubmissionAuditLog extends Model
{
    public $timestamps = true;

    protected $table = 'submission_audit_logs';

    protected $fillable = [
        'user_id',
        'submission_id',
        'action',
        'ip_address',
        'user_agent',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function submission()
    {
        return $this->belongsTo(PblSubmission::class);
    }
}
