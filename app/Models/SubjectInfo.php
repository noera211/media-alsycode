<?php

namespace App\Models;

use App\Models\QuestionSet;
use Illuminate\Database\Eloquent\Model;

class SubjectInfo extends Model
{
    protected $table = 'subject_info';

    protected $fillable = [
        'mata_pelajaran',
        'kelas',
        'deskripsi',
        'tujuan_pembelajaran',
        'current_evaluation_set_id',
    ];

    public function currentEvaluationSet()
    {
        return $this->belongsTo(QuestionSet::class, 'current_evaluation_set_id');
    }

    public function getTujuanArrayAttribute(): array
    {
        $text = $this->tujuan_pembelajaran;
        if (!$text) {
            return [];
        }

        return collect(preg_split('/\r\n|\r|\n/', $text))
            ->map(fn ($item) => trim($item))
            ->filter()
            ->values()
            ->all();
    }
}
