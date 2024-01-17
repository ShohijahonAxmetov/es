<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Character;
use App\Models\Answer;
use App\Models\AnswerQuestion;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function start(Request $request)
    {
    	if (session()->has('characters')) {
    		$characters = session('characters');
    	} else {
    		$characters = Character::select('id', 'name')->get();
    		foreach ($characters as $character) {
	    		$character->target = 1 / count($characters);
	    	}
    	}

    	$questionId = 1;

    	if ($request->input('answer') !== null && $request->input('question') !== null) {
    		$question = Question::find($request->input('question'));
    		if (!$question) return response('Question not found');

    		$answer = Answer::find($request->input('answer'));
    		if (!$answer) return response('Answer not found');

    		$result = $this->handleAnswer($request, $characters);

    		$questionId = $request->input('question') + 1;
    	} else {
    		session()->flush();
    	}

    	if (!Question::find($questionId)) {
    		session()->flush();
    	}

    	return response([
    		'characters' => $characters->map->only(['id', 'name', 'target'])->values(),
    		'question' => Question::find($questionId) ?? 'end',
    		'answers' => Answer::all(),
    	]);
    }

    public function handleAnswer(Request $request, $characters)
    {
    	$request->validate([
    		'answer' => 'required|integer',
    		'question' => 'required|integer',
    	]);

		$characters = $this->calc($characters, $request->input('question'), $request->input('answer'));

		session(['characters' => $characters]);
    }		

    public function calc($characters, $questionId, $answerId)
    {
    	foreach ($characters as $character) {
    		$target = $character->target;

    		$answerQuestion = AnswerQuestion::where([
    			['answer_id', $answerId],
    			['question_id', $questionId]
    		])
    			->first();

			$P = $this->PCalc($characters, $answerQuestion);

			$character->target = $character->answerQuestions->where('id', $answerQuestion->id)->first()->pivot->probability * $target / $P;
    	}

    	return $characters;
    }

    public function PCalc($characters, $answerQuestion)
    {
    	$P = 0;
    	foreach ($characters as $character) {
    		$P += $character->target * $character->answerQuestions->where('id', $answerQuestion->id)->first()->pivot->probability;
    	}

    	return $P;
    }
}
