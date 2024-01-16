<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Question;
use App\Models\AnswerQuestion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AnswerQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    	$counter = 1;
        foreach (Answer::all() as $answer) {
        	foreach (Question::all() as $question) {
        		if (!AnswerQuestion::find($counter)) {
        			AnswerQuestion::create([
	        			'id' => $counter,
	        			'answer_id' => $answer->id,
	        			'question_id' => $question->id,
	        		]);

	        		$counter ++;
        		}
        	}
        }
    }
}
