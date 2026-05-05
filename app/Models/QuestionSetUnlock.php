<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionSetUnlock extends Model
{
    protected $fillable = ['student_id', 'question_set_id'];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function questionSet()
    {
        return $this->belongsTo(QuestionSet::class);
    }
}
