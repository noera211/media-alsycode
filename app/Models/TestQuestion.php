<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestQuestion extends Model
{
    protected $fillable = [
        'question', 'option_a', 'option_b', 'option_c', 'option_d', 'option_e',
        'correct_answer', 'created_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getOptionsAttribute(): array
    {
        $opts = [
            'A' => $this->option_a,
            'B' => $this->option_b,
            'C' => $this->option_c,
            'D' => $this->option_d,
        ];
        if ($this->option_e) {
            $opts['E'] = $this->option_e;
        }
        return $opts;
    }
}