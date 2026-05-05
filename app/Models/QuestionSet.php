<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionSet extends Model
{
    protected $fillable = ['name', 'type', 'description', 'created_by'];

    // Label tipe yang ramah tampilan
    public const TYPE_LABELS = [
        'pretest'   => 'Pretest',
        'posttest'  => 'Posttest',
        'ulangan'   => 'Ulangan',
        'latihan'   => 'Latihan',
    ];

    // Badge warna per tipe (Tailwind classes)
    public const TYPE_COLORS = [
        'pretest'   => 'bg-blue-100 text-blue-700 border-blue-200',
        'posttest'  => 'bg-purple-100 text-purple-700 border-purple-200',
        'ulangan'   => 'bg-orange-100 text-orange-700 border-orange-200',
        'latihan'   => 'bg-green-100 text-green-700 border-green-200',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function questions()
    {
        return $this->belongsToMany(
            TestQuestion::class,
            'question_set_items',
            'question_set_id',
            'test_question_id'
        )->withPivot('order')->orderByPivot('order');
    }

    public function results()
    {
        return $this->hasMany(QuestionSetResult::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPE_LABELS[$this->type] ?? $this->type;
    }

    public function getTypeColorAttribute(): string
    {
        return self::TYPE_COLORS[$this->type] ?? 'bg-gray-100 text-gray-700 border-gray-200';
    }
}
