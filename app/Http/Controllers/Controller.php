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

    		/*
    		 * СОХРАНИТЬ В СЕССИЮ ЗАДАННЫЕ ВОПРОСЫ, ЧТОБЫ ВОПРОСЫ НЕ ПОВТОРЯЛИСЬ
    		 */

    		$questions = session()->has('questions') ? session('questions') : [];
    		$questions[] = $request->input('question');
    		session(['questions' => $questions]);


			/*
    		 * ОБРАБОТКА ОТВЕТА
    		 */

    		$characters = $this->handleAnswer($request, $characters);


    		/*
    		 * НАЙТИ СЛЕДУЮЩИЙ ВОПРОС
    		 */

    		// $questionId = $request->input('question') + 1;
    		$questionId = $this->getNextQuestionId($characters);
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

		return $characters;
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



    public function getNextQuestionId($characters)
    {
    	/*
    	 * НАЙТИ ОЖИДАЕМЫЕ TARGETЫ ПЕРСОНАЖЕЙ, ЕСЛИ ЗАДАН ВОПРОС q И ДАН ОТВЕТ ans
    	 */

    	// $data = [
    	// 	'answer_question_id' => 1,
    	// 	'character_id' => 1,
    	// 	'predict_target' => 0.8
    	// ];
    	$data = [];

    	foreach ($characters as $character) {
    		$target = $character->target;

    		$answerQuestions = session()->has('questions') ? AnswerQuestion::whereNotIn('question_id', session('questions'))->with('question')->get() : AnswerQuestion::with('question')->get();
			foreach ($answerQuestions as $answerQuestion) {

				$P = $this->PCalc($characters, $answerQuestion);

				$data[] = [
					'answert_question' => $answerQuestion,
					'answer_question_id' => $answerQuestion->id,
					'character_id' => $character->id,
					'predict_target' => $character->answerQuestions->where('id', $answerQuestion->id)->first()->pivot->probability * $target / $P
				];
			}	
    	}

    	/*
    	 * ФОРМАТИРОВАТЬ ДАННЫЕ ВЫЧИСЛИТЬ ЭНТРОПИЮ
    	 */

    	// $formattedData = [
    	// 	'answer_question_id' => 1,
    	// 	'targets' => [
    	// 		[
    	// 			'character_id' => 1,
    	// 			'predict_target' => 0.65
    	// 		],
    	// 		[
    	// 			'character_id' => 2,
    	// 			'predict_target' => 0.39
    	// 		]
    	// 	],
    	//  'entropy' => 1.9
    	// ];
    	$formattedData = [];
    	$counter = 0;
    	foreach ($answerQuestions as $answerQuestion) {
    		$formattedData[$counter]['answer_question_id'] = $answerQuestion->id;
    		$formattedData[$counter]['answer_question'] = $answerQuestion;

			foreach ($data as $item) {
				if ($item['answer_question_id'] == $answerQuestion->id) $tempArray[] = $item;
	    	}

			$formattedData[$counter]['targets'] = $tempArray;

			$targets = array_map(function($target) { return $target['predict_target']; }, $tempArray);
			$formattedData[$counter]['entropy'] = $this->HCalc($targets);

	    	$counter ++;
	    	unset($tempArray);
    	}

    	/*
    	 * ВОЗВРАЩАЕМ 0, ТАК КАК НЕ ОСТАЛОСЬ ВОПРОСОВ
    	 */

    	if (!isset($formattedData[0])) return 0;

    	// $dataForCalcEntropy = [];
    	// $counter = 0;
    	// foreach ($formattedData as $item) {
    	// 	foreach (Question::all() as $question) {
    	// 		if ($question->id == $item['answer_question']->question_id) {
    	// 			$dataForCalcEntropy[$counter]['question_id'] = 
    	// 		}
    	// 	}

    	// 	$counter ++;
    	// }

    	$dataWithMinEntropy = $formattedData[0];
    	foreach ($formattedData as $value) {
    		if ($dataWithMinEntropy['entropy'] > $value['entropy']) $dataWithMinEntropy = $value;
    	}
    	
    	return AnswerQuestion::find($dataWithMinEntropy['answer_question_id'])->question_id;
    }

    public function HCalc(array $targets)
    {
    	$result = 0;

    	foreach ($targets as $value) {
    		$result += $value * log (1/$value, 2);
    	}

    	return $result;
    }
}
