<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PblActivity extends Model
{
    protected $fillable = [
        'title', 'topic', 'difficulty', 'problem', 'related_materi', 'created_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function submissions()
    {
        return $this->hasMany(PblSubmission::class, 'activity_id');
    }
}
