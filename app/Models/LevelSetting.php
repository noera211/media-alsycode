<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LevelSetting extends Model
{
    protected $fillable = ['difficulty', 'min_materi', 'updated_by'];

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
