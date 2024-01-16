<?php

namespace Database\Seeders;

use App\Models\AnswerQuestion;
use App\Models\Character;
use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AnswerQuestionCharacterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    	$probabilities = [
    		0.9, 0.1, 0.9, 0.9, 0.1,
    		0.1, 0.9, 0.1, 0.1, 0.9,

    		0.1, 0.4, 0.1, 0.9, 0.8,
    		0.9, 0.6, 0.9, 0.1, 0.2,

    		0.1, 0.8, 0.9, 0.1, 0.8,
    		0.9, 0.2, 0.1, 0.9, 0.2
    	];

    	$counter = 1;
    	foreach (Character::all() as $character) {
    		foreach (AnswerQuestion::all() as $value) {
	        	if (!DB::table('answer_question_character')->where('id', $counter)->exists()) {
	        		DB::table('answer_question_character')->insert([
		        		'id' => $counter,
		        		'answer_question_id' => $value->id,
		        		'character_id' => $character->id,
		        		'probability' => $probabilities[$counter-1]
		        	]);
		        	
		        	$counter ++;	
	        	}
	    	}
    	}
    }
}
