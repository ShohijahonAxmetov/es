<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnswerQuestion extends Model
{
    use HasFactory;

    protected $table = 'answer_question';

    protected $fillable = [
    	'answer_id',
    	'question_id',
    ];

    public function characters()
    {
    	return $this->belongsToMany(Character::class, 'answer_question_character', 'answer_question_id', 'character_id');
    }
}
