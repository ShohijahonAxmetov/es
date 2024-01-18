<?php

namespace Database\Seeders;

use App\Models\Question;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
        	[
        		'id' => 1,
        		'property_id' => 2,
        		'question' => 'Является ли песонаж женского пола?',
        	],
        	[
        		'id' => 2,
        		'property_id' => 1,
        		'question' => 'Рост больше 2м?',
        	],
        	[
        		'id' => 3,
        		'property_id' => 3,
        		'question' => 'Цвет глаз черного цвета?',
        	],
        	[
        		'id' => 4,
        		'property_id' => 4,
        		'question' => 'Цвет волос желтого цвета?',
        	],
        	[
        		'id' => 5,
        		'property_id' => 1,
        		'question' => 'Выше 1.7м?',
        	],
        	[
        		'id' => 6,
        		'property_id' => 1,
        		'question' => 'Носит классические одежды?',
        	],
        	[
        		'id' => 7,
        		'property_id' => 1,
        		'question' => 'Имеется личный вертолет?',
        	],
        ];

        foreach ($data as $key => $item) {
        	if (!Question::find($item['id'])) Question::create($item);
        }
    }
}
