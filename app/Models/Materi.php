<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    protected $table = 'materi';

    protected $fillable = [
        'title', 'description', 'type', 'duration',
        'content', 'pdf_file', 'video_url', 'created_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function progresses()
    {
        return $this->hasMany(MateriProgress::class);
    }

    public function getYoutubeIdAttribute(): ?string
    {
        if (!$this->video_url) return null;
        preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $this->video_url, $m);
        return $m[1] ?? null;
    }
}
