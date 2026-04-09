<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MateriProgress extends Model
{
    protected $table = 'materi_progress';

    protected $fillable = ['user_id', 'materi_id', 'status', 'completed_at'];

    protected $casts = ['completed_at' => 'datetime'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function materi()
    {
        return $this->belongsTo(Materi::class);
    }
}
