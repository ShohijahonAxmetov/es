<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Character extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
    	'name',
    ];

    public function answerQuestions()
    {
    	return $this->belongsToMany(AnswerQuestion::class, 'answer_question_character', 'character_id', 'answer_question_id');
    }

    public function propertyValue()
    {
    	return $this->belongsToMany(PropertyValue::class);
    }
}
