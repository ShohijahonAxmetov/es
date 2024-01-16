<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Character;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function start(Request $request)
    {
    	if ($request->input('answer') !== null && $request->input('question') !== null) {
    		$result = $this->handleAnswer($request);

    		$question = Question::find($request->input('question'));
    		if (!$question) return response('Questions end');
    	}

    	$questionId = 1;
    	if ($request->input('question') !== null) $questionId = $request->input('question');

    	$characters = Character::all();
    	foreach ($characters as $character) {
    		$character->target = 1 / count($characters);
    	}

    	return response([
    		'characters' => $characters,
    		'answers' => Answer::all(),
    		'question' => Question::find($questionId) ?? 'end',
    	]);
    }

    public function handleAnswer(Request $request)
    {
    	$request->validate([
    		'answer' => 'required|integer',
    		'question' => 'required|integer',
    	]);


    }

    public function calc()
    {
    	//
    }
}
